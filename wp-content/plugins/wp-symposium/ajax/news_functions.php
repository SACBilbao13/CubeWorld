<?php

include_once('../../../../wp-config.php');

// Delete all news alerts
if ($_POST['action'] == 'delete_all_news') {
	global $wpdb,$current_user;
	if (is_user_logged_in()) {
		$sql = "DELETE FROM ".$wpdb->base_prefix."symposium_news WHERE subject = %d";
		$wpdb->query($wpdb->prepare($sql, $current_user->ID));
	}
}


// Get news content
if ($_POST['action'] == 'get_news') {

	global $wpdb,$current_user;
	$html = '';

	$max_items = 50;

	if (is_user_logged_in()) {

		// Get new news items
		$sql = "SELECT nid, news, added, new_item
			FROM ".$wpdb->base_prefix."symposium_news 
			WHERE subject = %d 
			ORDER BY new_item desc, nid DESC LIMIT 0,%d";

		$items = $wpdb->get_results($wpdb->prepare($sql, $current_user->ID, $max_items));

		// Prepare to return comments in JSON format
		$return_arr = array();
	
		// Loop through comments, adding to array if any exist
		if ($items) {
			foreach ($items as $item) {

	
				$row_array['nid'] = $item->nid;
				$row_array['news'] = stripslashes($item->news);
				$row_array['added'] = __wps__time_ago($item->added);
				$row_array['new_item'] = $item->new_item;
				array_push($return_arr, $row_array);
			}	
			$row_array['nid'] = 0;
			$sql = "SELECT ID FROM ".$wpdb->prefix."posts WHERE lower(post_content) LIKE '%[symposium-alerts]%' AND post_type = 'page' AND post_status = 'publish';";
			$pages = $wpdb->get_results($sql);	
			if ($pages) {
				$url = get_permalink($pages[0]->ID);
				$row_array['news'] = $url;
			}
			array_push($return_arr, $row_array);
		} 

	
		echo json_encode($return_arr);
		exit;

	} else {

		echo '[]';
		exit;
	}
}

if ($_POST['action'] == 'clear_read_news') {

	global $wpdb,$current_user;

	if (is_user_logged_in()) {

		// Clear read news items
		$wpdb->query("UPDATE ".$wpdb->base_prefix."symposium_news SET new_item = '' WHERE subject = ".$current_user->ID);

	}

	exit;
}	
	
?>

	
