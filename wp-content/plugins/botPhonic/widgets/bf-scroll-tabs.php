<?php
if (!defined('ABSPATH')) exit;

class BF_Scroll_Tabs extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'bf_scroll_tabs';
	}

	public function get_title()
	{
		return 'BF Scroll Tabs';
	}

	public function get_icon()
	{
		return 'eicon-tabs';
	}

	public function get_categories()
	{
		return ['botphonic-widgets'];
	}

	public function get_script_depends()
	{
		return ['bf-scroll-tabs'];
	}

	public function get_style_depends()
	{
		return ['bf-scroll-tabs'];
	}

	protected function register_controls()
	{

		$this->start_controls_section(
			'section_content',
			[
				'label' => 'Content',
			]
		);

		$this->add_control(
			'tab_position',
			[
				'label' => 'Tab Position',
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => 'Top',
					'left' => 'Left',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control('tab_name', [
			'label' => 'Tab Name',
			'type' => \Elementor\Controls_Manager::TEXT,
			'label_block' => true
		]);

		$repeater->add_control('tab_desc', [
			'label' => 'Tab Description',
			'type' => \Elementor\Controls_Manager::TEXT,
			'label_block' => true
		]);

		$repeater->add_control('title', [
			'label' => 'Title',
			'type' => \Elementor\Controls_Manager::TEXT,
			'label_block' => true
		]);

		$repeater->add_control('text', [
			'label' => 'Text',
			'type' => \Elementor\Controls_Manager::WYSIWYG,
		]);

		$repeater->add_control('image', [
			'label' => 'Image',
			'type' => \Elementor\Controls_Manager::MEDIA,
		]);

		$repeater->add_control('cta_text', [
			'label' => 'CTA Text',
			'type' => \Elementor\Controls_Manager::TEXT,
			'label_block' => true

		]);

		$repeater->add_control('cta_url', [
			'label' => 'CTA URL',
			'type' => \Elementor\Controls_Manager::URL,
		]);

		$this->add_control('tabs', [
			'label' => 'Tabs',
			'type' => \Elementor\Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'title_field' => '{{{ tab_name }}}',
		]);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$tabs = $settings['tabs'];
		$layout = $settings['tab_position'];
		$widget_id = $this->get_id();
?>

<section class="bf-layout-<?php echo esc_attr($layout); ?>" id="<?php echo esc_attr('bf-' . $widget_id); ?>">
	<div class="bf-scroll-container">
		<?php if (!empty($tabs)) : ?>
		<div class="bf-sticky-section">
			<nav class="bf-tabs-nav">
				<div class="bf-tabs-wrapper">
					<?php foreach ($tabs as $index => $item): ?>
					<button class="bf-tab-button <?php echo $index === 0 ? 'bf-active' : ''; ?>" data-index="<?php echo esc_attr($index); ?>">
						<span class="bf-tab-name">
							<?php echo esc_html($item['tab_name']); ?>
						</span>
						<?php if(!empty($item['tab_desc'])) { ?>
						<span class="bf-tab-description">
							<?php echo esc_html($item['tab_desc']); ?>
						</span>
						<?php } ?>
					<span class="bf-tab-indicator"></span>
					</button>
					<?php endforeach; ?>
				</div>
			</nav>
			<div class="bf-content-area">
				<?php foreach ($tabs as $index => $item): ?>
				<div class="bf-content-panel <?php echo $index === 0 ? 'bf-active' : ''; ?>" data-index="<?php echo esc_attr($index); ?>">
					<div class="bf-content-inner">
						<div class="bf-content-grid">
							<div class="bf-panel-text-col">
								<h3> <?php echo !empty($item['title']) ? esc_html($item['title']) : ''; ?> </h3>
								<p> <?php echo !empty($item['text']) ? $item['text'] : ''; ?> </p>

								<?php if (!empty($item['cta_text']) && !empty($item['cta_url']['url'])): ?>
								<a href="<?php echo esc_url($item['cta_url']['url']); ?>" class="bf-panel-cta"<?php echo !empty($item['cta_url']['is_external']) ? 'target="_blank"' : ''; ?> <?php echo !empty($item['cta_url']['nofollow']) ? 'rel="nofollow"' : ''; ?>>
									<?php echo esc_html($item['cta_text']); ?>
								</a>
								<?php endif; ?>
							</div>

							<div class="bf-square-img-wrapper">
								<?php if (!empty($item['image']['url'])): ?> <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title']); ?>" /> <?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php
	}
}

\Elementor\Plugin::instance()->widgets_manager->register(new BF_Scroll_Tabs());