<?php
global $layout;
if (empty($layout)) {
	$layout = g7_page_layout();
}
switch ($layout) {
	case 1:
		echo '<div class="two-thirds column"><div id="main">';
		break;
	case 2:
		echo '<div class="one-third column">';
		get_sidebar();
		echo '</div><div class="two-thirds column"><div id="main">';
		break;
	case 3:
	default:
		echo '<div class="sixteen columns"><div id="main">';
		break;
}