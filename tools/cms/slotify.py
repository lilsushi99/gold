"""Turn original template HTML into (a) a Blade template where every visible text / image / link is a named
slot and (b) a JSON definition holding slot labels + the original values as defaults.
Repeated sibling blocks (cards, list items) are detected and become add/remove-able "groups".
The original markup, classes and structure are preserved byte-for-byte apart from the slot placeholders."""
import html as H
import re

TOKEN = re.compile(r'(<!--.*?-->|<script\b.*?</script>|<style\b.*?</style>|<[^>]+>)', re.S | re.I)
VOID = {'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'}
ATTR = re.compile(r'(\s)([\w:-]+)=(["\'])(.*?)\3', re.S)
URL_ATTRS = {('img', 'src'), ('source', 'src'), ('video', 'src'), ('audio', 'src'), ('iframe', 'src'), ('a', 'href'),
             ('video', 'poster'), ('img', 'data-src')}
TEXT_ATTRS = {('img', 'alt'), ('a', 'title'), ('input', 'placeholder'), ('textarea', 'placeholder'), ('img', 'title')}
ANY_URL_ATTRS = {'data-background', 'data-bg', 'data-image', 'data-bg-image'}
ANY_TEXT_ATTRS = {'data-count', 'data-to', 'data-percent', 'data-text'}
KIND = {'h1': 'Heading', 'h2': 'Heading', 'h3': 'Heading', 'h4': 'Heading', 'h5': 'Heading', 'h6': 'Heading',
        'p': 'Paragraph', 'a': 'Link text', 'button': 'Button text', 'li': 'List item', 'span': 'Text',
        'label': 'Label', 'blockquote': 'Quote', 'small': 'Small text', 'strong': 'Bold text', 'em': 'Text'}


class Node:
    __slots__ = ('kind', 'name', 'open', 'close', 'kids', 'raw')

    def __init__(self, kind, name='', open='', raw=''):
        self.kind, self.name, self.open, self.close, self.raw, self.kids = kind, name, open, '', raw, []


def parse(src):
    root = Node('root')
    stack = [root]
    for tok in TOKEN.split(src):
        if not tok:
            continue
        if tok.startswith('<!--'):
            continue                                   # comments are dropped
        if tok[0] == '<' and re.match(r'<(script|style)\b', tok, re.I):
            stack[-1].kids.append(Node('raw', raw=tok))
        elif tok[0] == '<' and tok[1:2] == '/':
            name = re.match(r'</\s*([\w:-]+)', tok)
            name = name.group(1).lower() if name else ''
            for i in range(len(stack) - 1, 0, -1):
                if stack[i].name == name:
                    stack[i].close = tok
                    del stack[i:]
                    break
        elif tok[0] == '<':
            m = re.match(r'<([A-Za-z][\w:-]*)', tok)
            if not m:
                stack[-1].kids.append(Node('raw', raw=tok)); continue
            name = m.group(1).lower()
            n = Node('el', name, tok)
            stack[-1].kids.append(n)
            if name not in VOID and not tok.rstrip().endswith('/>'):
                stack.append(n)
        else:
            stack[-1].kids.append(Node('text', raw=tok))
    return root


def skeleton(n):
    # structure only: tag names + nesting (+ attribute NAMES). Differing static values become hidden per-item slots.
    if n.kind != 'el':
        return ''
    names = ','.join(sorted(m.group(2).lower() for m in ATTR.finditer(n.open) if m.group(2).lower() not in ('id',)))
    if n.name == 'svg':
        return 'svg'
    cls = re.search(r'class=["\']([^"\']*)', n.open)
    cls = ' '.join(sorted(cls.group(1).split())) if cls else ''
    return f"{n.name}.{cls}[{names}](" + ','.join(skeleton(k) for k in n.kids if k.kind == 'el') + ')'


def has_slot(n):
    if n.kind == 'text':
        return bool(n.raw.strip())
    if n.kind == 'el':
        if n.name == 'svg':
            return False
        if any(a in n.open for a in ('src=', 'href=', 'data-background', 'alt=')):
            return True
        return any(has_slot(k) for k in n.kids)
    return False


class Ctx:
    def __init__(self, var, prefix, url_map, allow_groups=True, vary=None):
        self.var, self.prefix, self.url_map, self.allow_groups = var, prefix, url_map, allow_groups
        self.slots, self.n, self.groups = [], 0, []
        self.vary, self.idx = vary or set(), 0

    def add(self, typ, label, default, hint):
        self.n += 1
        key = f'{self.prefix}{self.n}'
        self.slots.append({'key': key, 'type': typ, 'label': label, 'default': default, 'hint': hint})
        return key


def slot_kind(name, lname, raw):
    """Which kind of slot (if any) an attribute becomes."""
    if (name, lname) in URL_ATTRS or lname in ANY_URL_ATTRS:
        return None if (raw in ('', '#') or raw.startswith('javascript:')) else 'url'
    if (name, lname) in TEXT_ATTRS or lname in ANY_TEXT_ATTRS:
        return 'text'
    if lname == 'style' and 'url(' in raw and re.search(r'url\((["\']?)([^)"\']+)\1\)', raw):
        return 'style'
    return None


def raw_of(n):
    if n.kind != 'el':
        return n.raw
    return n.open + ''.join(raw_of(k) for k in n.kids) + n.close


def collect_static(node, out=None, counter=None):
    """{(element index, attribute): value} for attributes that are NOT slots, plus svg markup, in emit() order."""
    out = {} if out is None else out
    counter = counter if counter is not None else [0]
    if node.kind != 'el':
        return out
    counter[0] += 1
    i = counter[0]
    for m in ATTR.finditer(node.open):
        name, val = m.group(2), H.unescape(m.group(4))
        if node.name != 'svg' and slot_kind(node.name, name.lower(), val):
            continue
        out[(i, name.lower())] = val
    if node.name == 'svg':
        out[(i, '__svg__')] = raw_of(node)
        return out
    for k in node.kids:
        collect_static(k, out, counter)
    return out


def varying(run):
    sigs = [collect_static(r) for r in run]
    keys = set().union(*[set(x) for x in sigs])
    return {k for k in keys if len({x.get(k) for x in sigs}) > 1}


def esc_at(s):
    """Escape '@' for Blade in static markup (outside our {{ }} expressions)."""
    parts = re.split(r'(\{\{.*?\}\}|\{!!.*?!!\})', s, flags=re.S)
    return ''.join(p if p.startswith(('{{', '{!!')) else p.replace('@', '&#64;') for p in parts)


def map_url(v, url_map):
    if v.endswith('.html') or '.html#' in v:
        base = v.split('#')[0]
        if base in url_map:
            return url_map[base] + (('#' + v.split('#', 1)[1]) if '#' in v else '')
        if not v.startswith(('http', '//')):
            return '#'
    return v


def attr_pass(n, ctx, anc):
    """Replace slot-able attributes of an opening tag with Blade expressions."""
    def sub(m):
        pre, name, q, val = m.groups()
        lname = name.lower()
        raw = H.unescape(val)
        kind = slot_kind(n.name, lname, raw)
        if kind == 'url':
            raw = map_url(raw, ctx.url_map)
            typ = 'image' if (n.name in ('img', 'source') or lname in ANY_URL_ATTRS or lname == 'poster') else 'url'
            key = ctx.add(typ, {'image': 'Image', 'url': 'Link URL'}[typ], raw, hint(anc, n))
            return f'{pre}{name}="{{{{ cms_url({ctx.var}[\'{key}\']) }}}}"'
        if kind == 'text':
            lab = {'alt': 'Image alt text', 'title': 'Title tooltip', 'placeholder': 'Placeholder'}.get(lname, 'Value')
            key = ctx.add('alt' if lname == 'alt' else 'text', lab, raw, hint(anc, n))
            return f'{pre}{name}="{{{{ {ctx.var}[\'{key}\'] }}}}"'
        if kind == 'style':
            mm = re.search(r'url\((["\']?)([^)"\']+)\1\)', raw)
            key = ctx.add('image', 'Background image', mm.group(2), hint(anc, n))
            new = raw.replace(mm.group(0), 'url({{ cms_url(' + f"{ctx.var}['{key}']" + ') }})')
            return f'{pre}{name}="{new}"'
        if (ctx.idx, lname) in ctx.vary:
            key = ctx.add('hidden', f'Hidden attribute: {name}', raw, hint(anc, n))
            ctx.slots[-1]['attr'] = lname
            return f'{pre}{name}="{{{{ {ctx.var}[\'{key}\'] }}}}"'
        return m.group(0)
    return esc_at(ATTR.sub(sub, n.open))


def hint(anc, n=None):
    names = [a for a in anc[-2:]]
    return ' > '.join(names) + (f' > {n.name}' if n is not None and n.name not in names[-1:] else '')


def short_class(n):
    cls = re.search(r'class=["\']([^"\']*)', n.open)
    c = cls.group(1).split()[0] if cls and cls.group(1).strip() else ''
    return n.name + ('.' + c if c else '')


def emit(node, ctx, anc, in_svg=False):
    if node.kind == 'raw':
        return '@verbatim' + node.raw + '@endverbatim' if node.raw.lower().startswith(('<script', '<style')) else esc_at(node.raw)
    if node.kind == 'text':
        s = node.raw
        if in_svg or not s.strip():
            return esc_at(s)
        lead = s[:len(s) - len(s.lstrip())]
        trail = s[len(s.rstrip()):]
        val = H.unescape(s.strip())
        parent = anc[-1].split('.')[0] if anc else ''
        typ = 'textarea' if len(val) > 90 else 'text'
        key = ctx.add(typ, KIND.get(parent, 'Text'), re.sub(r'\s+', ' ', val), hint(anc))
        return f"{lead}{{{{ {ctx.var}['{key}'] }}}}{trail}"
    # element
    if not in_svg:
        ctx.idx += 1
    if node.name == 'svg' and not in_svg and (ctx.idx, '__svg__') in ctx.vary:
        key = ctx.add('hidden', 'Hidden icon (svg)', raw_of(node), hint(anc, node))
        ctx.slots[-1]['attr'] = '__svg__'
        return f"{{!! {ctx.var}['{key}'] !!}}"
    svg = in_svg or node.name == 'svg'
    open_tag = esc_at(node.open) if svg else attr_pass(node, ctx, anc)
    out = [open_tag]
    anc2 = anc + [short_class(node)]
    out.append(emit_children(node, ctx, anc2, svg))
    out.append(node.close)
    return ''.join(out)


def emit_children(parent, ctx, anc, in_svg):
    kids = parent.kids
    if in_svg or not ctx.allow_groups:
        return ''.join(emit(k, ctx, anc, in_svg) for k in kids)
    out, i = [], 0
    while i < len(kids):
        k = kids[i]
        if k.kind == 'el' and has_slot(k):
            sk = skeleton(k)
            j, run = i, []
            while j < len(kids):
                if kids[j].kind == 'el' and skeleton(kids[j]) == sk:
                    run.append(kids[j]); j += 1
                elif kids[j].kind == 'text' and not kids[j].raw.strip() and any(
                        kids[x].kind == 'el' and skeleton(kids[x]) == sk for x in range(j + 1, len(kids)) if kids[x].kind == 'el'
                        ) and _next_el(kids, j) is not None and skeleton(_next_el(kids, j)) == sk:
                    j += 1
                else:
                    break
            if len(run) >= 2:
                gkey = f'g{len(ctx.groups) + 1}'
                vary = varying(run)
                ictx = Ctx('$it', 't', ctx.url_map, allow_groups=False, vary=vary)
                body = emit(run[0], ictx, anc)
                items = []
                for r in run:
                    c = Ctx('$it', 't', ctx.url_map, allow_groups=False, vary=vary)
                    emit(r, c, anc)
                    items.append({s['key']: s['default'] for s in c.slots})
                ctx.groups.append({'key': gkey, 'label': f'Repeating items ({short_class(run[0])})',
                                   'slots': ictx.slots, 'items': items})
                out.append(f"@foreach (($g['{gkey}'] ?? []) as $it){body}@endforeach")
                i = j
                continue
        out.append(emit(k, ctx, anc))
        i += 1
    return ''.join(out)


def _next_el(kids, j):
    for x in range(j + 1, len(kids)):
        if kids[x].kind == 'el':
            return kids[x]
        if kids[x].kind == 'text' and kids[x].raw.strip():
            return None
    return None


def slotify(src, url_map=None):
    """Return (blade_template, definition_dict)."""
    root = parse(src)
    ctx = Ctx("$s", 's', url_map or {})
    blade = emit_children(root, ctx, [], False)
    return blade, {'slots': ctx.slots, 'groups': ctx.groups}
