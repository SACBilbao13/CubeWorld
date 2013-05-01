<?php

include_once('../../../../wp-config.php');
include_once('../wp-symposium-blogpost_functions.php');


// Start blogpost content
if ($_POST['action'] == 'wp-symposium-blogpost') {
	
	global $wpdb;
	
	$html = '<div class="__wps__wrapper">';

		// This filter allows others to add text (or whatever) above the output
		$html = apply_filters ( 'symposium_blogpost_filter_top', $html);
		
		$html .= '<div id="symposium_blogpost">';
		
		// Stores values for more
		$nr_of_posts = get_option('symposium_blogpost_nr_of_posts', '10');
		$start = '0';
		$html .= '<div id="symposium_blogpost_start" style="display:none">'.$start.'</div>';
		$html .= '<div id="symposium_blogpost_page_length" style="display:none">'.$nr_of_posts.'</div>';
		
		$html .= blogpost_get_more_posts($_POST['uid1'], $nr_of_posts, $start);
		
		$html .= '</div>'; // symposium_blogpost
		
	$html .= '</div>'; // __wps__wrapper
	
	echo $html;
	exit;	
}

// Get more posts
if ($_POST['action'] == 'getPosts') {
	
	$html .= blogpost_get_more_posts($_POST['uid1'], $_POST['page_length'], $_POST['start']);
	
	echo $html;
}

?>
