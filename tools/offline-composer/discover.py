import json, re, sys, urllib.request, urllib.error
from concurrent.futures import ThreadPoolExecutor

OVERRIDE = {
 'egulias/email-validator':'egulias/EmailValidator',
 'monolog/monolog':'Seldaek/monolog',
 'tijsverkoyen/css-to-inline-styles':'tijsverkoyen/CssToInlineStyles',
 'guzzlehttp/guzzle':'guzzle/guzzle','guzzlehttp/psr7':'guzzle/psr7','guzzlehttp/promises':'guzzle/promises','guzzlehttp/uri-template':'guzzle/uri-template',
 'nesbot/carbon':'CarbonPHP/carbon',
 'dflydev/dot-access-data':'dflydev/dflydev-dot-access-data',
 'phpoption/phpoption':'schmittjoh/php-option',
 'graham-campbell/result-type':'GrahamCampbell/Result-Type',
 'carbonphp/carbon-doctrine-types':'CarbonPHP/carbon-doctrine-types',
}
def repo_of(name):
    if name in OVERRIDE: return OVERRIDE[name]
    if name.startswith('league/'): return 'thephpleague/'+name.split('/')[1]
    if name.startswith('psr/'): return 'php-fig/'+name.split('/')[1]
    return name

def fetch(repo, ref):
    u=f'https://raw.githubusercontent.com/{repo}/{ref}/composer.json'
    try:
        return json.load(urllib.request.urlopen(u, timeout=20))
    except urllib.error.HTTPError as e:
        return None
    except Exception as e:
        return None

def skip(n): return n=='php' or n.startswith(('ext-','lib-')) or n.startswith('composer-') or n.startswith('illuminate/')

