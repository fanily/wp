<?php
/**
 * Init function
 */
if( !function_exists( 'otw_fc_init' ) ){
	
	function otw_fc_init(){
		
		global $otw_fc_shortcode_component, $otw_fc_shortcode_object, $otw_fc_js_version, $otw_fc_css_version, $otw_fc_plugin_url;
		
		if( is_admin() ){
			add_action('admin_menu', 'otw_fc_init_admin_menu' );
			add_action('add_meta_boxes', 'otw_fc_meta_boxes');
			add_action( 'save_post', 'otw_fc_save_meta_box' );
		}else{
			add_action( 'the_content', 'otw_fc_show_facebook_comments', 1000 );
		}
		
		$otw_fc_shortcode_component = otw_load_component( 'otw_shortcode' );
		$otw_fc_shortcode_object = otw_get_component( $otw_fc_shortcode_component );
		$otw_fc_shortcode_object->js_version = $otw_fc_js_version;
		$otw_fc_shortcode_object->css_version = $otw_fc_css_version;
		
		$otw_fc_shortcode_object->add_default_external_lib( 'css', 'style', get_stylesheet_directory_uri().'/style.css', 'live_preview', 10 );
		
		$otw_fc_shortcode_object->shortcodes['facebook_comments'] = array( 'title' => __('Facebook Comments', 'otw_fc' ), 'options' => true, 'enabled' => true, 'children' =>false, 'order' => 112,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_shortcode/', 'url' => $otw_fc_plugin_url.'include/otw_components/otw_shortcode/' );
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_fc_shortcode_object.labels.php' );
		$otw_fc_shortcode_object->init();
		
		//form component
		$otw_fc_form_component = otw_load_component( 'otw_form' );
		$otw_fc_form_object = otw_get_component( $otw_fc_form_component );
		$otw_fc_form_object->js_version = $otw_fc_js_version;
		$otw_fc_form_object->css_version = $otw_fc_css_version;
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_fc_form_object.labels.php' );
		$otw_fc_form_object->init();
		
		include_once( 'otw_fc_process_actions.php' );
	}
}

/**
 * Init admin menu
 */
if( !function_exists( 'otw_fc_init_admin_menu' ) ){
	
	function otw_fc_init_admin_menu(){
		
		global $otw_fc_plugin_url;
		
		add_menu_page(__('Facebook Comments', 'otw_fc'), __('Facebook Comments', 'otw_fc'), 'manage_options', 'otw-fc', 'otw_fc_options', $otw_fc_plugin_url.'/images/otw-menu-icon.png');
	}
}

/** plugin options
  *
  */
 if( !function_exists( 'otw_fc_options' ) ){
	
	function otw_fc_options(){
		
		global $otw_fc_shortcode_object;
		
		$values = get_option( $otw_fc_shortcode_object->shortcodes['facebook_comments']['object']->shortcode_name.'_options' );
		
		if( !is_array( $values ) ){
			$values = array();
		}
		
		if( !isset( $_POST['shortcode_objectc'] ) ){
			$_POST['shortcode_object'] = array();
		}
		
		foreach( $values as $key => $value ){
			$_POST['shortcode_object']['otw-shortcode-element-'.$key ] = $value;
		}
		
		$plugin_settings = get_option( 'otw_fc_settings' );
		
		if( !is_array( $plugin_settings ) ){
			$plugin_settings = array();
		}
		
		if( isset( $plugin_settings['validfor'] ) && is_array( $plugin_settings['validfor'] ) && count( $plugin_settings['validfor'] ) ){
			
			foreach( $plugin_settings['validfor'] as $key => $value ){
				$values['otw-fc-'.$key ] = $value;
			}
		}
		
		$custom_post_types = get_post_types( array(  'public'   => true, '_builtin' => false ), 'object' );
		
		require_once( 'otw_fc_options.php' );
	}
}

/** show facebook comments
  *
  */
 if( !function_exists( 'otw_fc_show_facebook_comments' ) ){
	
	function otw_fc_show_facebook_comments( $content ){
		
		global $otw_fc_shortcode_object, $post;
		
		//get the plugin options
		$options = get_option( 'otw_fc_settings' );
		
		if( !is_array( $options ) ){
			$options = array();
		}
		if( !isset( $options['validfor'] ) ){
			$options['validfor'] = array();
		}
		
		if( isset( $post->post_type ) && isset( $options['validfor'][ $post->post_type ] ) && ( $options['validfor'][ $post->post_type ] == 'yes' ) ){
		
			//check if the comments are disabled from edit post metabox
			$meta_data = get_post_meta( $post->ID, 'otw_fc_disable_facebook_comments', true );
			
			if( $meta_data != 'yes' ){
				$fb_comments = do_shortcode( '[otw_shortcode_facebook_comments item_id="'.$post->ID.'"]');
				$content .= $fb_comments;
			}
		}
		
		return $content;
	}
}

/**
 * meta boxes
 */
if( !function_exists( 'otw_fc_meta_boxes' ) ){
	
	function otw_fc_meta_boxes(){
		
		$plugin_settings = get_option( 'otw_fc_settings' );
		
		if( !is_array( $plugin_settings ) ){
			$plugin_settings = array();
		}
		
		if( isset( $plugin_settings['validfor'] ) && is_array( $plugin_settings['validfor'] ) && count( $plugin_settings['validfor'] ) ){
			
			foreach( $plugin_settings['validfor'] as $key => $value ){
			
				if( $value == 'yes' ){
					
					add_meta_box('otw_fc_facebook_comments_metabox', __('OTW Facebook Comments', 'otw_fc'), 'otw_fc_facebook_comments_metabox', $key , 'normal', 'high');
				}
			}
			
		}
		
	}
}

/**
 * meta box for fb comments
 */
if( !function_exists( 'otw_fc_facebook_comments_metabox' ) ){
	function otw_fc_facebook_comments_metabox( $post ){
		
		$meta_data = get_post_meta( $post->ID, 'otw_fc_disable_facebook_comments', true );
		
		$otw_fc_comments_checked = '';
		
		if( $meta_data == 'yes' ){
			$otw_fc_comments_checked = ' checked="checked"';
		}
		
		require_once( 'otw_fc_item_metabox.php' );
	}
}

/**
 * save meta boxes
 */
if( !function_exists( 'otw_fc_save_meta_box' ) ){
	
	function otw_fc_save_meta_box( $post_id ){
		
		if( $post_id ){
			
			if( isset( $_POST['otw_fc_item_meta_options'] ) && ( $_POST['otw_fc_item_meta_options'] == '1' ) ){
				
				if( isset( $_POST['otw_fc_disable_facebook_comments'] ) ){
					
					add_post_meta( $post_id, 'otw_fc_disable_facebook_comments', sanitize_text_field( $_POST['otw_fc_disable_facebook_comments'] ), true);
					
					// If POST is in the DB update it
					update_post_meta( $post_id, 'otw_fc_disable_facebook_comments', sanitize_text_field( $_POST['otw_fc_disable_facebook_comments'] ) );
				}else{
					//set it to no if exists
					
					$meta_data = get_post_meta( $post_id, 'otw_fc_disable_facebook_comments', true );
					
					if( $meta_data == 'yes' ){
						
						update_post_meta( $post_id, 'otw_fc_disable_facebook_comments', 'no' );
					}
				}
			}
		}
	}
}