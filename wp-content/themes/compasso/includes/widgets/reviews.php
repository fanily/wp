<?php
class G7_Reviews_Widget extends G7_Widget {

	function __construct() {

		$this->base_posts = new G7_Posts;
		$this->base_posts->orderby = false;
		$this->base_posts->cat = false;
		$this->base_posts->show_excerpts = false;

		$this->widget = array(
			'id_base' => 'g7_reviews',
			'name' => G7_NAME . ' - Reviews',
			'description' => __('A list of latest reviews', 'g7theme')
		);

		parent::__construct();
	}

	function set_fields() {
		$this->fields = $this->base_posts->get_fields();
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_title . $title . $after_title;

		if (g7_option('enable_ratings')) {
			$query_args = $this->base_posts->get_query_args($instance);
			$query_args['meta_key'] = '_g7_enable_rating';
			$query_args['meta_value'] = '1';
			$posts = new WP_Query($query_args);
			echo $this->base_posts->loop($posts, $instance);
		}

		echo $after_widget;
		wp_reset_postdata();
	}

}
