# Deployment (GitHub Actions → your server)

Two manual-trigger workflows ship in this repo:

| Workflow | Trigger | Deploys | Needs approval |
|---|---|---|---|
| `Deploy (staging)` | Actions → *Run workflow* → pick ref | your staging dir | no |
| `Deploy (production)` | Actions → *Run workflow* (ref defaults to `main`) | your live dir | yes, if you add a required reviewer on the `production` environment |

They use **only GitHub-owned actions** (`actions/checkout`) — raw `ssh`/`rsync`/`tar`
on the runner — so they work even when the org restricts third-party actions.

## What one deploy run does

1. Preflight: fails fast (before touching your server) if any secret is missing.
2. **Production guard**: refuses any ref other than `main` unless you tick `allow_non_main`.
3. Backs up the current release on the server → `releases-backup/pre-<runid>.tgz` (kept 14 days).
4. `php artisan down` (maintenance mode).
5. Syncs code — `rsync --delete` if rsync exists on the host, otherwise tar-over-ssh (no file deletions).
   Always excludes: `.git`, `.env*`, `storage/`, `bootstrap/cache/*`, `node_modules`, `public/storage`,
   `public/assets/uploads` (your uploads/database are never deleted), `releases-backup/`.
6. `php artisan optimize:clear && php artisan up`.
7. Health check: 6 attempts × 10 s for `GET <URL>/login` → `200`.
8. **Auto-rollback**: if the health check fails, the backup from step 3 is restored and the run fails loudly.

## One-time setup (repo admin required)

### 1. SSH key

Create a dedicated deploy key pair; add the **public** key to the server user's
`~/.ssh/authorized_keys`. If you use `rsync`, it must be installed on the server
(binary packages are pre-installed on almost every host).

### 2. Repository secrets

Settings → Secrets and variables → Actions → *New repository secret*:

| Secret | staging value | production value |
|---|---|---|
| `STAGING_SSH_HOST` / `PRODUCTION_SSH_HOST` | host or IP | host or IP |
| `STAGING_SSH_USER` / `PRODUCTION_SSH_USER` | ssh user | ssh user |
| `STAGING_SSH_PORT` / `PRODUCTION_SSH_PORT` | `22` (optional) | `22` (optional) |
| `STAGING_SSH_KEY` / `PRODUCTION_SSH_KEY` | **private** key contents | private key |
| `STAGING_DEPLOY_PATH` / `PRODUCTION_DEPLOY_PATH` | absolute path to the app root (the dir containing `artisan`) | same for live |
| `STAGING_PHP_BIN` / `PRODUCTION_PHP_BIN` | `php` — on cPanel use the full path, e.g. `/opt/cpanel/ea-php81/root/usr/bin/php` | same for live |
| `STAGING_URL` / `PRODUCTION_URL` | `https://staging.example.com` | `https://mm-heritage-hotel.dizihotel.com` |

### 3. Environments

Settings → Environments → create `staging` and `production`.
On `production` add **Protection rules → Required reviewers** (1 admin) so every
production deploy needs approval. The workflows already reference these environments.

## Verifying a deploy works

```
Actions → Deploy (staging) → Run workflow → ref = the branch under test
```
Watch the run log: the health-check step prints `attempt N: HTTP 200`.
After merging PR #4 into `main`, the same button on `Deploy (production)` ships it
(approve the pending deployment when prompted).

To smoke-test the fixed endpoints after any deploy:

```
BASE=https://<staging-host> EMAIL=<admin-email> PASS=<admin-pass> ./tools/verify_fixes.sh
```

## Troubleshooting

- **Run fails on missing secrets** → names/typos in step 2 (the error lists exactly which).
- **`Permission denied (publickey)`** → wrong key/user, or the server's `sshd` needs
  `PubkeyAuthentication yes`; home-dir perms (`chmod 755 ~ && chmod 700 ~/.ssh`).
- **`rsync: command not found`** → harmless: the workflow auto-falls back to tar-over-ssh
  (note: the fallback never deletes removed files; run one manual cleanup if a release deletes many).
- **Health check fails but the site is actually fine** → give it a moment: some hosts
  rate-limit HTTPS probes. The rollback restores the previous code — re-check server-side
  logs (`storage/logs/laravel.log`, nginx error log) before rerunning.
- **502 Bad Gateway on the live host after a deploy** → PHP-FPM/nginx layer, not this
  workflow (the health check catches it and rolls back). Usual fixes: restart PHP-FPM,
  check `php -v` matches `PHP_BIN`, raise `fastcgi_read_timeout`, run
  `php artisan optimize:clear` in the app dir. See BUGS.md §C for the on-server checklist.
- **`.env` drift** → the workflow never overwrites `.env*` on the server; if a release
  needs new env keys (e.g. a new driver), edit the server `.env` first, then deploy.

## Deliberate design choices

- **Manual trigger only** — no push-to-deploy. A shared staging box can also be
  destroyed by one bad `main` push; you want humans (and PR review) in the loop.
- **`vendor/` is committed in this repo** → the deploy needs no composer step
  (nothing is fetched on the server during the deploy window). If you later move
  `vendor/` out of git, add a `composer install --no-dev --optimize-autoloader`
  step right after the sync step.
- **No `php artisan migrate`** — the repo has zero migrations (schema ships via the
  SQL dump). If you add migrations later, insert `$PHP_BIN artisan migrate --force`
  between the sync and cache-refresh steps.
