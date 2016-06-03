<?php
/* Template Name: List */

if (get_query_var('paged')) {
	$paged = get_query_var('paged');
} elseif (get_query_var('page')) {
	$paged = get_query_var('page');
} else {
	$paged = 1;
}

$number = get_post_meta(get_the_ID(), '_g7_list_number', true);
if (empty($number)) {
	$number = get_option('posts_per_page');
}
$cat = get_post_meta(get_the_ID(), '_g7_list_category', true);

$custom_posts = new WP_Query(array(
	'posts_per_page' => $number,
	'cat' => $cat,
	'paged' => $paged
));
$layout = g7_page_layout();

get_header();
?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php if (!is_front_page()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part('content', 'page'); ?>
		<?php endwhile; ?>
	<?php endif; ?>

	<?php if ($custom_posts->have_posts()) : ?>

		<div class="list-container mb20">
			<?php while ($custom_posts->have_posts()) : $custom_posts->the_post(); ?>
				<?php get_template_part('content'); ?>
			<?php endwhile; ?>
		</div>

		<?php g7_pagination($custom_posts->max_num_pages); ?>

	<?php else: ?>

		<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>