<?php
$prefix = '_g7_';

$sidebars['sidebar'] = 'Default Sidebar';
foreach ((array)g7_option('sidebar') as $v) {
	if (trim($v) == '') {
		continue;
	}
	$sidebars[$v] = $v;
}

$meta_boxes['layout'] = array(
	'id'           => 'layout_metabox',
	'title'        => 'Layout Options',
	'pages'        => array('page', 'post'),
	'templates_ex' => array('page-masonry.php', 'page-categories-masonry.php'),
	'context'      => 'normal',
	'priority'     => 'high',
	'fields'       => array(
		array(
			'name'    => 'Page Layout',
			'id'      => $prefix . 'layout',
			'type'    => 'select',
			'options' => array(
				'0'   => 'Default Layout',
				'1'   => 'Right Sidebar',
				'2'   => 'Left Sidebar',
				'3'   => 'Full Width'
			)
		),
		array(
			'name'    => 'Sidebar',
			'id'      => $prefix . 'sidebar',
			'type'    => 'select',
			'options' => $sidebars
		)
	)
);

$meta_boxes['list'] = array(
	'id'        => 'list_metabox',
	'title'     => 'List Options',
	'pages'     => array('page'),
	'templates' => array('page-list.php'),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		array(
			'name'    => 'Number of posts per page',
			'id'      => $prefix . 'list_number',
			'type'    => 'text',
			'size'    => 2,
			'desc'    => 'Leave this blank if you want to use default setting (<a href="' . admin_url('options-reading.php') . '" target="_blank">Reading Settings</a>)'
		),
		array(
			'name'    => 'Filter by Category',
			'id'      => $prefix . 'list_category',
			'type'    => 'category'
		)
	)
);

$meta_boxes['grid'] = array(
	'id'        => 'grid_metabox',
	'title'     => 'Grid Options',
	'pages'     => array('page'),
	'templates' => array('page-grid.php'),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		array(
			'name'    => 'Number of Columns',
			'id'      => $prefix . 'grid_columns',
			'type'    => 'select',
			'options' => array(
				'1'   => '1',
				'2'   => '2',
				'3'   => '3',
				'4'   => '4'
			)
		),
		array(
			'name'    => 'Number of posts per page',
			'id'      => $prefix . 'grid_number',
			'type'    => 'text',
			'size'    => 2,
			'desc'    => 'Leave this blank if you want to use default setting (<a href="' . admin_url('options-reading.php') . '" target="_blank">Reading Settings</a>)'
		),
		array(
			'name'    => 'Filter by Category',
			'id'      => $prefix . 'grid_category',
			'type'    => 'category'
		)
	)
);

$meta_boxes['masonry'] = array(
	'id'        => 'masonry_metabox',
	'title'     => 'Masonry Options',
	'pages'     => array('page'),
	'templates' => array('page-masonry.php'),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		array(
			'name'    => 'Number of Columns',
			'id'      => $prefix . 'masonry_columns',
			'type'    => 'select',
			'options' => array(
				'2'   => '2',
				'3'   => '3',
				'4'   => '4'
			)
		),
		array(
			'name'    => 'Number of posts per page',
			'id'      => $prefix . 'masonry_number',
			'type'    => 'text',
			'size'    => 2,
			'desc'    => 'Leave this blank if you want to use default setting (<a href="'.admin_url('options-reading.php').'" target="_blank">Reading Settings</a>)'
		),
		array(
			'name'    => 'Filter by Category',
			'id'      => $prefix . 'masonry_category',
			'type'    => 'category'
		)
	)
);

$meta_boxes['category'] = array(
	'id'        => 'category_metabox',
	'title'     => 'Categories',
	'pages'     => array('page'),
	'templates' => array('page-categories.php'),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		array(
			'name'        => 'Categories (2 columns)',
			'id'          => $prefix . 'cat2col',
			'type'        => 'categories',
			'default_num' => 4
		),
		array(
			'name'        => 'Categories (1 column)',
			'id'          => $prefix . 'cat1col',
			'type'        => 'categories',
			'default_num' => 6
		)
	)
);

$meta_boxes['category2'] = array(
	'id'        => 'category2_metabox',
	'title'     => 'Categories',
	'pages'     => array('page'),
	'templates' => array('page-categories-masonry.php'),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		array(
			'name'        => 'Categories',
			'id'          => $prefix . 'cat',
			'type'        => 'categories',
			'default_num' => 4
		)
	)
);

if (g7_option('enable_seo')) {
	$meta_boxes['seo'] = array(
		'id'       => 'seo_metabox',
		'title'    => 'SEO Options',
		'pages'    => array('post', 'page'),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => 'Description',
				'id'   => $prefix . 'seo_description',
				'type' => 'textarea',
				'cols' => '60',
				'rows' => '2',
				'desc' => 'Use this to add meta description'
			),
			array(
				'name' => 'Keywords',
				'id'   => $prefix . 'seo_keywords',
				'type' => 'textarea',
				'cols' => '60',
				'rows' => '2',
				'desc' => 'Use this to add keywords (comma separated)'
			),
			array(
				'name'  => 'Noindex',
				'id'    => $prefix . 'seo_noindex',
				'type'  => 'checkbox',
				'label' => 'Enable noindex for this post/page',
				'std'   => 0
			)
		)
	);
}

if (g7_option('enable_ratings')) {
	$meta_boxes['rating'] = array(
		'id'       => 'rating_metabox',
		'title'    => 'Rating',
		'pages'    => array('post'),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'id'      => $prefix . 'enable_rating',
				'type'    => 'checkbox',
				'label'   => 'Enable rating on this post'
			),
			array(
				'id'      => $prefix . 'criteria',
				'id2'     => $prefix . 'rating',
				'id3'     => $prefix . 'overall_rating',
				'type'    => 'rating',
				'min'     => '0',
				'max'     => '5',
				'step'    => '0.5'
			)
		)
	);
}

$meta_boxes['sitemap'] = array(
	'id'        => 'sitemap_metabox',
	'title'     => 'Sitemap Options',
	'pages'     => array('page'),
	'templates' => array('page-sitemap.php'),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		array(
			'name' => 'Show Pages',
			'id'   => $prefix . 'sitemap_pages',
			'type' => 'checkbox',
			'std'  => 1
		),
		array(
			'name' => 'Pages Title',
			'id'   => $prefix . 'sitemap_pages_title',
			'type' => 'text',
			'std'  => 'Pages'
		),
		array(
			'name' => 'Show Categories',
			'id'   => $prefix . 'sitemap_categories',
			'type' => 'checkbox',
			'std'  => 1
		),
		array(
			'name' => 'Categories Title',
			'id'   => $prefix . 'sitemap_categories_title',
			'type' => 'text',
			'std'  => 'Categories'
		),
		array(
			'name' => 'Show Monthly Archives',
			'id'   => $prefix . 'sitemap_month',
			'type' => 'checkbox',
			'std'  => 1
		),
		array(
			'name' => 'Monthly Archives Title',
			'id'   => $prefix . 'sitemap_month_title',
			'type' => 'text',
			'std'  => 'Archives by Month'
		),
		array(
			'name' => 'Show Latest Posts',
			'id'   => $prefix . 'sitemap_posts',
			'type' => 'checkbox',
			'std'  => 1
		),
		array(
			'name' => 'Latest Posts Title',
			'id'   => $prefix . 'sitemap_posts_title',
			'type' => 'text',
			'std'  => 'Latest Posts'
		),
		array(
			'name' => 'Number of Latest Posts',
			'id'   => $prefix . 'sitemap_posts_num',
			'type' => 'text',
			'size' => 3,
			'std'  => 20
		)
	)
);

$meta_boxes['single'] = array(
	'id'       => 'single_metabox',
	'title'    => 'Post Options',
	'pages'    => array('post'),
	'context'  => 'normal',
	'priority' => 'high',
	'fields'   => array(
		array(
			'name'    => 'Featured image view',
			'id'      => $prefix . 'featured_image',
			'type'    => 'select',
			'std'     => '1',
			'options' => array(
				'1' => 'Show image',
				'2' => 'Show full height (uncropped) image',
				'3' => 'Hide image'
			)
		)
	)
);
