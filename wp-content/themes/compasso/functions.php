<?php
/**
 * Theme name and options field name
 */
define('G7_NAME', 'Compasso');
define('G7_FOLDERNAME', 'compasso');
define('G7_OPTIONNAME', 'compasso_options');

/**
 * Theme directory and url
 */
define('PARENT_DIR', get_template_directory());
define('PARENT_URL', get_template_directory_uri());
define('CHILD_DIR', get_stylesheet_directory());
define('CHILD_URL', get_stylesheet_directory_uri());

/**
 * Social media types
 * used in widgets and shortcodes
 */
define('G7_SOCIAL', serialize(array(
	'500px',
	'addthis',
	'behance',
	'blogger',
	'delicious',
	'deviantart',
	'digg',
	'dopplr',
	'dribbble',
	'evernote',
	'facebook',
	'flickr',
	'forrst',
	'github',
	'google+',
	'grooveshark',
	'instagram',
	'lastfm',
	'linkedin',
	'mail',
	'myspace',
	'path',
	'paypal',
	'picasa',
	'pinterest',
	'posterous',
	'reddit',
	'rss',
	'sharethis',
	'skype',
	'soundcloud',
	'spotify',
	'stumbleupon',
	'tumblr',
	'twitter',
	'viddler',
	'vimeo',
	'virb',
	'windows',
	'wordpress',
	'youtube',
	'zerply'
)));

if (!defined('G7_SHORTCODES')) {
	define('G7_SHORTCODES', true);
}


/**
 * Sets up the content width
 */
if (!isset($content_width)) {
	$content_width = 625;
}


/**
 * Get options from database
 */
if (!function_exists('g7_option')) {
	$g7_option = get_option(G7_OPTIONNAME);
	function g7_option($v, $default = '') {
		global $g7_option;
		if (!empty($g7_option[$v])) {
			if (is_string($g7_option[$v])) {
				return stripslashes($g7_option[$v]);
			}
			return $g7_option[$v];
		} else {
			return $default;
		}
	}
}


require_once(PARENT_DIR . '/includes/ajax_action.php');
require_once(PARENT_DIR . '/includes/aq_resizer.php');
require_once(PARENT_DIR . '/includes/options/options.php');
require_once(PARENT_DIR . '/includes/metabox/metabox.php');
require_once(PARENT_DIR . '/includes/widgets.php');

if (G7_SHORTCODES) {
	require_once(PARENT_DIR . '/includes/shortcodes/shortcodes.php');
	require_once(PARENT_DIR . '/includes/shortcodes/tinymce.php');
}


/**
 * Theme setup
 * register various WordPress features
 */
if (!function_exists('g7_theme_setup')) {
	function g7_theme_setup() {
		//Language localization
		load_theme_textdomain('g7theme', PARENT_DIR . '/lang');
		$locale = get_locale();
		$locale_file = PARENT_DIR . "/lang/$locale.php";
		if (is_readable($locale_file)) {
			require_once($locale_file);
		}

		//Add support for custom backgrounds
		add_theme_support('custom-background');

		//Activate post thumbnails
		add_theme_support('post-thumbnails');

		//automatic feed links
		add_theme_support('automatic-feed-links');

		//Add menu location
		register_nav_menus(array(
			'mainmenu' => __('Main Menu', 'g7theme'),
			'topmenu' => __('Top Menu', 'g7theme')
		));
	}
	add_action('after_setup_theme', 'g7_theme_setup');
}


/**
 * Enqueue all javascript files used in public
 */
if (!function_exists('g7_scripts')) {
	function g7_scripts() {
		wp_enqueue_script('jquery', false, array(), false, true);
		wp_enqueue_script('easing', PARENT_URL . '/js/jquery.easing.1.3.js', array('jquery'), false, true);
		wp_enqueue_script('placeholder', PARENT_URL . '/js/jquery.placeholder.min.js', array('jquery'), false, true);
		wp_enqueue_script('jquery-masonry2', PARENT_URL . '/js/jquery.masonry.min.js', array('jquery'), false, true);
		wp_enqueue_script('fitvids', PARENT_URL . '/js/jquery.fitvids.js', array('jquery'), false, true);
		if (is_front_page() && g7_option('slider')) {
			wp_enqueue_script('flex', PARENT_URL . '/js/jquery.flexslider-min.js', array('jquery'), false, true);
		}
		if (g7_option('responsive')) {
			wp_enqueue_script('mobilemenu', PARENT_URL . '/js/jquery.mobilemenu.js', array('jquery'), false, true);
		}
		if (g7_option('lightbox')) {
			wp_enqueue_script('prettyPhoto', PARENT_URL . '/js/jquery.prettyPhoto.js', array('jquery'), false, true);
		}
		wp_enqueue_script('scripts', PARENT_URL . '/js/scripts.js', array('jquery'), false, true);
		wp_localize_script('scripts', 'g7', array(
			'ajaxurl'               => admin_url('admin-ajax.php'),
			'slider_animation'      => g7_option('slider_animation'),
			'slider_slideshowSpeed' => g7_option('slider_slideshowSpeed'),
			'slider_animationSpeed' => g7_option('slider_animationSpeed'),
			'slider_pauseOnHover'   => g7_option('slider_pauseOnHover'),
			'navigate_text'         => __('選擇分類...', 'g7theme'),
			'rtl'                   => is_rtl()
		));
		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply', false, array(), false, true);
		}
	}
	add_action('wp_enqueue_scripts', 'g7_scripts');
}


/**
 * Enqueue all css files used in public
 */
if (!function_exists('g7_styles')) {
	function g7_styles() {
		if (is_front_page() && g7_option('slider')) {
			wp_enqueue_style('flexslider', PARENT_URL . '/css/flexslider.css');
		}
		if (g7_option('color')) {
			$color = str_replace('#', '', g7_option('color'));
			wp_enqueue_style('skin', PARENT_URL . '/css/skin.php?c=' . $color);
		}
		if (g7_option('responsive')) {
			wp_enqueue_style('mediaqueries', PARENT_URL . '/css/mediaqueries.css');
		}
		if (g7_option('lightbox')) {
			wp_enqueue_style('prettyPhoto', PARENT_URL . '/css/prettyPhoto.css');
		}
	}
	add_action('wp_enqueue_scripts', 'g7_styles');
}


/**
 * Update notifier
 */
if (g7_option('update_notifier')) {
	include_once('update-notifier.php');
}


/**
 * custom excerpt more
 */
if (!function_exists('g7_excerpt_more')) {
	function g7_excerpt_more($more) {
		return '';
	}
	add_filter('excerpt_more', 'g7_excerpt_more');
}


/**
 * custom excerpt length
 */
if (!function_exists('g7_excerpt_length')) {
	function g7_excerpt_length($length) {
		return 20;
	}
	add_filter('excerpt_length', 'g7_excerpt_length');
}


/**
 * Add custom body class
 */
if (!function_exists('g7_body_class')) {
	function g7_body_class($classes) {
		if (in_array('search-no-results', $classes)) {
			$classes[] = 'g7-single';
		}
		elseif (is_home() ||
			is_archive() ||
			is_search() ||
			is_page_template('page-list.php') ||
			is_page_template('page-grid.php') ||
			is_page_template('page-masonry.php') ||
			is_page_template('page-categories.php') ||
			is_page_template('page-categories-masonry.php')) {
			$classes[] = 'g7-list';
		}
		elseif (is_singular() || is_404()) {
			$classes[] = 'g7-single';
		}
		return $classes;
	}
	add_filter('body_class', 'g7_body_class');
}


/**
 * Shows a featured image from a post
 */
if (!function_exists('g7_image')) {
	function g7_image($w, $h, $link = 1, $overlay = true) {
		if (!has_post_thumbnail()) {
			return '';
		}
		$thumb = get_post_thumbnail_id();
		$img_url = wp_get_attachment_url($thumb, 'full');
		$image = aq_resize($img_url, $w, $h, true);
		if (empty($image)) {
			return '';
		}
		switch ($link) {
			case 1:
				$link1 = '<a href="'.get_permalink().'">';
				$link2 = '</a>';
				$class = '';
				break;
			case 2:
				$link1 = '<a href="'.$img_url.'" rel="prettyPhoto">';
				$link2 = '</a>';
				$class = ' zoom';
				break;
			default:
				$link1 = '';
				$link2 = '';
				$class = '';
				break;
		}
		return sprintf(
			'%s<img src="%s" alt="%s">%s%s',
			$link1,
			$image,
			esc_attr(get_the_title()),
			$overlay ? '<span class="overlay' . $class . '"></span>' : '',
			$link2
		);
	}
}


/**
 * Custom favicon
 * Upload the favicon file from Theme Options page
 */
if (!function_exists('g7_favicon')) {
	function g7_favicon() {
		if (g7_option('favicon')) {
			echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.g7_option('favicon').'">';
		}
	}
	add_action('wp_head', 'g7_favicon');
}


/**
 * Custom login logo
 * Upload the logo file from Theme Options page
 */
if (!function_exists('g7_login_logo')) {
	function g7_login_logo() {
		if (g7_option('login_logo')) {
			echo '<style type="text/css">
			.login h1 a { background-image: url('.g7_option('login_logo').') !important; }
			</style>';
		}
	}
	add_action('login_head', 'g7_login_logo');
}


/**
 * Shows share icons on single post page
 * Can be activated individually from Theme Options page
 */
if (!function_exists('g7_share')) {
	function g7_share() {
		$share = array();
		if (g7_option('share_facebook')) {
			$share['facebook'] = array(
				'title' => 'Share this post on Facebook',
				'url' => 'http://www.facebook.com/sharer.php?u=%s&t=%s'
			);
		}
		if (g7_option('share_twitter')) {
			$share['twitter'] = array(
				'title' => 'Tweet about this post',
				'url' => 'http://twitter.com/share?url=%s&text=%s'
			);
		}
		if (g7_option('share_delicious')) {
			$share['delicious'] = array(
				'title' => 'Bookmak on del.icio.us',
				'url' => 'http://del.icio.us/post?url=%s&title=%s'
			);
		}
		if (g7_option('share_digg')) {
			$share['digg'] = array(
				'title' => 'Submit to Digg',
				'url' => 'http://digg.com/submit?url=%s&title=%s'
			);
		}
		if (g7_option('share_reddit')) {
			$share['reddit'] = array(
				'title' => 'Share this on Reddit',
				'url' => 'http://reddit.com/submit?url=%s&title=%s'
			);
		}
		if (g7_option('share_stumbleupon')) {
			$share['stumbleupon'] = array(
				'title' => 'Share on StumbleUpon',
				'url' => 'http://www.stumbleupon.com/submit?url=%s&title=%s'
			);
		}
		if (g7_option('share_linkedin')) {
			$share['linkedin'] = array(
				'title' => 'Share this on Linkedin',
				'url' => 'http://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s'
			);
		}
		if (g7_option('share_google+')) {
			$share['google+'] = array(
				'title' => 'Share on Google+',
				'url' => 'https://plus.google.com/share?url=%s'
			);
		}
		?>
		<?php if (!empty($share)) : ?>
		<ul>
			<?php foreach ($share as $k => $v) : ?>
			<li>
				<a href="<?php echo esc_attr(sprintf($v['url'], get_permalink(), get_the_title_rss())); ?>" title="<?php echo esc_attr($v['title']); ?>" target="_blank" rel="nofollow">
					<img src="<?php echo PARENT_URL; ?>/images/social/32px/<?php echo $k; ?>.png" alt="<?php echo $v['title']; ?>">
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
		<?php
	}
}


/**
 * Get page layout
 * right sidebar, left sidebar, or full width
 */
if (!function_exists('g7_page_layout')) {
	function g7_page_layout() {
		$default_layout = g7_option('layout', 1);
		if (is_page() || is_single()) {
			$layout = get_post_meta(get_the_ID(), '_g7_layout', true);
			if (empty($layout)) {
				return $default_layout;
			}
			return $layout;
		}
		return $default_layout;
	}
}


/**
 * Shows related posts on single post
 */
if (!function_exists('g7_related_posts')) {
	function g7_related_posts($post_id, $number = 5) {
		$tags = get_the_tags();
		if (!$tags) {
			?>
			<span class="norelated"><?php _e('No related posts found', 'g7theme'); ?>.</span>
			<?php
			return;
		}
		if ($tags) {
			foreach($tags as $tag) {
				$t[] = $tag->term_id;
			}
		}
		$related_posts = new WP_Query(array(
			'showposts' => $number,
			'post__not_in' => array($post_id),
			'tag__in' => $t,
		));
		?>
		<?php if ($related_posts->have_posts()) : ?>
			<ul>
				<?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
				<li class="post">
					<div class="block-image side"><?php echo g7_image(45, 45, true, false); ?></div>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<span class="date"><?php the_time(get_option('date_format')); ?></span>
					<div class="clear"></div>
				</li>
				<?php endwhile; ?>
			</ul>
		<?php else: ?>
			<span class="norelated"><?php _e('No related posts found', 'g7theme'); ?>.</span>
		<?php endif; ?>
		<?php
		wp_reset_postdata();
	}
}


/**
 * Pagination function
 *
 * @param string $pages
 * @param int $range
 * @author Kriesi
 * @link http://www.kriesi.at/archives/how-to-build-a-wordpress-post-pagination-without-plugin
 */
if (!function_exists('g7_pagination')) {
	function g7_pagination($pages = '', $range = 3, $numbered = true) {
		if (!$numbered) {
			echo '<div class="pagination box">' . get_posts_nav_link() . '</div>';
			return;
		}
		$showitems = ($range * 2) + 1;
		global $paged;
		if (empty($paged)) {
			$paged = 1;
		}
		if ($pages == '') {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if (!$pages) {
				$pages = 1;
			}
		}
		if (1 != $pages) {
			echo '<div class="pagination box">';
			if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
				echo '<a href="'.get_pagenum_link(1).'"><span class="arrows">&laquo;</span> ' . __('第一頁', 'g7theme') . '</a>';
			}
			if ($paged > 1 && $showitems < $pages) {
				echo '<a href="'.get_pagenum_link($paged - 1).'"><span class="arrows">&lsaquo;</span> ' . __('前一頁', 'g7theme') . '</a>';
			}
			for ($i = 1; $i <= $pages; $i++) {
				if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
					if ($paged == $i) {
						echo '<span class="current">'.$i.'</span>';
					} else {
						echo '<a href="'.get_pagenum_link($i).'" class="inactive">'.$i.'</a>';
					}
				}
			}
			if ($paged < $pages && $showitems < $pages) {
				echo '<a href="'.get_pagenum_link($paged + 1).'">' . __('下一頁', 'g7theme') . ' <span class="arrows">&rsaquo;</span></a>';
			}
			if ($paged < $pages-1 && $paged + $range - 1 < $pages && $showitems < $pages) {
				echo '<a href="'.get_pagenum_link($pages).'">' . __('最終頁', 'g7theme') . ' <span class="arrows">&raquo;</span></a>';
			}
			echo "</div>\n";
		}
	}
}


/**
 * Comment List
 * called from comments.php
 */
if (!function_exists('g7_commentlist')) {
	function g7_commentlist($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		switch ($comment->comment_type) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e('Pingback:', 'g7theme'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'g7theme'), '<span class="edit-link">', '</span>'); ?></p>
		<?php
				break;
			default :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<a class="comment-avatar" href="<?php comment_author_url(); ?>"><?php echo get_avatar($comment, 45); ?></a>
				<div class="comment-content">
					<footer>
						<span class="fn"><?php comment_author_link(); ?></span>
						<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
							<time pubdate datetime="<?php comment_time('c'); ?>">
								<?php printf(__('%1$s at %2$s', 'g7theme'), get_comment_date(), get_comment_time()); ?>
							</time>
						</a>
						<?php edit_comment_link(__('(Edit)', 'g7theme'), '<span class="edit-link">', '</span>'); ?>
					</footer>
					<?php comment_text(); ?>
					<?php if ($comment->comment_approved == '0') : ?>
						<div class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'g7theme'); ?></div>
					<?php endif; ?>
					<?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'g7theme'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
				</div>
			</article><!-- #comment-## -->
		<?php
				break;
		endswitch;
	}
}


/**
 * Breadcrumb navigation
 */
if (!function_exists('g7_breadcrumbs')) {
	function g7_breadcrumbs() {
		if (is_front_page()) {
			return;
		}
		if (!g7_option('breadcrumbs')) {
			return;
		}
		$separator = sprintf(
			'<span class="bc-separator"><img src="%s/images/%s" alt="&raquo;"></span>',
			PARENT_URL,
			is_rtl() ? 'arrow-left2.gif' : 'arrow-right2.gif'
		);
		$name = __('首頁', 'g7theme');
		$currentBefore = '<span class="bc-current">';
		$currentAfter = '</span>';
		echo '<p id="breadcrumbs">';
		if (g7_option('breadcrumbs_text')) {
			echo '<span class="bc-info">';
			echo g7_option('breadcrumbs_text');
			echo '</span> ';
		}
		if (!is_home() && !is_front_page() || is_paged()) {
			global $post;
			$home = esc_url(home_url('/'));
			echo '<a href="' . $home . '">' . $name . '</a> ' . $separator . ' ';
			if (is_tax()) {
				  $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
				  echo $currentBefore . $term->name . $currentAfter;
			} elseif (is_category()) {
				global $wp_query;
				$cat_obj = $wp_query->get_queried_object();
				$thisCat = $cat_obj->term_id;
				$thisCat = get_category($thisCat);
				$parentCat = get_category($thisCat->parent);
				if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $separator . ' '));
				_e('Category Archives', 'g7theme');
				echo " $separator ";
				echo $currentBefore . '';
				single_cat_title();
				echo '' . $currentAfter;
			} elseif (is_day()) {
				echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $separator . ' ';
				echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $separator . ' ';
				echo $currentBefore . get_the_time('d') . $currentAfter;
			} elseif (is_month()) {
				echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $separator . ' ';
				echo $currentBefore . get_the_time('F') . $currentAfter;
			} elseif (is_year()) {
				echo $currentBefore . get_the_time('Y') . $currentAfter;
			} elseif (is_single()) {
				$postType = get_post_type();
				if ($postType == 'post') {
					$cat = get_the_category(); $cat = $cat[0];
					echo get_category_parents($cat, TRUE, ' ' . $separator . ' ');
				}
				echo $currentBefore;
				the_title();
				echo $currentAfter;
			} elseif (is_page() && !$post->post_parent) {
				echo $currentBefore;
				the_title();
				echo $currentAfter;
			} elseif (is_page() && $post->post_parent) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page = get_page($parent_id);
					$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
					$parent_id  = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $separator . ' ';
				echo $currentBefore;
				the_title();
				echo $currentAfter;
			} elseif (is_search()) {
				echo $currentBefore . __('搜尋結果:', 'g7theme'). ' &quot;' . get_search_query() . '&quot;' . $currentAfter;
			} elseif (is_tag()) {
				echo $currentBefore . __('Post Tagged with:', 'g7theme'). ' &quot;';
				single_tag_title();
				echo '&quot;' . $currentAfter;
			} elseif (is_author()) {
				global $author;
				$userdata = get_userdata($author);
				echo $currentBefore . __('Author Archive', 'g7theme') . $currentAfter;
			} elseif (is_404()) {
				echo $currentBefore . __('Page Not Found', 'g7theme') . $currentAfter;
			}
			if (get_query_var('paged')) {
				//if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ' (';
				//echo __('Page') . ' ' . get_query_var('paged');
				//if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ')';
			}
			echo '</p>';
		}
	}
}


/**
 * Shows menu from a location
 */
if (!function_exists('g7_menu')) {
	function g7_menu($location, $class = '') {
		if (has_nav_menu($location)) {
			wp_nav_menu(array(
				'theme_location' => $location,
				'container' => '',
				'menu_id' => $location,
				'menu_class' => $class
			));
		} else {
			echo '<ul id="' . $location . '"><li><a href="' . esc_url(home_url('/')) . '">Home</a>';
			wp_list_pages('title_li=');
			echo '</ul>';
		}
	}
}


/**
 * Insert footer scripts
 */
if (!function_exists('g7_footer_scripts')) {
	function g7_footer_scripts() {
		echo g7_option('footer_scripts');
	}
	add_action('wp_footer', 'g7_footer_scripts');
}


/**
 * Add custom title
 */
if (!function_exists('g7_seo_title')) {
	function g7_seo_title($title) {
		global $post;
		if ($post && g7_option('enable_seo')) {
			if (is_single() || is_page()) {
				$seo_title = get_post_meta($post->ID, '_g7_seo_title', true);
			}
			if (!empty($seo_title)) {
				return $seo_title . ' | ';
			}
		}
		return $title;
	}
	add_filter('wp_title', 'g7_seo_title', 10);
}


/**
 * Add meta description
 */
if (!function_exists('g7_seo_description')) {
	function g7_seo_description() {
		$seo_description = '';
		if (is_home() || is_front_page()) {
			if (g7_option('meta_description')) {
				$seo_description = g7_option('meta_description');
			}
		} elseif (is_single() || is_page()) {
			global $post;
			if ($post && g7_option('enable_seo')) {
				$seo_description = get_post_meta($post->ID, '_g7_seo_description', true);
			}
		}
		if ($seo_description) {
			echo '<meta name="description" content="'. esc_html(strip_tags($seo_description)) .'">' . "\n";
		}
	}
	add_action('wp_head', 'g7_seo_description');
}


/**
 * Add meta keywords
 */
if (!function_exists('g7_seo_keywords')) {
	function g7_seo_keywords() {
		$seo_keywords = '';
		if (is_home() || is_front_page()) {
			if (g7_option('meta_keywords')) {
				$seo_keywords = g7_option('meta_keywords');
			}
		} elseif (is_single() || is_page()) {
			global $post;
			if ($post && g7_option('enable_seo')) {
				$seo_keywords = get_post_meta($post->ID, '_g7_seo_keywords', true);
			}
		}
		if ($seo_keywords) {
			echo '<meta name="keywords" content="'. esc_html(strip_tags($seo_keywords)) .'">' . "\n";
		}
	}
	add_action('wp_head', 'g7_seo_keywords');
}


/**
 * SEO Noindex
 */
if (!function_exists('g7_seo_noindex')) {
	function g7_seo_noindex() {
		$noindex = false;
		if (is_category() && g7_option('noindex_cat')) {
			$noindex = true;
		} elseif (is_tag() && g7_option('noindex_tag')) {
			$noindex = true;
		} elseif (is_archive() && g7_option('noindex_arc')) {
			$noindex = true;
		} elseif (is_single() || is_page()) {
			global $post;
			if ($post && g7_option('enable_seo')) {
				$noindex = get_post_meta($post->ID, '_g7_seo_noindex', true);
			}
		}
		if ($noindex) {
			echo '<meta name="robots" content="noindex">';
		}
	}
	add_action('wp_head', 'g7_seo_noindex');
}


/**
 * get post rating
 */
if (!function_exists('g7_post_rating')) {
	function g7_post_rating() {
		if (!g7_option('enable_ratings')) {
			return;
		}
		$post_id        = get_the_ID();
		$enable_rating  = get_post_meta($post_id, '_g7_enable_rating', true);
		if (!$enable_rating) {
			return;
		}
		$overall_rating = get_post_meta($post_id, '_g7_overall_rating', true);
		return sprintf(
			'<div class="rating">%s</div>',
			g7_rating($overall_rating)
		);
	}
}


/**
 * get star image of rating
 */
if (!function_exists('g7_rating')) {
	function g7_rating($rating, $size = '') {
		if (empty($rating)) {
			return;
		}
		$round = round(($rating * 2), 0) / 2; //rounding to nearest half
		$prefix = 'small_';
		if ($size == 'big') {
			$prefix = '';
		}
		return sprintf(
			'<img src="%s/images/stars/%s.png" alt="%s">',
			PARENT_URL,
			$prefix . $round,
			$rating
		);
	}
}


/**
 * get sidebar ID from the sidebar name
 */
if (!function_exists('g7_sidebar_id')) {
	function g7_sidebar_id($sidebar_name) {
		$sidebar_id = str_replace(' ', '', $sidebar_name);
		$sidebar_id = strtolower($sidebar_id);
		return $sidebar_id;
	}
}


/**
 * get category name from category ID
 */
if (!function_exists('g7_category_name')) {
	function g7_category_name($cat_id, $link = false, $zero_name = '') {
		$cat_id = (int)$cat_id;
		if ($cat_id == 0) {
			if ($zero_name) {
				$name = $zero_name;
			} else {
				$name = __('All Categories', 'g7theme');
			}
		}
		else {
			if (term_exists($cat_id, 'category')) {
				$name = get_the_category_by_ID($cat_id);
				if ($link) {
					$name = sprintf(
						'<a href="%s" title="%s">%s</a>',
						esc_url(get_category_link($cat_id)),
						__('View all posts under this category', 'g7theme'),
						$name
					);
				}
			} else {
				$name = '';
			}
		}
		return $name;
	}
}
