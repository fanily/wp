<?php
$postCount = 20; // The number of posts to show in the feed
$posts = query_posts('showposts=' . $postCount);
header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
//header('Content-Type: application/rss+xml; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0">
<channel>
  <title><?php bloginfo_rss('name'); ?> - Feed</title>
  <link><?php bloginfo_rss('url') ?></link>
  <description><?php bloginfo_rss('description') ?></description>
  <generator>Fanily 粉絲玩樂</generator>
  <image>
    <url>logo連結</url>
    <title>Fanily 粉絲玩樂</title>
    <link>https://www.fanily.tw/wp-content/uploads/2016/06/fanilylogo.png</link>
  </image>
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
/*
global $post;
$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "full" );
if ( $img )
  echo "    <photo><![CDATA[" . $img[0] . "]]></photo>\n";
*/

ob_start();
the_content();
$content = ob_get_clean();
$content = preg_replace('/<\/?(a |b|span|strong)[^>]*>/i', '', $content);
$content = preg_replace("[\r\n]", '', $content);
$content = preg_replace('/<\/?(p|div|h1|h2|h3|h4)[^>]*>/i', "\n", $content);
$content = str_replace('&nbsp;', ' ', $content);
$content = html_entity_decode($content);
$content = preg_split("/\n\n+/", $content);
$text = array();
foreach ($content as $line) {
  $line = trim($line);
  if ($line == '') continue;
  $text[] = $line;
}
$photos = '';
for ($i = 0; $i < count($text); $i ++) {
  $line = $text[$i];
  if (preg_match_all('/<img [^>]*src="([^"]+)"[^>]*alt="([^"]+)"[^>]*>/i', $line, $images, PREG_SET_ORDER)) {
    $text[$i] = trim(preg_replace('/<\/?img[^>]*>/i', '', $line));
    foreach ($images as $image) {
      $photos .= '<aphoto paragraph="' . $i . '"><photo_url><![CDATA[' . $image[1] . ']]></photo_url><photo_desc><![CDATA[' . $image[2] . "]]></photo_desc></aphoto>\n";
    }
  }
}
$description = '';
foreach ($text as $line) {
  $description .= "<![CDATA[$line]]>\n";
}
?>
    <news_photos><?php echo $photos; ?></news_photos>
    <pubDate><?php echo mysql2date('Y-m-d H:i+0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
    <description><?php echo $description; ?></description>
  </item>
  <?php endwhile; ?>
</channel>
</rss>
