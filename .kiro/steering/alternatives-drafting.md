---
inclusion: auto
name: alternatives-drafting
description: Draft a Botphonic "Alternatives" (competitor comparison) post from a source .md file in wp-content/themes/botphonic-child/assets/content/ or a Google Docs link. Maps the content verbatim into the existing Alternatives ACF fields, validates the draft against the source, and reports what was placed. Use when asked to create, draft, import, or build an Alternatives post.
---

# Drafting an Alternatives post

Goal: zero content modification. Every part of the source goes into the matching ACF field exactly as written, or it is reported. Nothing is invented. If content does not fit the field structure, leave it out and report it. Do not bend it to fit.

This workflow overrides the speed rules in `workflow.md`. Every step below, including validation, is required.

## Source of truth (re-check every run)

- Fields: `wp-content/themes/botphonic-child/inc/acf-alternatives.php` (group `group_botphonic_alternatives`, keys `field_bpalt_*`).
- Format rules: `inc/function-alternative.php` (`botphonic_alt_prose`, `botphonic_alt_inline`, `botphonic_alt_profile_parts`, `botphonic_alt_cell`, `botphonic_alt_glance_value`, `botphonic_alt_parse_rating`, `botphonic_alt_stars`, `botphonic_alt_data`). The file has no code comments, so read the functions themselves.
- Schema: `inc/function-schema.php` (all site schema lives here). Alternatives get it automatically; see §5.
- Page order: `single-alternatives.php`.
- Reference post: ID 23639, "6 Best Rosie AI Alternatives in 2026", drafted from `assets/content/Rosie AI Alternatives.md`. It shows the house markup, but it also has errors (see the last section). When the two disagree, follow this guide.

If the code differs from this guide, follow the code and say so in the report.

## 1. Read the whole source first

- Make no DB writes and create no files until the full source is read and the mapping is planned.
- `.md` in `assets/content/`: read every text line. Google Docs exports put images at the end as base64 definitions (`[imageN]: <data:image/png;base64,…>`). These are huge single lines. Note each `![][imageN]` reference and where it sits, but do not load the base64 into context.
- Google Docs link: download both exports into `tools/` (temp files):
  - `https://docs.google.com/document/d/<DOC_ID>/export?format=md`: the text source.
  - `https://docs.google.com/document/d/<DOC_ID>/export?format=html`: use only to recover structure that the Markdown export flattens, such as list items and line breaks inside table cells (Pros/Cons).
  - If either download returns a login page or an error, stop. Ask the user to share the doc ("Anyone with the link") or save an `.md` export in `assets/content/`.
- Never edit, move, or delete the source file.

## 2. Map sections to fields

Page order: intro → why-look → methodology → comparison → profiles (inline CTA after the first one) → how-to-choose → final verdict → editor content → end-of-article CTA → FAQs. Map the source onto this order as written. Never reorder source content to make it fit.

| Source                                                                                                                                      | Field                                                                             | Stored as                                                                                            |
| ------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- |
| H1                                                                                                                                          | `post_title`                                                                      | plain text                                                                                           |
| Platform named in the H1 (e.g. "Rosie AI")                                                                                                  | `subject_name` (required)                                                         | exact substring of the H1; if the H1 names no single platform, leave it empty and report             |
| Paragraphs before the first H2                                                                                                              | `intro_content`                                                                   | HTML                                                                                                 |
| 1st H2 prose section before the comparison table                                                                                            | `why_look_title` + `why_look_content`                                             | title verbatim, body HTML                                                                            |
| 2nd H2 prose section before the comparison table                                                                                            | `methodology_title` + `methodology_content`                                       | same                                                                                                 |
| 3rd+ H2 prose section before the comparison table                                                                                           | none                                                                              | report                                                                                               |
| H2 that contains the comparison table                                                                                                       | `comparison_title`                                                                | plain text                                                                                           |
| Text between that H2 and the table (e.g. the legend)                                                                                        | `comparison_intro`                                                                | HTML                                                                                                 |
| The table                                                                                                                                   | `competitors`, `glance_rows`, `comparison_categories`                             | see §3                                                                                               |
| Text directly after the table                                                                                                               | `comparison_note`                                                                 | HTML                                                                                                 |
| H2 directly above the first platform H3                                                                                                     | `profiles_heading`                                                                | plain text                                                                                           |
| Text between that H2 and the first H3                                                                                                       | none                                                                              | report                                                                                               |
| Each platform H3                                                                                                                            | one `competitor_profiles` row                                                     | see §4                                                                                               |
| 1st H2 prose section after the profiles                                                                                                     | `how_to_choose_title` + `how_to_choose_content`                                   | title verbatim, body HTML                                                                            |
| 2nd H2 prose section after the profiles                                                                                                     | `final_verdict_title` + `final_verdict_content`                                   | same                                                                                                 |
| Further H2 sections before the FAQs                                                                                                         | `post_content` (editor), heading kept as `<h2>`                                   | HTML; renders after the verdict with no TOC entry (say so in the report)                             |
| FAQ questions (H3 or bold line) + answers                                                                                                   | `faqs[]`: `question` (text), `answer` (HTML)                                      | the template prints its own "Frequently asked questions" heading, so the source FAQ H2 is not stored |
| CTA block in the source (heading, line, button text/URL)                                                                                    | `inline_cta_*` if right after the first profile, `mid_cta_*` if after the verdict | only when the source has one                                                                         |
| Anything after the FAQs, SEO title/meta description (Yoast is installed, but the editor writes these; see §5), anything else without a slot | none                                                                              | report                                                                                               |

H3/H4 subheadings inside a prose section stay in that section's HTML as `<h3>`/`<h4>`, verbatim.

## 3. Comparison table

- Header row: the first cell (e.g. "Feature / Category") has no field, so report it. Botphonic must be the first data column. Its values go in `botphonic_value`, and the template adds the Botphonic column header itself. Each other header cell becomes a `competitors[]` `name`, in source order. The number of competitors must equal the number of competitor values in every row. Leave `logo` empty unless the source contains that logo image.
- Rows before the first category row → `glance_rows[]`: `label`, `value_type`, `botphonic_value`, `competitor_values[]` (`value`).
  - `value_type`: use `yesno` when the row's values are ✓/✗ (text cells in the same row still render as text); otherwise `text`, except for ratings:
    - Numeric ratings from 0 to 5 → `rating`, each cell stored verbatim. `botphonic_alt_parse_rating` reads `4.8`, `4,8`, `4.8/5`, `4.8 out of 5` and `4.8 stars`, and the template draws partially filled stars (4.5 = four and a half). Only the stars are shown, not the number.
    - Star-emoji ratings (`⭐⭐⭐⭐`, `★★★☆☆`) → `text`, emoji verbatim. A text cell holding only star glyphs renders as that many whole stars.
    - A cell in a `rating` row that is not one of those formats (e.g. `⭐⭐⭐⭐ 4.8/5`, `N/A`, a number above 5) renders as plain text. Store it verbatim and report it.
- A row with a bold first cell and all other cells empty starts a new `comparison_categories[]` row. `category_name` = the cell text.
- The rows after it go in that category's `features[]`: `feature_name`, `botphonic_value`, `competitor_values[]`.
- Cell encoding (this is how `botphonic_alt_cell` reads values):
  - `✓` → `yes`, `✗` → `no`.
  - `✓ extra` / `✓ (extra)` → `yes: extra` / `yes: (extra)`: a tick with the text verbatim as a caption. `✗` works the same way with `no:`.
  - Everything else is stored verbatim, including `Not documented`, which renders as the neutral pill. Empty cells stay empty and render as a dash.
- If Botphonic is not the first data column, or a row has a different cell count from the header, do not realign it. Report it.

## 4. Platform write-ups (one `competitor_profiles` row per H3)

- `name`: the H3 text. An H3 with no text is an export artifact: skip it and report it as skipped.
- `screenshot`: the first image under the H3 (see §6). Report any further images in the same write-up.
- `content` (full-toolbar HTML), in source order, using the reference post's markup:

```html
[alt-screenshot]
<p>Lead paragraph, verbatim.</p>
<div class="bpg-alt-profile__bestfor"><p><strong>Best for:</strong> verbatim text.</p>
[alt-rating]</div>
<h4 class="bpg-alt-profile__sub">What Stands Out:</h4>
<ul class="bpg-alt-profile__points"><li><strong>Lead-in:</strong> verbatim text</li></ul>
[alt-pros-cons]
<p><strong>Note:</strong> any later text in the write-up, verbatim.</p>
```

  - Put each token where its source element sits: `[alt-screenshot]` at the image, `[alt-rating]` at the "Rating:" line, `[alt-pros-cons]` at the Pros/Cons tables. House style puts `[alt-rating]` inside the "Best for" box, but only do that when the Rating line directly follows "Best for" in the source.
  - A standalone bold label line (e.g. `**What Stands Out:**`) becomes `<h4 class="bpg-alt-profile__sub">` with the same text, colon kept.
  - Bullets go in `<ul class="bpg-alt-profile__points">`. Keep each bold lead-in and its punctuation exactly where the source has them.
  - Other paragraphs stay `<p>`. Do not add wrappers the source does not call for (`__lead`, `__verdict`, `bpg-alt-box`).
- Rating line `**Rating:** ⭐⭐⭐⭐⭐ 4.8/5 on G2` → `rating_value` `4.8`, `rating_source` `G2`. The template re-renders it as "Rating: ★★★★★ 4.8/5 on G2". If the line does not follow the pattern `Rating: <stars> <n>/5 on <source>`, keep it verbatim as a `<p>` and report it.
- Pros / Cons (single-cell tables in the Markdown export):
  - Do not store the leading `Pros:` / `Cons:` label. The template prints its own "Pros" / "Cons" headings.
  - Each item becomes one `pros[]` / `cons[]` row. Leave `title` empty. Put the whole item, verbatim, in `text` as `<p>…</p>` (inline markup only: bold, italic, links). Keep any bold lead-in inside `text`, because a `title` makes the template add ": ".
  - Item boundaries: take them from real list items or line breaks in the Google Doc HTML export when you have it. In a flattened Markdown cell, split only at a sentence end: `.`, `!` or `?`, then a space, then a capital letter. If there is no clear boundary (e.g. "…and Zapier White-label and agency support…"), keep that text as one item, insert no punctuation, and flag it in the report.
  - A review inside the cell (`Author  ⭐⭐⭐⭐ quote [Verified G2 Review](url)`) maps to:
    - `pros_review_enable` = 1
    - `pros_review_author` = the name, verbatim
    - `pros_review_rating` = the number of ⭐
    - `pros_review_quote` = `<p>quote</p>`, verbatim
    - `pros_review_url` = the link URL
    - `pros_review_label` = the link text, verbatim

    Use the matching `cons_review_*` fields for a review in the Cons cell. If it is unclear where the author name starts, flag it. Each box holds one review; report any extra ones.

## 5. Text rules

Format-only conversions are allowed. List each one used in the report under Corrections/changes:
- Markdown → HTML: paragraphs to `<p>`, `**x**` to `<strong>`, `*x*`/`_x_` to `<em>`, `[text](url)` to `<a href="url">text</a>`, lists to `<ul>`/`<ol>` + `<li>`, in-section headings to `<h3>`/`<h4>`.
- Remove `#` and `**` markers from plain-text fields (titles, names, labels, questions).
- Remove Markdown escapes: `\$` → `$`, `\!` → `!`, `\*` → `*`, `\_` → `_`, and so on.
- Trim trailing spaces at line ends.
- The table-cell encodings in §3 and the rating/review mapping in §4.

Everything else stays exactly as written: wording, order, capitalization, punctuation, straight vs curly quotes, dashes, emoji, numbers, spacing, and typos (e.g. "moths" stays "moths"). Do not add `target` or `rel` to links. In HTML fields, escape only `<`, `>`, and `&` in text. Plain-text fields (titles, names, cells) are stored unescaped.

Never add anything the source does not contain (the slug is the one exception, see §7), including Yoast SEO title/description (never placeholder text either; the reference post's meta description went live starting "Rosie ai meta description:"), excerpt, featured image, categories/tags, alt text, captions, CTA text/URLs (`hero_cta_*`, `comparison_cta_text`, `inline_cta_*`, `mid_cta_*`), `toc_heading_levels`, logos, ratings, reviews, links, headings, and summaries. Leave these fields empty so the template defaults apply.

Schema is automatic; never put JSON-LD, FAQ schema or microdata in any field. `inc/function-schema.php` and Yoast output an `Article` node for each guide (author, publish and modified dates, publisher) and list the guides as an `ItemList` on `/alternatives/`. So `post_author` and the dates must be real. Do not add review stars or `aggregateRating` for competitors: the ratings come from G2, not from Botphonic users, so Google treats them as self-serving.

## 6. Images

- Use only images present in the source. Upload each image that has a slot (a profile's first image → that profile's `screenshot`) to the Media Library, attached to the draft:
  1. Decode the base64 (or download the HTML export's image URL) to a temp file in `tools/`.
  2. Run `media_handle_sideload()`.
  3. Name the file `<sanitize_title(post_title)>-imageN.<ext>`.
  4. Set no alt text, caption, or description.
- Report images that have no image field (inside prose sections, or a second image in a profile) under Content remaining, with their position.
- Never reuse unrelated existing media.

## 7. Create the draft

- Always create a new post: `post_type` `alternatives`, `post_status` `draft`. Never update an existing post, including 23639, unless the user says so. If a post with the same title exists, still create a new draft and mention the existing one.
- `post_author`: the author the user names. Otherwise use the author of the most recent Alternatives post (look it up each run). State which in the report. The author appears in the page's `Article` schema.
- `post_name` (slug): always the brand name only, `sanitize_title(subject_name)`, e.g. `smallest-ai`, `rosie-ai`. Never the title-based slug WordPress would generate. Pass it to `wp_insert_post()`. If another Alternatives post already uses that slug, still set it and say so in the report (WordPress adds `-2` when the draft is published).
- `post_excerpt` stays empty. `post_content` stays empty unless §2 routes sections there.
- Write every field with `update_field('<field key>', $value, $post_id)` using the `field_bpalt_*` keys; field names do not save reliably on a brand-new post. Repeater rows can use sub-field names.
- Copy, don't retype. Build values with a temp script that extracts the exact source lines and cells and applies only the §5 conversions. Retyping content by hand is how silent edits slip in.

## 8. Validate (required)

Run a temp verify script against the saved draft:
1. Read raw values back: `get_field($name, $id, false)` or `get_post_meta()`. Formatted values run wptexturize and would hide or invent quote and dash differences. Normalize both sides the same way: strip tags, decode entities, collapse whitespace, and reverse the §5 encodings (e.g. `yes` ↔ ✓).
2. Every mapped field equals its source text exactly.
3. Coverage: every source text unit (heading, paragraph, list item, table cell, FAQ, link) appears once in the draft or is listed in the report. There must be zero unexplained differences.
4. Structure:
   - The competitor count equals the value count in every glance row and feature row.
   - There is one profile per platform H3.
   - The FAQ count matches the source.
   - Each token appears at most once per profile.
   - Every source link (text + URL) is present.
5. `botphonic_alt_data($id)` resolves without errors, and its `toc` lists the expected sections and profiles.
6. `post_name` equals `sanitize_title(subject_name)`.

Fix any mismatch in the draft (never in the source) and re-run until the check is clean.

## 9. Final report (this order)

- Draft: post ID, slug, and edit link, printed by the script with `admin_url('post.php?post=<ID>&action=edit')`.
- **Content added**: for each field, what went in (source section or lines, item counts).
- **Content remaining**: source content with no field to hold it, with its location and a suggested option. Include every judgment call flagged above (ambiguous splits, uncertain author names).
- **Content missing/skipped**: anything deliberately not transferred (empty headings, export artifacts, labels the template prints itself), or "None".
- **Extra additions**: anything stored that is not in the source, or "None". List separately the template defaults that will still appear on the page even though they are not stored (e.g. the sidebar "Book a free demo" button and the "Frequently asked questions" heading).
- **Before publishing (editor to-do)**: things the draft deliberately leaves empty that the editor must set:
  - Featured image ("Comparison Cover Image"). The archive card shows it cropped to 2:1 (`aspect-ratio: 50 / 25`; the featured first card is not cropped), and the site logo shows if it is missing. A 2:1 image avoids cropping.
  - Yoast SEO title and meta description. Keep the title in line with the H1.
  - The ratings row type, if the source gave star emoji and the editor wants decimal stars (switch to `rating` and enter numbers).
- **Corrections/changes**: every change to source content (the goal is "None"), then the format-only conversions used.

## Tooling

- Temp files go in `wp-content/themes/botphonic-child/tools/` with a `_alt-` prefix: payload JSON, draft script, verify script, downloaded exports, image temp files. Delete them all at the end, even after a failure, and confirm the folder is back to how it was.
- PHP CLI: `d:\xampp\php\php.exe`. WP-CLI is not installed. XAMPP MySQL must be running.
- Write PHP files with the file tool (UTF-8, no BOM). PowerShell `Set-Content -Encoding UTF8` adds a BOM, which causes "headers already sent" warnings.
- The PowerShell console garbles UTF-8 (’ shows as ΓÇÖ). Redirect script output to a file, read it with the file tool, and never compare text taken from console output.
- Bootstrap for every script (sets the `$_SERVER` keys a plugin warns about in CLI):

```php
<?php
if (PHP_SAPI !== 'cli') { exit(1); }
$_SERVER['HTTP_HOST'] = $_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
define('WP_USE_THEMES', false);
require dirname(__DIR__, 4) . '/wp-load.php';
// Media uploads also need:
// require_once ABSPATH . 'wp-admin/includes/file.php';
// require_once ABSPATH . 'wp-admin/includes/media.php';
// require_once ABSPATH . 'wp-admin/includes/image.php';
```

## Don't copy these from the reference post (23639)

The post is now published, and its Ratings glance row has since been changed to type `rating` with numbers (4.9, 4.5, …). That is an editor change made after drafting, not drafting convention: follow §3. Post 23770 (`smallest-ai`) still has the star-emoji `text` row.


- `competitors` lists 4 names, but every row has 5 values (Cresta is missing).
- Every competitor logo is the same "Synthflow Logo" attachment (23646).
- Botphonic's screenshot is attachment 4161, titled "Poly Ai". None of the source's own images were used.
- `comparison_cta_text` is "Get A Demo", which is not in the source.
- The Yoast meta description starts with leftover placeholder text ("Rosie ai meta description: …"), and the Yoast title ("…for Effective Solutions") does not match the H1.
