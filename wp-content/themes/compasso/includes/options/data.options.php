<?php
$options_data = array(
	'general' => array(
		'title' => 'General',
		'fields' => array(
			'color' => array(
				'type'       => 'color',
				'title'      => 'Color Scheme',
				'presets'    => array(
					'#ef4423',
					'#fb6239',
					'#ff764c',
					'#fe9b00',
					'#9fc54e',
					'#3e93d2',
					'#00adef',
					'#2790b0',
					'#e15f4e',
					'#ff6fbc',
					'#dd577a',
					'#ff4c54',
				),
				'std'        => '#ff4c54',
				'desc'       => 'Choose from predefined colors or use the color picker for more colors'
			),
			'responsive' => array(
				'type'       => 'checkbox',
				'title'      => 'Responsive',
				'label'      => 'Enable responsive',
				'std'        => '1'
			),
			'layout' => array(
				'type'       => 'select',
				'title'      => 'Default Layout',
				'class'      => 'medium',
				'options'    => array(
					'1' => 'Right Sidebar',
					'2' => 'Left Sidebar',
					'3' => 'Full Width'
				),
				'std'        => '1',
				'desc'       => 'Default layout for all pages. You can change the layout individually on each page'
			),
			'logo' => array(
				'type'       => 'checkbox',
				'title'      => 'Main Logo',
				'label'      => 'Show logo image',
				'std'        => '1',
				'hidden'     => 1
			),
			'logo_image' => array(
				'type'       => 'image',
				'title'      => '',
				'desc'       => 'Enter a logo image url or upload an image',
				'std'        => PARENT_URL . '/images/logo.png'
			),
			'login_logo' => array(
				'type'       => 'image',
				'title'      => 'Custom Login Logo',
				'desc'       => 'Custom logo for your login page'
			),
			'favicon' => array(
				'type'       => 'image',
				'title'      => 'Custom Favicon',
				'desc'       => 'Favicon image (16px x 16px)'
			),
			'update_notifier' => array(
				'type'       => 'checkbox',
				'title'      => 'Theme Update Notifier',
				'label'      => 'Enable',
				'std'        => '1',
				'desc'       => 'If enabled, you will get notified when a new theme update is available'
			)
		)
	),
	'header' => array(
		'title' => 'Header',
		'fields' => array(
			'top_bar' => array(
				'type'       => 'checkbox',
				'title'      => 'Top Bar',
				'label'      => 'Show the top bar',
				'std'        => '1'
			),
			'header_text' => array(
				'type'       => 'textarea',
				'title'      => 'Header Text',
				'rows'       => '2',
				'desc'       => 'HTML or text to be inserted into top right of header'
			),
			'banner' => array(
				'type'       => 'checkbox',
				'title'      => 'Header Banner',
				'label'      => 'Show banner',
				'std'        => '1',
				'desc'       => 'Check this to show a banner in the header of your site'
			),
			'banner_image' => array(
				'type'       => 'image',
				'title'      => 'Banner Image',
				'desc'       => 'Enter a banner image url or upload an image',
				'std'        => PARENT_URL . '/images/banner-468x60.png'
			),
			'banner_link' => array(
				'type'       => 'text',
				'title'      => 'Banner Link',
				'desc'       => 'Destination URL for your banner e.g. http://www.example.com'
			),
		)
	),
	'sidebar' => array(
		'title' => 'Sidebar',
		'fields' => array(
			'sidebar' => array(
				'type'       => 'texts',
				'title'      => 'Custom Sidebar',
				'desc'       => 'This theme has 1 default sidebar. You can add more sidebars from here.'
			)
		)
	),
	'slider' => array(
		'title' => 'Slider',
		'fields' => array(
			'slider' => array(
				'type'       => 'checkbox',
				'title'      => 'Enable Slider',
				'label'      => 'Enable',
				'std'        => '1'
			),
			'slider_tags' => array(
				'type'       => 'text',
				'title'      => 'Slider Tags',
				'std'        => 'featured',
				'desc'       => 'Separate multiple tags with commas'
			),
			'slider_limit' => array(
				'type'       => 'text',
				'title'      => 'Slider Limit',
				'std'        => '5',
				'attributes' => 'style="width:40px"',
				'desc'       => 'maximum numbers of slides to display'
			),
			'slider_animation' => array(
				'type'       => 'select',
				'title'      => 'Animation',
				'class'      => 'medium',
				'options'    => array(
					'slide' => 'slide',
					'fade'  => 'fade'
				),
				'std'        => 'fade'
			),
			'slider_slideshowSpeed' => array(
				'type'       => 'text',
				'title'      => 'Slideshow Speed',
				'std'        => '7000',
				'attributes' => 'style="width:50px"',
				'desc'       => 'speed of the slideshow cycling, in milliseconds'
			),
			'slider_animationSpeed' => array(
				'type'       => 'text',
				'title'      => 'Animation Speed',
				'std'        => '600',
				'attributes' => 'style="width:50px"',
				'desc'       => 'speed of animations, in milliseconds'
			),
			'slider_pauseOnHover' => array(
				'type'       => 'checkbox',
				'title'      => 'Pause on Hover',
				'label'      => '',
				'std'        => '1'
			),
			'slider_caption' => array(
				'type' => 'multicheck',
				'title' => 'Caption',
				'options' => array(
					'slider_category' => 'Show Category',
					'slider_rating'   => 'Show Rating (if available)',
					'slider_readmore' => 'Show Read More Link'
				),
				'std' => array(
					'slider_category' => '1',
					'slider_rating'   => '1',
					'slider_readmore' => '1'
				)
			)
		)
	),
	'breadcrumbs' => array(
		'title' => 'Breadcrumbs',
		'fields' => array(
			'breadcrumbs' => array(
				'type'       => 'checkbox',
				'title'      => 'Enable Breadcrumb Navigation',
				'label'      => 'Enable',
				'std'        => '1',
				'desc'       => 'If enabled, breadcrumbs will be shown above page title'
			),
			'breadcrumbs_text' => array(
				'type'       => 'text',
				'title'      => 'Prefix',
				'std'        => 'You are here:',
				'desc'       => 'Text to be inserted before the breadcrumbs'
			)
		)
	),
	'posts' => array(
		'title' => 'Posts',
		'fields' => array(
			'list' => array(
				'type' => 'multicheck',
				'title' => 'Posts List',
				'options' => array(
					'list_thumbnail' => 'Show Thumbnail',
					'list_rating'    => 'Show Rating (if available)',
					'list_category'  => 'Show Category',
					'list_date'      => 'Show Date',
					'list_comments'  => 'Show Comments Link',
					'list_excerpt'   => 'Show Excerpt',
					'list_author'    => 'Show Author',
					'list_readmore'  => 'Show Read More Link'
				),
				'std' => array(
					'list_thumbnail' => '1',
					'list_rating'    => '1',
					'list_category'  => '1',
					'list_date'      => '1',
					'list_comments'  => '1',
					'list_excerpt'   => '1',
					'list_author'    => '1',
					'list_readmore'  => '1'
				),
				'desc' => 'Settings for pages that show many posts (category, tag, search result, author, archive, template list, template grid, template masonry)'
			),
			'single' => array(
				'type' => 'multicheck',
				'title' => 'Single Post Settings',
				'options' => array(
					'single_featured_image' => 'Show Featured Image',
					'single_tags'           => 'Show Tags',
					'single_author_info'    => 'Show Author Info',
					'single_related'        => 'Show Related Posts',
					'single_share'          => 'Show Share Icons'
				),
				'std' => array(
					'single_featured_image' => '1',
					'single_tags'           => '1',
					'single_author_info'    => '1',
					'single_related'        => '1',
					'single_share'          => '1'
				),
				'desc' => 'Settings for single post'
			),
			'share' => array(
				'type' => 'multicheck',
				'title' => 'Share Icons',
				'options' => array(
					'share_facebook'    => 'Facebook',
					'share_twitter'     => 'Twitter',
					'share_delicious'   => 'Delicious',
					'share_digg'        => 'Digg',
					'share_reddit'      => 'Reddit',
					'share_stumbleupon' => 'StumbleUpon',
					'share_linkedin'    => 'Linkedin',
					'share_google+'     => 'Google+'
				),
				'std' => array(
					'share_facebook'    => '1',
					'share_twitter'     => '1',
					'share_delicious'   => '1',
					'share_digg'        => '1',
					'share_reddit'      => '1',
					'share_stumbleupon' => '1',
					'share_linkedin'    => '1',
					'share_google+'     => '1'
				),
				'desc' => 'Share icons for single post'
			),
			'enable_ratings' => array(
				'type'       => 'checkbox',
				'label'      => 'Enable',
				'title'      => 'Enable Ratings',
				'desc'       => 'Check this to enable Rating Settings in posts',
				'std'        => '1'
			),
			'lightbox' => array(
				'type'       => 'checkbox',
				'label'      => 'Enable',
				'title'      => 'Enable prettyPhoto',
				'desc'       => 'If enabled, clicking on thumbnails will open full size image using prettyPhoto',
				'std'        => '0'
			)
		)
	),
	'contact' => array(
		'title' => 'Contact',
		'fields' => array(
			'contact_email' => array(
				'type'       => 'text',
				'title'      => 'Email address',
				'desc'       => 'if empty, admin email address will be used'
			),
			'contact_subject' => array(
				'type'       => 'text',
				'title'      => 'Subject',
				'std'        => 'A Message From {name}'
			),
			'contact_success' => array(
				'type'       => 'textarea',
				'title'      => 'Success message',
				'rows'       => '2',
				'std'        => 'Thanks. Your message has been sent.'
			)
		)
	),
	'seo' => array(
		'title' => 'SEO',
		'fields' => array(
			'meta_description' =>  array(
				'type'       => 'textarea',
				'title'      => 'Homepage Meta Description',
				'desc'       => 'Enter the meta description of your homepage',
				'rows'       => '2',
				'std'        => ''
			),
			'meta_keywords' =>  array(
				'type'       => 'textarea',
				'title'      => 'Homepage Meta Keywords',
				'desc'       => 'Enter the meta keywords of your homepage (comma separated)',
				'rows'       => '2',
				'std'        => ''
			),
			'noindex' => array(
				'type'       => 'multicheck',
				'title'      => 'Noindex',
				'options'    => array(
					'noindex_cat' => 'Use noindex for Categories',
					'noindex_tag' => 'Use noindex for Tags',
					'noindex_arc' => 'Use noindex for Archives'
				),
				'desc'       => 'Exclude category pages, tag pages, or archive pages from being crawled (useful to avoid duplicate content)'
			),
			'enable_seo' => array(
				'type'       => 'checkbox',
				'label'      => 'Enable',
				'title'      => 'Enable in post/page',
				'desc'       => 'Check this to enable SEO Settings in post/page. You can enter meta description, meta keywords, and set noindex in each post/page',
				'std'        => '1'
			)
		)
	),
	'footer' => array(
		'title' => 'Footer',
		'fields' => array(
			'footer_widget' => array(
				'type'       => 'checkbox',
				'title'      => 'Footer Widget Area',
				'label'      => 'Show widget area',
				'std'        => '1'
			),
			'footer_text_1' => array(
				'type'       => 'textarea',
				'title'      => 'Footer Text 1',
				'rows'       => '2',
				'std'        => 'Copyright &copy; 2013 Compasso Theme. All rights reserved.'
			),
			'footer_text_2' => array(
				'type'       => 'textarea',
				'title'      => 'Footer Text 2',
				'rows'       => '2',
				'std'        => 'Powered by WordPress'
			),
			'footer_scripts' => array(
				'type'       => 'textarea',
				'title'      => 'Footer Scripts',
				'rows'       => '5',
				'desc'       => 'Enter your custom footer scripts (such as Google Analytics or other statistics code) here. It will be inserted before the closing body tag'
			)
		)
	)
);
