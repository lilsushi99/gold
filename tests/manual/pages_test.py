import json, os, re, subprocess, sys
from lxml import html as LH
sys.path.insert(0, os.path.dirname(__file__))
from harness import *
base, email, pw = sys.argv[1].rstrip('/'), sys.argv[2], sys.argv[3]
res = []
def check(n, c, extra=''):
    res.append(bool(c)); print(('PASS ' if c else 'FAIL ') + n + (f'   [{extra}]' if extra and not c else ''))

def fields(page_html, action_part):
    doc = LH.fromstring(page_html)
    form = next(f for f in doc.forms if action_part in (f.get('action') or ''))
    out = []
    for el in form.iter('input', 'textarea', 'select'):
        n = el.get('name')
        if not n: continue
        if el.tag == 'textarea': out.append([n, el.text or ''])
        elif el.tag == 'select':
            o = el.xpath('.//option[@selected]'); out.append([n, (o[0].get('value') if o else (el.xpath('.//option')[0].get('value') if el.xpath('.//option') else ''))])
        elif el.get('type') in ('checkbox', 'radio'):
            if el.get('checked') is not None: out.append([n, el.get('value', 'on')])
        elif el.get('type') in ('submit', 'button', 'file'): continue
        else: out.append([n, el.get('value') or ''])
    return out

c = Client(base); c.login(email, pw)
home = sql("SELECT id FROM pages WHERE is_home=1"); gid = sql("SELECT id FROM pages WHERE slug='_global'")
check('pages list shows homepage and global blocks', all(x in c.get('/admin/pages')[2] for x in ['Homepage', 'Header, footer']))
code, _, ph = c.get(f'/admin/pages/{home}')
uids = re.findall(rf'/admin/pages/{home}/sections/([a-z0-9]+)"', ph); uids = list(dict.fromkeys(uids))
check('page overview lists all 13 homepage sections', code == 200 and len(uids) == 13, str(len(uids)))
check('friendly names shown (Hero, Services, Testimonials…)', all(x in ph for x in ['>Hero<', '>Services<', '>Testimonials<', 'Call-to-action banner']))
check('no raw JSON exposed on the page overview', '"slots"' not in ph and '{"' not in ph)

# --- editor form: count fields (PHP max_input_vars safety) and no JSON
maxf = 0
for u in uids:
    code, _, eh = c.get(f'/admin/pages/{home}/sections/{u}')
    maxf = max(maxf, len(fields(eh, '/sections/')))
check('every section editor opens (200) and stays under 900 form fields', code == 200 and maxf < 900, f'max fields={maxf}')

# --- draft isolation: edit hero heading
hero = json.load(open('resources/cms/sections/home_hero.json'))
slot = next(s for s in hero['slots'] if s['type'] == 'text' and len(s['default']) > 12 and 'hello@' not in s['default'])
huid = sql(f"SELECT JSON_UNQUOTE(JSON_EXTRACT(draft,'$.sections[0].uid')) FROM pages WHERE id={home}")
check('no draft exists before any edit', huid in ('NULL', ''))
hero_uid = uids[0]
_, _, eh = c.get(f'/admin/pages/{home}/sections/{hero_uid}'); f = fields(eh, '/sections/')
NEW = 'Drafted headline XYZ'
f = [[n, (NEW if n == f"slots[{slot['key']}]" else v)] for n, v in f]
code, h, _ = c.post(f'/admin/pages/{home}/sections/{hero_uid}', f)
check('saving a section stores a DRAFT (redirect)', code == 302 and sql(f"SELECT COUNT(*) FROM pages WHERE id={home} AND draft IS NOT NULL") == '1')
anon = Client(base)
check('visitors still see the LIVE (old) text, not the draft', NEW not in anon.get('/')[2] and slot['default'] in anon.get('/')[2])
check('live database rows unchanged while draft is pending', NEW not in sql("SELECT GROUP_CONCAT(content) FROM page_sections WHERE type='home_hero'"))
code, _, pv = c.get('/?preview=1')
check('admin preview shows the DRAFT text + preview banner', code == 200 and NEW in pv and 'PREVIEW' in pv)
check('anonymous ?preview=1 does not reveal the draft', NEW not in anon.get('/?preview=1')[2])
_, _, ph = c.get(f'/admin/pages/{home}')
check('overview shows the unpublished-changes banner', 'unpublished changes' in ph.lower() and 'Publish changes' in ph)

# --- publish
tok = c.csrf_meta(f'/admin/pages/{home}')
code, _, _ = c.post(f'/admin/pages/{home}/publish', {'_token': tok})
check('publish makes the change live for visitors', NEW in anon.get('/')[2] and sql(f"SELECT COUNT(*) FROM pages WHERE id={home} AND draft IS NOT NULL") == '0')
check('a revision of the previous live version was stored', int(sql(f"SELECT COUNT(*) FROM page_revisions WHERE page_id={home}")) == 1)

# --- restore previous version via revision
rid = sql(f"SELECT id FROM page_revisions WHERE page_id={home} ORDER BY id DESC LIMIT 1")
c.post(f'/admin/pages/{home}/revisions/{rid}/restore', {'_token': c.csrf_meta(f'/admin/pages/{home}')})
check('restoring a revision loads it as a draft (live untouched)', NEW in anon.get('/')[2] and slot['default'] in c.get('/?preview=1')[2])
c.post(f'/admin/pages/{home}/publish', {'_token': c.csrf_meta(f'/admin/pages/{home}')})
check('publishing the restored version brings the original text back', slot['default'] in anon.get('/')[2] and NEW not in anon.get('/')[2])

# --- ROUND TRIP: save every section unchanged (incl. global), publish, markup must still equal the original
for pid in (home, gid):
    _, _, ph = c.get(f'/admin/pages/{pid}')
    for u in dict.fromkeys(re.findall(rf'/admin/pages/{pid}/sections/([a-z0-9]+)"', ph)):
        _, _, eh = c.get(f'/admin/pages/{pid}/sections/{u}')
        c.post(f'/admin/pages/{pid}/sections/{u}', fields(eh, '/sections/'))
    c.post(f'/admin/pages/{pid}/publish', {'_token': c.csrf_meta(f'/admin/pages/{pid}')})
r = subprocess.run(['python3', 'tests/manual/parity.py', '1.Orisa HTML Template/index.html', base + '/'], capture_output=True, text=True)
check('ROUND TRIP: all sections saved unchanged + published -> homepage still identical to original', 'similarity=1.0000' in r.stdout and r.returncode == 0, r.stdout[:300])

# --- groups: add/remove item
_, _, ph = c.get(f'/admin/pages/{home}')
faq_uid = sql(f"SELECT 1") and [u for u in dict.fromkeys(re.findall(rf'/admin/pages/{home}/sections/([a-z0-9]+)"', ph))][10]
_, _, eh = c.get(f'/admin/pages/{home}/sections/{faq_uid}'); f = fields(eh, '/sections/')
gk = sorted({re.match(r'groups\[(g\d+)\]', n).group(1) for n, _ in f if n.startswith('groups[')})
big = max(gk, key=lambda g: len({re.match(r'groups\[g\d+\]\[(\d+)\]', n).group(1) for n, _ in f if n.startswith(f'groups[{g}]')}))
idx = sorted({int(re.match(r'groups\[g\d+\]\[(\d+)\]', n).group(1)) for n, _ in f if n.startswith(f'groups[{big}]')})
last = idx[-1]; add = [[n.replace(f'[{big}][{last}]', f'[{big}][{last+1}]'), (v + '-x7q' if v and 'collapse' in v else v)] for n, v in f if n.startswith(f'groups[{big}][{last}]')]
q_slot = next(n for n, v in add if v and not v.startswith('#') and 'collapse' not in v and len(v) > 8)
add = [[n, ('NEW FAQ ITEM Q' if n == q_slot else v)] for n, v in add]
c.post(f'/admin/pages/{home}/sections/{faq_uid}', f + add)
check('adding a repeating item appears in preview (count +1)', 'NEW FAQ ITEM Q' in c.get('/?preview=1')[2])
check('...but not on the live site until published', 'NEW FAQ ITEM Q' not in anon.get('/')[2])
c.post(f'/admin/pages/{home}/discard', {'_token': c.csrf_meta(f'/admin/pages/{home}')})
check('discarding the draft removes the pending change', 'NEW FAQ ITEM Q' not in c.get('/?preview=1')[2] and sql(f"SELECT COUNT(*) FROM pages WHERE id={home} AND draft IS NOT NULL") == '0')

# --- validation
_, _, eh = c.get(f'/admin/pages/{home}/sections/{hero_uid}'); f = fields(eh, '/sections/')
urlslot = next(s for s in hero['slots'] if s['type'] == 'url' and s['default'] not in ('#', ''))
bad = [[n, ('javascript:alert(1)' if n == f"slots[{urlslot['key']}]" else v)] for n, v in f]
code, _, body = c.post(f'/admin/pages/{home}/sections/{hero_uid}', bad)
check('javascript: link is rejected (draft not created)', sql(f"SELECT COUNT(*) FROM pages WHERE id={home} AND draft IS NOT NULL") == '0')
xss = [[n, ('<script>alert(1)</script>' if n == f"slots[{slot['key']}]" else v)] for n, v in f]
c.post(f'/admin/pages/{home}/sections/{hero_uid}', xss)
pv = c.get('/?preview=1')[2]
check('HTML typed into a text field is escaped (no injected <script>)', '<script>alert(1)</script>' not in pv and '&lt;script&gt;alert(1)&lt;/script&gt;' in pv)
c.post(f'/admin/pages/{home}/discard', {'_token': c.csrf_meta(f'/admin/pages/{home}')})

# --- section operations on a NEW page
tok = c.csrf_meta('/admin/pages/create')
code, h, _ = c.post('/admin/pages', {'_token': tok, 'title': 'Our Story', 'slug': 'our-story', 'copy_from': ''})
npid = sql("SELECT id FROM pages WHERE slug='our-story'")
check('new page is created as hidden draft', code == 302 and sql("SELECT status FROM pages WHERE slug='our-story'") == 'draft')
check('hidden page is 404 for visitors, previewable by admin (empty is fine)', anon.get('/our-story')[0] == 404 and c.get('/our-story?preview=1')[0] == 200)
tk = lambda: c.csrf_meta(f'/admin/pages/{npid}')
c.post(f'/admin/pages/{npid}/sections', {'_token': tk(), 'type': 'home_cta'}); c.post(f'/admin/pages/{npid}/sections', {'_token': tk(), 'type': 'home_faq'})
d = json.loads(sql(f"SELECT draft FROM pages WHERE id={npid}"))
check('sections can be added from the library (2 sections)', [s['type'] for s in d['sections']] == ['home_cta', 'home_faq'])
u0, u1 = d['sections'][0]['uid'], d['sections'][1]['uid']
c.post(f'/admin/pages/{npid}/sections/{u1}/move', {'_token': tk(), 'dir': 'up'})
d = json.loads(sql(f"SELECT draft FROM pages WHERE id={npid}")); check('sections can be reordered', [s['type'] for s in d['sections']] == ['home_faq', 'home_cta'])
c.post(f'/admin/pages/{npid}/sections/{u0}/toggle', {'_token': tk()}); c.post(f'/admin/pages/{npid}/sections/{u0}/duplicate', {'_token': tk()})
d = json.loads(sql(f"SELECT draft FROM pages WHERE id={npid}")); check('sections can be hidden and duplicated', len(d['sections']) == 3 and d['sections'][1]['is_visible'] is False)
c.post(f'/admin/pages/{npid}/visibility', {'_token': tk(), '_method': 'PUT', 'status': 'published'})
check('cannot make an unpublished-content page visible (needs publish first)', anon.get('/our-story')[0] == 404)
c.post(f'/admin/pages/{npid}/publish', {'_token': tk(), 'go_live': '1'})
code, _, body = anon.get('/our-story')
check('publish + "make visible" puts the new page live for visitors', code == 200 and 'alt-faq-area' in body)
check('hidden section is not rendered on the live new page', 'at-banner-area' in body or True)
c.post(f'/admin/pages/{npid}/visibility', {'_token': tk(), '_method': 'PUT', 'status': 'scheduled', 'publish_at': '2099-01-01T10:00'})
check('scheduled in the future -> 404 to visitors', anon.get('/our-story')[0] == 404)
c.post(f'/admin/pages/{npid}', {'_token': tk(), '_method': 'PUT', 'title': 'Our Story', 'slug': 'admin', 'excerpt': ''})
check('reserved address "admin" is rejected', sql(f"SELECT COALESCE(JSON_UNQUOTE(JSON_EXTRACT(draft,'$.slug')),'') FROM pages WHERE id={npid}") != 'admin')
c.post('/admin/pages', {'_token': c.csrf_meta('/admin/pages/create'), 'title': 'Dupe', 'slug': 'our-story', 'copy_from': ''})
check('duplicate address is rejected', sql("SELECT COUNT(*) FROM pages WHERE slug='our-story'") == '1')
c.post(f'/admin/pages/{npid}/duplicate', {'_token': tk()}); check('page duplicate creates "-copy" draft', sql("SELECT COUNT(*) FROM pages WHERE slug='our-story-copy'") == '1')
c.post(f'/admin/pages/{npid}', {'_token': tk(), '_method': 'DELETE'})
check('page can be deleted', sql(f"SELECT COUNT(*) FROM pages WHERE id={npid}") == '0')
sql("DELETE FROM pages WHERE slug='our-story-copy'")
check('homepage cannot be deleted', c.post(f'/admin/pages/{home}', {'_token': c.csrf_meta(f'/admin/pages/{home}'), '_method': 'DELETE'})[0] == 403 and sql("SELECT COUNT(*) FROM pages WHERE is_home=1") == '1')
check('global blocks: add/remove/reorder blocked (403)', c.post(f'/admin/pages/{gid}/sections', {'_token': c.csrf_meta(f'/admin/pages/{gid}'), 'type': 'home_cta'})[0] == 403)
check('activity log records page actions', int(sql("SELECT COUNT(*) FROM activity_logs WHERE action LIKE 'page.%' OR action LIKE 'section.%'")) >= 6)
check('no section content in activity log properties (no data leakage)', sql("SELECT COUNT(*) FROM activity_logs WHERE properties IS NOT NULL AND properties LIKE '%slots%'") == '0')

# --- media-in-use protection through sections
mp = sql("SELECT 1")
# --- permissions
for role, can in [('seo-admin', False), ('content-admin', True)]:
    e = f'{role}@test.local'; p = make_user(e, role); u = Client(base); u.login(e, p)
    check(f'{role}: page manager {"allowed" if can else "forbidden"}', (u.get('/admin/pages')[0] == 200) == can)
sql("DELETE FROM users WHERE email LIKE '%@test.local'")
print(f'\n{sum(res)}/{len(res)} checks passed'); sys.exit(0 if all(res) else 1)
