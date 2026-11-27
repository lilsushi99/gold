"""Shared helpers for manual HTTP tests: login as a user, multipart upload, SQL access."""
import io, os, re, subprocess, urllib.request, urllib.parse, http.cookiejar, uuid
DB = os.environ.get('DB', 'orisa_cms')
def sql(q): return subprocess.run(['mysql', '-N', DB, '-e', q], capture_output=True, text=True).stdout.strip()
class NoR(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k): return None
class Client:
    def __init__(self, base): self.base = base.rstrip('/'); self.jar = http.cookiejar.CookieJar(); self.op = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(self.jar), NoR)
    def _do(self, req):
        try: r = self.op.open(req, timeout=60); return r.status, r.headers, r.read().decode('utf-8', 'ignore')
        except urllib.error.HTTPError as e: return e.code, e.headers, e.read().decode('utf-8', 'ignore')
    def get(self, path, headers=None): return self._do(urllib.request.Request(self.base + path, headers=headers or {}))
    def token(self, path='/admin/login'):
        return re.search(r'name="_token" value="([^"]+)"', self.get(path)[2]).group(1)
    def csrf_meta(self, path='/admin'):
        return re.search(r'name="csrf-token" content="([^"]+)"', self.get(path)[2]).group(1)
    def post(self, path, data, headers=None):
        return self._do(urllib.request.Request(self.base + path, urllib.parse.urlencode([tuple(x) for x in data] if isinstance(data, list) else data, doseq=True).encode(), headers=headers or {}, method='POST'))
    def login(self, email, pw):
        sql(f"UPDATE users SET must_change_password=0 WHERE email='{email}'")
        return self.post('/admin/login', {'_token': self.token(), 'email': email, 'password': pw})
    def upload(self, path, files, token, field='files[]', extra=None):
        b = uuid.uuid4().hex; body = io.BytesIO()
        for k, v in (extra or {}).items(): body.write(f'--{b}\r\nContent-Disposition: form-data; name="{k}"\r\n\r\n{v}\r\n'.encode())
        for name, data, ctype in files:
            body.write(f'--{b}\r\nContent-Disposition: form-data; name="{field}"; filename="{name}"\r\nContent-Type: {ctype}\r\n\r\n'.encode()); body.write(data); body.write(b'\r\n')
        body.write(f'--{b}--\r\n'.encode())
        h = {'Content-Type': f'multipart/form-data; boundary={b}', 'X-CSRF-TOKEN': token, 'Accept': 'application/json'}
        return self._do(urllib.request.Request(self.base + path, body.getvalue(), headers=h, method='POST'))
def make_user(email, role_slug, pw='Passw0rd!Test1'):
    h = subprocess.run(['php', '-r', f'echo password_hash("{pw}", PASSWORD_BCRYPT);'], capture_output=True, text=True).stdout
    sql(f"DELETE FROM users WHERE email='{email}'")
    sql(f"INSERT INTO users (role_id,name,email,password,must_change_password,is_active,created_at,updated_at) SELECT id,'Test {role_slug}','{email}','{h}',0,1,NOW(),NOW() FROM roles WHERE slug='{role_slug}'")
    return pw
