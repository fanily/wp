<?php
global $layout;
switch ($layout) {
	case 1:
		echo '</div></div><div class="one-third column">';
		get_sidebar();
		echo '</div>';
		break;
	case 2:
	case 3:
	default:
		echo '</div></div>';
		break;
}