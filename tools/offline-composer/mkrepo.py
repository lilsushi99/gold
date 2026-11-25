import json, re, subprocess, urllib.request, collections
from concurrent.futures import ThreadPoolExecutor
deps = json.load(open('deps.json'))['found']
SEMVER = re.compile(r'^v?(\d+)\.(\d+)(?:\.(\d+))?$')

def tags_of(item):
    name, repo = item
    out = subprocess.run(['git','ls-remote','--tags','--refs',f'https://github.com/{repo}.git'],
                         capture_output=True, text=True, timeout=120).stdout
    tags = []
    for line in out.splitlines():
        sha, ref = line.split('\t'); t = ref.replace('refs/tags/','')
        m = SEMVER.match(t)
        if m: tags.append((tuple(int(x or 0) for x in m.groups()), t, sha))
    tags.sort()
    if not tags: return name, repo, []
    maxmaj = tags[-1][0][0]
    best = {}                      # latest patch per major.minor, majors >= max-2
    for ver,t,sha in tags:
        if ver[0] >= maxmaj-2: best[(ver[0],ver[1])] = (ver,t,sha)
    keep = sorted(best.values())
    # cap: last 8 minor series per major
    per = collections.defaultdict(list)
    for k in keep: per[k[0][0]].append(k)
    final = [x for m in per for x in per[m][-8:]]
    return name, repo, final

def fetch(args):
    name, repo, ver, t, sha = args
    try:
        cj = json.load(urllib.request.urlopen(f'https://raw.githubusercontent.com/{repo}/{t}/composer.json', timeout=30))
    except Exception:
        return None
    if cj.get('name','').lower() != name.lower(): return None
    cj['version'] = t.lstrip('v') if not t.startswith('v') else t
    cj['version'] = '.'.join(map(str,ver))
    cj['dist'] = {'type':'zip','url':f'https://codeload.github.com/{repo}/zip/{sha}','reference':sha}
    cj['source'] = {'type':'git','url':f'https://github.com/{repo}.git','reference':sha}
    for k in ('require-dev','scripts','autoload-dev','extra'):
        if k == 'extra': 
            ex = cj.get('extra',{}); cj['extra'] = {x:ex[x] for x in ex if x in ('laravel','branch-alias')} if ex else {}
        else: cj.pop(k, None)
    return cj

with ThreadPoolExecutor(12) as ex: sel = list(ex.map(tags_of, deps.items()))
jobs = [(n,r,v,t,s) for n,r,fin in sel for (v,t,s) in fin]
print('packages', len(sel), 'versions to fetch', len(jobs), flush=True)
with ThreadPoolExecutor(16) as ex: res = [r for r in ex.map(fetch, jobs) if r]
json.dump(res, open('packages.json','w'))
print('written', len(res), 'package versions', flush=True)
