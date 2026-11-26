"""Manual HTTP test of the admin auth flow. Usage: python3 auth_flow.py BASE_URL ADMIN_EMAIL INITIAL_PASSWORD"""
import re, sys, urllib.request, urllib.parse, http.cookiejar

base, email, pw = sys.argv[1].rstrip('/'), sys.argv[2], sys.argv[3]
new_pw = 'Str0ngNewPass99'
results = []

class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k): return None

def client():
    jar = http.cookiejar.CookieJar()
    return urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar), NoRedirect)

def req(c, method, path, data=None):
    body = urllib.parse.urlencode(data).encode() if data is not None else None
    r = urllib.request.Request(base + path, data=body, method=method)
    try:
        resp = c.open(r); code, loc, html = resp.status, None, resp.read().decode()
    except urllib.error.HTTPError as e:
        code, loc, html = e.code, e.headers.get('Location'), e.read().decode()
    return code, loc, html

def token(html):
    m = re.search(r'name="_token" value="([^"]+)"', html); return m.group(1) if m else ''

def check(name, cond):
    results.append(cond); print(('PASS ' if cond else 'FAIL ') + name)

c = client()
code, loc, _ = req(c, 'GET', '/admin')
check('guest /admin redirects to login', code == 302 and loc.endswith('/admin/login'))

_, _, html = req(c, 'GET', '/admin/login'); t = token(html)
code, _, _ = req(c, 'POST', '/admin/login', {'email': email, 'password': pw})
check('POST without CSRF token is rejected (419)', code == 419)

code, loc, html = req(c, 'POST', '/admin/login', {'_token': t, 'email': email, 'password': 'wrong-password'})
check('wrong password rejected (stays on login)', code == 302 and loc.endswith('/admin/login'))
code, _, _ = req(c, 'GET', '/admin'); check('still unauthenticated after bad login', code == 302)

_, _, html = req(c, 'GET', '/admin/login'); t = token(html)
code, loc, _ = req(c, 'POST', '/admin/login', {'_token': t, 'email': email, 'password': pw})
check('valid login redirects', code == 302)
code, loc, _ = req(c, 'GET', '/admin')
check('forced password change before dashboard', code == 302 and loc.endswith('/admin/password'))

_, _, html = req(c, 'GET', '/admin/password'); t = token(html)
code, loc, _ = req(c, 'PUT', '/admin/password', {'_token': t, '_method': 'PUT', 'current_password': pw, 'password': 'weak', 'password_confirmation': 'weak'})
check('weak new password rejected', code == 302 and loc.endswith('/admin/password'))
_, _, html = req(c, 'GET', '/admin/password'); t = token(html)
code, loc, _ = req(c, 'PUT', '/admin/password', {'_token': t, '_method': 'PUT', 'current_password': pw, 'password': new_pw, 'password_confirmation': new_pw})
check('strong password accepted -> dashboard', code == 302 and loc.endswith('/admin'))
code, _, html = req(c, 'GET', '/admin')
check('dashboard renders (200) with sidebar', code == 200 and 'Recent activity' in html and 'class="sidebar"' in html)
check('dashboard is noindex', 'noindex' in html)

t = token(html)
code, loc, _ = req(c, 'POST', '/admin/logout', {'_token': t})
check('logout redirects to login', code == 302 and loc.endswith('/admin/login'))
code, _, _ = req(c, 'GET', '/admin'); check('dashboard blocked after logout', code == 302)

# new password works, old one does not
c2 = client(); _, _, html = req(c2, 'GET', '/admin/login'); t = token(html)
code, _, _ = req(c2, 'POST', '/admin/login', {'_token': t, 'email': email, 'password': pw})
code2, _, _ = req(c2, 'GET', '/admin'); check('old password no longer works', code2 == 302)
_, _, html = req(c2, 'GET', '/admin/login'); t = token(html)
req(c2, 'POST', '/admin/login', {'_token': t, 'email': email, 'password': new_pw})
code, _, _ = req(c2, 'GET', '/admin'); check('new password works', code == 200)

# rate limiting (fresh client, unknown address to keep the real account unaffected)
c3 = client(); codes = []
for i in range(7):
    _, _, html = req(c3, 'GET', '/admin/login'); t = token(html)
    code, _, _ = req(c3, 'POST', '/admin/login', {'_token': t, 'email': 'nobody@example.com', 'password': 'x' + str(i)})
    codes.append(code)
check('login is rate limited after 5 attempts (429)', 429 in codes)

# lockout must also block the CORRECT password for that account/IP
c4 = client()
for i in range(5):
    _, _, html = req(c4, 'GET', '/admin/login'); tk = token(html)
    req(c4, 'POST', '/admin/login', {'_token': tk, 'email': email, 'password': 'bad' + str(i)})
_, _, html = req(c4, 'GET', '/admin/login'); tk = token(html)
code, _, _ = req(c4, 'POST', '/admin/login', {'_token': tk, 'email': email, 'password': new_pw})
check('correct password is blocked during lockout (429)', code == 429)

print(f'\n{sum(results)}/{len(results)} checks passed')
sys.exit(0 if all(results) else 1)
