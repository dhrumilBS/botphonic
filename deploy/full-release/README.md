# Full release: header, About page, Industries hub, AI voice agents

One zip ships everything. One zip undoes it. No plugin changes, no
maintenance mode, and the site stays up the whole time.

| File | What it does |
| --- | --- |
| `botphonic-release-2026-10-28-deploy.zip` | 22 theme files: floating header + mega menu, About page refresh, Industries Hub template, AI voice agents library |
| `botphonic-release-2026-10-28-rollback.zip` | The 15 original files, restoring today's live site |
| `botphonic-release-2026-10-28-SHA256SUMS.txt` | Checksums of every file in both zips |

The zips are in `D:\xampp\deploy\botphonic\`, outside the web root. This
package **replaces** the earlier header-only package; do not upload both.

## What is in the release

- **Header:** new floating header and mega menu (Product, Solutions, Pricing,
  Resources), full-screen menu on tablet and phone, sticky Book a demo /
  Start free trial bar on phones. Max Mega Menu stays installed but is no
  longer shown; its CSS and JS stop loading.
- **About page:** rewritten and restyled, correct prices, founder photos,
  fixed links. The FAQ answers and FAQ schema now match the Pricing page.
- **Industries hub:** new "Industries Hub" template that lists every
  industry page with filters. It only appears once you assign it (step 7).
- **AI voice agents library:** redesigned `/ai-voice-agents/` page. Same live
  templates from the Botphonic API, with search, sort, filter counts and a
  new details popup. It no longer loads Bootstrap's JS from jsDelivr.

## Before you start (5 minutes)

1. **Pick a quiet time.** Paid traffic keeps flowing while you deploy; a
   low-traffic hour means fewer people see the few seconds of change-over.
2. **Back up the theme:** cPanel File Manager → `public_html/wp-content/themes/`
   → right-click `botphonic-child` → **Compress** → download the zip.
   (Or UpdraftPlus → Backup Now → Themes only.)
3. Make sure nobody has edited these files directly on live since
   2026-10-28 (their public CSS/JS matched the base on that date):
   `functions.php`, `header.php`, `inc/function-enqueue.php`,
   `template-about.php`, `style.css`, `assets/css/hfe.css`, `blog.css`,
   `solution-pages.css`, `about.css`, `assets/js/blog.js`, `about.js`,
   `temp-ai-voice-agents.php`, `ai-voice-agents.css`, `ai-voice-agents.js`,
   `utm.js`.
   `common-landing.css` is **not** touched, so its live-only mobile table
   fix is kept.

## Deploy

4. Your **hosting panel's File Manager** (cPanel, hPanel, Plesk) → open the
   WordPress root, the folder that contains `wp-config.php` (usually
   `public_html`). Upload the zip **here**, not into the themes folder: the
   paths inside start with `wp-content/themes/botphonic-child/...`.
5. Right-click `botphonic-release-2026-10-28-deploy.zip` → **Extract** →
   extract into that same folder → allow it to **overwrite** existing files.
6. Delete the uploaded zip from the server.
7. **Assign the Industries template** (a page setting, so the zip can't do
   it): WP admin → Pages → **Industry** → Page Attributes / Template →
   **Industries Hub** → **Update**.
8. **Purge every cache:** caching plugin (WP Rocket, LiteSpeed, etc.), any
   server cache your host offers, and Cloudflare (Purge Everything). CSS and
   JS URLs change by themselves (their version is the file time), so
   visitors' browsers pick up the new files without a hard refresh.

### Why there is no downtime

Unzip tools write files one at a time, in the order they sit in the zip. The
order is chosen so the site never runs code that isn't on disk yet: new,
unused files first; then the About page; then `functions.php` (loads the new
header code) before `header.php` (calls it); then style clean-up. Every one
of the 38 in-between states (23 deploy, 15 rollback) was replayed on the local
copy across the home, About, Industry, Blog, product and AI voice agents pages:
all returned
200 with no PHP errors. The old header serves until `header.php` lands; then
the new one takes over.

Use your hosting panel's File Manager or the `unzip` command; both extract
in archive order, in place. **Do not use Filester (WP admin → File Manager) to
extract:** it is built on elFinder, which either unpacks into a new folder
(nothing deploys) or, with "Here", may delete the existing `wp-content` folder
and replace it with the zip's contents, depending on its merge setting.

## Check it worked (2 minutes, in a private window)

- **Home:** floating header with Product, Solutions, Pricing, Resources,
  Sign in, Book a demo, Start free trial. Open each mega menu.
- **Phone:** menu button opens the full-screen menu; the Book a demo / Start
  free trial bar sits at the bottom.
- **/about/:** new page with founder photos; FAQ "How much does Botphonic
  cost?" says Starter $12 and pay as you go $0.13.
- **/industry/:** 13 industry cards with filter buttons (after step 7).
- **A blog post and /pricing/:** look normal.
- View source on any page: `assets/css/header.css` loads; nothing from
  `maxmegamenu` or `megamenu` loads.

## Roll back (if anything looks wrong)

1. WP admin → Pages → **Industry** → Template → **Default template** →
   **Update** (otherwise that page keeps the new template without its styles).
2. Upload and extract `botphonic-release-2026-10-28-rollback.zip` exactly as
   in steps 4 to 6 (WordPress root, overwrite).
3. Purge every cache.

The rollback restores the 15 original files in a safe order (`header.php`
first). The 7 new files stay on the server but nothing loads them; delete
them afterwards if you like:

- `assets/css/header.css`, `assets/js/header.js`
- `assets/css/industries.css`, `assets/js/industries.js`
- `inc/function-header.php`, `template-parts/header/site-header.php`
- `template-industries.php`

Max Mega Menu and its saved menu were never changed, so the old header comes
back exactly as it was.

## Rebuilding

From the repository root, after committing to `main`:

```bash
python -I deploy/full-release/build-package.py
```

The script refuses to build if git's list of changed files no longer matches
its upload or rollback order, so a new file can't be missed.
