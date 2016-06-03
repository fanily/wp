<?php
class G7_Tabs_Widget extends G7_Widget {

	function __construct() {

		$this->widget = array(
			'id_base' => 'g7_tabs',
			'name' => G7_NAME . ' - Tabbed Content',
			'description' => __('Latest posts, popular posts, tags', 'g7theme')
		);

		parent::__construct();
	}

	function set_fields() {
		$fields = array(
			'latest_number' => array(
				'type' => 'text',
				'label' => __('Number of latest posts to show', 'g7theme'),
				'std' => 5,
				'class' => '',
				'attributes' => 'size="3"'
			),
			'popular_number' => array(
				'type' => 'text',
				'label' => __('Number of popular posts to show', 'g7theme'),
				'std' => 5,
				'class' => '',
				'attributes' => 'size="3"'
			),
			'show_thumbnails' => array(
				'type' => 'checkbox',
				'label' => __('Show thumbnails', 'g7theme'),
				'std' => 1
			),
			'show_dates' => array(
				'type' => 'checkbox',
				'label' => __('Show dates', 'g7theme'),
				'std' => 1
			),
			'show_commentlinks' => array(
				'type' => 'checkbox',
				'label' => __('Show Comment Links', 'g7theme'),
				'std' => 1
			)
		);
		$this->fields = $fields;
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;

		$latest = new WP_Query(array(
			'showposts' => $instance['latest_number'],
			'orderby' => 'date',
			'order' => 'DESC'
		));

		$popular = new WP_Query(array(
			'showposts' => $instance['popular_number'],
			'orderby' => 'comment_count',
			'order' => 'DESC'
		));

		$lightbox = g7_option('lightbox', 0);
		$thumbnail_link = 1;
		if ($lightbox) {
			$thumbnail_link = 2;
		}
		?>

		<ul class="tabs">
			<li><a href="#tab-latest" class="active"><?php _e('Latest', 'g7theme'); ?></a></li>
			<li><a href="#tab-popular"><?php _e('Popular', 'g7theme'); ?></a></li>
			<li><a href="#tab-tags"><?php _e('Tags', 'g7theme'); ?></a></li>
		</ul>

		<ul class="tabs-content">
			<li id="tab-latest" class="active">
				<?php if ($latest->have_posts()) : ?>
				<ul>
					<?php $counter = 1; while ($latest->have_posts()) : $latest->the_post(); ?>
					<li class="post">
						<div class="block-image side">
							<?php echo g7_image(45, 45, $thumbnail_link, false); ?>
						</div>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

						<?php if ($instance['show_dates'] == 1) : ?>
						<span class="date"><?php the_time(get_option('date_format')); ?></span>
						<?php endif; ?>

						<?php if ($instance['show_commentlinks'] == 1) : ?>
						<span class="comments-link"><?php comments_popup_link(); ?></span>
						<?php endif; ?>

						<div class="clear"></div>
					</li>
					<?php $counter++; endwhile; ?>
				</ul>
				<?php endif; ?>
			</li>
			<li id="tab-popular">
				<?php if ($popular->have_posts()) : ?>
				<ul>
					<?php $counter = 1; while ($popular->have_posts()) : $popular->the_post(); ?>
					<li class="post">
						<div class="block-image side">
							<?php echo g7_image(45, 45, $thumbnail_link, false); ?>
						</div>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

						<?php if ($instance['show_dates'] == 1) : ?>
						<span class="date"><?php the_time(get_option('date_format')); ?></span>
						<?php endif; ?>

						<?php if ($instance['show_commentlinks'] == 1) : ?>
						<span class="comments-link"><?php comments_popup_link(); ?></span>
						<?php endif; ?>

						<div class="clear"></div>
					</li>
					<?php $counter++; endwhile; ?>
				</ul>
				<?php endif; ?>
			</li>
			<li id="tab-tags">
				<?php wp_tag_cloud('smallest=8&largest=22'); ?>
			</li>
		</ul>

		<?php
		echo $after_widget;
		wp_reset_postdata();
	}

}
