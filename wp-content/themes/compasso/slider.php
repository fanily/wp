<?php
$slider_tags = explode(',', g7_option('slider_tags'));
foreach ((array)$slider_tags as $tag) {
	$tags[] = trim($tag);
}

$slider = new WP_Query(array(
	'showposts' => g7_option('slider_limit'),
	'tag_slug__in' => $tags
));

$slider_width = 620;
$slider_height = 350;
?>
<?php if ($slider->have_posts()) : ?>
<div class="sixteen columns mb30">
	<div class="flexslider">
		<ul class="slides">
			<?php while ($slider->have_posts()) : $slider->the_post(); ?>
			<?php
			$image = g7_image($slider_width, $slider_height, 1, false);
			if (!$image) {
				continue;
			}
			$post_id        = get_the_ID();
			$enable_rating  = g7_option('enable_ratings') && get_post_meta($post_id, '_g7_enable_rating', true);
			$overall_rating = get_post_meta($post_id, '_g7_overall_rating', true);
			?>
			<li>
				<?php echo $image; ?>
				<div class="flex-caption">
					<div class="caption">
						<?php if (g7_option('slider_category')) : ?>
						<span class="category">
							<?php the_category(', '); ?>
						</span>
						<?php endif; ?>

						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

						<?php if (g7_option('slider_rating') && $enable_rating && $overall_rating) : ?>
							<div class="rating"><?php echo g7_rating($overall_rating); ?></div>
						<?php endif; ?>

						<div class="excerpt">
							<?php the_excerpt(); ?>
						</div>

						<?php if (g7_option('slider_readmore')) : ?>
						<div class="more">
							<a class="btn" href="<?php the_permalink(); ?>"><?php _e('read more', 'g7theme'); ?></a>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</li>
			<?php endwhile; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
