<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

class BotPhonic_Integration_Widget extends Widget_Base
{

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

	protected function register_controls()
	{

		$this->start_controls_section(
			'tabs_section',
			[
				'label' => __('BotPhonic Integration Items', 'botphonic'),
			]
		);

		$this->add_control(
			'title_text',
			[
				'label' => 'Title',
				'type' => Controls_Manager::TEXT,
			]
		);
		$this->add_control(
			'desc_text',
			[
				'label' => 'Description',
				'type' => Controls_Manager::TEXTAREA,
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label' => __('Title HTML Tag', 'botphonic'),
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'p' => 'p',
					'div' => 'div',
					'span' => 'span',
				],
				'default' => "h2"

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
				'label' => __('Column Count', 'botphonic'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'min' => 1,
					'max' => 6,
				],
				'step' => 1,
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
				'label' => __('Column Gap (px)', 'botphonic'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'min' => 8,
					'max' => 50,
				],
				'step' => 1,
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
				'label' => __('Top Margin (px)', 'botphonic'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'min' => 0,
					'max' => 100,
				],
				'step' => 1,
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
				'name' => 'title_typography',
				'label' => __('Title Typography', 'botphonic'),
				'selector' => '{{WRAPPER}} .botphonic-call_featured',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Title Color', 'botphonic'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .botphonic-call_featured' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$json_path = plugins_url('assets/integration/integration_data_with_images.json', __DIR__);
		// if (!file_exists($json_path)) return 'Integration data not found.';
		$data = json_decode(file_get_contents($json_path), true);
		if (!$data) return 'Invalid JSON.';
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes('title_text', 'basic');
		$this->add_inline_editing_attributes('desc_text', 'advanced');



?>

<div class="row">
	<div class="col-lg-3">
		<div class="botphonic-call-sidebar">
			<div class="botphonic-call-filters-clone"></div>
			<div class="botphonic-call-filters">
				<div class="explore">
					<span> Explore </span>
					<ul class="botphonic-call-ul">
						<li> <a class="active" href="#" data-filter="all">All</a></li>
					</ul>
				</div>

				<div class="best-for">
					<span> Best For </span>
					<ul class="botphonic-call-ul">
						<li> <a href="#" data-filter="For Sales Teams">For Sales Teams</a></li>
						<li> <a href="#" data-filter="For Support Teams">For Support Teams</a></li>
					</ul>
				</div>

				<div class="categories">
					<span> Categories </span>
					<ul class="botphonic-call-ul">
						<li> <a href="#" data-filter="Sales Automation">Sales Automation</a></li>
						<li> <a href="#" data-filter="CRM">CRM</a></li>
						<li> <a href="#" data-filter="HelpDesk">HelpDesk</a></li>
						<li> <a href="#" data-filter="Productivity">Productivity</a></li>
						<li> <a href="#" data-filter="E-commerce">E-commerce</a></li>
						<li> <a href="#" data-filter="Data and Reporting">Data and Reporting</a></li>
						<li> <a href="#" data-filter="Survey and Marketing">Survey and Marketing</a></li>
						<li> <a href="#" data-filter="Recruitment and ERP">Recruitment and ERP</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="col col-lg-9">
		<div class="botphonic-call_integration">
			<div class="all-integration">
				<?php printf('<%1$s ' . $this->get_render_attribute_string('title_text') . 'class="botphonic-call_featured allintegration">%2$s</%1$s>', $settings['title_tag'], esc_html($settings['title_text'])); ?>
				<div <?= $this->get_render_attribute_string('desc_text'); ?>><?= $settings['desc_text']; ?></div>

				<div class="botphonic-search">
					<input type="text" class="form-control" placeholder="Search Integration">
				</div>

				<ul class="botphonic-integration-ul">
					<?php foreach ($data as $item) { ?>
					<li data-category="<?= esc_attr($item["data-category"]); ?>" class="botphonic-integration-li" data-name="<?= esc_attr($item["data-name"]); ?>">
						<div class="card hover">                                     
							<?php if(1 == 2) { ?>
							<a href="<?= $item["link"]; ?>">
								<img width="750" height="300" class=" lazy-loaded lazyloaded" decoding="async" src="<?= esc_url($item["image"]); ?>" data-src="<?= esc_url($item["image"]); ?>" alt="<?= esc_attr($item["data-name"]); ?>">
							</a>
							<?php } ?>
							<div class="card-body">
								<h4 class="h4">
									<?php if(1 == 2) { ?> <a href="<?= $item["link"]; ?>" class="content_a"><?= esc_html($item["content_a"]); ?></a>  <?php } ?>
									<a href="#" class="content_a"><?= esc_html($item["content_a"]); ?></a>
								</h4>
								<small><?= esc_html($item["category"]); ?></small>
							</div>

						</div>
					</li>
					<?php } ?>
				</ul>
				<h4 id="botphonic-integration_nokeyword">No result</h4>
			</div>
		</div>
	</div>
</div>
<?php
	}
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new BotPhonic_Integration_Widget());
