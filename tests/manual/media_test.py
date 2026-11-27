import io, json, os, sys
from PIL import Image
sys.path.insert(0, os.path.dirname(__file__))
from harness import *
base, email, pw = sys.argv[1], sys.argv[2], sys.argv[3]
res = []
def check(n, c): res.append(bool(c)); print(('PASS ' if c else 'FAIL ') + n)
def img(w, h, fmt='PNG'):
    b = io.BytesIO(); Image.new('RGB', (w, h), (200, 30, 90)).save(b, fmt); return b.getvalue()

anon = Client(base)
check('anonymous cannot open media library', anon.get('/admin/media')[0] == 302)
c = Client(base); c.login(email, pw); tok = c.csrf_meta()
check('admin opens media library', c.get('/admin/media')[0] == 200)

code, _, body = c.upload('/admin/media', [('Team Photo.PNG', img(400, 300), 'image/png')], tok)
j = json.loads(body); m = j['uploaded'][0] if j.get('uploaded') else {}
check('valid PNG uploads', code == 200 and m.get('width') == 400 and m['url'].startswith('/storage/media/'))
check('uploaded file is publicly reachable (200, image/png)', c.get(m['url'])[0] == 200)
check('filename is slugged + randomised', 'team-photo-' in m['url'] and m['url'].endswith('.png'))

code, _, body = c.upload('/admin/media', [('big.jpg', img(3200, 1800, 'JPEG'), 'image/jpeg')], tok)
big = json.loads(body)['uploaded'][0]
check('oversized image is scaled to max 2560px', big['width'] == 2560 and big['height'] == 1440)

for name, data, ct, label in [
    ('shell.php', b'<?php echo 1;', 'application/x-php', '.php file'),
    ('evil.png', b'<?php system($_GET["c"]);', 'image/png', 'PHP disguised as PNG'),
    ('run.exe', b'MZ\x90\x00', 'application/octet-stream', '.exe file'),
    ('page.html', b'<script>alert(1)</script>', 'text/html', '.html file'),
    ('fake.pdf', b'just text not a pdf', 'application/pdf', 'text disguised as PDF'),
    ('x.phtml', b'<?php ?>', 'image/png', '.phtml file'),
]:
    code, _, body = c.upload('/admin/media', [(name, data, ct)], tok)
    check(f'rejects {label}', code == 422 and not json.loads(body).get('uploaded'))

svg = b'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 20" onload="alert(1)"><script>alert(1)</script><a href="javascript:alert(1)"><rect width="40" height="20" fill="red" onclick="x()"/></a><foreignObject><div>x</div></foreignObject><image href="https://evil.example/x.png"/></svg>'
code, _, body = c.upload('/admin/media', [('logo.svg', svg, 'image/svg+xml')], tok)
s = json.loads(body)['uploaded'][0]; stored = c.get(s['url'])[2]
check('SVG accepted but sanitised (no script/onload/onclick/javascript:/foreignObject/external image)',
      code == 200 and not any(x in stored.lower() for x in ['<script', 'onload', 'onclick', 'javascript:', 'foreignobject', 'evil.example']) and '<rect' in stored)
check('SVG dimensions read from viewBox', (s['width'], s['height']) == (40, 20))
code, _, body = c.upload('/admin/media', [('xxe.svg', b'<?xml version="1.0"?><!DOCTYPE s [<!ENTITY x SYSTEM "file:///etc/passwd">]><svg xmlns="http://www.w3.org/2000/svg">&x;</svg>', 'image/svg+xml')], tok)
check('SVG with external entity (XXE) is rejected', code == 422)
pdf = b'%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF'
code, _, body = c.upload('/admin/media', [('brochure.pdf', pdf, 'application/pdf')], tok)
check('PDF document accepted', code == 200 and json.loads(body)['uploaded'][0]['kind'] == 'document')

# edit alt text + listing / search / filter
mid = m['id']
check('edit page renders', c.get(f'/admin/media/{mid}')[0] == 200)
c.post(f'/admin/media/{mid}', {'_token': c.token('/admin/media/%d' % mid) if False else c.csrf_meta(f'/admin/media/{mid}'), '_method': 'PUT', 'alt_text': 'Our team at work', 'title': 'Team', 'description': 'd'})
check('alt text saved to DB', sql(f"SELECT alt_text FROM media WHERE id={mid}") == 'Our team at work')
check('search finds by alt text', 'Team' in c.get('/admin/media?q=team+at+work')[2] and 'brochure' not in c.get('/admin/media?q=team+at+work')[2])
check('filter by documents shows only the PDF', 'brochure' in c.get('/admin/media?kind=document')[2] and 'Team Photo' not in c.get('/admin/media?kind=document')[2])
code, _, body = c.get('/admin/media/picker?kind=image', {'Accept': 'application/json'})
check('picker JSON feed lists images only', code == 200 and all(x['kind'] == 'image' for x in json.loads(body)['data']) and len(json.loads(body)['data']) >= 3)

# delete protection
sql(f"INSERT INTO settings (`group`,`key`,value,type,created_at,updated_at) VALUES ('general','test_logo','{mid}','media',NOW(),NOW()) ON DUPLICATE KEY UPDATE value='{mid}'")
tk = c.csrf_meta(f'/admin/media/{mid}')
c.post(f'/admin/media/{mid}', {'_token': tk, '_method': 'DELETE'})
check('in-use file cannot be deleted', sql(f"SELECT COUNT(*) FROM media WHERE id={mid}") == '1')
check('usage is shown on the edit page', 'test_logo' in c.get(f'/admin/media/{mid}')[2])
sql("DELETE FROM settings WHERE `key`='test_logo'")
path = sql(f"SELECT path FROM media WHERE id={mid}")
c.post(f'/admin/media/{mid}', {'_token': tk, '_method': 'DELETE'})
check('unused file deletes (row + file)', sql(f"SELECT COUNT(*) FROM media WHERE id={mid}") == '0' and not os.path.exists('storage/app/public/' + path))

# permissions
for role, can in [('seo-admin', False), ('content-admin', True)]:
    e = f'{role}@test.local'; p = make_user(e, role); u = Client(base); u.login(e, p)
    check(f'{role}: media library {"allowed" if can else "forbidden (403)"}', (u.get('/admin/media')[0] == 200) == can)
    if role == 'seo-admin': check('seo-admin can still use the picker feed', u.get('/admin/media/picker', {'Accept': 'application/json'})[0] == 200)
sql("DELETE FROM users WHERE email LIKE '%@test.local'")
print(f'\n{sum(res)}/{len(res)} checks passed'); sys.exit(0 if all(res) else 1)
