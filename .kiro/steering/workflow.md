---
inclusion: always
---

# Working style

- Speed first. For small changes (CSS tweaks, copy edits, single-file fixes): read the file, edit, reply. Use as few steps as possible.
- After editing, do one quick check (re-read the changed section). Skip test harnesses, headless browsers, screenshots, and multi-viewport measurements unless the user asks or the change is risky.
- Only open the files the task needs. No broad codebase exploration for small fixes.
- Keep replies short: what changed and where. Paste full files only when asked.
- If the request is clear enough, pick a sensible option and proceed instead of asking.
- Exception: drafting an Alternatives post follows `alternatives-drafting.md` in full, including its validation step.

# Project facts

- WordPress site on XAMPP at `d:\xampp\htdocs\botphonic`. Not a git repo.
- Custom plugin: `wp-content/plugins/botPhonic` (Elementor widgets, `assets/css`, `assets/js`).
- Design tokens (`--bpg-*`, `--r-*`, `--site-font`, etc.) live in `:root` of `wp-content/themes/botphonic-child/style.css`.
- No build step for CSS/JS. Plugin assets are versioned by file modification time, so a page reload picks up changes.
