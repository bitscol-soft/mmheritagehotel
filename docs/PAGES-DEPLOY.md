# Publishing the preview on GitHub Pages

`https://bitscol-soft.github.io/mmheritagehotel/` serves the **visual preview** of the
application: every registered screen, rendered from `tools/fixtures` with sample data.
A "nothing is saved here" guard intercepts every form.

Pages cannot run PHP, Laravel or MySQL, so this is a click-through of the screens, not the
live application. Real writes need a PHP host (BUGS #23).

## How it is published

`Settings → Pages → Build and deployment → Source` is **Deploy from a branch**, branch
`arena/7ee36555-mmheritagehotel`, path `/`. Everything at the branch root is therefore
public, which drives the whole design:

1. **The site is committed at the root.** `index.html` is the dashboard, `preview/` is the
   explorer, and one directory per route holds its `index.html`. Push to the branch and
   Pages rebuilds automatically — no workflow and no settings change is involved.
2. **`_config.yml` keeps the application private.** Jekyll ignores dotfiles, so `.env` is
   never published; `database/` (the 172-table dump), `app/`, `module/`, `vendor/`, `docs/`,
   `storage/`, `tests/` and the rest are listed under `exclude`. `public/index.php` and
   `public/.htaccess` are excluded too, so no PHP source is served as a page.
3. **Assets are reused, not duplicated.** `public/assets`, `public/frontend` and
   `public/food_menu` already exist in the repository, so page URLs point at
   `/public/...` instead of a second 23 MB copy.
4. **404.html is the explorer**, so any unknown path lands somewhere useful.

Verify the safety net after touching anything here:

```bash
curl -sI https://bitscol-soft.github.io/mmheritagehotel/.env                                  # 404
curl -sI https://bitscol-soft.github.io/mmheritagehotel/database/mmheritagehotel_db.sql      # 404
```

## Regenerating the published site

```bash
node tools/export-static-preview.cjs --out dist-preview   # 457 screens, ~5 s
node tools/check-static-preview.cjs dist-preview          # fails on any broken link
node tools/publish-branch-site.cjs                        # copies to the root, verified
git add -A && git commit -m "Publish the preview" && git push
```

`publish-branch-site.cjs` rewrites `assets|frontend|food_menu` to `public/...`, folds
`./` segments, regenerates `_config.yml` from whatever is in the tree, and resolves every
relative reference on disk before reporting `PUBLISH_OK`. It writes
`.pages-publish-manifest.json` so a later run can clean up pages that no longer exist.

Two quirks of the export that matter for the published copy:

- **`http://localhost/...` URLs in the fixtures** (Laravel `asset()`/`url()` output) are
  folded to site-relative paths by the exporter. Without that, 4,078 links sent visitors to
  their own machine.
- **566 links point at routes the preview never exported** (the banquet `bq/` router, POS
  `x/` paths, parameterised edit screens). They 404 on the live preview server too, so the
  exporter makes them inert (`href="#"`) and logs the count rather than shipping dead ends.

## The `Publish preview site` workflow

`.github/workflows/pages-autopublish.yml` keeps the branch copy in step with the code.
On every push it exports, runs the same link check, writes the root, and commits the
result as `github-actions[bot]` — but **only when something differs**. The export is
deterministic, so most pushes end with "site already current".

It cannot loop: the job skips its own commits (bot author + `chore(site):` subject), and
pushes made with `GITHUB_TOKEN` do not start workflow runs. It also refuses to commit if
the link check fails or `_config.yml` stops excluding `.env`, `database/` or
`public/index.php`. So editing a fixture or a Blade view is enough to update the public
site — nobody has to remember to run anything.

## The `Pages preview` workflow

`.github/workflows/pages-preview.yml` still runs on every push: it exports into a temp
directory, checks all ~120k internal links, and uploads a Pages artifact. It is the guard,
not the publisher — its deploy job reports that the repository publishes from a branch.
Keep it: it catches link and fixture regressions before they reach the site.

## If you would rather publish from Actions

Switch the Source dropdown to **GitHub Actions**, delete the committed site files plus
`_config.yml` (one commit), and the workflow's deploy job takes over. That path also needs
the `github-pages` environment to allow the branch, which it already does.
