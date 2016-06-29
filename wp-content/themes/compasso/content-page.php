<article id="post-<?php the_ID(); ?>" <?php post_class('box mb20'); ?>>

	<header class="entry-header">
		<?php g7_breadcrumbs(); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<!--<?php edit_post_link(__('Edit', 'g7theme'), '<div class="entry-meta">(', ')</div>'); ?>-->
	</header>

	<?php if (has_post_thumbnail()) : ?>
	<div class="post-image-page clearfix mt20">
		<?php echo g7_image(940, 450, 0, false); ?>
	</div>
	<?php endif; ?>

	<?php if (!empty($post->post_content)) : ?>
		<div class="entry-content clearfix mt20">
			<?php the_content(); ?>
		</div>
		<?php wp_link_pages(array(
			'before'         => '<footer class="entry-footer mt20"><p><strong>' . __('Pages', 'g7theme') . ':</strong> ',
			'after'          => '</p></footer>',
			'next_or_number' => 'number',
		)); ?>
	<?php endif; ?>

</article>
