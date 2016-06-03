<?php
/* Template Name: Grid */

if (get_query_var('paged')) {
	$paged = get_query_var('paged');
} elseif (get_query_var('page')) {
	$paged = get_query_var('page');
} else {
	$paged = 1;
}

$number = get_post_meta(get_the_ID(), '_g7_grid_number', true);
if (empty($number)) {
	$number = get_option('posts_per_page');
}
$cat    = get_post_meta(get_the_ID(), '_g7_grid_category', true);
$column = get_post_meta(get_the_ID(), '_g7_grid_columns', true);

$custom_posts = new WP_Query(array(
	'posts_per_page' => $number,
	'cat' => $cat,
	'paged' => $paged
));

$image_w = 420;
$image_h = 300;
$layout = g7_page_layout();

if ($layout == 3) {
	//full width without sidebar: allow 1, 2, 3, or 4 columns
	switch ($column) {
		case 1:
			$image_w = 940;
			$image_h = 500;
			break;
		case 2:
			$class = 'eight columns';
			$image_w = 460;
			$image_h = 320;
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
}
else {
	//with sidebar: only allow 1 or 2 columns
	switch ($column) {
		case 1:
			$image_w = 620;
			$image_h = 400;
			break;
		case 2:
		default:
			$column = 2;
			$class = 'one-third column';
			break;
	}
}

$class2[0] = ' omega';
$class2[1] = ' alpha';
$class2[2] = '';
$class2[3] = '';

$i = 1;

get_header();
?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php if (!is_front_page()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part('content', 'page'); ?>
		<?php endwhile; ?>
	<?php endif; ?>

	<?php if ($custom_posts->have_posts()) : ?>

		<?php if ($column == 1) : ?>

			<div class="grid-container clearfix">
			<?php while ($custom_posts->have_posts()) : $custom_posts->the_post(); ?>
				<?php get_template_part('content', 'grid'); ?>
			<?php endwhile; ?>
			</div>

		<?php else : ?>

			<div class="grid-container clearfix">
			<?php while ($custom_posts->have_posts()) : $custom_posts->the_post(); $mod = $i % $column; ?>
				<div class="<?php echo $class; ?><?php echo $class2[$mod]; ?>">
				<?php get_template_part('content', 'grid'); ?>
				</div>
				<?php if ($mod == 0) : ?><div class="clear"></div><?php endif; ?>
			<?php $i++; endwhile; ?>
			</div>

		<?php endif; ?>

		<?php g7_pagination($custom_posts->max_num_pages); ?>

	<?php endif; wp_reset_postdata(); ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>