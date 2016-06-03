<?php
/**
 * Class for widgets
 * Individual widgets extends from this class instead of from WP_Widget directly
 *
 */
class G7_Widget extends WP_Widget {

	protected $widget;
	protected $fields;

	function __construct() {
		$this->set_fields();
		parent::WP_Widget(
			$this->widget['id_base'],
			$this->widget['name'],
			array(
				'description' => $this->widget['description']
			),
			isset($this->widget['control_ops']) ? $this->widget['control_ops'] : array()
		);
	}

    function update($new_instance, $old_instance) {
		$instance = wp_parse_args($new_instance, $old_instance);

		//for checkboxes: if unchecked, set the value to 0
		foreach ((array)$this->fields as $k => $v) {
			if ($v['type'] == 'checkbox') {
				$instance[$k] = isset($new_instance[$k]) ? 1 : 0;
			}
		}

		return $instance;
    }

    function set_fields() {
    	$this->fields = array();
    }

    function form($instance) {

		foreach ($this->fields as $k => $v) {

			echo '<p>';

			if (in_array($v['type'], array('text', 'textarea', 'select', 'category'))) {
				echo '<label for="' . $this->get_field_id($k) .'">' . $v['label'] . ':</label>' . "\n";
			}

			$value = $instance ? $instance[$k] : $v['std'];

			switch ($v['type']) {

				case 'text':
					printf(
						'<input type="text" name="%s" id="%s" value="%s" class="%s" %s />',
						$this->get_field_name($k),
						$this->get_field_id($k),
						$value,
						isset($v['class']) ? $v['class'] : 'widefat',
						isset($v['attributes']) ? $v['attributes'] : ''
					);
					break;

				case 'textarea':
					printf(
						'<textarea name="%s" id="%s" class="%s" %s>%s</textarea>',
						$this->get_field_name($k),
						$this->get_field_id($k),
						isset($v['class']) ? $v['class'] : 'widefat',
						isset($v['attributes']) ? $v['attributes'] : '',
						$value
					);
					break;

				case 'select':
					printf(
						'<select name="%s" id="%s" class="%s">',
						$this->get_field_name($k),
						$this->get_field_id($k),
						isset($v['class']) ? $v['class'] : 'widefat'
					);
					foreach ((array)$v['options'] as $k2 => $v2) {
						printf(
							'<option value="%s"%s>%s</option>',
							$k2,
							selected($value, $k2, false),
							$v2
						);
					}
					echo '</select>';
					break;

				case 'category':
					wp_dropdown_categories(
						array(
							'hide_empty' => 0,
							'name' => $this->get_field_name($k),
							'id' => $this->get_field_id($k),
							'class' => 'widefat',
							'hierarchical' => 1,
							'show_option_all' => __('All Categories', 'g7theme'),
							'selected' => $value
						)
					);
					break;

				case 'checkbox':
					printf(
						'<input id="%s" class="checkbox" type="checkbox" value="1" name="%s" %s>
						<label for="%s">%s</label>',
						$this->get_field_id($k),
						$this->get_field_name($k),
						checked($value, true, false),
						$this->get_field_id($k),
						$v['label']
					);
					break;

				case 'custom':
					break;

			}

			if (isset($v['desc'])) {
				echo '<br><small>' . $v['desc'] . '</small>';
			}
			echo '</p>';
		}
    }

}

/**
 * Base class for all post related widgets
 */
class G7_Posts {

	var $orderby = true;
	var $cat = true;
	var $limit = true;
	var $show_thumbnails = true;
	var $show_ratings = true;
	var $show_dates = true;
	var $show_commentlinks = true;
	var $show_excerpts = true;

	function get_fields() {
		$fields['title'] = array(
			'type' => 'text',
			'label' => __('Title', 'g7theme'),
			'std' => ''
		);
		if ($this->orderby) {
			$fields['orderby'] = array(
				'type' => 'select',
				'label' => __('Post order', 'g7theme'),
				'options' => array(
					1 => __('Recent', 'g7theme'),
					2 => __('Popular', 'g7theme'),
					3 => __('Random', 'g7theme')
				),
				'std' => 1
			);
		}
		if ($this->cat) {
			$fields['cat'] = array(
				'type' => 'category',
				'label' => __('Filter by Category', 'g7theme'),
				'std' => 0
			);
		}
		if ($this->limit) {
			$fields['limit'] = array(
				'type' => 'text',
				'label' => __('Number of posts to show', 'g7theme'),
				'std' => 5,
				'class' => '',
				'attributes' => 'size="3"'
			);
		}
		if ($this->show_thumbnails) {
			$fields['show_thumbnails'] = array(
				'type' => 'checkbox',
				'label' => __('Show thumbnails', 'g7theme'),
				'std' => 1
			);
		}
		if ($this->show_ratings) {
			$fields['show_ratings'] = array(
				'type' => 'checkbox',
				'label' => __('Show ratings (if available)', 'g7theme'),
				'std' => 1
			);
		}
		if ($this->show_dates) {
			$fields['show_dates'] = array(
				'type' => 'checkbox',
				'label' => __('Show dates', 'g7theme'),
				'std' => 1
			);
		}
		if ($this->show_commentlinks) {
			$fields['show_commentlinks'] = array(
				'type' => 'checkbox',
				'label' => __('Show comment links', 'g7theme'),
				'std' => 1
			);
		}
		if ($this->show_excerpts) {
			$fields['show_excerpts'] = array(
				'type' => 'select',
				'label' => __('Show excerpts', 'g7theme'),
				'options' => array(
					0 => __('No', 'g7theme'),
					1 => __('Show on first item', 'g7theme'),
					2 => __('Show on all items', 'g7theme')
				),
				'std' => 0
			);
		}

		return $fields;
	}

	function get_query_args($instance) {
		$orderby = 'date';
		if ($this->orderby) {
			switch ($instance['orderby']) {
				case 2:
					$orderby = 'comment_count';
					break;
				case 3:
					$orderby = 'rand';
					break;
				case 1:
				default:
					$orderby = 'date';
			}
		}
		$order = 'DESC';

		$query_args['showposts'] = $instance['limit'];
		if ($this->cat) {
			$query_args['cat'] = $instance['cat'];
		}
		$query_args['orderby'] = $orderby;
		$query_args['order'] = $order;

		return $query_args;
	}

	function loop($posts, $instance, $class = '') {
		$loop = '';
		$lightbox = g7_option('lightbox', 0);
		$thumbnail_link = 1;
		if ($lightbox) {
			$thumbnail_link = 2;
		}

		if ($posts->have_posts()) {
			$loop .= sprintf(
				'<ul%s>',
				$class ? ' class="'.$class.'"' : ''
			);

			$counter = 1;
			while ($posts->have_posts()) {
				$posts->the_post();

				$loop .= '<li class="post">';

				if (!empty($instance['show_thumbnails'])) {
					$loop .= '<div class="block-image side">' . g7_image(45, 45, $thumbnail_link, false) . '</div>';
				}

				$loop .= sprintf(
					'<h3><a href="%s">%s</a></h3>',
					get_permalink(),
					get_the_title()
				);

				if (!empty($instance['show_ratings'])) {
					$loop .= g7_post_rating();
				}

				if (!empty($instance['show_dates'])) {
					$loop .= sprintf(
						'<span class="date">%s</span>',
						get_the_time(get_option('date_format'))
					);
				}

				if (!empty($instance['show_commentlinks'])) {
					$loop .= sprintf(
						'<span class="comments-link"><a href="%s">%s</a></span>',
						get_comments_link(),
						get_comments_number()
					);
				}

				$loop .= '<div class="clear"></div>';

				if (!empty($instance['show_excerpts'])) {
					if (($instance['show_excerpts'] == 1 && $counter == 1) || $instance['show_excerpts'] == 2) {
						$loop .= sprintf(
							'<div class="excerpt">%s</div>',
							wp_trim_words(get_the_content(), 30)
						);
					}
				}

				$loop .= '</li>';

				$counter++;
			}

			$loop .= '</ul>';
		}

		return $loop;
	}

}

require_once(PARENT_DIR . '/includes/widgets/comments.php');
require_once(PARENT_DIR . '/includes/widgets/flickr.php');
require_once(PARENT_DIR . '/includes/widgets/posts.php');
require_once(PARENT_DIR . '/includes/widgets/social.php');
require_once(PARENT_DIR . '/includes/widgets/twitter.php');
require_once(PARENT_DIR . '/includes/widgets/tabs.php');
require_once(PARENT_DIR . '/includes/widgets/ads-125.php');
require_once(PARENT_DIR . '/includes/widgets/ads-300.php');
require_once(PARENT_DIR . '/includes/widgets/video.php');
require_once(PARENT_DIR . '/includes/widgets/facebook.php');
require_once(PARENT_DIR . '/includes/widgets/reviews.php');
require_once(PARENT_DIR . '/includes/widgets/subpages.php');
require_once(PARENT_DIR . '/includes/widgets/contact.php');


/**
 * Add widgetized area:
 * - Default Sidebar
 * - Custom Sidebar
 * - Footer 1
 * - Footer 2
 * - Footer 3
 * - Footer 4
 *
 * Add custom widgets
 *
 */
if (!function_exists('g7_widgets_init')) {
	function g7_widgets_init() {
		register_sidebar(array(
			'name'          => __('Default Sidebar', 'g7theme'),
			'id'            => 'sidebar',
			'before_widget' => '<li id="%1$s" class="widget box %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>'
		));
		$custom_sidebar = g7_option('sidebar');
		$i = 1;
		if (!empty($custom_sidebar)) {
			foreach ($custom_sidebar as $v) {
				if (empty($v)) {
					continue;
				}
				register_sidebar(array(
					'name'          => $v,
					'id'            => g7_sidebar_id($v),
					'before_widget' => '<li id="%1$s" class="widget box %2$s">',
					'after_widget'  => '</li>',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '</h2>'
				));
				$i++;
			}
		}
		register_sidebar(array(
			'name'          => __('Footer 1', 'g7theme'),
			'id'            => 'footer1',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>'
		));
		register_sidebar(array(
			'name'          => __('Footer 2', 'g7theme'),
			'id'            => 'footer2',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>'
		));
		register_sidebar(array(
			'name'          => __('Footer 3', 'g7theme'),
			'id'            => 'footer3',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>'
		));
		register_sidebar(array(
			'name'          => __('Footer 4', 'g7theme'),
			'id'            => 'footer4',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>'
		));

		register_widget('G7_Posts_Widget');
		register_widget('G7_Twitter_Widget');
		register_widget('G7_Flickr_Widget');
		register_widget('G7_Comments_Widget');
		register_widget('G7_Social_Widget');
		register_widget('G7_Tabs_Widget');
		register_widget('G7_Ads125_Widget');
		register_widget('G7_Ads300_Widget');
		register_widget('G7_Video_Widget');
		register_widget('G7_Facebook_Widget');
		register_widget('G7_Reviews_Widget');
		register_widget('G7_Contact_Widget');
		register_widget('G7_Subpages_Widget');
	}
	add_action('widgets_init', 'g7_widgets_init');
}
