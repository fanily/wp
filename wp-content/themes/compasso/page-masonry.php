<?php
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
$cat    = get_post_meta(get_the_ID(), '_g7_masonry_category', true);
$column = get_post_meta(get_the_ID(), '_g7_masonry_columns', true);

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

<?php if (!is_front_page()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="sixteen columns">
			<?php get_template_part('content', 'page'); ?>
		</div>
	<?php endwhile; ?>
<?php endif; ?>

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