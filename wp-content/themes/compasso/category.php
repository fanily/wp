<?php /*
$category = get_category(get_query_var('cat'));
$category_rss = '';
if (!empty($category)) {
	$rss_link = get_category_feed_link($category->cat_ID);
	$rss_title = __('Subscribe to this category', 'g7theme');
	$rss_image = sprintf('<img src="%s/images/social/16px/rss.png" alt="RSS">', PARENT_URL);
	$category_rss = sprintf(
		'<div class="category-feed"><a class="social" href="%s" title="%s" rel="nofollow">%s</a></div>',
		$rss_link,
		$rss_title,
		$rss_image
	);
}
$category_desc = '';
$category_description = category_description();
if (!empty($category_description)) {
	$category_desc = apply_filters('category_archive_meta', '<div class="archive-meta">' . $category_description . '</div>');
}

get_header();
?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php if (have_posts()) : ?>

		<header class="page-header box mb20">
			<?php g7_breadcrumbs(); ?>
			<h1 class="page-title"><?php echo single_cat_title('', false); ?></h1>
			<?php echo $category_rss; ?>
			<?php echo $category_desc; ?>
		</header>

		<div class="list-container mb20">
			<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part('content'); ?>
			<?php endwhile; ?>
		</div>

		<?php g7_pagination(); ?>

	<?php else : ?>

		<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); */ ?>


<?php

$category = get_category(get_query_var('cat'));
$category_rss = '';
if (!empty($category)) {
	$rss_link = get_category_feed_link($category->cat_ID);
	$rss_title = __('Subscribe to this category', 'g7theme');
	$rss_image = sprintf('<img src="%s/images/social/16px/rss.png" alt="RSS">', PARENT_URL);
	$category_rss = sprintf(
		'<div class="category-feed"><a class="social" href="%s" title="%s" rel="nofollow">%s</a></div>',
		$rss_link,
		$rss_title,
		$rss_image
	);
}
$category_desc = '';
$category_description = category_description();
if (!empty($category_description)) {
	$category_desc = apply_filters('category_archive_meta', '<div class="archive-meta">' . $category_description . '</div>');
}

/* Template Name: Masonry */

if (get_query_var('paged')) {
	$paged = get_query_var('paged');
} elseif (get_query_var('page')) {
	$paged = get_query_var('page');
} else {
	$paged = 1;
}

$number = get_post_meta(get_the_ID(), '_g7_masonry_number', true);
if (empty($number)) {
	$number = get_option('posts_per_page');
}
$cat    = (string) $category->term_id;
$column = "4";

$custom_posts = new WP_Query(array(
	'posts_per_page' => $number,
	'cat' => $cat,
	'paged' => $paged
));

$image_w = 420;
$image_h = null;

switch ($column) {
	case 2:
		$class = 'eight columns';
		$image_w = 460;
		break;
	case 3:
		$class = 'one-third column';
		break;
	case 4:
	default:
		$column = 4;
		$class = 'four columns';
		break;
}

get_header();
?>

		<div class="sixteen columns">
			<article id="post-<?php echo $category->term_id; ?>" class="box mb20 post-<?php echo $category->term_id; ?> page type-page status-publish hentry">
	<header class="entry-header">
		<!--p id="breadcrumbs"><a href="<?php echo get_site_url(); ?>/">首頁</a> <span class="bc-separator"><img src="<?php echo get_site_url(); ?>/wp-content/themes/compasso/images/arrow-right2.gif" alt="»"></span> <span class="bc-current"><?php echo $category->name; ?></span></p-->
		<h1 class="entry-title"><?php echo $category->name; ?></h1>
		<div class="entry-meta"><?php if (function_exists('z_taxonomy_image')) z_taxonomy_image(); ?><div>
		<!--<div class="entry-meta">(<a class="post-edit-link" href="<?php echo get_site_url(); ?>/wp-admin/post.php?post=<?php echo $category->term_id; ?>&amp;action=edit">Edit</a>)</div>-->
		<!--<div class="entry-meta">(<a class="post-edit-link" href="<?php echo get_site_url(); ?>/wp-admin/term.php?tag_ID=<?php echo $category->term_id; ?>&amp;action=edit">Edit</a>)</div-->
	</header>
			</article>
		</div>

<?php /* if (!is_front_page()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="sixteen columns">
			<?php get_template_part('content', 'page'); ?>
		</div>
        <?php break; ?>
	<?php endwhile; ?>
<?php endif; */ ?>

<?php if ($custom_posts->have_posts()) : ?>

	<div class="clear"></div>
	<div class="masonry-container clearfix masonry<?php echo $column; ?>col">
		<?php while ($custom_posts->have_posts()) : $custom_posts->the_post(); ?>
			<div class="<?php echo $class; ?> masonry-item">
				<?php get_template_part('content', 'grid'); ?>
			</div>
		<?php endwhile; ?>
	</div>

	<div class="clear"></div>

	<div class="sixteen columns">
		<?php g7_pagination($custom_posts->max_num_pages); ?>
	</div>

<?php endif; wp_reset_postdata(); ?>

<?php get_footer(); ?>
