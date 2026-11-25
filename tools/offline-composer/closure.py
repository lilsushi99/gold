import json, subprocess, sys
for rnd in range(6):
    d = json.load(open('deps.json'))
    pk = json.load(open('packages.json'))
    need = set()
    for p in pk:
        for r in p.get('require', {}):
            if r=='php' or r.startswith(('ext-','lib-','composer-','illuminate/')) or r=='hhvm': continue
            need.add(r)
        # replaced names are satisfied
    have = set(d['found'])
    for p in pk: have |= set(p.get('replace', {}) or {})
    new = sorted(n for n in need if n not in have)
    print(f'round {rnd}: {len(new)} new ->', new, flush=True)
    if not new: break
    sys.path.insert(0,'.')
    from discover import repo_of
    for n in new: d['found'][n] = repo_of(n)
    json.dump(d, open('deps.json','w'), indent=1)
    subprocess.run(['python3','mkrepo.py'], check=True)
print('CLOSED', flush=True)
