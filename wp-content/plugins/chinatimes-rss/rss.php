<?php
$postCount = 20; // The number of posts to show in the feed
$posts = query_posts('showposts=' . $postCount);
header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
//header('Content-Type: application/rss+xml; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0"
 xmlns:content="http://purl.org/rss/1.0/modules/content/"
 xmlns:wfw="http://wellformedweb.org/CommentAPI/"
 xmlns:dc="http://purl.org/dc/elements/1.1/"
 xmlns:atom="http://www.w3.org/2005/Atom"
 xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
 xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
 <?php do_action('rss2_ns'); ?>>
<channel>
  <title><?php bloginfo_rss('name'); ?> - Feed</title>
  <link><?php bloginfo_rss('url') ?></link>
  <description><?php bloginfo_rss('description') ?></description>
  <?php while(have_posts()) : the_post(); ?>
  <item ID='<?php the_ID() ?>'> 
    <title><![CDATA[<?php the_title_rss(); ?>]]></title>
    <link><![CDATA[<?php the_permalink_rss(); ?>]]></link>
    <author><![CDATA[<?php the_author(); ?>]]></author>
    <type><![CDATA[16]]></type>
<?php
foreach ( ( get_the_category() ) as $category ) {
  echo "    <categoryname><![CDATA[" . $category->cat_name . "]]></categoryname>\n";
}
global $post;
$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "full" );
if ( $img )
  echo "    <photo><![CDATA[" . $img[0] . "]]></photo>\n";
?>
    <pubDate><?php echo mysql2date('Y-m-d H:i+0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
    <description><![CDATA[<?php the_content() ?>]]></description>
    <?php rss_enclosure(); ?>
    <?php do_action('rss2_item'); ?>
  </item>
  <?php endwhile; ?>
</channel>
</rss>
