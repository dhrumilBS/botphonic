# Header redesign: deploy to live

One zip to go live, one zip to undo it. No database changes, no plugin
changes, no maintenance mode.

| File | What it does |
| --- | --- |
| `botphonic-header-redesign-deploy.zip` | 11 theme files: the new header and mega menu |
| `botphonic-header-redesign-rollback.zip` | The 7 original files, restoring the old header |
| `botphonic-header-redesign-SHA256SUMS.txt` | Checksums of every file in both zips |

Built from git by `build-package.py` (see "Rebuilding" below). The zips are
written to `D:\xampp\deploy\botphonic\`, outside the web root.

## Before you deploy

1. Take a backup of `wp-content/themes/botphonic-child/` on live
   (UpdraftPlus "Themes" backup, or download the folder).
2. Confirm live still matches the base commit. The package replaces whole
   files, so any edit made directly on live to these files would be lost:
   `functions.php`, `header.php`, `style.css`, `assets/css/hfe.css`,
   `assets/css/blog.css`, `assets/css/solution-pages.css`, `assets/js/blog.js`.
   On 2026-10-27 the public CSS/JS files on live were identical to the base
   commit.

## Deploy (one click, no downtime)

1. Open cPanel **File Manager** (or the Filester file manager in WP admin).
2. Go to the WordPress root: the folder that contains `wp-config.php`
   (usually `public_html`).
3. Upload `botphonic-header-redesign-deploy.zip` there.
4. Right-click the zip, choose **Extract**, extract into that same folder and
   allow it to overwrite existing files.
5. Delete the uploaded zip from the server.
6. Purge caches: any page cache plugin, server cache (LiteSpeed/Varnish) and
   Cloudflare. CSS/JS URLs change automatically (their version is the file
   time), so browsers pick up new assets without a hard refresh.

Why this has no downtime: files inside the zip are ordered. New files that
nothing uses yet are written first, then `functions.php` (which loads the new
header code), then `header.php` (which calls it). At no point can a visitor's
request load a file that calls code that is not on disk yet. For the few
milliseconds between `functions.php` and `header.php`, a request would show the
old menu without its plugin styling; no errors.

Do not extract with a tool that sorts files alphabetically or extracts in
parallel. cPanel File Manager, Filester and the `unzip` command all extract
in archive order.

## Check it worked

- Home page: Product, Solutions, Pricing, Resources, Sign in, Book a demo,
  Start free trial. Open each mega menu.
- Below 1200px wide: menu button opens the full-screen menu. Below 768px:
  sticky Book a demo / Start free trial bar at the bottom.
- View source: `assets/css/header.css` and `assets/js/header.js` are loaded,
  and no `maxmegamenu` / `megamenu` assets are.

## Roll back

Same steps with `botphonic-header-redesign-rollback.zip`, then purge caches.
It restores the 7 original files in a safe order (`header.php` first).
The 4 new files stay on the server but nothing loads them; delete them
afterwards if you like:

- `assets/css/header.css`
- `assets/js/header.js`
- `inc/function-header.php`
- `template-parts/header/site-header.php`

Max Mega Menu and the "Main Menu" in Appearance > Menus are never changed, so
the old header comes back exactly as it was.

## Rebuilding

From the repository root, after committing changes to `feature/header-redesign`:

```bash
python -I deploy/header-redesign/build-package.py
```

The script refuses to build if the list of changed files in git no longer
matches its upload order, so a new file can't be missed silently.
