<?php
/* Template Name: Categories Masonry */

$layout  = g7_page_layout();
$post_id = get_the_ID();
$cat     = get_post_meta($post_id, '_g7_cat', true);
$count   = count($cat['cat']);
get_header();
?>

<?php if (!is_front_page()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="sixteen columns">
			<?php get_template_part('content', 'page'); ?>
		</div>
	<?php endwhile; ?>
<?php endif; ?>

<?php if ($count) : ?>
	<div class="clear"></div>
	<div class="category2 masonry-container clearfix masonry3col">
		<?php for ($i = 0; $i < $count - 1; $i++) : ?>
			<?php
			$c = new WP_Query(array(
				'showposts' => $cat['num'][$i],
				'cat'       => $cat['cat'][$i],
				'orderby'   => 'date',
				'order'     => 'DESC'
			));
			$box_title   = g7_category_name($cat['cat'][$i], true, __('Recent Posts', 'g7theme'));
			$image_w     = 420;
			$image_h     = null;
			$image_class = 'post-image clearfix';
			$j = 1;
			?>
			<?php if ($c->have_posts()) : ?>
				<div class="one-third column masonry-item">
					<div class="box widget">
						<?php while ($c->have_posts()) : $c->the_post(); ?>
							<?php get_template_part('content', 'category'); ?>
						<?php $j++; endwhile; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endfor; ?>
	</div>

	<div class="clear"></div>
<?php endif; ?>

<?php get_footer(); ?>