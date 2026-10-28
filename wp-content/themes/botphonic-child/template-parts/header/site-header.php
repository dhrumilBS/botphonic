<?php
/**
 * Site header and mega menu.
 *
 * One menu for every screen: a mega menu at 1200px and up, a full-screen
 * accordion below that. Content comes from botphonic_header_nav().
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bph_nav = botphonic_header_nav();
$bph_logo_id = (int) get_theme_mod('custom_logo');
?>
<header id="wrapper-navbar" class="bph" data-bph>
	<div class="bph__bar">
		<a class="bph__logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name') . ' home'); ?>">
			<?php
			if ($bph_logo_id) {
				echo wp_get_attachment_image($bph_logo_id, 'full', false, array(
					'class'    => 'bph__logo-img',
					'alt'      => '',
					'loading'  => 'eager',
					'decoding' => 'async',
				));
			} else {
				echo '<span class="bph__logo-text">' . esc_html(get_bloginfo('name')) . '</span>';
			}
			?>
		</a>

		<nav class="bph__nav" id="bph-nav" aria-label="Main">
			<ul class="bph__menu">
				<?php foreach ($bph_nav as $bph_key => $bph_menu) : ?>
					<?php if (empty($bph_menu['columns'])) : ?>
						<li class="bph__item">
							<a class="bph__top" <?php echo botphonic_header_link_attrs($bph_menu['url']); ?>><?php echo esc_html($bph_menu['label']); ?></a>
						</li>
						<?php continue; ?>
					<?php endif; ?>

					<?php $bph_panel_id = 'bph-panel-' . sanitize_html_class($bph_key); ?>
					<li class="bph__item bph__item--mega<?php echo botphonic_header_menu_has_current($bph_menu) ? ' is-current' : ''; ?>" data-bph-item>
						<button type="button" class="bph__top bph__trigger" aria-expanded="false" aria-controls="<?php echo esc_attr($bph_panel_id); ?>" data-bph-trigger>
							<span><?php echo esc_html($bph_menu['label']); ?></span>
							<?php echo botphonic_header_icon('chevron-down', 16); ?>
							<?php echo botphonic_header_icon('plus', 20); ?>
							<?php echo botphonic_header_icon('minus', 20); ?>
						</button>

						<div class="bph__panel" id="<?php echo esc_attr($bph_panel_id); ?>">
							<div class="bph-card">
								<div class="bph-card__grid">
									<?php foreach ($bph_menu['columns'] as $bph_i => $bph_col) : ?>
										<?php
										$bph_label_id = $bph_panel_id . '-col-' . $bph_i;
										$bph_has_label = !empty($bph_col['label']);
										$bph_col_class = 'bph-col' . (!empty($bph_col['span']) ? ' bph-col--span-' . (int) $bph_col['span'] : '');
										?>
										<div class="<?php echo esc_attr($bph_col_class); ?>">
											<?php if ($bph_has_label) : ?>
												<p class="bph-col__label" id="<?php echo esc_attr($bph_label_id); ?>"><?php echo esc_html($bph_col['label']); ?></p>
											<?php endif; ?>

											<ul class="bph-col__list<?php echo !empty($bph_col['items']) ? ' bph-col__list--rich' : ''; ?>"<?php echo $bph_has_label ? ' aria-labelledby="' . esc_attr($bph_label_id) . '"' : ''; ?>>
												<?php foreach ((array) ($bph_col['items'] ?? array()) as $bph_link) : ?>
													<li>
														<a class="bph-mi" <?php echo botphonic_header_link_attrs($bph_link['url']); ?>>
															<span class="bph-mi__icon"><?php echo botphonic_header_icon($bph_link['icon']); ?></span>
															<span class="bph-mi__text">
																<span class="bph-mi__title"><?php echo esc_html($bph_link['title']); ?></span>
																<span class="bph-mi__desc"><?php echo esc_html($bph_link['desc']); ?></span>
															</span>
														</a>
													</li>
												<?php endforeach; ?>

												<?php foreach ((array) ($bph_col['links'] ?? array()) as $bph_link) : ?>
													<li><a class="bph-ml" <?php echo botphonic_header_link_attrs($bph_link['url']); ?>><?php echo esc_html($bph_link['title']); ?></a></li>
												<?php endforeach; ?>

												<?php if (!empty($bph_col['more'])) : ?>
													<li>
														<a class="bph-ml bph-ml--more" <?php echo botphonic_header_link_attrs($bph_col['more']['url']); ?>>
															<?php echo esc_html($bph_col['more']['title']); ?>
															<?php echo botphonic_header_icon('arrow-right', 16); ?>
														</a>
													</li>
												<?php endif; ?>
											</ul>
										</div>
									<?php endforeach; ?>

									<?php if (!empty($bph_menu['feature'])) : ?>
										<?php $bph_feature = $bph_menu['feature']; ?>
										<div class="bph-feature">
											<span class="bph-badge bph-badge--dark"><?php echo esc_html($bph_feature['badge']); ?></span>
											<p class="bph-feature__title"><?php echo esc_html($bph_feature['title']); ?></p>

											<?php if (!empty($bph_feature['text'])) : ?>
												<p class="bph-feature__text"><?php echo esc_html($bph_feature['text']); ?></p>
											<?php endif; ?>

											<?php if (!empty($bph_feature['stats'])) : ?>
												<dl class="bph-feature__stats">
													<?php foreach ($bph_feature['stats'] as $bph_stat) : ?>
														<div>
															<dt><?php echo esc_html($bph_stat['label']); ?></dt>
															<dd><?php echo esc_html($bph_stat['value']); ?></dd>
														</div>
													<?php endforeach; ?>
												</dl>
											<?php endif; ?>

											<a class="bph-btn bph-btn--white" href="<?php echo esc_url($bph_feature['cta']['url']); ?>">
												<?php echo !empty($bph_feature['cta']['icon']) ? botphonic_header_icon($bph_feature['cta']['icon'], 16) : ''; ?>
												<?php echo esc_html($bph_feature['cta']['title']); ?>
											</a>
										</div>
									<?php endif; ?>
								</div>

								<?php if (!empty($bph_menu['footer'])) : ?>
									<?php $bph_footer = $bph_menu['footer']; ?>
									<div class="bph-card__foot">
										<p class="bph-card__note">
											<?php if (!empty($bph_footer['badge'])) : ?>
												<span class="bph-badge"><?php echo esc_html($bph_footer['badge']); ?></span>
											<?php endif; ?>
											<span><?php echo esc_html($bph_footer['text']); ?></span>
										</p>
										<a class="bph-card__link" href="<?php echo esc_url($bph_footer['link']['url']); ?>"><?php echo esc_html($bph_footer['link']['title']); ?></a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

			<a class="bph__signin bph__signin--menu" href="<?php echo esc_url(BOTPHONIC_SIGNIN_URL); ?>">Sign in</a>
		</nav>

		<div class="bph__actions">
			<a class="bph__top bph__signin" href="<?php echo esc_url(BOTPHONIC_SIGNIN_URL); ?>">Sign in</a>
			<a class="bph-btn bph-btn--accent bph__demo" href="<?php echo esc_url(BOTPHONIC_DEMO_URL); ?>">Book a demo</a>
			<a class="bph-btn bph-btn--coral bph__trial" href="<?php echo esc_url(BOTPHONIC_REGISTER_URL); ?>">Start free trial</a>
			<button type="button" class="bph__toggle" aria-expanded="false" aria-controls="bph-nav" aria-label="Open menu" data-bph-toggle>
				<?php echo botphonic_header_icon('menu', 24); ?>
				<?php echo botphonic_header_icon('close', 24); ?>
			</button>
		</div>
	</div>

	<div class="bph__cta-bar">
		<a class="bph-btn bph-btn--accent" href="<?php echo esc_url(BOTPHONIC_DEMO_URL); ?>">Book a demo</a>
		<a class="bph-btn bph-btn--coral" href="<?php echo esc_url(BOTPHONIC_REGISTER_URL); ?>">Start free trial</a>
	</div>
</header><!-- #wrapper-navbar -->
