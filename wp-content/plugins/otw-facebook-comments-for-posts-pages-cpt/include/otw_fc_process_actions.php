<?php
/**
 * Process otw actions
 *
 */
if( isset( $_POST['otw_action'] ) ){

	switch( $_POST['otw_action'] ){
		
		case 'manage_otw_fc_options':
				
				global $otw_fc_shortcode_object;
				$options = array();
				
				$options['appid'] = sanitize_text_field( $otw_fc_shortcode_object->shortcodes['facebook_comments']['object']->format_attribute( '', 'otw-shortcode-element-appid', $_POST, false, '', true ) );
				$options['admins'] = sanitize_text_field( $otw_fc_shortcode_object->shortcodes['facebook_comments']['object']->format_attribute( '', 'otw-shortcode-element-admins', $_POST, false, '', true ) );
				$options['fbnameserver'] = sanitize_text_field( $otw_fc_shortcode_object->shortcodes['facebook_comments']['object']->format_attribute( '', 'otw-shortcode-element-fbnameserver', $_POST, false, '', true ) );
				$options['opengraph'] = sanitize_text_field( $otw_fc_shortcode_object->shortcodes['facebook_comments']['object']->format_attribute( '', 'otw-shortcode-element-opengraph', $_POST, false, '', true ) );
				$options['colorscheme'] = sanitize_text_field( $otw_fc_shortcode_object->shortcodes['facebook_comments']['object']->format_attribute( '', 'otw-shortcode-element-colorscheme', $_POST, false, '', true ) );
				$options['numposts'] = sanitize_text_field( $otw_fc_shortcode_object->shortcodes['facebook_comments']['object']->format_attribute( '', 'otw-shortcode-element-numposts', $_POST, false, '', true ) );
				$options['orderby'] = sanitize_text_field( $otw_fc_shortcode_object->shortcodes['facebook_comments']['object']->format_attribute( '', 'otw-shortcode-element-orderby', $_POST, false, '', true ) );
				
				update_option( $otw_fc_shortcode_object->shortcodes['facebook_comments']['object']->shortcode_name.'_options', $options );
				
				$custom_post_types = get_post_types( array(  'public'   => true, '_builtin' => false ), 'object' );
				
				$plugin_options = array();
				$plugin_options['validfor'] = array();
				$plugin_options['validfor']['post'] = 'no';
				$plugin_options['validfor']['page'] = 'no';
				
				if( isset( $_POST['otw-fc-post'] ) ){
					$plugin_options['validfor']['post'] = sanitize_text_field( $_POST['otw-fc-post'] );
				}
				if( isset( $_POST['otw-fc-page'] ) ){
					$plugin_options['validfor']['page'] = sanitize_text_field( $_POST['otw-fc-page'] );
				}
				
				if( is_array( $custom_post_types ) && count( $custom_post_types ) ){
					foreach( $custom_post_types as $cp_type ){
					
						if( isset( $_POST['otw-fc-'.$cp_type->name ] )  ){
							
							$plugin_options['validfor'][ $cp_type->name ] = sanitize_text_field( $_POST['otw-fc-'.$cp_type->name ] );
						}
					}
				}
				
				update_option( 'otw_fc_settings', $plugin_options );
				
				wp_redirect( admin_url( 'admin.php?page=otw-fc&message=1' ) );
			break;
	}
}
?>