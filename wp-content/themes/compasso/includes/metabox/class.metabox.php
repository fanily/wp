<?php
class G7_Meta_Box {

	var $meta_box;
	var $nonce_action;
	var $nonce_name;

	function __construct($meta_box) {
		$this->meta_box = $meta_box;
		add_action('add_meta_boxes', array(&$this, 'add'));
		add_action('save_post', array(&$this, 'save'));

		$this->nonce_action = 'g7mb-save-' . $this->meta_box['id'];
		$this->nonce_name = 'nonce_' . $this->meta_box['id'];
	}

	function add() {
		foreach ($this->meta_box['pages'] as $page) {
			add_meta_box(
				$this->meta_box['id'],
				$this->meta_box['title'],
				array($this, 'display'),
				$page,
				$this->meta_box['context'],
				$this->meta_box['priority']
			);
		}
	}

	function display($post) {
		wp_nonce_field($this->nonce_action, $this->nonce_name);

		foreach ((array)$this->meta_box['fields'] as $field) {
			echo '<div class="g7mb-field">';
			$this->display_fields($field, $post->ID);
			echo '</div>';
		}
	}

	function display_fields($field, $post_id) {
		if (isset($field['name'])) {
			printf(
				'<div class="g7mb-label"><label for="%s">%s</label></div>',
				$field['id'],
				$field['name']
			);
		}
		echo '<div class="g7mb-input">';

		$value = get_post_meta($post_id, $field['id'], true);

		if ($value == '' && isset($field['std'])) {
			if (!isset($_GET['post'])) {
				$value = $field['std'];
			}
		}

		switch ($field['type']) {

			case 'text':
				printf(
					'<input type="text" name="%s" id="%s" size="%s" value="%s" />',
					$field['id'],
					$field['id'],
					isset($field['size']) ? $field['size'] : 40,
					$value
				);
				break;

			case 'textarea':
				printf(
					'<textarea name="%s" id="%s" cols="%s" rows="%s">%s</textarea>',
					$field['id'],
					$field['id'],
					isset($field['cols']) ? $field['cols'] : 40,
					isset($field['rows']) ? $field['rows'] : 2,
					$value
				);
				break;

			case 'select':
				printf(
					'<select name="%s" id="%s">',
					$field['id'],
					$field['id']
				);
				foreach ((array)$field['options'] as $k => $v) {
					printf(
						'<option value="%s" %s>%s</option>',
						$k,
						selected($value, $k, false),
						$v
					);
				}
				echo '</select>';
				break;

			case 'checkbox':
				printf(
					'<input type="checkbox" name="%s" id="%s" value="1" %s />%s',
					$field['id'],
					$field['id'],
					checked($value, '1', false),
					isset($field['label']) ? ' <label for="' . $field['id'] . '">' . $field['label'] . '</label>' : ''
				);
				break;

			case 'category':
				wp_dropdown_categories(
					array(
						'hide_empty' => 0,
						'name' => $field['id'],
						'id' => $field['id'],
						'class' => '',
						'hierarchical' => 1,
						'show_option_all' => __('All Categories', 'g7theme'),
						'selected' => $value
					)
				);
				break;

			case 'slider':
				printf(
					'<div class="g7-slider" data-min="%s" data-max="%s" data-step="%s"></div>
					<span class="g7-slider-value">%s</span>
					<input type="hidden" name="%s" value="%s">',
					$field['min'],
					$field['max'],
					$field['step'],
					$value,
					$field['id'],
					$value
				);
				break;

			case 'rating':
                $this->rating($field, $post_id);
				break;

			case 'categories':
				$this->categories($field, $post_id);
				break;
		}

		if (isset($field['desc'])) {
			if ($field['type'] == 'checkbox' || $field['type'] == 'textarea') {
				echo '<br>';
			}
			printf(
				' <span class="description">%s</span>',
				$field['desc']
			);
		}

		echo '</div>';
	}

	function rating($field, $post_id) {
		$criteria_value = get_post_meta($post_id, $field['id'], true);
		$rating_value   = get_post_meta($post_id, $field['id2'], true);
		$overall_rating = get_post_meta($post_id, $field['id3'], true);
		$row = sprintf(
			'<tr>
				<td>
					<input type="text" name="%s[]" value="%s" size="35">
				</td>
				<td>
					<div class="g7-slider" data-min="%s" data-max="%s" data-step="%s"></div>
				</td>
				<td>
					<input type="text" readonly="readonly" name="%s[]" value="%s" size="2">
				</td>
				<td>
					<a class="g7-rating-delete" href="#">Delete</a>
				</td>
			</tr>',
			$field['id'],
			'%s',
			$field['min'],
			$field['max'],
			$field['step'],
			$field['id2'],
			'%s'
		);
		$row2 = '';
		if (empty($criteria_value)) {
			$row2 .= sprintf(
				$row,
				'',
				''
			);
		} else {
			$i = 1;
			$count = count($criteria_value);
			for ($i = 1; $i <= $count; $i++) {
				$row2 .= sprintf(
					$row,
					$criteria_value[$i - 1],
					$rating_value[$i - 1]
				);
			}
		}
		printf(
			'<div class="g7-rating-add"><a href="#">%s</a></div>
			<table class="g7-rating">
				<thead>
					<tr>
						<th>%s</th>
						<th colspan="3">%s</th>
					</tr>
				</thead>
				<tbody>
					%s
				</tbody>
				<tfoot>
					<tr>
						<td>%s</td>
						<td></td>
						<td><input type="text" name="%s" id="%s" value="%s" size="2" readonly="readonly"></td>
						<td></td>
					</tr>
				</tfoot>
			</table>',
			__('Add Rating', 'g7theme'),
			__('Criteria', 'g7theme'),
			__('Rating', 'g7theme'),
			$row2,
			__('Overall', 'g7theme'),
			$field['id3'],
			$field['id3'],
			$overall_rating
		);
	}

	function categories($field, $post_id) {
		$value = get_post_meta($post_id, $field['id'], true);

		$row = '
			<div class="widget">
				<div class="widget-top">
					<div class="widget-title-action">
						<a href="#" class="widget-action"></a>
					</div>
					<div class="widget-title"><h4>%1$s</h4></div>
				</div>
				<div class="widget-inside">
					<div class="widget-content">
						<p>
							<label>' . __('Category', 'g7theme') . ':</label>
							%3$s
						</p>
						<p>
							<label>' . __('Number of posts to show', 'g7theme') . ':</label>
							<input type="text" size="3" value="%4$s" name="%2$s[num][]">
						</p>
					</div>
					<div class="widget-control-actions">
						<div class="alignleft">
							<a href="#remove" class="widget-control-remove">' . __('Delete', 'g7theme') . '</a>
						</div>
						<br class="clear">
					</div>
				</div>
			</div>
		';

		$rows = '';

		if ($value) {
			$count = count($value['cat']);
			for ($i = 0; $i < $count - 1; $i++) {
				$rows .= sprintf(
					$row,
					g7_category_name($value['cat'][$i]),
					$field['id'],
					$this->category_dropdown($field['id'] . '[cat][]', $value['cat'][$i]),
					$value['num'][$i]
				);
			}
		}

		$row2 = sprintf(
			$row,
			__('All Categories', 'g7theme'),
			$field['id'],
			$this->category_dropdown($field['id'] . '[cat][]'),
			$field['default_num']
		);

		echo <<<EOT
<div class="g7-add-item">
	<a href="#" data-name="Category" data-title="Category">Add Category</a>
</div>
<div class="g7-dragdrop">
	{$rows}
</div>
<div class="g7-dragdrop-item">
	{$row2}
</div>
EOT;
	}

	function category_dropdown($name, $value = 0) {
		$dropdown = wp_dropdown_categories(
			array(
				'echo' => '0',
				'hide_empty' => 0,
				'name' => $name,
				'id' => 'tmp',
				'class' => 'g7-category-dropdown',
				'hierarchical' => 1,
				'show_option_all' => __('All Categories', 'g7theme'),
				'selected' => $value
			)
		);
		$dropdown = str_replace(" id='tmp'", '', $dropdown);
		return $dropdown;
	}

	function save($post_id) {
		global $post_type;
		$post_type_object = get_post_type_object($post_type);

		//Check whether:
		//- the post is autosaved
		//- the post is a revision
		//- current post type is supported
		//- user has proper capability
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			|| (!isset($_POST['post_ID']) || $post_id != $_POST['post_ID'])
			|| (!in_array($post_type, $this->meta_box['pages']))
			|| (!current_user_can($post_type_object->cap->edit_post, $post_id))
			) {
			return $post_id;
		}

		//verify nonce
		if (!isset($_POST[$this->nonce_name]) || !wp_verify_nonce($_POST[$this->nonce_name], $this->nonce_action)) {
			return $post_id;
		}

		//check if template included
		if (!empty($this->meta_box['templates'])) {
			if (isset($_POST['page_template']) && !in_array($_POST['page_template'], $this->meta_box['templates'])) {
				return $post_id;
			}
		}

		//check if template not excluded
		if (!empty($this->meta_box['templates_ex'])) {
			if (isset($_POST['page_template']) && in_array($_POST['page_template'], $this->meta_box['templates_ex'])) {
				return $post_id;
			}
		}

		foreach ((array)$this->meta_box['fields'] as $field) {
			if ($field['type'] == 'rating') {
				$fields[] = $field['id'];
				$fields[] = $field['id2'];
				$fields[] = $field['id3'];
			} else {
				$fields[] = $field['id'];
			}
		}

		foreach ((array)$fields as $field_id) {
			$name = $field_id;
			$old = get_post_meta($post_id, $name, true);
			if (isset($_POST[$name])) {
				$new = $_POST[$name];
				if ($new != $old) {
					update_post_meta($post_id, $name, $new);
				} elseif ($new == '' && $old) {
					delete_post_meta($post_id, $field_id, $old);
				}
			} else {
				if ($old) {
					delete_post_meta($post_id, $field_id, $old);
				}
			}
		}
	}
}

class G7_Meta_Boxes {

	var $meta_boxes;

	function __construct($meta_boxes) {
		$this->meta_boxes = $meta_boxes;
		foreach ((array)$this->meta_boxes as $meta_box) {
			new G7_Meta_Box($meta_box);
		}
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue'));
	}

	function enqueue() {
		$screen = get_current_screen();
		if ($screen->base != 'post') {
			return;
		}

		foreach ($this->meta_boxes as $meta_box) {
			if (!empty($meta_box['templates'])) {
				$g7mb[$meta_box['id']] = $meta_box['templates'];
			} else {
				$g7mb[$meta_box['id']] = array();
			}
		}
		foreach ($this->meta_boxes as $meta_box) {
			if (!empty($meta_box['templates_ex'])) {
				$g7mb2[$meta_box['id']] = $meta_box['templates_ex'];
			}
		}

		wp_enqueue_style('g7-metabox', PARENT_URL . '/includes/metabox/metabox.css');
		wp_enqueue_style('jquery-ui-custom', PARENT_URL .'/includes/metabox/jquery-ui-custom.css');
		wp_enqueue_script('jquery', false, array(), false, true);
		wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, true);
		wp_enqueue_script('jquery-ui-slider', false, array('jquery'), false, true);
		wp_enqueue_script('g7-metabox', PARENT_URL . '/includes/metabox/metabox.js', array('jquery'), false, true);
		wp_localize_script('g7-metabox', 'g7mb', $g7mb);
		wp_localize_script('g7-metabox', 'g7mb2', $g7mb2);
	}
}