<?php
global $j, $box_title, $image_w, $image_h, $image_class;
$post_id        = get_the_ID();
$enable_rating  = g7_option('enable_ratings') && get_post_meta($post_id, '_g7_enable_rating', true);
$overall_rating = get_post_meta($post_id, '_g7_overall_rating', true);
$lightbox       = g7_option('lightbox', 0);
$thumbnail_link = 1;
if ($lightbox) {
	$thumbnail_link = 2;
}
?>
<?php if ($j == 1) : ?>
	<h2 class="widgettitle">
		<?php echo $box_title; ?>
	</h2>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="<?php echo $image_class; ?>">
			<?php echo g7_image($image_w, $image_h, $thumbnail_link); ?>
		</div>
		<div class="box-inner">
			<h2 class="entry-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
			<?php if ($enable_rating && $overall_rating) : ?>
				<div class="rating"><?php echo g7_rating($overall_rating); ?></div>
			<?php endif; ?>
			<span class="date"><?php the_time(get_option('date_format')); ?></span>
			<span class="comments-link"><?php comments_popup_link(); ?></span>
			<div class="excerpt">
				<?php the_excerpt(); ?>
			</div>
		</div>
	</article>
<?php else : ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class('morepost'); ?>>
		<div class="block-image side">
			<?php echo g7_image(45, 45, $thumbnail_link, false); ?>
		</div>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<span class="date"><?php the_time(get_option('date_format')); ?></span>
		<span class="comments-link"><?php comments_popup_link(); ?></span>
		<div class="clear"></div>
	</div>
<?php endif; ?>