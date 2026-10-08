<?php

/**
 * Botphonic child theme — ACF field group for the Alternatives CPT.
 *
 * Registered in PHP rather than stored in the database so the fields ship with
 * the theme: a comparison guide is a structured document, and a deploy that
 * carries the templates but not the fields would render an empty page. The
 * field plugin on this site is secure-custom-fields (WordPress's fork of
 * ACF 6), which exposes the same API, hence the function_exists() guard and the
 * acf/init hook — both are no-ops if the plugin is ever deactivated, and the
 * templates already degrade to printing nothing.
 *
 * Renderers for these values live in inc/function-alternative.php; the
 * templates themselves read one resolved array from botphonic_alt_data().
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;


/* ==========================================================================
   1. A third wysiwyg toolbar
   ========================================================================== */

/**
 * "Inline", alongside ACF's Full and Basic, for wysiwyg fields whose value is
 * printed inside an element the template already owns — a bullet in a list, a
 * review quote, a CTA line. Those places are styled for a single run of text,
 * so the toolbar deliberately offers no headings, lists or blocks: just
 * emphasis, a link, and an escape hatch to strip formatting.
 *
 * ACF matches on the sanitised label, so 'Inline' here means
 * `'toolbar' => 'inline'` on a field.
 *
 * Only ever adds a key. Full and Basic are left untouched, because Full is
 * built from the mce_buttons filters and is how an editor plugin injects
 * whatever toolbar the site has configured.
 *
 * @param array $toolbars Registered toolbars.
 * @return array
 */
function botphonic_alt_wysiwyg_toolbar($toolbars)
{
	if (!is_array($toolbars)) {
		return $toolbars;
	}

	$toolbars['Inline'] = array(
		1 => array('bold', 'italic', 'link', 'removeformat', 'undo', 'redo'),
	);

	return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars', 'botphonic_alt_wysiwyg_toolbar');


/* ==========================================================================
   2. Field group
   ========================================================================== */

/**
 * Register the Alternatives field group.
 *
 * @return void
 */
function botphonic_alt_register_fields()
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	$brand = function_exists('botphonic_alt_brand') ? botphonic_alt_brand() : 'Botphonic';

	/*
	 * Conditional logic used by every field in the inline-CTA block: the whole
	 * block hides until a heading is typed, which is also what makes the CTA
	 * optional on the front end (botphonic_alt_cta() returns nothing without a
	 * heading).
	 */
	$needs_inline_heading = array(
		array(
			array('field' => 'field_bpalt_inline_cta_heading', 'operator' => '!=empty'),
		),
	);

	$pros_review_on = array(
		array(
			array('field' => 'field_bpalt_pros_review_enable', 'operator' => '==', 'value' => '1'),
		),
	);

	$cons_review_on = array(
		array(
			array('field' => 'field_bpalt_cons_review_enable', 'operator' => '==', 'value' => '1'),
		),
	);

	acf_add_local_field_group(
		array(
			'key' => 'group_botphonic_alternatives',
			'title' => __('Alternatives Guide Fields', 'botphonic'),
			'fields' => array(

				/* ---------------------------------------------------------
				   Tab: Hero
				   --------------------------------------------------------- */
				array(
					'key' => 'field_bpalt_tab_hero',
					'label' => __('Hero', 'botphonic'),
					'name' => 'bpalt_tab_hero',
					'type' => 'tab',
					'placement' => 'top',
				),
				array(
					'key' => 'field_bpalt_subject_name',
					'label' => __('Subject Name', 'botphonic'),
					'name' => 'subject_name',
					'type' => 'text',
					'required' => 1,
					'instructions' => __('The platform this guide is a roundup of alternatives to, e.g. "Twilio". Used on the archive card and in headings.', 'botphonic'),
				),
				array(
					'key' => 'field_bpalt_hero_cta_text',
					'label' => __('Sidebar CTA Button Text', 'botphonic'),
					'name' => 'hero_cta_text',
					'type' => 'text',
					'default_value' => 'Book a free demo',
					'wrapper' => array('width' => '50'),
				),
				array(
					'key' => 'field_bpalt_hero_cta_url',
					'label' => __('Sidebar CTA Button URL', 'botphonic'),
					'name' => 'hero_cta_url',
					'type' => 'url',
					'instructions' => __('Falls back to the contact page when empty.', 'botphonic'),
					'wrapper' => array('width' => '50'),
				),
				array(
					'key' => 'field_bpalt_toc_heading_levels',
					'label' => __('Sidebar TOC Headings', 'botphonic'),
					'name' => 'toc_heading_levels',
					'type' => 'select',
					'instructions' => __('Which headings appear in the "On this page" sidebar and in the sticky bar on mobile.', 'botphonic')
						. '<br><strong>' . __('H3 Only', 'botphonic') . '</strong> &mdash; ' . __('the numbered platform write-ups. The default, and the most useful navigation on a long roundup: it is the list a reader is actually scanning for.', 'botphonic')
						. '<br><strong>' . __('H2 Only', 'botphonic') . '</strong> &mdash; ' . __('the page sections (methodology, comparison, how to choose, verdict, FAQs). Better on a short guide with few platforms.', 'botphonic')
						. '<br><strong>' . __('Both H2 and H3', 'botphonic') . '</strong> &mdash; ' . __('sections with the platforms nested underneath. Thorough, but long.', 'botphonic')
						. '<br><br>' . __('The "Overview" link back to the top is always shown, whichever option is chosen.', 'botphonic'),
					'choices' => array(
						'h3' => __('H3 Only — platform write-ups (default)', 'botphonic'),
						'h2' => __('H2 Only — page sections', 'botphonic'),
						'both' => __('Both H2 and H3', 'botphonic'),
					),
					'default_value' => 'h3',
					'allow_null' => 0,
					'ui' => 1,
					'return_format' => 'value',
				),

				/* ---------------------------------------------------------
				   Tab: Intro & methodology
				   --------------------------------------------------------- */
				array(
					'key' => 'field_bpalt_tab_intro',
					'label' => __('Intro & Methodology', 'botphonic'),
					'name' => 'bpalt_tab_intro',
					'type' => 'tab',
					'placement' => 'top',
				),
				array(
					'key' => 'field_bpalt_intro_content',
					'label' => __('Opening Content', 'botphonic'),
					'name' => 'intro_content',
					'type' => 'wysiwyg',
					'tabs' => 'all',
					'media_upload' => 0,
					'instructions' => __('The opening paragraphs, before the "Why look for an alternative" heading. Rendered without a heading of its own.', 'botphonic'),
				),
				array(
					'key' => 'field_bpalt_why_look_title',
					'label' => __('Why-Look Section Title', 'botphonic'),
					'name' => 'why_look_title',
					'type' => 'text',
					'default_value' => 'Why look for an alternative',
				),
				array(
					'key' => 'field_bpalt_why_look_content',
					'label' => __('Why-Look Content', 'botphonic'),
					'name' => 'why_look_content',
					'type' => 'wysiwyg',
					'tabs' => 'all',
					'media_upload' => 0,
					'instructions' => __('Leave empty to drop the section, and its table-of-contents entry, entirely.', 'botphonic'),
				),
				array(
					'key' => 'field_bpalt_methodology_title',
					'label' => __('Methodology Section Title', 'botphonic'),
					'name' => 'methodology_title',
					'type' => 'text',
					'default_value' => 'How we analysed and selected these alternatives',
				),
				array(
					'key' => 'field_bpalt_methodology_content',
					'label' => __('Methodology Content', 'botphonic'),
					'name' => 'methodology_content',
					'type' => 'wysiwyg',
					'tabs' => 'all',
					'media_upload' => 0,
					'instructions' => __('How the platforms below were chosen and scored. Worth filling in: it is the part of a comparison page that earns trust.', 'botphonic'),
				),

				/* ---------------------------------------------------------
				   Tab: Comparison table
				   --------------------------------------------------------- */
				array(
					'key' => 'field_bpalt_tab_compare',
					'label' => __('Comparison Table', 'botphonic'),
					'name' => 'bpalt_tab_compare',
					'type' => 'tab',
					'placement' => 'top',
				),
				array(
					'key' => 'field_bpalt_comparison_title',
					'label' => __('Comparison Table Title', 'botphonic'),
					'name' => 'comparison_title',
					'type' => 'text',
					'default_value' => 'Compare the options at a glance',
				),
				array(
					'key' => 'field_bpalt_comparison_intro',
					'label' => __('Comparison Table Intro', 'botphonic'),
					'name' => 'comparison_intro',
					'type' => 'wysiwyg',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
					'delay' => 1,
					'instructions' => __('Optional paragraph between the heading and the table. Leave empty to render the table straight after the heading.', 'botphonic'),
				),
				array(
					'key' => 'field_bpalt_competitors',
					'label' => __('Competitors', 'botphonic'),
					'name' => 'competitors',
					'type' => 'repeater',
					'layout' => 'table',
					'button_label' => __('Add competitor', 'botphonic'),
					'instructions' => sprintf(
						/* translators: %s: brand name. */
						__('%s is always the first column and does not need to be listed here. List the other competitors in the exact order you want them as columns — every row below must supply its values in this same order.', 'botphonic'),
						$brand
					),
					'sub_fields' => array(
						array(
							'key' => 'field_bpalt_comp_name',
							'label' => __('Name', 'botphonic'),
							'name' => 'name',
							'type' => 'text',
						),
						array(
							'key' => 'field_bpalt_comp_logo',
							'label' => __('Logo', 'botphonic'),
							'name' => 'logo',
							'type' => 'image',
							'return_format' => 'id',
							'preview_size' => 'thumbnail',
							'mime_types' => 'svg,png,webp,jpg,jpeg',
						),
					),
				),
				array(
					'key' => 'field_bpalt_glance_rows',
					'label' => __('At-a-Glance Rows', 'botphonic'),
					'name' => 'glance_rows',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => __('Add row', 'botphonic'),
					'instructions' => __('Always-visible summary rows above the grouped feature table — the three or four things a buyer decides on (e.g. Best for, Pricing model, Rating). "Competitor Values" must match the Competitors order above.', 'botphonic'),
					'sub_fields' => array(
						array(
							'key' => 'field_bpalt_gr_label',
							'label' => __('Row Label', 'botphonic'),
							'name' => 'label',
							'type' => 'text',
							'wrapper' => array('width' => '30'),
						),
						array(
							'key' => 'field_bpalt_gr_type',
							'label' => __('Type', 'botphonic'),
							'name' => 'value_type',
							'type' => 'select',
							'choices' => array(
								'text' => __('Text', 'botphonic'),
								'yesno' => __('Yes / No', 'botphonic'),
								'rating' => __('Star rating (0–5)', 'botphonic'),
							),
							'default_value' => 'text',
							'allow_null' => 0,
							'wrapper' => array('width' => '20'),
						),
						array(
							'key' => 'field_bpalt_gr_brand',
							/* translators: %s: brand name. */
							'label' => sprintf(__('%s Value', 'botphonic'), $brand),
							'name' => 'botphonic_value',
							'type' => 'text',
							'wrapper' => array('width' => '25'),
						),
						array(
							'key' => 'field_bpalt_gr_competitor_values',
							'label' => __('Competitor Values (in Competitors order)', 'botphonic'),
							'name' => 'competitor_values',
							'type' => 'repeater',
							'layout' => 'table',
							'button_label' => __('Add value', 'botphonic'),
							'wrapper' => array('width' => '25'),
							'sub_fields' => array(
								array(
									'key' => 'field_bpalt_gr_cv_value',
									'label' => __('Value', 'botphonic'),
									'name' => 'value',
									'type' => 'text',
								),
							),
						),
					),
				),
				array(
					'key' => 'field_bpalt_comparison_categories',
					'label' => __('Feature Categories', 'botphonic'),
					'name' => 'comparison_categories',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => __('Add category', 'botphonic'),
					'instructions' => __('Each row becomes a collapsible group in the table, e.g. "Voice & call handling". All groups start closed, so the table opens at a readable height however many features it carries.', 'botphonic'),
					'sub_fields' => array(
						array(
							'key' => 'field_bpalt_cc_name',
							'label' => __('Category Name', 'botphonic'),
							'name' => 'category_name',
							'type' => 'text',
						),
						array(
							'key' => 'field_bpalt_cc_features',
							'label' => __('Features', 'botphonic'),
							'name' => 'features',
							'type' => 'repeater',
							'layout' => 'block',
							'button_label' => __('Add feature', 'botphonic'),
							'instructions' => __('Type "yes" or "no" for a tick or a cross, "yes: 50+ languages" for a tick with a caption, or free text (e.g. "2–4 weeks") to print it as-is. "Not published" renders as a neutral pill instead of a claim. "Competitor Values" must match the Competitors order.', 'botphonic'),
							'sub_fields' => array(
								array(
									'key' => 'field_bpalt_ccf_name',
									'label' => __('Feature Name', 'botphonic'),
									'name' => 'feature_name',
									'type' => 'text',
								),
								array(
									'key' => 'field_bpalt_ccf_brand',
									/* translators: %s: brand name. */
									'label' => sprintf(__('%s Value', 'botphonic'), $brand),
									'name' => 'botphonic_value',
									'type' => 'text',
								),
								array(
									'key' => 'field_bpalt_ccf_competitor_values',
									'label' => __('Competitor Values (in Competitors order)', 'botphonic'),
									'name' => 'competitor_values',
									'type' => 'repeater',
									'layout' => 'table',
									'button_label' => __('Add value', 'botphonic'),
									'sub_fields' => array(
										array(
											'key' => 'field_bpalt_ccf_cv_value',
											'label' => __('Value', 'botphonic'),
											'name' => 'value',
											'type' => 'text',
										),
									),
								),
							),
						),
					),
				),
				array(
					// Footnotes belong under the thing they annotate. A caveat
					// about how the latency row was measured, or which figures
					// are unverified, reads as a condition of the table once the
					// reader has seen it — put above, it is skipped.
					'key' => 'field_bpalt_comparison_note',
					'label' => __('Note Below the Table', 'botphonic'),
					'name' => 'comparison_note',
					'type' => 'wysiwyg',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
					'delay' => 1,
					'instructions' => __('Footnotes printed directly under the table, before the CTA — measurement caveats, what is vendor-stated, when the figures were last checked. Leave empty to omit.', 'botphonic'),
				),
				array(
					'key' => 'field_bpalt_comparison_cta_text',
					'label' => __('Comparison Table CTA Text', 'botphonic'),
					'name' => 'comparison_cta_text',
					'type' => 'text',
					'default_value' => 'Book a free demo',
					'instructions' => sprintf(
						/* translators: %s: brand name. */
						__('The button in the last row of the table, under the %s column.', 'botphonic'),
						$brand
					),
				),

				/* ---------------------------------------------------------
				   Tab: Platform write-ups
				   --------------------------------------------------------- */
				array(
					'key' => 'field_bpalt_tab_profiles',
					'label' => __('Platform Write-ups', 'botphonic'),
					'name' => 'bpalt_tab_profiles',
					'type' => 'tab',
					'placement' => 'top',
				),
				array(
					'key' => 'field_bpalt_profiles_heading',
					'label' => __('Write-ups Section Heading', 'botphonic'),
					'name' => 'profiles_heading',
					'type' => 'text',
					'instructions' => __('Optional heading above the first numbered platform, e.g. "7 best Twilio alternatives for voice AI". Leave empty to render the write-ups straight after the comparison table.', 'botphonic'),
				),
				array(
					'key' => 'field_bpalt_competitor_profiles',
					'label' => __('Platform Profiles', 'botphonic'),
					'name' => 'competitor_profiles',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => __('Add profile', 'botphonic'),
					'instructions' => sprintf(
						/* translators: %s: brand name. */
						__('One detailed write-up per platform covered, in display order. %s is usually listed first.', 'botphonic'),
						$brand
					),
					'sub_fields' => array(
						array(
							'key' => 'field_bpalt_cp_name',
							'label' => __('Name', 'botphonic'),
							'name' => 'name',
							'type' => 'text',
							'wrapper' => array('width' => '100'),
						),
						array(
							'key' => 'field_bpalt_cp_screenshot',
							'label' => __('Screenshot', 'botphonic'),
							'name' => 'screenshot',
							'type' => 'image',
							'return_format' => 'id',
							'preview_size' => 'medium',
						),
						/*
						 * One editor for the whole write-up, rather than a field
						 * per section. A comparison guide never has the same
						 * shape twice — one platform warrants a pricing
						 * breakdown, the next a migration note — so the
						 * structure belongs to the writer. The three blocks that
						 * cannot be typed are positioned with a token.
						 *
						 * 'delay' matters inside a repeater: without it every
						 * profile row boots its own TinyMCE instance on page
						 * load.
						 */
						array(
							'key' => 'field_bpalt_cp_content',
							'label' => __('Profile Content', 'botphonic'),
							'name' => 'content',
							'type' => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
							'delay' => 1,
							'instructions' => __('The whole write-up for this platform, in the order it should render. Use the Text tab for the wrappers below — the classes are what the design hangs off, so keep them.', 'botphonic')
								. '<br><code>&lt;div class="bpg-alt-profile__lead"&gt;…&lt;/div&gt;</code> — ' . __('prose under the numbered heading', 'botphonic')
								. '<br><code>&lt;h4 class="bpg-alt-profile__sub"&gt;What stands out&lt;/h4&gt;</code> — ' . __('any subheading, titled however you like', 'botphonic')
								. '<br><code>&lt;ul class="bpg-alt-profile__points"&gt;&lt;li&gt;&lt;strong&gt;Lead-in&lt;/strong&gt;: text&lt;/li&gt;&lt;/ul&gt;</code> — ' . __('a bullet list', 'botphonic')
								. '<br><code>&lt;div class="bpg-alt-profile__bestfor"&gt;…&lt;/div&gt;</code> — ' . __('tinted box; put a subheading and prose inside it', 'botphonic')
								. '<br><code>&lt;div class="bpg-alt-profile__verdict"&gt;…&lt;/div&gt;</code> — ' . __('closing box with the accent left border', 'botphonic')
								. '<br><code>&lt;div class="bpg-alt-box"&gt;…&lt;/div&gt;</code> — ' . __('the same box with no fixed job, for any passage worth lifting out. Add <code>bpg-alt-box--accent</code> to tint it, <code>bpg-alt-box--rail</code> for the left rule, <code>bpg-alt-box--lg</code> for a roomier one; they combine.', 'botphonic')
								. '<br><br>' . __('Three blocks are generated from the fields below rather than written here. Put each on its own line where you want it:', 'botphonic')
								. '<br><code>[alt-screenshot]</code> · <code>[alt-rating]</code> · <code>[alt-pros-cons]</code>'
								. '<br>' . __('A token can go inside one of the boxes above, not just between them. The house style is to put <code>[alt-rating]</code> as the last line inside the "Best for" box, so the score and who it suits read as one panel:', 'botphonic')
								. '<br><code>&lt;div class="bpg-alt-profile__bestfor"&gt;&lt;h4 class="bpg-alt-profile__sub"&gt;Best for&lt;/h4&gt;&lt;p&gt;…&lt;/p&gt;[alt-rating]&lt;/div&gt;</code>'
								. '<br>' . __('Leave a token out and that block still renders — the screenshot above this content, the rating and pros/cons after it.', 'botphonic'),
						),
						array(
							'key' => 'field_bpalt_cp_rating_value',
							'label' => __('Rating (out of 5)', 'botphonic'),
							'name' => 'rating_value',
							'type' => 'number',
							'min' => 0,
							'max' => 5,
							'step' => 0.1,
							'wrapper' => array('width' => '50'),
							'instructions' => __('Rendered where <code>[alt-rating]</code> sits in the content above, and on the archive card for the first profile.', 'botphonic'),
						),
						array(
							'key' => 'field_bpalt_cp_rating_source',
							'label' => __('Rating Source', 'botphonic'),
							'name' => 'rating_source',
							'type' => 'text',
							'default_value' => 'G2',
							'wrapper' => array('width' => '50'),
						),

						// Pros
						array(
							'key' => 'field_bpalt_cp_pros',
							'label' => __('Pros', 'botphonic'),
							'name' => 'pros',
							'type' => 'repeater',
							'layout' => 'block',
							'button_label' => __('Add pro', 'botphonic'),
							'instructions' => __('Pros and cons render together as one grid, where <code>[alt-pros-cons]</code> sits in the content above.', 'botphonic'),
							'sub_fields' => array(
								array(
									'key' => 'field_bpalt_cppr_title',
									'label' => __('Bold Lead-in', 'botphonic'),
									'name' => 'title',
									'type' => 'text',
								),
								array(
									'key' => 'field_bpalt_cppr_text',
									'label' => __('Text', 'botphonic'),
									'name' => 'text',
									'type' => 'wysiwyg',
									'tabs' => 'all',
									'toolbar' => 'inline',
									'media_upload' => 0,
									'delay' => 1,
									'instructions' => __('Renders as one bullet, so keep it to a single run of text.', 'botphonic'),
								),
							),
						),
						array(
							'key' => 'field_bpalt_pros_review_enable',
							'label' => __('Show a Review Under Pros', 'botphonic'),
							'name' => 'pros_review_enable',
							'type' => 'true_false',
							'ui' => 1,
						),
						array(
							'key' => 'field_bpalt_pros_review_quote',
							'label' => __('Pros Review Quote', 'botphonic'),
							'name' => 'pros_review_quote',
							'type' => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'inline',
							'media_upload' => 0,
							'delay' => 1,
							'instructions' => __('Renders inside the quote mark, so keep it to a single run of text.', 'botphonic'),
							'conditional_logic' => $pros_review_on,
						),
						array(
							'key' => 'field_bpalt_pros_review_author',
							'label' => __('Reviewer Name', 'botphonic'),
							'name' => 'pros_review_author',
							'type' => 'text',
							'wrapper' => array('width' => '50'),
							'conditional_logic' => $pros_review_on,
						),
						array(
							'key' => 'field_bpalt_pros_review_rating',
							'label' => __('Reviewer Rating (1–5)', 'botphonic'),
							'name' => 'pros_review_rating',
							'type' => 'number',
							'min' => 1,
							'max' => 5,
							'step' => 0.1,
							'wrapper' => array('width' => '50'),
							'instructions' => __('Leave empty when the quote carries no score — the stars are skipped rather than defaulted to five.', 'botphonic'),
							'conditional_logic' => $pros_review_on,
						),
						array(
							'key' => 'field_bpalt_pros_review_url',
							'label' => __('Review Link', 'botphonic'),
							'name' => 'pros_review_url',
							'type' => 'url',
							'instructions' => __('Link to the original review. The verification label below becomes a link to it.', 'botphonic'),
							'conditional_logic' => $pros_review_on,
						),
						array(
							'key' => 'field_bpalt_pros_review_label',
							'label' => __('Verification Label', 'botphonic'),
							'name' => 'pros_review_label',
							'type' => 'text',
							'placeholder' => 'Verified G2 review',
							'instructions' => __('The line under the quote. Leave empty to use "Verified [Rating Source] review".', 'botphonic'),
							'conditional_logic' => $pros_review_on,
						),

						// Cons
						array(
							'key' => 'field_bpalt_cp_cons',
							'label' => __('Cons', 'botphonic'),
							'name' => 'cons',
							'type' => 'repeater',
							'layout' => 'block',
							'button_label' => __('Add con', 'botphonic'),
							'sub_fields' => array(
								array(
									'key' => 'field_bpalt_cpco_title',
									'label' => __('Bold Lead-in', 'botphonic'),
									'name' => 'title',
									'type' => 'text',
								),
								array(
									'key' => 'field_bpalt_cpco_text',
									'label' => __('Text', 'botphonic'),
									'name' => 'text',
									'type' => 'wysiwyg',
									'tabs' => 'all',
									'toolbar' => 'inline',
									'media_upload' => 0,
									'delay' => 1,
									'instructions' => __('Renders as one bullet, so keep it to a single run of text.', 'botphonic'),
								),
							),
						),
						array(
							'key' => 'field_bpalt_cons_review_enable',
							'label' => __('Show a Review Under Cons', 'botphonic'),
							'name' => 'cons_review_enable',
							'type' => 'true_false',
							'ui' => 1,
						),
						array(
							'key' => 'field_bpalt_cons_review_quote',
							'label' => __('Cons Review Quote', 'botphonic'),
							'name' => 'cons_review_quote',
							'type' => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'inline',
							'media_upload' => 0,
							'delay' => 1,
							'instructions' => __('Renders inside the quote mark, so keep it to a single run of text.', 'botphonic'),
							'conditional_logic' => $cons_review_on,
						),
						array(
							'key' => 'field_bpalt_cons_review_author',
							'label' => __('Reviewer Name', 'botphonic'),
							'name' => 'cons_review_author',
							'type' => 'text',
							'wrapper' => array('width' => '50'),
							'conditional_logic' => $cons_review_on,
						),
						array(
							'key' => 'field_bpalt_cons_review_rating',
							'label' => __('Reviewer Rating (1–5)', 'botphonic'),
							'name' => 'cons_review_rating',
							'type' => 'number',
							'min' => 1,
							'max' => 5,
							'step' => 0.1,
							'wrapper' => array('width' => '50'),
							'instructions' => __('Leave empty when the quote carries no score.', 'botphonic'),
							'conditional_logic' => $cons_review_on,
						),
						array(
							'key' => 'field_bpalt_cons_review_url',
							'label' => __('Review Link', 'botphonic'),
							'name' => 'cons_review_url',
							'type' => 'url',
							'instructions' => __('Link to the original review. The verification label below becomes a link to it.', 'botphonic'),
							'conditional_logic' => $cons_review_on,
						),
						array(
							'key' => 'field_bpalt_cons_review_label',
							'label' => __('Verification Label', 'botphonic'),
							'name' => 'cons_review_label',
							'type' => 'text',
							'placeholder' => 'Verified G2 review',
							'instructions' => __('The line under the quote. Leave empty to use "Verified [Rating Source] review".', 'botphonic'),
							'conditional_logic' => $cons_review_on,
						),
					),
				),

				/*
				 * Inline CTA, rendered immediately after the FIRST profile —
				 * which is Botphonic's own — while the reader is still on the
				 * strongest part of the page. Same box as the end-of-article
				 * CTA. Leave the heading empty to hide it.
				 */
				array(
					'key' => 'field_bpalt_inline_cta_heading',
					'label' => __('Inline CTA Heading', 'botphonic'),
					'name' => 'inline_cta_heading',
					'type' => 'text',
					'instructions' => __('Shown in a highlighted box straight after the first write-up. Leave empty to hide the whole block.', 'botphonic'),
				),
				array(
					'key' => 'field_bpalt_inline_cta_text',
					'label' => __('Inline CTA Text', 'botphonic'),
					'name' => 'inline_cta_text',
					'type' => 'wysiwyg',
					'tabs' => 'all',
					'toolbar' => 'inline',
					'media_upload' => 0,
					'delay' => 1,
					'instructions' => __('One supporting line under the heading.', 'botphonic'),
					'conditional_logic' => $needs_inline_heading,
				),
				array(
					'key' => 'field_bpalt_inline_cta_button_text',
					'label' => __('Inline CTA Button Text', 'botphonic'),
					'name' => 'inline_cta_button_text',
					'type' => 'text',
					'default_value' => 'Start free trial',
					'wrapper' => array('width' => '50'),
					'conditional_logic' => $needs_inline_heading,
				),
				array(
					'key' => 'field_bpalt_inline_cta_url',
					'label' => __('Inline CTA Button URL', 'botphonic'),
					'name' => 'inline_cta_url',
					'type' => 'url',
					'instructions' => __('Falls back to the contact page when empty.', 'botphonic'),
					'wrapper' => array('width' => '50'),
					'conditional_logic' => $needs_inline_heading,
				),

				/* ---------------------------------------------------------
				   Tab: Verdict, CTA & FAQs
				   --------------------------------------------------------- */
				array(
					'key' => 'field_bpalt_tab_rest',
					'label' => __('Verdict, CTA & FAQs', 'botphonic'),
					'name' => 'bpalt_tab_rest',
					'type' => 'tab',
					'placement' => 'top',
				),
				array(
					'key' => 'field_bpalt_how_to_choose_title',
					'label' => __('How-to-Choose Title', 'botphonic'),
					'name' => 'how_to_choose_title',
					'type' => 'text',
					'default_value' => 'How to choose the right alternative',
				),
				array(
					'key' => 'field_bpalt_how_to_choose_content',
					'label' => __('How-to-Choose Content', 'botphonic'),
					'name' => 'how_to_choose_content',
					'type' => 'wysiwyg',
					'tabs' => 'all',
					'media_upload' => 0,
				),
				array(
					'key' => 'field_bpalt_final_verdict_title',
					'label' => __('Final Verdict Title', 'botphonic'),
					'name' => 'final_verdict_title',
					'type' => 'text',
					'default_value' => 'Final verdict',
				),
				array(
					'key' => 'field_bpalt_final_verdict_content',
					'label' => __('Final Verdict Content', 'botphonic'),
					'name' => 'final_verdict_content',
					'type' => 'wysiwyg',
					'tabs' => 'all',
					'media_upload' => 0,
				),
				array(
					'key' => 'field_bpalt_mid_cta_heading',
					'label' => __('End-of-Article CTA Heading', 'botphonic'),
					'name' => 'mid_cta_heading',
					'type' => 'text',
					'instructions' => __('Leave empty to hide the whole block.', 'botphonic'),
				),
				array(
					'key' => 'field_bpalt_mid_cta_text',
					'label' => __('End-of-Article CTA Text', 'botphonic'),
					'name' => 'mid_cta_text',
					'type' => 'wysiwyg',
					'tabs' => 'all',
					'toolbar' => 'inline',
					'media_upload' => 0,
					'delay' => 1,
					'instructions' => __('One supporting line under the heading.', 'botphonic'),
				),
				array(
					'key' => 'field_bpalt_mid_cta_button_text',
					'label' => __('End-of-Article CTA Button Text', 'botphonic'),
					'name' => 'mid_cta_button_text',
					'type' => 'text',
					'default_value' => 'Talk to sales',
					'wrapper' => array('width' => '50'),
				),
				array(
					'key' => 'field_bpalt_mid_cta_url',
					'label' => __('End-of-Article CTA Button URL', 'botphonic'),
					'name' => 'mid_cta_url',
					'type' => 'url',
					'instructions' => __('Falls back to the contact page when empty.', 'botphonic'),
					'wrapper' => array('width' => '50'),
				),
				array(
					'key' => 'field_bpalt_faqs',
					'label' => __('FAQs', 'botphonic'),
					'name' => 'faqs',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => __('Add FAQ', 'botphonic'),
					'instructions' => __('Rendered as native <details> elements, so only one answer is open at a time and the page needs no JavaScript for it.', 'botphonic'),
					'sub_fields' => array(
						array(
							'key' => 'field_bpalt_faq_question',
							'label' => __('Question', 'botphonic'),
							'name' => 'question',
							'type' => 'text',
						),
						array(
							// A wysiwyg because answers often want a link or a
							// short list, and the template allows both.
							'key' => 'field_bpalt_faq_answer',
							'label' => __('Answer', 'botphonic'),
							'name' => 'answer',
							'type' => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
							'delay' => 1,
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => botphonic_alt_post_type(),
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'active' => true,
			'description' => __('Structured content for a competitor comparison guide. Registered in PHP by inc/acf-alternatives.php — edit that file, not this screen.', 'botphonic'),
		)
	);
}
add_action('acf/init', 'botphonic_alt_register_fields');


/* ==========================================================================
   3. Editor configuration, scoped to this post type
   --------------------------------------------------------------------------
   Everything below is gated on botphonic_alt_is_editor_screen(), so the blog,
   the customer stories and every other editor on the site keep exactly the
   toolbar Advanced Editor Tools is configured with under
   Settings → Advanced Editor Tools. Nothing here changes that screen or its
   options; it only overrides the toolbar for Alternatives.
   ========================================================================== */

/**
 * Whether the current admin screen is editing an Alternatives post.
 *
 * get_current_screen() only exists once wp-admin is loaded, and an editor can be
 * booted on the front end too, so the guard is required rather than defensive.
 *
 * @return bool
 */
function botphonic_alt_is_editor_screen()
{
	if (!is_admin() || !function_exists('get_current_screen')) {
		return false;
	}

	$screen = get_current_screen();

	return ($screen && botphonic_alt_post_type() === $screen->post_type);
}

/**
 * Toolbar row 1 for the Alternatives editors.
 *
 * ACF builds its "Full" toolbar by running the core mce_buttons filters
 * (secure-custom-fields/includes/fields/class-acf-field-wysiwyg.php), and
 * Advanced Editor Tools hooks the same filters at priority 999 to inject
 * whatever is configured on its settings screen. Hooking at 1000 therefore
 * lands last and wins — but only for this post type, which is the whole point:
 * a comparison write-up needs a different set of tools from a blog post, and
 * neither should dictate the other.
 *
 * `styleselect` is the important addition. It exposes the Formats dropdown
 * defined in botphonic_alt_tinymce_style_formats() below, which is what turns
 * the wrapper markup documented in the Profile Content field into one click
 * instead of hand-typed HTML.
 *
 * @param array $buttons Buttons for the first row.
 * @return array
 */
function botphonic_alt_mce_buttons_1($buttons)
{
	if (!botphonic_alt_is_editor_screen()) {
		return $buttons;
	}

	return array(
		'formatselect',
		'styleselect',
		'bold',
		'italic',
		'bullist',
		'numlist',
		'blockquote',
		'link',
		'unlink',
	);
}
add_filter('mce_buttons', 'botphonic_alt_mce_buttons_1', 1000);

/**
 * Toolbar row 2 for the Alternatives editors.
 *
 * `code` is the Source code dialog. It ships with Advanced Editor Tools rather
 * than WordPress core (wp-includes/js/tinymce/plugins has no `code`), so it is
 * only offered when that plugin is active — otherwise the button would render
 * dead. The Text tab is always there as the fallback, since every field in the
 * group is registered with 'tabs' => 'all'.
 *
 * `pastetext` matters more here than on a blog post: these write-ups are
 * assembled from vendor sites and review pages, and pasting rich text straight
 * in is what drags foreign inline styles into the markup.
 *
 * @param array $buttons Buttons for the second row.
 * @return array
 */
function botphonic_alt_mce_buttons_2($buttons)
{
	if (!botphonic_alt_is_editor_screen()) {
		return $buttons;
	}

	$row = array('pastetext', 'removeformat', 'charmap', 'undo', 'redo');

	if (class_exists('Advanced_Editor_Tools')) {
		$row[] = 'code';
	}

	$row[] = 'wp_help';

	return $row;
}
add_filter('mce_buttons_2', 'botphonic_alt_mce_buttons_2', 1000);

/**
 * Drop the third and fourth rows.
 *
 * Advanced Editor Tools leaves them empty by default, but a site-wide change to
 * its settings should not silently add rows to a field that is only 8 lines
 * tall inside a repeater.
 *
 * @return array
 */
function botphonic_alt_mce_buttons_empty($buttons)
{
	return botphonic_alt_is_editor_screen() ? array() : $buttons;
}
add_filter('mce_buttons_3', 'botphonic_alt_mce_buttons_empty', 1000);
add_filter('mce_buttons_4', 'botphonic_alt_mce_buttons_empty', 1000);

/**
 * Hide the TinyMCE menu bar on the Alternatives ACF fields.
 *
 * Advanced Editor Tools ships with `menubar` in its user options by default, and
 * its mce_options() turns it on for every editor whose id is not
 * `classic-block` — which is every ACF wysiwyg field, since those get ids like
 * `acf-editor-3`.
 *
 * That matters because the menu bar is a second, unfiltered route to tools the
 * curated toolbar above deliberately leaves out: Insert → Table, Format →
 * colours, alignment. A table dropped into a profile write-up survives the save
 * (TinyMCE's default valid_elements permits it) but no rule in
 * assets/css/alternatives.css styles a bare table inside .bpg-alt-prose, so it
 * lands on the page unstyled. Closing the menu bar closes that route, and leaves
 * the toolbar as an honest description of what the field supports.
 *
 * `classic-block` is excluded on purpose: that is the main editor, not a field,
 * and AET has its own `menubar_block` preference for it which should be
 * respected. No other post type is affected, and AET's settings screen is not
 * modified — this only overrides the value for these editors.
 *
 * @param array  $settings  TinyMCE settings.
 * @param string $editor_id Editor instance id.
 * @return array
 */
function botphonic_alt_tinymce_chrome($settings, $editor_id = '')
{
	if (!botphonic_alt_is_editor_screen() || 'classic-block' === $editor_id) {
		return $settings;
	}

	$settings['menubar'] = false;

	return $settings;
}
add_filter('tiny_mce_before_init', 'botphonic_alt_tinymce_chrome', 99, 2);

/**
 * The Formats dropdown: the Alternatives design blocks, as one-click formats.
 *
 * These are the same five wrappers the Profile Content field documents. Having
 * them here means a writer never has to switch to the Text tab to get a "Best
 * for" box, and — more importantly — never has to remember the class name, which
 * is the one thing that silently breaks the layout when mistyped.
 *
 * `wrapper => true` on the two boxes is what lets a selection of several
 * paragraphs be wrapped in one <div>; without it TinyMCE would convert each
 * paragraph into its own div. The list format uses `selector` because the class
 * belongs on the <ul>, not on the list items.
 *
 * Scoped to this post type, so no other editor gains a dropdown full of classes
 * that would do nothing on its page.
 *
 * Runs at priority 99, not the default 10, and the reason is specific: the parent
 * theme's understrap_tiny_mce_before_init() (botphonic/inc/editor.php) appends
 * five Bootstrap formats — Lead Paragraph, Small, Blockquote, Blockquote Footer,
 * Cite — by merging into whatever `style_formats` already holds. A child theme's
 * functions.php loads before its parent's, so at equal priority that merge landed
 * after this assignment and the dropdown showed ten entries, five of them classes
 * (.lead, .blockquote-footer) that no Alternatives template styles. Running last
 * makes this list authoritative here and leaves the parent's formats intact
 * everywhere else.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function botphonic_alt_tinymce_style_formats($settings)
{
	if (!botphonic_alt_is_editor_screen()) {
		return $settings;
	}

	$formats = array(
		array(
			'title' => __('Lead paragraph block', 'botphonic'),
			'block' => 'div',
			'classes' => 'bpg-alt-profile__lead',
			'wrapper' => true,
		),
		array(
			'title' => __('Subheading (H4)', 'botphonic'),
			'block' => 'h4',
			'classes' => 'bpg-alt-profile__sub',
		),
		array(
			'title' => __('Bullet list (styled)', 'botphonic'),
			'selector' => 'ul',
			'classes' => 'bpg-alt-profile__points',
		),
		array(
			'title' => __('"Best for" box', 'botphonic'),
			'block' => 'div',
			'classes' => 'bpg-alt-profile__bestfor',
			'wrapper' => true,
		),
		array(
			'title' => __('Verdict box', 'botphonic'),
			'block' => 'div',
			'classes' => 'bpg-alt-profile__verdict',
			'wrapper' => true,
		),
		// The generic member of the same family (alternatives.css § 3.1). The two
		// boxes above are the house patterns with fixed jobs; this one is for a
		// passage that needs lifting out of the copy and has no other name.
		array(
			'title' => __('Highlighted box', 'botphonic'),
			'block' => 'div',
			'classes' => 'bpg-alt-box',
			'wrapper' => true,
		),
		array(
			'title' => __('Highlighted box (accent, with rule)', 'botphonic'),
			'block' => 'div',
			'classes' => 'bpg-alt-box bpg-alt-box--accent bpg-alt-box--rail',
			'wrapper' => true,
		),
	);

	// Assigned, not merged: a dropdown mixing these with the parent theme's
	// Bootstrap formats invites picking one that does nothing on this template.
	$settings['style_formats'] = wp_json_encode($formats);
	$settings['style_formats_merge'] = false;

	return $settings;
}
add_filter('tiny_mce_before_init', 'botphonic_alt_tinymce_style_formats', 99);

/**
 * TinyMCE strips attributes it has no rule for, which would quietly delete the
 * classes the Alternatives layout hangs off — a writer would save a profile and
 * watch its "Best for" box turn into a bare paragraph.
 *
 * Every element the profile content and the free-text sections are documented to
 * use is whitelisted here, and only on this post type, so no other editor on the
 * site has its allowed markup widened.
 *
 * Appends to `extended_valid_elements` rather than assigning it. Advanced Editor
 * Tools does not set that key at all (its mce_options() only touches toolbars,
 * tables, autop and font sizes), but a future plugin might, and clobbering it
 * would strip markup somewhere else on the site.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function botphonic_alt_tinymce_elements($settings)
{
	if (!botphonic_alt_is_editor_screen()) {
		return $settings;
	}

	$allow = array(
		'div[id|class]',
		'p[id|class]',
		'h2[id|class]',
		'h3[id|class]',
		'h4[id|class]',
		'h5[id|class]',
		'ul[id|class]',
		'ol[id|class]',
		'li[id|class]',
		'span[id|class]',
		'strong[class]',
		'em[class]',
		'a[href|title|target|rel|id|class]',
	);

	$existing = empty($settings['extended_valid_elements']) ? '' : $settings['extended_valid_elements'] . ',';

	$settings['extended_valid_elements'] = $existing . implode(',', $allow);

	return $settings;
}
add_filter('tiny_mce_before_init', 'botphonic_alt_tinymce_elements');

/**
 * Show the design blocks inside the editor.
 *
 * Without a stylesheet in the TinyMCE iframe the Formats dropdown above applies
 * classes that look like nothing, so a writer cannot see whether a "Best for"
 * box wrapped one paragraph or three.
 *
 * `mce_css` takes a comma-separated list and is appended to rather than replaced,
 * because WordPress and any active plugin have already put their own sheets in
 * it. add_editor_style() was the alternative and was rejected: it applies to
 * every classic editor on the site, and these rules have no business on a blog
 * post.
 *
 * @param string $stylesheets Comma-separated stylesheet URLs.
 * @return string
 */
function botphonic_alt_mce_css($stylesheets)
{
	if (!botphonic_alt_is_editor_screen()) {
		return $stylesheets;
	}

	$url = add_query_arg(
		'ver',
		botphonic_child_asset_ver('/assets/css/alternatives-editor.css'),
		get_stylesheet_directory_uri() . '/assets/css/alternatives-editor.css'
	);

	// The Manrope the front end uses, so line breaks in the editor fall where
	// they will on the page.
	if (defined('BOTPHONIC_FONT_SRC')) {
		$url = BOTPHONIC_FONT_SRC . ',' . $url;
	}

	return empty($stylesheets) ? $url : $stylesheets . ',' . $url;
}
add_filter('mce_css', 'botphonic_alt_mce_css');
