<?php
class G7_Social_Widget extends G7_Widget {

	private $max_items = 6;

	function __construct() {

		$this->widget = array(
			'id_base' => 'g7_social',
			'name' => G7_NAME . ' - Social',
			'description' => __('Social media icons', 'g7theme'),
			'control_ops' => array('width' => 350)
		);

		parent::__construct();
	}

	function set_fields() {
		$fields['title'] = array(
			'type' => 'text',
			'label' => __('Title', 'g7theme'),
			'std' => ''
		);
		$fields['size'] = array(
			'type' => 'select',
			'label' => __('Icon size', 'g7theme'),
			'options' => array(
				'16' => '16px',
				'32' => '32px'
			),
			'std' => '32',
			'class' => ''
		);
		$fields['style'] = array(
			'type' => 'select',
			'label' => __('Style', 'g7theme'),
			'options' => array(
				'horizontal' => 'Horizontal',
				'vertical' => 'Vertical'
			),
			'std' => '1',
			'class' => ''
		);

		$icons[''] = '';
		foreach (unserialize(G7_SOCIAL) as $v) {
			$icons[$v] = $v;
		}

		for ($i = 1; $i <= $this->max_items; $i++) {
			$fields['icon'.$i] = array(
				'type' => 'select',
				'label' => 'Icon '.$i,
				'options' => $icons,
				'std' => '',
				'class' => '',
				'attributes' => 'style="width:300px"'
			);
			$fields['url'.$i] = array(
				'type' => 'text',
				'label' => 'URL '.$i,
				'std' => '',
				'class' => '',
				'attributes' => 'style="width:300px"'
			);
			$fields['text'.$i] = array(
				'type' => 'text',
				'label' => 'Text '.$i,
				'std' => '',
				'class' => '',
				'attributes' => 'style="width:300px"'
			);
		}
		$this->fields = $fields;
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;

		$title = apply_filters('widget_title', $instance['title']);
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}
		?>

		<ul class="<?php echo $instance['style']; ?>">
			<?php for ($i = 1; $i <= $this->max_items; $i++) : ?>
			<?php if (!empty($instance['url'.$i])) : ?>
			<li>
				<a href="<?php echo $instance['url'.$i]; ?>"<?php if ($instance['style'] == 'horizontal') : ?> title="<?php echo $instance['text'.$i]; ?>"<?php endif; ?>>
					<img src="<?php echo PARENT_URL; ?>/images/social/<?php echo $instance['size']; ?>px/<?php echo $instance['icon'.$i]; ?>.png" alt="<?php echo $instance['icon'.$i]; ?>">
					<?php if ($instance['style'] == 'vertical') : ?>
						<?php echo $instance['text'.$i]; ?>
					<?php endif; ?>
				</a>
			</li>
			<?php endif; ?>
			<?php endfor; ?>
		</ul>
		<div class="clear"></div>

		<?php

		echo $after_widget;
	}

}
