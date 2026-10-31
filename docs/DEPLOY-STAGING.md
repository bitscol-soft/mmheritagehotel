# Staging deploy (BUGS #23)

The repository is ahead of the live site: files that exist here are missing on the
server, which is why `/api/dashboard`, `/reports/expense-analysis` and the
permitted-users grid still return 500 in production. Publishing the code somewhere it
can actually run is what closes #23.

GitHub Pages cannot do this — it serves static files, so it shows the screens but runs no
PHP. This document deploys the real application.

## What you need

| | |
|---|---|
| A host | any Linux box or cPanel account with **PHP 8.1** (this app is Laravel 8.83 and supports 7.3–8.x) and **MySQL 5.7+/MariaDB 10.3+** |
| PHP extensions | `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`, `zip` |
| ~500 MB disk | the repository commits `vendor/`, so no Composer run is needed on the server |
| An SSH user | with write access to the application directory (the deploy workflow uses ssh/rsync) |
| A database | one empty database plus a user with full rights on it |

Neither `composer` nor `node` has to be installed on the server: `vendor/` ships in the
repository and the compiled front-end assets are committed under `public/`.

## 1. Put the code on the server

Either clone it:

```bash
git clone -b arena/7ee36555-mmheritagehotel https://github.com/bitscol-soft/mmheritagehotel.git /var/www/staging
```

or let the deploy workflow do it — run **Actions → Deploy (staging)** once with the branch
after step 3; it rsyncs the tree to `STAGING_DEPLOY_PATH` and fails safely if `.env` is
missing (it never overwrites `.env`, `storage/`, or uploads).

## 2. Create the database

```sql
CREATE DATABASE mmheritagehotel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'mmheritage'@'localhost' IDENTIFIED BY '<password>';
GRANT ALL PRIVILEGES ON mmheritagehotel.* TO 'mmheritage'@'localhost';
FLUSH PRIVILEGES;
```

The schema is not in migrations — it is the dump at `database/mmheritagehotel_db.sql`
(173 tables, 460 KB). `tools/setup-server.sh` imports it for you.

## 3. Configure and import

Run this **on the server, from the application root**:

```bash
chmod +x tools/setup-server.sh
./tools/setup-server.sh \
  --db-name mmheritagehotel \
  --db-user mmheritage \
  --db-pass '<password>' \
  --url https://staging.example.com
```

It checks PHP and the extensions, creates `.env` from `.env.example`, generates `APP_KEY`,
imports the dump, links `public/storage`, and caches config and routes. It is idempotent:
an existing `.env` is never overwritten and an existing database is never re-imported
(`--fresh-db` drops it first, deliberately). On cPanel, add
`--php /opt/cpanel/ea-php81/root/usr/bin/php`.

Then point the web server's **document root at `<app>/public`** — never at the app root,
or `.env` and `database/` become downloadable.

## 4. Prove it works

From your workstation, against the staging URL:

```bash
BASE=https://staging.example.com EMAIL=<admin> PASS=<password> ./tools/verify_fixes.sh
```

That logs in and asserts the behaviour of ~47 routes fixed in this branch: previously-500
admin screens must answer 200, deliberately narrowed routes must 404 (not 500), intentional
redirects must 302, unauthenticated `/optimize-clear`, `/update-debug` and `/db-backup` must
be refused, and an unknown room slug must 404. It exits non-zero on any deviation and
refuses to run against the live host unless `ALLOW_LIVE=1`.

`SKIP_LOGIN=1` runs only the public checks (no credentials needed).

While clicking through, `storage/logs/laravel.log` should stay silent.

## 5. Keep it current

`Actions → Deploy (staging) → Run workflow → ref = the branch`. The workflow backs up the
release, puts the app in maintenance mode, syncs, clears caches, health-checks `/login`
six times, and restores the backup automatically if that check fails. See
`docs/DEPLOYMENT.md` for the one-time secrets (`STAGING_SSH_HOST`, `STAGING_SSH_USER`,
`STAGING_SSH_KEY`, `STAGING_DEPLOY_PATH`, `STAGING_PHP_BIN`, `STAGING_URL`).

## Known gaps to expect on a fresh box

| Symptom | Cause |
|---|---|
| `/reports/monthlyVat` errors on SQLite | `MONTHNAME` is MySQL-only; a MySQL staging box is exactly what makes this pass |
| `db-backup` fails | needs the `mysqldump` binary on the server |
| Google Drive uploads do nothing | there is no `google` disk in `config/filesystems.php`; uploads stay on local disk (documented in `.env.example`) |
| Blank page, no error | `storage/` or `bootstrap/cache/` not writable by the PHP-FPM user |
| 500 on every page | cached config from another environment: `php artisan optimize:clear` |

## Two things to settle before production

1. **BUGS #24** — `.env` with `APP_KEY` and DB credentials, plus the 172-table dump, are in
   public git history. Rotate them and purge history (`git filter-repo`) before the staging
   database holds anything real.
2. **BUGS #31** — Laravel 8 and PHP 8.1 are end-of-life. Staging is the natural place to
   run `docs/LARAVEL-11-UPGRADE.md` steps 1–5 without risking production.
