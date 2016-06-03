<?php
$post_id        = get_the_ID();
$enable_rating  = g7_option('enable_ratings') && get_post_meta($post_id, '_g7_enable_rating', true);
$overall_rating = get_post_meta($post_id, '_g7_overall_rating', true);
$image_w        = 150;
$image_h        = 150;
$lightbox       = g7_option('lightbox', 0);
$thumbnail_link = 1;
if ($lightbox) {
	$thumbnail_link = 2;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('box post'); ?>>

	<?php if (g7_option('list_thumbnail') && has_post_thumbnail()) : ?>
	<div class="block-image side">
		<?php echo g7_image($image_w, $image_h, $thumbnail_link); ?>
	</div>
	<?php endif; ?>

	<h2 class="entry-title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>

	<?php if (g7_option('list_rating') && $enable_rating && $overall_rating) : ?>
	<div class="rating"><?php echo g7_rating($overall_rating); ?></div>
	<?php endif; ?>

	<?php if (!is_category() && g7_option('list_category')) : ?>
	<span class="category">
		<?php the_category(' '); ?>
	</span>
	<?php endif; ?>

	<?php if (g7_option('list_date')) : ?>
	<span class="date"><?php the_time(get_option('date_format')); ?></span>
	<?php endif; ?>

	<?php if (g7_option('list_comments')) : ?>
	<span class="comments-link"><?php comments_popup_link(); ?></span>
	<?php endif; ?>

	<?php if (g7_option('list_excerpt')) : ?>
	<div class="excerpt"><?php the_excerpt(); ?></div>
	<?php endif; ?>

	<?php if (g7_option('list_author') || g7_option('list_readmore')) : ?>
	<div class="entry-footer">

		<?php if (g7_option('list_readmore')) : ?>
		<a href="<?php the_permalink(); ?>" class="readmore" title="<?php _e('Read More', 'g7theme'); ?>">+</a>
		<?php endif; ?>

		<?php if (g7_option('list_author')) : ?>
		<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author"><?php the_author(); ?></a>
		<?php endif; ?>

	</div>
	<?php endif; ?>

	<div class="clear"></div>

</article>