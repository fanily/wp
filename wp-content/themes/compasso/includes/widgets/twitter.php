<?php
class G7_Twitter_Widget extends G7_Widget {

	function __construct() {

		$this->widget = array(
			'id_base' => 'g7_twitter',
			'name' => G7_NAME . ' - Twitter',
			'description' => __('Latest twitter updates', 'g7theme')
		);

		parent::__construct();
	}

	function set_fields() {
		$fields = array(
			'title' => array(
				'type' => 'text',
				'label' => __('Title', 'g7theme'),
				'std' => __('Latest Tweets', 'g7theme')
			),
			'consumer_key' => array(
				'type' => 'text',
				'label' => 'Consumer key',
				'std' => ''
			),
			'consumer_secret' => array(
				'type' => 'text',
				'label' => 'Consumer secret',
				'std' => ''
			),
			'access_token' => array(
				'type' => 'text',
				'label' => 'Access token',
				'std' => ''
			),
			'access_token_secret' => array(
				'type' => 'text',
				'label' => 'Access token secret',
				'std' => ''
			),
			'screen_name' => array(
				'type' => 'text',
				'label' => 'Twitter username',
				'std' => ''
			),
			'count' => array(
				'type' => 'text',
				'label' => __('Number of updates', 'g7theme'),
				'std' => 2,
				'attributes' => 'size="3"'
			),
			'follow_text' => array(
				'type' => 'text',
				'label' => __('Follow text', 'g7theme'),
				'std' => __('Follow us on Twitter', 'g7theme')
			)
		);
		$this->fields = $fields;
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;

		$title = apply_filters('widget_title', $instance['title']);
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}

		$username            = $instance['screen_name'];
		$number              = $instance['count'];
		$consumer_key        = isset($instance['consumer_key']) ? $instance['consumer_key'] : '';
		$consumer_secret     = isset($instance['consumer_secret']) ? $instance['consumer_secret'] : '';
		$access_token        = isset($instance['access_token']) ? $instance['access_token'] : '';
		$access_token_secret = isset($instance['access_token_secret']) ? $instance['access_token_secret'] : '';

		$transient_name = "twitter_{$username}_{$number}";
		$cache_time = 10;

		if (!empty($username)) {
			if (false === ($tweets = get_transient($transient_name))) {
				require_once PARENT_DIR . '/includes/twitteroauth/twitteroauth.php';
				$toa = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
				$tweets = $toa->get(
					'statuses/user_timeline',
					array(
						'screen_name'     => $username,
						'count'           => $number,
						'exclude_replies' => false
					)
				);
				// print_r($tweets);
				// die;
				if (!empty($tweets) && empty($tweets->errors)) {
					set_transient($transient_name, $tweets, 60 * $cache_time);
				}
			}
			?>

			<?php if (!empty($tweets) && empty($tweets->errors)) : ?>

				<ul>
					<?php
					foreach ($tweets as $tweet) :
					$time = strtotime($tweet->created_at);
					$human_time = human_time_diff($time);
					$tweet_link = "http://twitter.com/{$username}/statuses/{$tweet->id_str}";
					?>
					<li>
						<?php echo $this->twitter_content($tweet->text); ?>
						<span class="date"><a href="<?php echo $tweet_link; ?>"><?php echo $human_time; ?> <?php _e('ago', 'g7theme'); ?></a></span>
					</li>
					<?php endforeach; ?>
				</ul>

			<?php else : ?>
			
				<?php  if (!empty($tweets->errors)) : ?>
					<!--
					<?php foreach ($tweets->errors as $error) : ?>
					<?php echo $error->message; ?> (code: <?php echo $error->code; ?>)
					<?php endforeach; ?>
					-->
				<?php endif;  ?>
				
			<?php endif; ?>

			<?php if (!empty($instance['follow_text'])) : ?>
			<a class="follow-us" href="http://twitter.com/<?php echo $username; ?>">
				<?php echo $instance['follow_text']; ?>
			</a>
			<?php endif; ?>

			<?php
		}

		echo $after_widget;
	}

	function twitter_content($content) {
		//create general link
		$content = make_clickable($content);

		//create link for @username
		$content = preg_replace("/[@]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/\\1\">\\0</a>", $content);

		//create link for #hashtag
		$content = preg_replace("/[#]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/search?q=%23\\1\">\\0</a>", $content);

		return $content;
	}

}
