<?php
class OTW_Shortcode_FaceBook_Comments extends OTW_Shortcodes{

	private $app_id = '';
	
	private $admins = '';
	
	public function __construct(){
		
		$this->has_options = true;
		
		$this->has_custom_options = false;
		
		$this->has_preview = false;
		
		parent::__construct();
		
		$this->shortcode_name = 'otw_shortcode_facebook_comments';
		
		if( !is_admin() ){
			add_action( 'wp_head', array( $this, 'otw_shortcode_facebook_meta_tags' ) );
			add_filter('language_attributes', array( $this, 'otw_shortcode_facebook_schema' ));
		}
	}
	
	/**
	 * apply settings
	 */
	public function apply_settings(){
		
		$this->settings = array(
			'colorscheme' => array(
					'light'          => $this->get_label( 'light (default)' ),
					'dark'     => $this->get_label( 'dark' )
				),
			'default_colorscheme' => 'light',
			
			'orderby' => array(
				'social' => $this->get_label( 'Top (default)' ),
				'reverse_time' => $this->get_label( 'Newest' ),
				'time' => $this->get_label( 'Oldest' )
			),
			'default_orderby' => 'social',
			
			'fbnameserver' => array(
				'no' => $this->get_label( 'No (default)' ),
				'yes' => $this->get_label( 'Yes' ),
			),
			'default_fbnameserver' => 'no',
			
			'opengraph' => array(
				'no' => $this->get_label( 'No (default)' ),
				'yes' => $this->get_label( 'Yes' ),
			),
			'default_opengraph' => 'no',
			
			'default_numposts' => 10
		);
	}
	/**
	 * Shortcode icon_link admin interface
	 */
	public function build_shortcode_editor_options(){
		
		$this->apply_settings();
		
		$html = '';
		
		$source = array();
		if( isset( $_POST['shortcode_object'] ) ){
			$source = $_POST['shortcode_object'];
		}
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-appid', 'name' => 'otw-shortcode-element-appid', 'label' => $this->get_label( 'Facebook App ID' ), 'description' => $this->get_label( 'Create an app to get an ID: <a href="https://developers.facebook.com/apps/" target="_blank">https://developers.facebook.com/apps/</a>' ), 'parse' => $source, 'value' => '' )  );
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-admins', 'name' => 'otw-shortcode-element-admins', 'label' => $this->get_label( 'Moderators' ), 'description' => $this->get_label( 'By default, all admins to the App ID can moderate comments. To add moderators, enter each Facebook Profile ID by a comma without spaces' ), 'parse' => $source, 'value' => '' )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-fbnameserver', 'name' => 'otw-shortcode-element-fbnameserver', 'label' => $this->get_label( 'Use Facebook NameServer' ), 'description' => $this->get_label( 'Only enable this if Facebook Comments do not appear' ), 'parse' => $source, 'options' => $this->settings['fbnameserver'], 'value' => $this->settings['default_fbnameserver'] ) );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-opengraph', 'name' => 'otw-shortcode-element-opengraph', 'label' => $this->get_label( 'Use Open Graph NameServer' ), 'description' => $this->get_label( 'Only enable this if Facebook comments are not appearing, not all information is being passed to Facebook or if you have not enabled Open Graph elsewhere within WordPress' ), 'parse' => $source, 'options' => $this->settings['opengraph'], 'value' => $this->settings['default_opengraph'] ) );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-colorscheme', 'name' => 'otw-shortcode-element-colorscheme', 'label' => $this->get_label( 'Color scheme' ), 'description' => $this->get_label( 'The color scheme used by the plugin. Can be "light" or "dark".' ), 'parse' => $source, 'options' => $this->settings['colorscheme'], 'value' => $this->settings['default_colorscheme'] ) );
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-numposts', 'name' => 'otw-shortcode-element-numposts', 'label' => $this->get_label( 'Number of Comments' ), 'description' => $this->get_label( 'The number of comments to show by default. The minimum value is 1.' ), 'parse' => $source, 'value' => $this->settings['default_numposts'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-orderby', 'name' => 'otw-shortcode-element-orderby', 'label' => $this->get_label( 'Order By' ), 'description' => $this->get_label( 'The order to use when displaying comments.' ), 'parse' => $source, 'options' => $this->settings['orderby'], 'value' => $this->settings['default_orderby'] ) );
		
		return $html;
	}
	
	/** build icon link shortcode
	 *
	 *  @param array
	 *  @return string
	 */
	public function build_shortcode_code( $attributes ){
		
		$code = '';
		
		if( !$this->has_error ){
		
			$code = '[otw_shortcode_facebook_comments';
			
			$code .= $this->format_attribute( 'colorscheme', 'colorscheme', $attributes, false, '', true );
			$code .= $this->format_attribute( 'numposts', 'numposts', $attributes, false, '', true );
			$code .= $this->format_attribute( 'orderby', 'orderby', $attributes, false, '', true );
			
			$code .= ']';
			
			$code .= '[/otw_shortcode_facebook_comments]';
			
			$options = array();
			$options['appid'] = $this->format_attribute( '', 'appid', $attributes, false, '', true );
			$options['admins'] = $this->format_attribute( '', 'admins', $attributes, false, '', true );
			$options['fbnameserver'] = $this->format_attribute( '', 'fbnameserver', $attributes, false, '', true );
			$options['opengraph'] = $this->format_attribute( '', 'opengraph', $attributes, false, '', true );
			
			update_option( $this->shortcode_name.'_options', $options );
		}
		
		return $code;
	}
	
	/**
	 * Process shortcode icon link
	 */
	public function display_shortcode( $attributes, $content ){
		
		$html = '';
		$item_id = $this->format_attribute( '', 'item_id', $attributes, false, '' );
		
		if( is_admin() ){
			$html = '<img src="'.$this->component_url.'images/sidebars-icon-placeholder.png'.'" alt=""/>';
		}else{
			$html = '';
			
			if( !$item_id ){
				
				global $post;
				
				if( isset( $post->ID ) && intval( $post->ID ) ){
					$item_id = $post->ID;
				}
			}
			
			if( $item_id ){
			
				
				$href = get_permalink( $item_id );
				//$href = 'https://developers.facebook.com/docs/plugins/comments#configurator';
				
				$attributes = get_option( $this->shortcode_name.'_options' );
				
				$colorscheme = $this->settings['default_colorscheme'];
					
				if( $value = $this->format_attribute( '', 'colorscheme', $attributes, false, '' ) ){
					$colorscheme = $value;
				}
				
				$numposts = $this->settings['default_numposts'];
				
				if( $value = $this->format_attribute( '', 'numposts', $attributes, false, '' ) ){
					$numposts = $value;
				}
				
				$orderby = $this->settings['default_orderby'];
				
				if( $value = $this->format_attribute( '', 'orderby', $attributes, false, '' ) ){
					$orderby = $value;
				}
				
				$html .= '<div class="fb-comments" data-href="'.$href.'" data-numposts="'.$numposts.'" data-colorscheme="'.$colorscheme.'" data-order-by="'.$orderby.'"></div>';
				
				$this->app_id = $this->format_attribute( '', 'appid', $attributes, false, '' );
				$this->admins = $this->format_attribute( '', 'admins', $attributes, false, '' );
				
				if( $this->is_live_preview ){
				}else{
					add_action( 'wp_footer', array( $this, 'otw_shortcode_facebook_comments_js' ) );
				}
			}
		}
		
		return $this->format_shortcode_output( $html );
	}
	
	/**
	 * Return shortcode attributes
	 */
	public function get_shortcode_attributes( $attributes ){
		
		$shortcode_attributes = array();
		
		if( isset( $attributes['item_type'] ) ){
		
			if( isset( $this->settings['item_type_options'][ $attributes['item_type'] ] ) ){
				$shortcode_attributes['iname'] = $this->settings['item_type_options'][ $attributes['item_type'] ];
			}else{
				$shortcode_attributes['iname'] = ucfirst( $attributes['item_type'] );
			}
		}
		
		return $shortcode_attributes;
	}
	
	function otw_shortcode_facebook_comments_js(){
		
		$html = '<div id="fb-root"></div>';
		$html .= '<script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5"; fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script>';
		
		echo $html;
	}
	
	function otw_shortcode_facebook_meta_tags(){
		
		$html = '';
		
		//get shortcode options
		$options = get_option( $this->shortcode_name.'_options' );
		
		if( is_array( $options ) ){
		
			if( !empty( $options['appid'] ) ){
				$html .= '<meta property="fb:app_id" content="'.$options['appid'].'"/>';
			}
			if (!empty( $options['admins'] ) ){
				$html .= '<meta property="fb:admins" content="'.$options['admins'].'"/>';
			}
		}
		echo $html;
	}
	
	function otw_shortcode_facebook_schema( $attributes ){
		
		//get shortcode options
		$options = get_option( $this->shortcode_name.'_options' );
		
		if( is_array( $options ) ){
			
			if( isset( $options['fbnameserver'] ) && ( $options['fbnameserver'] == 'yes' ) ){
				$attributes .= "\n xmlns:og=\"http://ogp.me/ns#\"";
			}
			
			if( isset( $options['opengraph'] ) && ( $options['opengraph'] == 'yes' ) ){
				$attributes .= "\n xmlns:fb=\"http://ogp.me/ns/fb#\"";
			}
		}
		return $attributes;
	}
}
?>