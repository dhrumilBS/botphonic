<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

class BotPhonic_Integration_Widget extends Widget_Base
{
	/**
	 * Tags the Title HTML Tag control is allowed to output.
	 * Anything not in this list falls back to h2.
	 */
	const ALLOWED_TITLE_TAGS = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span'];

	public function get_name()
	{
		return 'botphonic-integration';
	}

	public function get_title()
	{
		return __('BotPhonic Integration', 'botphonic');
	}

	public function get_icon()
	{
		return 'eicon-tabs';
	}

	public function get_categories()
	{
		return ['botphonic-widgets'];
	}

	public function get_style_depends()
	{
		return ['botphonic-integration-css'];
	}

	public function get_script_depends()
	{
		return ['jquery', 'botphonic-integration-js'];
	}

	/**
	 * Read the integration list from disk.
	 *
	 * The original used plugins_url() + file_get_contents(), which issued a real
	 * outbound HTTP request on every render. That is slow, fails when
	 * allow_url_fopen is off, and breaks on any environment sitting behind HTTP
	 * auth (staging, local). Read from the filesystem instead.
	 *
	 * Result is cached for the request and in a transient across requests.
	 */
	private function get_integration_data()
	{
		static $memo = null;

		if (null !== $memo) {
			return $memo;
		}

		$cached = get_transient('botphonic_integration_data');
		if (is_array($cached)) {
			$memo = $cached;
			return $memo;
		}

		$path = plugin_dir_path(__DIR__) . 'assets/integration/integration_data_with_images.json';

		if (!file_exists($path) || !is_readable($path)) {
			$memo = [];
			return $memo;
		}

		$decoded = json_decode(file_get_contents($path), true);
		$memo    = is_array($decoded) ? $decoded : [];

		// Short TTL so content edits show up quickly; bump or delete the
		// transient from your deploy script if the JSON is updated in code.
		set_transient('botphonic_integration_data', $memo, HOUR_IN_SECONDS);

		return $memo;
	}

	/**
	 * Filter groups, defined once so the sidebar and the mobile select stay
	 * in sync. Keys are the data-filter values, values are the visible labels.
	 */
	private function get_filter_groups()
	{
		return [
			[
				'key'    => 'explore',
				'label'  => __('Explore', 'botphonic'),
				'items'  => [
					'all' => __('All Integrations', 'botphonic'),
				],
			],
			[
				'key'    => 'best-for',
				'label'  => __('Best For', 'botphonic'),
				'items'  => [
					'For Sales Teams'   => __('For Sales Teams', 'botphonic'),
					'For Support Teams' => __('For Support Teams', 'botphonic'),
				],
			],
			[
				'key'    => 'categories',
				'label'  => __('Categories', 'botphonic'),
				'items'  => [
					'Sales Automation'     => __('Sales Automation', 'botphonic'),
					'CRM'                  => __('CRM', 'botphonic'),
					'HelpDesk'             => __('HelpDesk', 'botphonic'),
					'Productivity'         => __('Productivity', 'botphonic'),
					'E-commerce'           => __('E-commerce', 'botphonic'),
					'Data and Reporting'   => __('Data and Reporting', 'botphonic'),
					'Survey and Marketing' => __('Survey and Marketing', 'botphonic'),
					'Recruitment and ERP'  => __('Recruitment and ERP', 'botphonic'),
				],
			],
		];
	}

	protected function register_controls()
	{

		$this->start_controls_section(
			'tabs_section',
			[
				'label' => __('Botphonic Integration Items', 'botphonic'),
			]
		);

		$this->add_control(
			'title_text',
			[
				'label'   => __('Title', 'botphonic'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('All Integrations', 'botphonic'),
			]
		);

		$this->add_control(
			'desc_text',
			[
				'label' => __('Description', 'botphonic'),
				'type'  => Controls_Manager::TEXTAREA,
			]
		);

		$this->add_control(
			'search_placeholder',
			[
				'label'   => __('Search Placeholder', 'botphonic'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Search integrations', 'botphonic'),
			]
		);

		$this->add_control(
			'empty_text',
			[
				'label'       => __('Empty State Text', 'botphonic'),
				'type'        => Controls_Manager::TEXT,
				'default'     => __('No integrations match that search. Try a different name or category.', 'botphonic'),
				'description' => __('Shown when a search or filter returns nothing.', 'botphonic'),
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => __('Title HTML Tag', 'botphonic'),
				'type'    => Controls_Manager::SELECT2,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'p'    => 'p',
					'div'  => 'div',
					'span' => 'span',
				],
				'default' => 'h2',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'style_section',
			[
				'label' => __('Style', 'botphonic'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'col_count',
			[
				'label'   => __('Column Count', 'botphonic'),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min' => 1,
						'max' => 6,
					],
				],
				'default' => [
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .botphonic-call_integration' => '--col: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'col_gap',
			[
				'label'   => __('Column Gap (px)', 'botphonic'),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min' => 8,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .botphonic-call_integration' => '--gap: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'margin_top',
			[
				'label'   => __('Top Margin (px)', 'botphonic'),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 24,
				],
				'selectors' => [
					'{{WRAPPER}} .botphonic-call_integration' => '--top: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __('Title Typography', 'botphonic'),
				'selector' => '{{WRAPPER}} .botphonic-call_featured',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __('Title Color', 'botphonic'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .botphonic-call_featured' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$data = $this->get_integration_data();

		if (empty($data)) {
			// render() echoes, it does not return — a returned string was silently
			// discarded before. Only surface the problem to editors.
			if (Plugin::$instance->editor->is_edit_mode()) {
				echo '<div class="elementor-alert elementor-alert-warning">'
					. esc_html__('Integration data could not be loaded. Check assets/integration/integration_data_with_images.json.', 'botphonic')
					. '</div>';
			}
			return;
		}

		$settings = $this->get_settings_for_display();

		$title_tag = in_array($settings['title_tag'], self::ALLOWED_TITLE_TAGS, true)
			? $settings['title_tag']
			: 'h2';

		$default_title = isset($settings['title_text']) ? $settings['title_text'] : '';
		$widget_id     = $this->get_id();

		$this->add_inline_editing_attributes('title_text', 'basic');
		$this->add_inline_editing_attributes('desc_text', 'advanced');

		// Push the classes through the render attribute instead of concatenating a
		// second class="" onto the tag — the old version emitted two class
		// attributes (and no space before the second) in the editor, which meant
		// the title lost .allintegration and the JS could not find it.
		$this->add_render_attribute('title_text', 'class', ['botphonic-call_featured', 'allintegration']);
		$this->add_render_attribute('title_text', 'data-default-label', $default_title);

		$groups = $this->get_filter_groups();
?>

<div class="row botphonic-integration-wrap">
	<div class="col-lg-3">

		<div class="botphonic-call-sidebar">
			<div class="botphonic-call-filters">
				<?php foreach ($groups as $group) : ?>
				<div class="<?= esc_attr($group['key']); ?>">
					<p class="botphonic-filter-title"><?= esc_html($group['label']); ?></p>
					<ul class="botphonic-call-ul">
						<?php foreach ($group['items'] as $value => $label) : ?>
						<li>
							<button type="button"
								class="botphonic-filter-btn<?= ('all' === $value) ? ' active' : ''; ?>"
								data-filter="<?= esc_attr($value); ?>"
								aria-pressed="<?= ('all' === $value) ? 'true' : 'false'; ?>"><?= esc_html($label); ?></button>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="botphonic-call-filters-mobile">
			<label class="botphonic-visually-hidden" for="botphonic-filter-select-<?= esc_attr($widget_id); ?>">
				<?= esc_html__('Filter integrations', 'botphonic'); ?>
			</label>
			<select id="botphonic-filter-select-<?= esc_attr($widget_id); ?>" class="form-control botphonic-filter-select">
				<?php foreach ($groups as $group) : ?>
					<?php if ('explore' === $group['key']) : ?>
						<?php foreach ($group['items'] as $value => $label) : ?>
						<option value="<?= esc_attr($value); ?>"><?= esc_html($label); ?></option>
						<?php endforeach; ?>
					<?php else : ?>
					<optgroup label="<?= esc_attr($group['label']); ?>">
						<?php foreach ($group['items'] as $value => $label) : ?>
						<option value="<?= esc_attr($value); ?>"><?= esc_html($label); ?></option>
						<?php endforeach; ?>
					</optgroup>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</div>

	</div>

	<div class="col col-lg-9">
		<div class="botphonic-call_integration">
			<div class="all-integration">

				<?php printf(
					'<%1$s %2$s>%3$s</%1$s>',
					esc_attr($title_tag),
					$this->get_render_attribute_string('title_text'),
					esc_html($default_title)
				); ?>

				<div <?= $this->get_render_attribute_string('desc_text'); ?>>
					<?= wp_kses_post($settings['desc_text']); ?>
				</div>

				<div class="botphonic-search">
					<label class="botphonic-visually-hidden" for="botphonic-search-<?= esc_attr($widget_id); ?>">
						<?= esc_html__('Search integrations', 'botphonic'); ?>
					</label>
					<input type="search"
						id="botphonic-search-<?= esc_attr($widget_id); ?>"
						class="form-control botphonic-search-input"
						autocomplete="off"
						placeholder="<?= esc_attr($settings['search_placeholder']); ?>">
				</div>

				<ul class="botphonic-integration-ul">
					<?php foreach ($data as $item) :
						$name     = isset($item['data-name']) ? $item['data-name'] : '';
						$cats     = isset($item['data-category']) ? $item['data-category'] : '';
						$link     = isset($item['link']) ? $item['link'] : '';
						$label    = isset($item['content_a']) ? $item['content_a'] : '';
						$category = isset($item['category']) ? $item['category'] : '';
						$image    = isset($item['image']) ? $item['image'] : '';
					?>
					<li class="botphonic-integration-li"
						data-category="<?= esc_attr($cats); ?>"
						data-name="<?= esc_attr($name); ?>">
						<a href="<?= esc_url($link); ?>">
							<div class="card hover">
								<?php if (!empty($image)) : ?>
								<div class="top">
									<img src="<?= esc_url($image); ?>"
										alt="<?= esc_attr($label); ?>"
										loading="lazy" decoding="async">
								</div>
								<?php endif; ?>
								<div class="card-body">
									<h4 class="h4"><?= esc_html($label); ?></h4>
									<small><?= esc_html($category); ?></small>
								</div>
							</div>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>

				<p class="botphonic-integration-empty" role="status" hidden>
					<?= esc_html($settings['empty_text']); ?>
				</p>

			</div>
		</div>
	</div>
</div>
<?php
	}
}

// register_widget_type() was deprecated in Elementor 3.5.
if (method_exists(Plugin::instance()->widgets_manager, 'register')) {
	Plugin::instance()->widgets_manager->register(new BotPhonic_Integration_Widget());
} else {
	Plugin::instance()->widgets_manager->register_widget_type(new BotPhonic_Integration_Widget());
}