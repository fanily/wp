<?php
/* Template Name: Sitemap */
$custom = get_post_custom();

get_header();
?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php while (have_posts()) : the_post(); ?>
		<?php get_template_part('content', 'page'); ?>
	<?php endwhile; ?>

	<div class="box mb20">
		<?php if (!empty($custom['_g7_sitemap_pages'][0])) : ?>
		<h3><?php echo $custom['_g7_sitemap_pages_title'][0]; ?></h3>
		<ul>
			<?php wp_list_pages('title_li=&depth=0'); ?>
		</ul>
		<?php endif; ?>


		<?php if (!empty($custom['_g7_sitemap_categories'][0])) : ?>
		<h3><?php echo $custom['_g7_sitemap_categories_title'][0]; ?></h3>
		<ul>
			<?php wp_list_categories('sort_column=name&optioncount=0&hierarchical=1&title_li='); ?>
		</ul>
		<?php endif; ?>


		<?php if (!empty($custom['_g7_sitemap_month'][0])) : ?>
		<h3><?php echo $custom['_g7_sitemap_month_title'][0]; ?></h3>
		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>
		<?php endif; ?>


		<?php if (!empty($custom['_g7_sitemap_posts'][0])) : ?>
		<h3><?php echo $custom['_g7_sitemap_posts_title'][0]; ?></h3>
		<ul>
			<?php
			$recent_posts = wp_get_recent_posts(array(
				'numberposts' => $custom['_g7_sitemap_posts_num'][0],
				'post_status' => 'publish'
			));
			foreach ($recent_posts as $recent) : ?>
			<li>
				<a href="<?php echo get_permalink($recent['ID']); ?>" title="<?php echo esc_attr($recent['post_title']); ?>">
					<?php echo $recent['post_title']; ?>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>

	<?php comments_template(); ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>