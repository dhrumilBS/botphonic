"""Build the full release deploy and rollback zips from git.

Release = floating header + mega menu, About page refresh, Industries Hub.

Usage (from the repository root):
    python -I deploy/full-release/build-package.py [OUTPUT_DIR]

Files come from git commits, not the working tree:
    deploy   = integration/header-and-about  (all three features)
    rollback = search-redesign               (what is live today)

Zip entries are written in a fixed order because unzip tools extract in
archive order. That order is what makes the upload safe while the site is
live: no request can load a PHP file that calls code which is not on disk yet.
"""

import hashlib
import os
import subprocess
import sys
import zipfile
from datetime import datetime

BASE_REF = "search-redesign"
NEW_REF = "integration/header-and-about"
THEME = "wp-content/themes/botphonic-child/"

DEPLOY_ORDER = [
    # 1. New files that nothing loads yet.
    "assets/css/header.css",
    "assets/js/header.js",
    "inc/function-header.php",
    "template-parts/header/site-header.php",
    "assets/css/industries.css",
    "assets/js/industries.js",
    "template-industries.php",
    # 2. About page: styles and script, then the template that uses them.
    "assets/css/about.css",
    "assets/js/about.js",
    "template-about.php",
    # 3. Industries assets loader, then functions.php (loads function-header.php)
    #    before header.php (calls it).
    "inc/function-enqueue.php",
    "functions.php",
    "header.php",
    # 4. Clean-up of the old header styles.
    "assets/css/hfe.css",
    "assets/css/blog.css",
    "assets/css/solution-pages.css",
    "assets/js/blog.js",
    "style.css",
    # 5. Unrelated whitespace-only change merged from feature/utm-tracking.
    "assets/js/utm.js",
]

# Rollback: old header.php first (stops calling the new header code), then
# functions.php and the loader, then the About page and styles. New files are
# left in place; nothing loads them once these are restored.
ROLLBACK_ORDER = [
    "header.php",
    "functions.php",
    "inc/function-enqueue.php",
    "template-about.php",
    "assets/css/about.css",
    "assets/js/about.js",
    "assets/css/hfe.css",
    "assets/css/blog.css",
    "assets/css/solution-pages.css",
    "assets/js/blog.js",
    "style.css",
    "assets/js/utm.js",
]


def git(*args):
    return subprocess.run(["git", *args], check=True, capture_output=True).stdout


def changed_files():
    out = git("diff", "--name-only", f"{BASE_REF}..{NEW_REF}", "--", THEME).decode()
    return sorted(line[len(THEME):] for line in out.splitlines() if line)


def modified_files():
    out = git("diff", "--name-only", "--diff-filter=M", f"{BASE_REF}..{NEW_REF}", "--", THEME).decode()
    return sorted(line[len(THEME):] for line in out.splitlines() if line)


def build(path, ref, order):
    sums = []
    stamp = datetime.now().timetuple()[:6]
    with zipfile.ZipFile(path, "w", zipfile.ZIP_DEFLATED) as zf:
        for rel in order:
            data = git("show", f"{ref}:{THEME}{rel}").replace(b"\r\n", b"\n")
            info = zipfile.ZipInfo(THEME + rel, date_time=stamp)
            info.compress_type = zipfile.ZIP_DEFLATED
            info.external_attr = 0o644 << 16
            zf.writestr(info, data)
            sums.append((hashlib.sha256(data).hexdigest(), THEME + rel))
    return sums


def main():
    out_dir = sys.argv[1] if len(sys.argv) > 1 else os.path.join("..", "..", "deploy", "botphonic")
    out_dir = os.path.abspath(out_dir)
    os.makedirs(out_dir, exist_ok=True)

    missing = set(changed_files()) ^ set(DEPLOY_ORDER)
    if missing:
        sys.exit(f"DEPLOY_ORDER is out of date with git, check: {sorted(missing)}")
    missing = set(modified_files()) ^ set(ROLLBACK_ORDER)
    if missing:
        sys.exit(f"ROLLBACK_ORDER is out of date with git, check: {sorted(missing)}")

    base_sha = git("rev-parse", "--short", BASE_REF).decode().strip()
    new_sha = git("rev-parse", "--short", NEW_REF).decode().strip()
    name = "botphonic-release-" + datetime.now().strftime("%Y-%m-%d")

    deploy_sums = build(os.path.join(out_dir, f"{name}-deploy.zip"), NEW_REF, DEPLOY_ORDER)
    rollback_sums = build(os.path.join(out_dir, f"{name}-rollback.zip"), BASE_REF, ROLLBACK_ORDER)

    with open(os.path.join(out_dir, f"{name}-SHA256SUMS.txt"), "w", newline="\n") as fh:
        fh.write(f"# deploy = {NEW_REF} @ {new_sha}, rollback = {BASE_REF} @ {base_sha}\n")
        fh.write("# deploy\n")
        fh.writelines(f"{s}  {p}\n" for s, p in deploy_sums)
        fh.write("# rollback\n")
        fh.writelines(f"{s}  {p}\n" for s, p in rollback_sums)

    print(f"Built in {out_dir}")
    print(f"  {name}-deploy.zip    {len(deploy_sums)} files from {NEW_REF} @ {new_sha}")
    print(f"  {name}-rollback.zip  {len(rollback_sums)} files from {BASE_REF} @ {base_sha}")


if __name__ == "__main__":
    main()
