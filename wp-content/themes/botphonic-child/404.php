<?php
/**
 * The template for displaying 404 pages.
 *
 * Standalone one-screen layout: no theme header/footer. wp_head() and
 * wp_footer() are kept so the title, meta and robots output still work.
 *
 * @package Botphonic_Child
 */

defined('ABSPATH') || exit;

$request_path = isset($_SERVER['REQUEST_URI'])
	? esc_html(wp_parse_url(wp_unslash($_SERVER['REQUEST_URI']), PHP_URL_PATH))
	: '';

$pricing_url = get_permalink(2027);
$pricing_url = $pricing_url ? $pricing_url : home_url('/pricing/');
$stories_slug = defined('CUSTOME_STORY_SLUG') ? CUSTOME_STORY_SLUG : 'success-stories';

$links = [
	'Explore AI voice agents' => home_url('/'),
	'See pricing' => $pricing_url,
	'Customer success stories' => home_url('/' . $stories_slug . '/'),
	'Read the blog' => home_url('/blog/'),
];

// Waveform: lively on the left, flat on the right (the call "dropping").
$bars = [10, 18, 30, 22, 38, 26, 14, 34, 42, 24, 16, 30, 20, 12, 6, 3, 3, 3, 3, 3, 3, 3];
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="bp-err-html">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<style>
	html.bp-err-html, body.bp-err-page { margin: 0; padding: 0 !important; background: #F5F9FF; }
	.bp-err { position: relative; overflow: hidden; box-sizing: border-box; min-height: 100vh; min-height: 100dvh; display: flex; flex-direction: column; padding: clamp(20px, 3vh, 32px) var(--bpg-gutter, 24px); color: var(--text-color, #5C6972); font-family: var(--site-font, "Manrope", system-ui, sans-serif); background: linear-gradient(135deg, #EEF4FF 0%, #FFFFFF 48%, #FFF1EA 100%); }
	.bp-err *, .bp-err *::before, .bp-err *::after { box-sizing: border-box; }
	.bp-err::before { content: ""; position: absolute; width: 55vw; aspect-ratio: 1; left: -18vw; top: -20vw; background: radial-gradient(closest-side, rgba(37, 99, 235, .16), transparent 72%); pointer-events: none; }
	.bp-err::after { content: ""; position: absolute; width: 50vw; aspect-ratio: 1; right: -14vw; bottom: -18vw; background: radial-gradient(closest-side, rgba(255, 126, 85, .18), transparent 70%); pointer-events: none; }
	.bp-err > * { position: relative; z-index: 1; }
	.bp-err__brand { display: inline-flex; align-items: center; align-self: flex-start; color: var(--primary, #072032); font-size: 22px; font-weight: 800; letter-spacing: -0.01em; text-decoration: none; }
	.bp-err__brand img { display: block; max-height: 36px; width: auto; }
	.bp-err__inner { flex: 1; width: 100%; max-width: var(--bpg-max, 1200px); margin: 0 auto; display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr); align-items: center; gap: clamp(32px, 6vw, 88px); padding: 16px 0; }
	.bp-err__pill { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px; padding: 7px 14px; border-radius: 999px; background: #fff; border: 1px solid var(--bpg-coral-line, rgba(255,126,85,.28)); color: var(--bpg-coral-2, #F2673B); font-size: 14px; font-weight: 700; box-shadow: var(--bpg-shadow-xs, 0 1px 2px rgba(11,35,56,.05)); }
	.bp-err__pill i { width: 8px; height: 8px; border-radius: 50%; background: var(--bpg-coral, #FF7E55); }
	.bp-err__code { margin: 0; font-size: clamp(80px, 11vw, 140px); font-weight: 800; line-height: 1; letter-spacing: -0.04em; background: linear-gradient(135deg, #2563EB 0%, #4F46E5 60%, #FF7E55 130%); -webkit-background-clip: text; background-clip: text; color: transparent; }
	.bp-err .bp-err__title { margin: 8px 0 14px; color: var(--primary, #072032); font-size: clamp(26px, 3vw, 40px); line-height: 1.2; letter-spacing: -0.01em; text-transform: none; }
	.bp-err__text { margin: 0 0 28px; max-width: 48ch; font-size: 17px; line-height: 1.6; }
	.bp-err__actions { display: flex; flex-wrap: wrap; align-items: center; gap: 12px 24px; }
	.bp-err a.bp-err__btn { display: inline-flex; align-items: center; justify-content: center; min-height: 52px; padding: 0 28px; border-radius: var(--bpg-r-sm, 12px); background: var(--bpg-coral, #FF7E55); color: #fff; font-weight: 700; text-decoration: none; box-shadow: 0 10px 24px -12px rgba(242, 103, 59, .7); transition: background .2s ease, transform .2s ease; }
	.bp-err a.bp-err__btn:hover { background: var(--bpg-coral-2, #F2673B); color: #fff; transform: translateY(-1px); }
	.bp-err a.bp-err__ghost { color: var(--accent, #2563EB); font-weight: 700; text-decoration: none; }
	.bp-err a.bp-err__ghost:hover { color: var(--bpg-accent-2, #1D4ED8); text-decoration: underline; text-underline-offset: 4px; }
	.bp-err a:focus-visible { outline: 2px solid var(--accent, #2563EB); outline-offset: 3px; }
	.bp-err__card { background: rgba(255, 255, 255, .85); border: 1px solid var(--bpg-line, rgba(11,35,56,.10)); border-radius: var(--bpg-r-lg, 22px); padding: 22px; box-shadow: var(--bpg-shadow-lg, 0 32px 64px -38px rgba(11,35,56,.34)); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); }
	.bp-err__card-head { display: flex; align-items: center; gap: 14px; padding-bottom: 18px; border-bottom: 1px solid var(--bpg-line, rgba(11,35,56,.10)); }
	.bp-err__avatar { display: grid; place-items: center; flex: none; width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #2563EB, #4F46E5); color: #fff; }
	.bp-err__who { display: flex; flex-direction: column; flex: 1; min-width: 0; line-height: 1.3; }
	.bp-err__who strong { color: var(--primary, #072032); font-size: 16px; }
	.bp-err__who span { color: var(--bpg-coral-2, #F2673B); font-size: 14px; font-weight: 700; }
	.bp-err__time { font-variant-numeric: tabular-nums; font-weight: 700; color: var(--bpg-ink-3, #6B7A88); }
	.bp-err__wave { display: flex; align-items: center; justify-content: space-between; gap: 5px; height: 88px; margin: 18px 0; }
	.bp-err__wave span { flex: 1; max-width: 8px; height: var(--h, 3px); border-radius: 99px; background: linear-gradient(180deg, #60a5fa, #2563EB); animation: bp-err-flat .6s ease forwards; animation-delay: calc(900ms + var(--i) * 45ms); }
	.bp-err__wave span:nth-child(n+15) { background: #cbd5e1; }
	@keyframes bp-err-flat { to { height: 3px; } }
	.bp-err__bubble { margin: 0 0 16px; padding: 14px 16px; border-radius: 4px 16px 16px 16px; background: var(--bpg-tint, #F4F8FD); border: 1px solid var(--bpg-tint-2, #EAF1FB); color: var(--primary, #072032); font-size: 15px; line-height: 1.5; font-weight: 500; }
	.bp-err__card-foot { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 8px 16px; font-size: 14px; color: var(--bpg-ink-3, #6B7A88); }
	.bp-err__card-foot code { max-width: 100%; overflow-wrap: anywhere; padding: 4px 10px; border-radius: 8px; background: var(--bpg-tint-2, #EAF1FB); color: var(--primary, #072032); font-size: 13px; }
	/* One-screen layout on desktop: no scrolling. */
	@media (min-width: 992px) and (min-height: 600px) {
		html.bp-err-html, body.bp-err-page { height: 100%; overflow: hidden; }
		.bp-err { height: 100vh; height: 100dvh; }
	}
	@media (max-width: 991px) {
		.bp-err__inner { grid-template-columns: 1fr; text-align: center; }
		.bp-err__text, .bp-err__links { margin-left: auto; margin-right: auto; }
		.bp-err__actions, .bp-err__links ul { justify-content: center; }
		.bp-err__brand { align-self: center; }
		.bp-err__card { text-align: left; max-width: 520px; width: 100%; margin: 0 auto; }
	}
	@media (prefers-reduced-motion: reduce) {
		.bp-err__wave span { animation: none; height: 3px; }
		.bp-err a.bp-err__btn:hover { transform: none; }
	}
</style>
</head>
<body <?php body_class('bp-err-page'); ?>>
<?php wp_body_open(); ?>

<main id="main" class="bp-err bp-err--404">

	<a class="bp-err__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
		<?php echo has_custom_logo() ? wp_kses_post(get_custom_logo()) : esc_html(get_bloginfo('name')); ?>
	</a>

	<div class="bp-err__inner">

		<div class="bp-err__copy">
			<span class="bp-err__pill"><i aria-hidden="true"></i>Page not found</span>
			<p class="bp-err__code" aria-hidden="true">404</p>
			<h1 class="bp-err__title">We couldn&rsquo;t find that page</h1>
			<p class="bp-err__text">The link may be mistyped, or the page may have moved. Head back home or jump to one of our most visited pages.</p>

			<div class="bp-err__actions">
				<a class="bp-err__btn" href="<?php echo esc_url(home_url('/')); ?>">Back to homepage</a>
				<a class="bp-err__ghost" href="<?php echo esc_url(home_url('/contact/')); ?>">Talk to our team</a>
			</div>
		</div>

		<div class="bp-err__card" role="img" aria-label="A phone call from Botphonic AI that has ended">
			<div class="bp-err__card-head">
				<span class="bp-err__avatar" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
				</span>
				<span class="bp-err__who">
					<strong>Botphonic AI</strong>
					<span>Call ended</span>
				</span>
				<span class="bp-err__time">00:00</span>
			</div>

			<div class="bp-err__wave" aria-hidden="true">
				<?php foreach ($bars as $i => $h): ?>
						<span style="--h:<?php echo (int) $h; ?>px;--i:<?php echo (int) $i; ?>"></span>
				<?php endforeach; ?>
			</div>

			<p class="bp-err__bubble">I couldn&rsquo;t reach that page. Let me take you somewhere useful.</p>

			<div class="bp-err__card-foot">
				<span>Page not found</span>
				<?php if ($request_path): ?>
						<code><?php echo $request_path; // Escaped above. ?></code>
				<?php endif; ?>
			</div>
		</div>

	</div>
</main>

<?php wp_footer(); ?>
</body>
</html>