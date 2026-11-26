"""HTTP checks for the public CMS-rendered site. Usage: python3 public_site.py BASE_URL ADMIN_EMAIL ADMIN_PASSWORD
Modifies and then restores rows in the local dev database via the mysql CLI (DB name from env DB, default orisa_cms)."""
import json, os, re, subprocess, sys, urllib.request, urllib.parse, http.cookiejar
base, email, pw = sys.argv[1].rstrip('/'), sys.argv[2], sys.argv[3]
DB = os.environ.get('DB', 'orisa_cms')
res = []
def check(n, c): res.append(bool(c)); print(('PASS ' if c else 'FAIL ') + n)
def sql(q): return subprocess.run(['mysql', '-N', DB, '-e', q], capture_output=True, text=True).stdout.strip()
class NoR(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k): return None
def get(path, opener=None):
    o = opener or urllib.request.build_opener(NoR)
    try:
        r = o.open(base + path, timeout=30); return r.status, r.headers, r.read().decode('utf-8', 'ignore')
    except urllib.error.HTTPError as e:
        return e.code, e.headers, e.read().decode('utf-8', 'ignore')

code, h, html = get('/')
check('home renders 200', code == 200)
check('title + canonical + robots in head', '<title>' in html and 'rel="canonical"' in html and 'content="index,follow"' in html)
check('theme attribute + saved-theme script present', 'data-bs-theme="light"' in html and "localStorage.getItem('theme')" in html)
# every local asset referenced by the page must exist
urls = set(re.findall(r'(?:src|href)="((?:%s)?/assets/[^"#?]+)' % re.escape(base), html))
bad = [u for u in urls if get(u.replace(base, ''))[0] != 200]
check(f'all {len(urls)} referenced /assets files load (200)', not bad); [print('   missing', b) for b in bad[:8]]

hero = json.load(open('resources/cms/sections/home_hero.json'))
slot = next(s for s in hero['slots'] if s['type'] == 'text' and len(s['default']) > 12 and 'hello@' not in s['default'])
marker = 'CMS-EDIT-TEST-12345'
sid = sql("SELECT id FROM page_sections WHERE type='home_hero'")
orig = sql(f"SELECT content FROM page_sections WHERE id={sid}")
sql(f"UPDATE page_sections SET content=JSON_SET(content,'$.slots.{slot['key']}','{marker}') WHERE id={sid}")
before = html.count(slot['default'])
after_html = get('/')[2]
check(f"DB edit of slot {slot['key']} appears on public page (one occurrence replaced)", marker in after_html and after_html.count(slot['default']) == before - 1)
sql(f"UPDATE page_sections SET is_visible=0 WHERE type='home_faq'")
code, h, html2 = get('/')
check('hidden section is not rendered', 'alt-faq-area' not in html2 and 'alt-faq-area' in html)
sql(f"UPDATE page_sections SET is_visible=1 WHERE type='home_faq'")

sql("UPDATE pages SET status='draft' WHERE is_home=1")
check('draft homepage is 404 for the public', get('/')[0] == 404)
jar = http.cookiejar.CookieJar(); op = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar), NoR)
tok = re.search(r'name="_token" value="([^"]+)"', get('/admin/login', op)[2]).group(1)
try:
    op.open(urllib.request.Request(base + '/admin/login', urllib.parse.urlencode({'_token': tok, 'email': email, 'password': pw}).encode()))
except urllib.error.HTTPError:
    pass  # 302 redirect after login is expected (not followed)
code, h, html3 = get('/?preview=1', op)
check('admin can preview a draft (200, noindex, banner)', code == 200 and 'PREVIEW' in html3 and 'noindex' in html3 and h.get('X-Robots-Tag') == 'noindex')
check('anonymous ?preview=1 on a draft is still 404', get('/?preview=1')[0] == 404)
sql("UPDATE pages SET status='published' WHERE is_home=1")
sql("UPDATE pages SET status='scheduled', publish_at=DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE is_home=1")
check('future-scheduled page is 404', get('/')[0] == 404)
sql("UPDATE pages SET status='published', publish_at=NULL WHERE is_home=1")
check('republished home is 200 again', get('/')[0] == 200)

check('/home redirects 301 to /', get('/home')[0] == 301 and get('/home')[1].get('Location', '').rstrip('/') == base)
check('/_global is not public (404)', get('/_global')[0] == 404)
check('unknown page is 404', get('/no-such-page')[0] == 404)
check('/admin is not shadowed by the page route', get('/admin')[0] == 302)
sql(f"UPDATE page_sections SET content='{orig.replace(chr(39), chr(39)*2)}' WHERE id={sid}")
check('restored original content', marker not in get('/')[2])
print(f'\n{sum(res)}/{len(res)} checks passed'); sys.exit(0 if all(res) else 1)
