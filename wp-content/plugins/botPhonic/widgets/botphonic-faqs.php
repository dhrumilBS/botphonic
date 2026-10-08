<?php

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class BotPhonic_Widget_FAQ extends Widget_Base
{
	public function get_name()
	{
		return 'botphonic-faq';
	}

	public function get_title()
	{
		return __('BotPhonic FAQ', 'botphonic');
	}

	public function get_categories()
	{
		return ['botphonic-widgets'];
	}

	public function get_style_depends()
	{
		return ['botphonic-faqs-css'];
	}

	public function get_script_depends()
	{
		return ['botphonic-faqs-js'];
	}

	protected function register_controls()
	{
		$this->start_controls_section(
			'faq_settings',
			[
				'label' => esc_html__('FAQ settings', 'botphonic'),
			]
		);

		$this->add_control(
			'faq_schema',
			[
				'label' => esc_html__('FAQ Schema', 'botphonic'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		if (!function_exists('botphonic_get_faq_rows') || !function_exists('botphonic_render_faq_group')) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$post_id = absint(get_the_ID());
		if (!$post_id) {
			$post_id = absint(get_queried_object_id());
		}

		$faq_html = botphonic_render_faq_group(
			botphonic_get_faq_rows($post_id),
			array(
				'schema' => 'yes' === ($settings['faq_schema'] ?? ''),
			)
		);

		if ('' === $faq_html) {
			return;
		}
		?>
		<div class="faq-section accordion-list">
			<div class="faq-content">
				<?= $faq_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the shared renderer. ?>
			</div>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register(new BotPhonic_Widget_FAQ());
