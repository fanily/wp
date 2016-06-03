<?php
$post_id            = get_the_ID();
$enable_rating      = g7_option('enable_ratings') && get_post_meta($post_id, '_g7_enable_rating', true);
if ($enable_rating) {
	$criteria       = get_post_meta($post_id, '_g7_criteria', true);
	$rating         = get_post_meta($post_id, '_g7_rating', true);
	$overall_rating = get_post_meta($post_id, '_g7_overall_rating', true);
}
$image_view = get_post_meta($post_id, '_g7_featured_image', true);
switch ($image_view) {
	case 1:
	default:
		$featured_image = g7_image(940, 450, 0, false);
		break;
	case 2:
		$featured_image = g7_image(940, null, 0, false);
		break;
	case 3:
		$featured_image = '';
		break;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('box mb20'); ?>>

	<header class="entry-header mb20">
		<?php g7_breadcrumbs(); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="entry-meta">
			<span><?php _e('Posted on', 'g7theme'); ?></span>
			<?php the_time(get_option('date_format')); ?>
			<span><?php _e('by', 'g7theme'); ?></span>
			<?php the_author(); ?>
			<span><?php _e('in', 'g7theme'); ?></span>
			<?php the_category(', ') ?>
			<span><?php _e('with', 'g7theme'); ?></span>
			<?php comments_popup_link(); ?>
			<?php edit_post_link(__('Edit', 'g7theme'), '(', ')'); ?>
		</div>
	</header>

	<?php if (g7_option('single_featured_image') && (has_post_thumbnail())) : ?>
		<?php if ($featured_image) : ?>
			<div class="post-image clearfix mb20">
				<?php echo $featured_image; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="entry-content clearfix mb20">
		<?php if ($enable_rating) : ?>
		<dl class="rating">
			<?php foreach ((array)$rating as $k => $v) : ?>

			<dt><?php echo $criteria[$k]; ?></dt>
			<dd><?php echo g7_rating($v); ?></dd>

			<?php endforeach; ?>

			<?php if (count($rating) > 1) : ?>
			<dt class="overall_rating"><?php _e('Overall', 'g7theme'); ?></dt>
			<dd class="overall_rating"><?php echo g7_rating($overall_rating, 'big'); ?></dd>
			<?php endif; ?>
		</dl>
		<?php endif; ?>
		<?php the_content(); ?>
	</div>

	<footer class="entry-footer">
		<?php wp_link_pages(array(
			'before'         => '<p><strong>' . __('Pages', 'g7theme') . ':</strong> ',
			'after'          => '</p>',
			'next_or_number' => 'number',
		)); ?>

		<?php if (g7_option('single_tags')) : ?>
		<div class="tags">
			<?php the_tags(__('Tags', 'g7theme') . ': ', ', ', ''); ?>
		</div>
		<?php endif; ?>

		<?php if (g7_option('single_share')) : ?>
		<div class="g7-share">
			<h3><?php _e('Share this post', 'g7theme'); ?></h3>
			<?php g7_share(); ?>
			<div class="clear"></div>
		</div>
		<?php endif; ?>

		<nav class="clearfix mt20">
			<div class="nav-previous"><?php previous_post_link(); ?></div>
			<div class="nav-next"><?php next_post_link(); ?></div>
		</nav>
	</footer>

</article>

<?php if (g7_option('single_author_info')) : ?>
<div class="author-info box mb20">
	<h3><?php printf(__('Author', 'g7theme'), get_the_author()); ?></h3>
	<div class="author-avatar">
		<?php echo get_avatar(get_the_author_meta('email'), '45'); ?>
	</div>
	<div class="author-link">
		<h4><?php the_author_link(); ?></h4>
		<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author">
			<?php printf(__('View all posts by %s', 'g7theme'), get_the_author()); ?> <span class="meta-nav">&rarr;</span>
		</a>
	</div>
	<div class="clear"></div>
	<div class="author-description">
		<?php the_author_meta('description'); ?>
	</div>
</div>
<?php endif; ?>

<?php if (g7_option('single_related')) : ?>
<div class="box widget mb20">
	<h3><?php _e('Related Posts', 'g7theme'); ?></h3>
	<?php g7_related_posts($post->ID); ?>
</div>
<?php endif; ?>