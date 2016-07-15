<?php
/*
Plugin Name: ChinaTimes RSS Feed
Description: This is a customized RSS Feed for ChinaTimes
*/
/* Start Adding Functions Below this Line */
 
add_action('init', 'chinatimesRSS');
function chinatimesRSS(){
  add_feed('chinatimes', 'chinatimesRSSFunc');
}

function chinatimesRSSFunc(){
  include('rss.php');
}
 
/* Stop Adding Functions Below this Line */
?>
