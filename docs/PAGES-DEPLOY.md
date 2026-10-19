# Publishing the preview on GitHub Pages

`https://bitscol-soft.github.io/mmheritagehotel/` is served by **GitHub Pages**. Two
things follow from that, and one of them is a hard limit:

1. **Pages serves static files only.** It cannot run PHP, Laravel, MySQL or a queue
   worker. The real application — login, CRUD, saving a booking — can only run on a
   host with PHP 8.1 and MySQL (see BUGS #23: staging deploy). What Pages *can* serve
   is the visual preview: all 457 screens rendered from `tools/fixtures`, with sample
   data and a "nothing is saved here" guard on every form.
2. **The preview is built and verified on every push** (`.github/workflows/pages-preview.yml`:
   export → 119k-link check → Pages artifact), but the **deploy step is blocked by a
   repository setting** that the automation token cannot change.

## Why the site currently shows just "mmheritagehotel"

`Settings → Pages → Build and deployment → Source` is still **Deploy from a branch**,
pointing at `arena/01a0fe5b-mmheritagehotel` (an old session branch). That branch has no
`index.html` at its root, so Jekyll renders the only thing it finds — `README.md`, whose
single heading is `# mmheritagehotel`. That is exactly what the live URL returns.

## To publish the preview — two admin settings

### 1. Switch the Pages source

`Settings` → `Pages` → `Build and deployment` → **Source: GitHub Actions**.

That alone stops the stale branch build. The next push (or a manual *Run workflow*)
publishes the static preview.

### 2. Let the branch deploy

`Settings` → `Environments` → **github-pages** → *Deployment branches* currently allows
only `main` and `arena/01a0fe5b-mmheritagehotel`. Pick one:

| Goal | What to do |
|---|---|
| Publish after merge (recommended) | Nothing more. `main` is already allowed; merging PR #18 triggers the workflow on `main` and it deploys. |
| Publish from this branch before merge | Add `arena/7ee36555-mmheritagehotel` to the policy (already done). Nothing else — the deploy job runs on any branch this workflow is triggered for. |

Until both are true the workflow stays **green**: the build and its link check always
run, and when the Pages source is not Actions the deploy job reports that as a notice and
skips only its final step.

**Choosing a branch under "Deploy from a branch" does not deploy this preview.** That mode
runs Jekyll over the whole repository (Laravel code, `vendor/`, these docs) and fails at
"Build with Jekyll" — verified on `arena/7ee36555-mmheritagehotel`. The preview is a
workflow artifact, so the dropdown itself must say **GitHub Actions**.

Two mechanics worth knowing:

- **`Run workflow` only appears for workflows on the default branch.** Before PR #18
  merges there is no `pages-preview.yml` on `main`, so the button will not list it on this
  branch — a push is what triggers it. (Once merged, use *Run workflow* freely.)
- **Re-running a run re-evaluates the gate.** After adding the branch to the environment
  and setting the variable, re-run the latest *Pages preview* run; its deploy job becomes
  eligible without needing another commit.

## Verifying by hand

The same artifact can be produced and served locally, mounted under `/<repo>/` exactly
as project Pages does:

```bash
node tools/export-static-preview.cjs --out dist-preview   # ~5 s, 457 screens
node tools/check-static-preview.cjs dist-preview          # 0 broken links or it fails
node tools/serve-static-preview.cjs --dir dist-preview --base mmheritagehotel --port 8099
# http://localhost:8099/mmheritagehotel/
```

`check-static-preview.cjs` fails on any broken internal link or any root-absolute URL.
It *reports* but does not fail on the 25 missing images inside the vendored stylesheets
under `public/assets` — those are pre-existing and 404 on the live preview server too.

## What the published site is not

- Not the Laravel app: no login, no database, no writes. Forms are intercepted.
- Not the API: AJAX calls in the fixtures hit nothing; the screens show their seeded state.
- A new export is produced on every push, so the published screens cannot drift from
  the repository.
