<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

class BotPhonic_How_To extends Widget_Base
{
	public function get_name()
	{
		return 'botphonic-how-to';
	}

	public function get_title()
	{
		return __('Botphonic How To', 'botphonic');
	}

	public function get_style_depends()
	{
		return ['botphonic-how-to'];
	}

	public function get_script_depends()
	{
		return ['botphonic-how-to'];
	}

	public function get_icon()
	{
		return 'eicon-number-field';
	}

	public function get_categories()
	{
		return ['botphonic-widgets'];
	}

	protected function register_controls()
	{
		$this->start_controls_section('howto_meta', [
			'label' => __('HowTo Settings', 'botphonic'),
		]);

		$this->add_control('howto_title', [
			'label' => __('HowTo Title', 'botphonic'),
			'type' => Controls_Manager::TEXT,
			'default' => '',
		]);

		$this->add_control('howto_description', [
			'label' => __('HowTo Description', 'botphonic'),
			'type' => Controls_Manager::TEXTAREA,
			'default' => '',
		]);

		$this->add_control('tab_position', [
			'label'   => __('Tab Position', 'botphonic'),
			'type'    => Controls_Manager::CHOOSE,
			'default' => 'left',
			'options' => [
				'left' => [
					'title' => __('Left', 'botphonic'),
					'icon'  => 'eicon-h-align-left',
				],
				'right' => [
					'title' => __('Right', 'botphonic'),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'toggle' => false,
		]);

		$this->add_control('enable_schema', [
			'label' => __('HowTo Schema', 'botphonic'),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => __('Yes', 'botphonic'),
			'label_off' => __('No', 'botphonic'),
			'return_value' => 'yes',
			'default' => 'yes',
		]);

		$this->end_controls_section();

		$this->start_controls_section('howto_steps', [
			'label' => __('Steps', 'botphonic'),
		]);

		$repeater = new Repeater();

		$repeater->add_control('step_title', [
			'label' => __('Step Title', 'botphonic'),
			'type' => Controls_Manager::TEXT,
			'default' => 'Run email & call sequences faster',
		]);

		$repeater->add_control('step_text', [
			'label' => __('Step Description', 'botphonic'),
			'type' => Controls_Manager::TEXTAREA,
			'default' => 'Create smart outreach sequences blended with AI-powered calls and follow-ups.',
		]);

		$repeater->add_control('step_image', [
			'label' => __('Preview Image', 'botphonic'),
			'type' => Controls_Manager::MEDIA,
		]);

		$this->add_control('steps', [
			'type' => Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'default' => [],
			'title_field' => '{{{ step_title }}}',
		]);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$tab_position = $settings['tab_position'] ?? 'left';

		if (empty($settings['steps'])) return;
		$schema_steps = []; ?>

<!-- HOW TO SECTION -->
<section class="steps-section">
	<div class="container">
		<div class="row justify-content-center mb-4">
			<div class="col-md-8 text-center">
				<?php if (!empty($settings['howto_title'])): ?>
				<h2><?= wp_kses_post($settings['howto_title']); ?></h2>
				<?php endif; ?>
				<?php if (!empty($settings['howto_description'])): ?>
				<p><?php echo esc_html($settings['howto_description']); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<div class="howto-tabs howto-tabs-<?= esc_attr($tab_position); ?> custom-tabs">
			<div class="row">
				<div class="col-12 col-lg-6">
					<div class="bf-left howto-tabs_nav custom-tab-buttons">
						<?php
		foreach ($settings['steps'] as $index => $step):
		$active = $index === 0 ? 'active' : '';
		$img = $step['step_image'] ?? '';
		$schema_steps[] = [
			'@type' => 'HowToStep',
			'name'  => wp_strip_all_tags($step['step_title']),
			'text'  => wp_strip_all_tags($step['step_text']),
		];
						?>
						<div class="bf-step custom-tab <?= esc_attr($active); ?>" data-index="<?= esc_attr($index); ?>" tabindex="0">
							<?php if (!empty($img['id'])): ?>
							<div class="bf-mobile"> <?= wp_get_attachment_image( $img['id'], 'full' ); ?> </div>
							<?php endif; ?>
							<h4 class="custom-tab-title"><?= esc_html($step['step_title']); ?></h4>
							<p class="custom-tab-desc"><?= esc_html($step['step_text']); ?></p>
							<div class="progress-bar"></div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="col-12 col-lg-6">
					<div class="bf-right howto-tabs_content custom-tab-content">
						<?php foreach ($settings['steps'] as $index => $step):
		$active = $index === 0 ? 'is-active' : 'is-hidden';
						?>
						<div class="custom-content <?= esc_attr($active); ?>" data-index="<?= esc_attr($index); ?>">
							<?= wp_get_attachment_image( $step['step_image']['id'], 'full' ); ?>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php if (!empty($settings['enable_schema']) && $settings['enable_schema'] === 'yes') : ?>
<!-- HOW TO SCHEMA -->
<script type="application/ld+json">
			<?= wp_json_encode([
							'@context'    => 'https://schema.org',
							'@type'       => 'HowTo',
							'name'        => $settings['howto_title'],
							'description' => $settings['howto_description'],
							'step'        => $schema_steps,
						], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>
<?php endif; ?>

<?php
	}

	public function _content_template()
	{
?>
<# if ( ! settings.steps || ! settings.steps.length ) { return; } #>

<!-- HOW TO SECTION -->
<section class="steps-section">
	<div class="container">
		<div class="row justify-content-center mb-4">
			<div class="col-md-8 text-center">
				<# if ( settings.howto_title ) { #>
				<h2>{{{ settings.howto_title }}}</h2>
				<# } #>
				<# if ( settings.howto_description ) { #>
				<p>{{ settings.howto_description }}</p>
				<# } #>
			</div>
		</div>

		<div class="howto-tabs howto-tabs-{{ settings.tab_position }} custom-tabs">
			<div class="row">
				<div class="col-12 col-lg-6">
					<div class="bf-left howto-tabs_nav custom-tab-buttons">
						<# _.each( settings.steps, function( step, index ) {
							var active = index === 0 ? 'active' : '';
						#>
						<div class="bf-step custom-tab {{ active }}" data-index="{{ index }}" tabindex="0">
							<# if ( step.step_image && step.step_image.url ) { #>
							<div class="bf-mobile">
								<img src="{{ step.step_image.url }}" alt="{{ step.step_title }}">
							</div>
							<# } #>
							<h4 class="custom-tab-title">{{ step.step_title }}</h4>
							<p class="custom-tab-desc">{{ step.step_text }}</p>
							<div class="progress-bar"></div>
						</div>
						<# }); #>
					</div>
				</div>
				<div class="col-12 col-lg-6">
					<div class="bf-right howto-tabs_content custom-tab-content">
						<# _.each( settings.steps, function( step, index ) {
							var active = index === 0 ? 'is-active' : 'is-hidden';
						#>
						<div class="custom-content {{ active }}" data-index="{{ index }}">
							<# if ( step.step_image && step.step_image.url ) { #>
							<img src="{{ step.step_image.url }}" alt="{{ step.step_title }}">
							<# } #>
						</div>
						<# }); #>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
	}
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\BotPhonic_How_To());