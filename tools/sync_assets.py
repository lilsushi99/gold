#!/usr/bin/env python3
"""Copy template assets into public/assets.
css/js/fonts are copied completely; images only if referenced by the given pages (or inside assets/imgs/template).
Usage: python3 tools/sync_assets.py index.html about-1.html ...   (names relative to '1.Orisa HTML Template')"""
import re, shutil, sys
from pathlib import Path
root = Path(__file__).resolve().parent.parent
src = root / '1.Orisa HTML Template'
dst = root / 'public' / 'assets'
for part in ('css', 'js', 'fonts'):
    if (src / 'assets' / part).exists():
        shutil.copytree(src / 'assets' / part, dst / part, dirs_exist_ok=True)
imgs = set()
pat = re.compile(r'assets/imgs/[^\s"\'()<>?#]+')
for page in sys.argv[1:]:
    imgs |= set(pat.findall((src / page).read_text(encoding='utf-8', errors='ignore')))
for css in (src / 'assets' / 'css').rglob('*.css'):          # images referenced from stylesheets
    for m in re.findall(r'url\(["\']?\.\./imgs/([^"\')?#]+)', css.read_text(errors='ignore')):
        imgs.add('assets/imgs/' + m)
tpl = src / 'assets' / 'imgs' / 'template'
if tpl.exists():
    shutil.copytree(tpl, dst / 'imgs' / 'template', dirs_exist_ok=True)
copied = missing = 0
for rel in sorted(imgs):
    s = src / rel
    if s.is_file():
        (dst / rel[len('assets/'):]).parent.mkdir(parents=True, exist_ok=True)
        shutil.copy2(s, dst / rel[len('assets/'):]); copied += 1
    else:
        missing += 1; print('missing:', rel)
print(f'images copied: {copied}, missing: {missing}')
