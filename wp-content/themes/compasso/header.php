<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php if (g7_option('top_bar')) : ?>
	<nav id="top">
		<div class="container clearfix">
			<div class="sixteen columns">
				<?php g7_menu('topmenu'); ?>
				<?php if (g7_option('header_text')) : ?>
				<div id="intro">
					<?php echo do_shortcode(g7_option('header_text')); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</nav>
	<?php endif; ?>

	<header>
		<div class="container">
			<div class="six columns">
				<div id="logo">
					<?php if (g7_option('logo') == '1') : ?>
						<a href="<?php echo esc_url(home_url('/')); ?>">
							<img src="<?php echo g7_option('logo_image'); ?>" alt="<?php bloginfo('name'); ?>">
						</a>
					<?php else : ?>
						<h1>
							<a href="<?php echo esc_url(home_url('/')); ?>">
								<?php bloginfo('name'); ?>
							</a>
						</h1>
						<h2 id="site-description"><?php bloginfo('description'); ?></h2>
					<?php endif; ?>
				</div>
			</div>
			<div class="ten columns">
				<?php if (g7_option('banner') && g7_option('banner_image')) : ?>
				<div id="top-banner">
					<a href="<?php echo g7_option('banner_link'); ?>">
						<img src="<?php echo g7_option('banner_image'); ?>" alt="banner">
					</a>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</header>

	<div id="wrapper">
		<div class="container">
			<nav id="mainnav" class="sixteen columns clearfix mb30">
				<?php g7_menu('mainmenu'); ?>
				<form method="get" id="searchf" action="<?php echo esc_url(home_url('/')); ?>">
					<input type="image" src="<?php echo PARENT_URL; ?>/images/search-16a.png" alt="Go" id="searchbtn">
					<input type="text" name="s" id="cari" placeholder="<?php _e('搜尋...', 'g7theme'); ?>">
				</form>
			</nav>

			<?php if (is_front_page() && g7_option('slider')) : ?>
				<?php get_template_part('slider'); ?>
			<?php endif; ?>
