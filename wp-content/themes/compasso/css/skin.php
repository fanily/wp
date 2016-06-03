<?php
/**
 * convert hex color to rgb format
 *
 * @param  string $hex color in hex format, without # prefix
 * @return array       color in rgb format
 */
function hex2rgb($hex) {
	$r = hexdec($hex[0] . $hex[1]);
	$g = hexdec($hex[2] . $hex[3]);
	$b = hexdec($hex[4] . $hex[5]);
	return array($r, $g, $b);
}

/**
 * add brightness to a color
 *
 * @param  string  $hex  color in hex format, without # prefix
 * @param  integer $add  number of steps
 * @return string        new brightened color in hex format
 */
function brighten($hex, $add) {
	list($r, $g, $b) = hex2rgb($hex);
	$new_r = max(0, min(255, $r + $add));
	$new_g = max(0, min(255, $g + $add));
	$new_b = max(0, min(255, $b + $add));
	$new_hex = dechex($new_r) . dechex($new_g) . dechex($new_b);
	return $new_hex;
}

header('Content-type: text/css');
header('Pragma: cache');
header('Cache-Control: max-age=86400, must-revalidate');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
$default_color = 'ff4c54';
$color = $default_color;
if (!empty($_GET['c'])) {
	$color = $_GET['c'];
	if (strlen($color) != 6) {
		$color = $default_color;
	}
}

$rgb = hex2rgb($color);
$rgb2 = implode(', ', $rgb);
$color2 = brighten($color, 40);
?>
a,
.author-link a:hover,
.entry-title a:hover,
.post .comments-link a:hover,
#topmenu > li:hover > a,
#topmenu li.current-menu-item > a,
#topmenu ul a:hover,
.widget .post > h3 a:hover,
.widget_g7_twitter .follow-us:hover,
.category1 .widgettitle a:hover,
.category2 .widgettitle a:hover,
.widget_g7_social li a:hover,
.widget_g7_subpages li a:hover,
.widget_g7_subpages li.current_page_item a {
	color: #<?php echo $color; ?>;
}
#mainmenu,
#mainmenu ul,
#mainmenu ul a:hover,
.category1 .widgettitle,
.category2 .widgettitle {
	border-color: #<?php echo $color; ?>;
}
#mainmenu > li.current-menu-item > a,
#mainmenu > li.current_page_item > a,
#searchbtn,
#submit,
.btn,
.post a.readmore:hover,
.post a.author:hover,
.post .category a,
.pagination a:hover,
.pagination span.current,
.widget_g7_tabs ul.tabs li a.active {
	background-color: #<?php echo $color; ?>;
}
.post .category a {
	background-color: #<?php echo $color; ?>;
	background-color: rgba(<?php echo $rgb2; ?>, 0.9);
}
.flex-caption .category a,
.flex-caption h2 a:hover {
	color: #<?php echo $color2; ?>;
}
.flex-control-paging li a.flex-active {
	background-color: #<?php echo $color2; ?>;
	background-color: rgba(<?php echo $rgb2; ?>, 0.8);
}