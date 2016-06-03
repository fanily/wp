<?php
class G7_TinyMCE {

	var $js_file;
	var $buttons;

	function __construct() {
		add_action('init', array(&$this, 'add_button'));
	}

	function add_button() {
		if (current_user_can('edit_posts') && get_user_option('rich_editing')) {
			add_filter('mce_external_plugins', array(&$this, 'add_plugin'));
			add_filter('mce_buttons', array(&$this, 'register_button'));
		}
	}

	function add_plugin($plugin_array) {
		if (version_compare(get_bloginfo('version'), 3.9, '>=')) {
			$plugin_array['g7_button'] = PARENT_URL . '/includes/shortcodes/buttons2.js';
		} else {
			$plugin_array['g7_button'] = PARENT_URL . '/includes/shortcodes/buttons.js';
		}
		return $plugin_array;
	}

	function register_button($buttons) {
		$buttons[] = 'separator';
		$buttons[] = 'sc_button';
		return $buttons;
	}
}

$g7_tinymce = new G7_TinyMCE;