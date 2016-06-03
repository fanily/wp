<?php
/* Template Name: Categories */

$layout    = g7_page_layout();
if ($layout == 3) {
	$layout = 1;
}
$post_id   = get_the_ID();
$cat2col   = get_post_meta($post_id, '_g7_cat2col', true);
$cat1col   = get_post_meta($post_id, '_g7_cat1col', true);
$count2col = count($cat2col['cat']);
$count1col = count($cat1col['cat']);

$class[0]  = ' alpha';
$class[1]  = ' omega';

get_header();
?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php if (!is_front_page()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part('content', 'page'); ?>
		<?php endwhile; ?>
	<?php endif; ?>

	<?php for ($i = 0; $i < $count2col - 1; $i++) : ?>
		<?php
		$c = new WP_Query(array(
			'showposts' => $cat2col['num'][$i],
			'cat'       => $cat2col['cat'][$i],
			'orderby'   => 'date',
			'order'     => 'DESC'
		));
		$box_title   = g7_category_name($cat2col['cat'][$i], true, __('Recent Posts', 'g7theme'));
		$image_w     = 420;
		$image_h     = 250;
		$image_class = 'post-image clearfix';
		$mod         = $i % 2;
		$j           = 1;
		?>
		<?php if ($c->have_posts()) : ?>
			<div class="one-third column <?php echo $class[$mod]; ?> category2">
				<div class="box widget mb20">
					<?php while ($c->have_posts()) : $c->the_post(); ?>
						<?php get_template_part('content', 'category'); ?>
					<?php $j++; endwhile; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($mod == 1) : ?>
		<div class="clear"></div>
		<?php endif; ?>
	<?php endfor; ?>

	<div class="clear"></div>

	<?php for ($i = 0; $i < $count1col - 1; $i++) : ?>
		<?php
		$c = new WP_Query(array(
			'showposts' => $cat1col['num'][$i],
			'cat'       => $cat1col['cat'][$i],
			'orderby'   => 'date',
			'order'     => 'DESC'
		));
		$box_title   = g7_category_name($cat1col['cat'][$i], true, __('Recent Posts', 'g7theme'));
		$image_w     = 420;
		$image_h     = 250;
		$image_class = 'post-image clearfix';
		$mod         = $i % 2;
		$j           = 1;
		?>
		<?php if ($c->have_posts()) : ?>
			<div class="category1">
				<div class="box widget mb20">
					<?php while ($c->have_posts()) : $c->the_post(); ?>
						<?php get_template_part('content', 'category'); ?>
					<?php $j++; endwhile; ?>
					<div class="clear"></div>
				</div>
			</div>
		<?php endif; ?>
	<?php endfor; ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>