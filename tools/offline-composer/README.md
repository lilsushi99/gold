# Installing Laravel dependencies without Packagist

Normal projects just run `composer install`. These scripts exist because the build
sandbox used for development can reach **GitHub but not packagist.org**. They build a
local Composer `package` repository from GitHub tags and let Composer resolve and
install the exact same Laravel 12 dependency tree from GitHub (`codeload.github.com`).

**You do not need this on your own machine or on cPanel with SSH.** Use plain Composer there.

## Exact Composer requirements (the project's real constraints)
- `php ^8.2` (platform pinned to 8.2.0 so the lock file stays installable on PHP 8.2 hosts)
- `laravel/framework ^12.0` → resolves to 12.74.x, which pulls 87 further packages
  (symfony/*, nesbot/carbon, monolog, guzzlehttp/*, league/*, ramsey/*, vlucas/phpdotenv, ...)

## Workflow (needs only git + curl + python3 + composer + network access to github.com)
```bash
cd tools/offline-composer
python3 closure.py        # expands deps.seed.json -> deps.json, writes packages.json
cd ../..
# temporary copy of composer.json that disables packagist and uses the local repo:
python3 - <<'PY'
import json
c = json.load(open('composer.json'))
c['repositories'] = [{'packagist.org': False},
                     {'type': 'package', 'package': json.load(open('tools/offline-composer/packages.json'))}]
json.dump(c, open('composer.sandbox.json', 'w'), indent=4)
PY
COMPOSER=composer.sandbox.json composer update --no-dev --no-scripts
```
`mkrepo.py` keeps the latest patch of each recent minor series, so resolution is fast.
Delete `composer.sandbox.json` and `composer.lock` afterwards; do not commit them.

## Alternative for hosting without SSH
Run `composer install --no-dev --optimize-autoloader` on any computer with internet,
zip the resulting `vendor/` folder and upload it next to the application (see CPANEL_DEPLOYMENT.md).
