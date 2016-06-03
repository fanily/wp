<?php get_header(); ?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php if (have_posts()) : ?>

		<?php
			/* Queue the first post, that way we know
			 * what author we're dealing with (if that is the case).
			 *
			 * We reset this later so we can run the loop
			 * properly with a call to rewind_posts().
			 */
			the_post();
		?>

		<header class="page-header box mb20">
			<?php g7_breadcrumbs(); ?>
			<h1 class="page-title author"><span><?php _e('Author Archives', 'g7theme'); ?></span></h1>
			<?php if (get_the_author_meta('description')) : ?>
			<div class="archive-meta clearfix">
				<div class="author-avatar">
					<?php echo get_avatar(get_the_author_meta('user_email')); ?>
				</div>
				<div class="author-description">
					<h2><?php the_author_link(); ?></h2>
					<?php the_author_meta('description'); ?>
				</div>
			</div>
			<?php endif; ?>
		</header>

		<?php
			/* Since we called the_post() above, we need to
			 * rewind the loop back to the beginning that way
			 * we can run the loop properly, in full.
			 */
			rewind_posts();
		?>

		<div class="list-container mb20">
			<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part('content'); ?>
			<?php endwhile; ?>
		</div>

		<?php g7_pagination(); ?>

	<?php else : ?>

		<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>