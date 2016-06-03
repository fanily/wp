<?php
class G7_Subpages_Widget extends G7_Widget {

	function __construct() {

		$this->widget = array(
			'id_base' => 'g7_subpages',
			'name' => G7_NAME . ' - Subpages',
			'description' => __('A list of sub pages', 'g7theme')
		);

		parent::__construct();
	}

	function set_fields() {
		$fields = array(
			'title' => array(
				'type' => 'text',
				'label' => __('Title', 'g7theme'),
				'std' => '',
				'desc' => 'If empty, parent page title will be used'
			),
			'parent' => array(
				'type' => 'text',
				'label' => __('Parent Page', 'g7theme'),
				'std' => '',
				'desc' => 'Enter a Page ID, or leave empty for current page'
			),
			'sortby' => array(
				'type' => 'select',
				'label' => __('Sort by', 'g7theme'),
				'std' => 'menu_order',
				'options' => array(
					'post_title' => 'Page title',
					'menu_order, post_title' => 'Page order',
					'ID' => 'Page ID',
				)
			),
			'exclude' => array(
				'type' => 'text',
				'label' => 'Exclude',
				'std' => '',
				'desc' => 'Page IDs, separated by commas.'
			)
		);
		$this->fields = $fields;
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;

		$title = apply_filters('widget_title', $instance['title']);
		if (empty($title)) {
			$title = get_the_title();
		}
		echo $before_title . $title . $after_title;

		$parent = $instance['parent'];
		if (empty($parent)) {
			$parent = get_the_ID();
		}

		$li = wp_list_pages(array(
			'echo' => 0,
			'title_li' => '',
			'child_of' => $parent,
			'exclude' => $instance['exclude'],
			'sort_column' => $instance['sortby']
		));

		echo "<ul>$li</ul>";

		echo $after_widget;
	}

}
