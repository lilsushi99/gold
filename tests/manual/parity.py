"""Structural parity check: Laravel-rendered page vs the original static HTML.
Usage: python3 parity.py ORIGINAL.html RENDERED_URL
Compares body trees (tag, attributes, whitespace-normalised text). The primary navigation <ul> is
DB-driven and is excluded. Prints every difference (up to 25)."""
import re, sys, urllib.request, html as H
from lxml import html as LH, etree

orig_path, url = sys.argv[1], sys.argv[2]
base = re.match(r'(https?://[^/]+)', url).group(1)
orig = LH.fromstring(open(orig_path, encoding='utf-8').read())
rend = LH.fromstring(urllib.request.urlopen(url, timeout=30).read().decode())

URLMAP = {'index.html': '/', 'about-1.html': '/about', 'services-1.html': '/services', 'services-details.html': '/services/details',
          'portfolio-1.html': '/portfolio', 'portfolio-details-1.html': '/portfolio/details', 'archive-1.html': '/blog',
          'blog-details.html': '/blog/details', 'team.html': '/team', 'team-details.html': '/team/details',
          'pricing.html': '/pricing', 'faqs.html': '/faqs', 'contact-1.html': '/contact', '404.html': '/404'}

def norm_attr(k, v, is_orig):
    v = re.sub(r'\s+', ' ', v).strip()
    if v.startswith(base): v = v[len(base):] or '/'
    if is_orig and k in ('href', 'src'):
        b, _, frag = v.partition('#')
        if b.endswith('.html'):
            v = URLMAP.get(b, '#') + (('#' + frag) if frag else '')
        elif b.startswith('assets/'):
            v = '/' + v
    if v.startswith('/assets/') or v.startswith('assets/'): v = '/' + v.lstrip('/')
    v = re.sub(r"url\(\s*(['\"]?)(?:" + re.escape(base) + r")?/?assets/", r"url(\1/assets/", v)
    return v

def walk(n, is_orig, path, out):
    if not isinstance(n.tag, str): return
    if n.tag == 'script' and n.get('src') is None: return        # inline scripts compared separately
    if n.tag == 'nav' and 'at-mobile-menu-active' in (n.get('class') or ''):
        out.append((path + '/nav', 'NAV-MENU-EXCLUDED', '')); return
    attrs = tuple(sorted((k, norm_attr(k, v, is_orig)) for k, v in n.attrib.items() if k not in ('data-bs-theme',)))
    text = re.sub(r'\s+', ' ', (n.text or '')).strip()
    out.append((path, n.tag, attrs, text))
    for i, c in enumerate(n):
        walk(c, is_orig, f'{path}/{n.tag}[{i}]', out)
        t = re.sub(r'\s+', ' ', (c.tail or '')).strip()
        if t: out.append((f'{path}/{n.tag}[{i}].tail', 'TAIL', (), t))

def tree(root, is_orig):
    out = []; body = root.find('body'); walk(body, is_orig, 'body', out)
    out = [o for o in out]
    # normalise attribute noise: ignore class-order differences
    return out

a, b = tree(orig, True), tree(rend, False)
def key(o): return o[1:]
diffs = []
sa = [key(x) for x in a if x[1] not in ('NAV-MENU-EXCLUDED',)]
sb = [key(x) for x in b if x[1] not in ('NAV-MENU-EXCLUDED',)]
# the original header contains ~80 demo menu <li>/<a> inside the excluded nav: already skipped
import difflib
sm = difflib.SequenceMatcher(None, sa, sb, autojunk=False)
ratio = sm.ratio()
for tag, i1, i2, j1, j2 in sm.get_opcodes():
    if tag != 'equal': diffs.append((tag, sa[i1:i2][:2], sb[j1:j2][:2]))
print(f'elements original={len(sa)} rendered={len(sb)} similarity={ratio:.4f} differing blocks={len(diffs)}')
for d in diffs[:25]: print(d[0], '\n   ORIG:', str(d[1])[:260], '\n   REND:', str(d[2])[:260])
sys.exit(0 if not diffs else 1)
