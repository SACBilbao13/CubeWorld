<?php
/*    Copyright 2012  Guillaume Assire aka AlphaGolf  (alphagolf@rocketmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !function_exists('get_roles_by_cap') ) {
function get_roles_by_cap ( $capability ) {
	global $wp_roles;

	$roles_with_cap = array();
	
	// Loop through each role object because we need to get the caps.
	foreach ( $wp_roles->role_objects as $key => $role ) {

		// Loop through Roles capabilities and compare with input string
		if ( is_array( $role->capabilities ) ) {
			foreach ( $role->capabilities as $cap => $true ) {
				if ( $capability == $cap ) { $roles_with_cap[] = $role->name; }
			}
		}
	}
	return implode (",", $roles_with_cap);
}
}

if ( !function_exists('symposium_get_user_roles') ) {
function symposium_get_user_roles( $userid ) {

	if ($userid && is_user_logged_in()) {
		$user_data = get_userdata( $userid ); 	   
		return $user_data->roles;
	} else {
		return "";
	}
}
}

if ( !function_exists('symposium_get_user_role') ) {
function symposium_get_user_role( $userid ) {
	
	$user_data = get_userdata( $userid );
	$user_roles = $user_data->roles;
	$user_role = strtolower(array_shift($user_roles));
	
	return $user_role;
}
}

function symposium_blogpost_add_comment($comment_ID) {
	
	global $wpdb;
	
	(array)$comment = get_comment($comment_ID);
	(array)$post = get_post($comment->comment_post_ID);
	(array)$user = get_user_by('email', $comment->comment_author_email);
	
	// Get user ID for comment author
	$user_ID = $comment->user_id;
	if ( ( $comment->user_id == 0 ) && ( get_option('symposium_blogpost_anonymous_comment_to_activity') != '' ) ) { $user_ID = $user->ID; }
	
	if ( $user_ID > 0 ) {
		
		// Insert the comment into WPS Activity
		$sql = "INSERT INTO ".$wpdb->base_prefix."symposium_comments ( subject_uid, author_uid, comment_parent, comment_timestamp, comment, is_group, type ) VALUES ( %d, %d, %d, %s, %s, %s, %s )";
		$url = __("Commented on", 'wp-symposium-blogpost').' <a href="'.get_permalink($post->ID)."#comment-".$comment_ID.'">'.$post->post_title."</a>";
		$cid = $wpdb->query( $wpdb->prepare( $sql, $user_ID, $user_ID, 0, $comment->comment_date_gmt, $url, '', $post->post_type.'comment' ) );
	}
}

function symposium_blogpost_remove_comment($comment_ID) {
	
	global $wpdb;
	
	(array)$comment = get_comment($comment_ID);
	(array)$user = get_user_by('email', $comment->comment_author_email);
	
	// Get user ID for comment author
	$user_ID = $comment->user_id;
	if ( ( $comment->user_id == 0 ) && ( get_option('symposium_blogpost_anonymous_comment_to_activity') != '' ) ) { $user_ID = $user->ID; }
	
	// Delete the comment from WPS Activity
	$comments = $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."symposium_comments WHERE subject_uid = '".$user_ID."' AND comment_timestamp = '".$comment->comment_date_gmt."'"); 
	foreach ($comments as $comment) {
		$sql = "DELETE FROM ".$wpdb->base_prefix."symposium_comments WHERE cid = %d";
		$rows_affected = $wpdb->query( $wpdb->prepare( $sql, $comment->cid) );
		
		// Delete any replies
		if ($rows_affected > 0) {
			$sql = "DELETE FROM ".$wpdb->base_prefix."symposium_comments WHERE comment_parent = '".$comment->cid."'";
			$rows_affected = $wpdb->query( $sql );
		}
	}
}

function blogpost_get_more_posts($user_id, $length, $offset) {

	global $wpdb;
	$html = '';
	
	// Standard WP install...
	$query = "SELECT ID,post_title,post_date FROM ".$wpdb->base_prefix."posts";
	$query .= " WHERE post_type = 'post' AND post_status = 'publish' AND post_author = '".$user_id."'";
	$query = apply_filters ( 'symposium_blogpost_query_hook', $query);
	$posts = $wpdb->get_results( $query, ARRAY_A );
	
	// WPMS install...
	if ( is_multisite() ) {
		(array)$sites = explode (',', get_option('symposium_blogpost_list_sites', '') );
		$user_blogs = get_blogs_of_user( $user_id );
		
		if (WPS_DEBUG) echo "sites: ".get_option('symposium_blogpost_list_sites', '');
		
		if ( in_array("1", $sites) ) {
			if (WPS_DEBUG) echo "<br />query site 1: ".$query."<br />count(posts): ".count($posts);
			foreach ($posts as $index => $blog_post) { $posts[$index]['blog_id'] = "1"; }
		} else {
			$posts = array();
		}
		
		foreach ($user_blogs as $blog) {
			if ( ( $blog->userblog_id != 1 ) && ( in_array($blog->userblog_id, $sites) ) ) {
			
				$query = "SELECT ID,post_title,post_date FROM ".$wpdb->base_prefix.$blog->userblog_id."_posts";
				$query .= " WHERE post_type = 'post' AND post_status = 'publish' AND post_author = '".$user_id."'";
				$query = apply_filters ( 'symposium_blogpost_query_hook', $query);
				$more_posts = $wpdb->get_results( $query, ARRAY_A );
				
				if (WPS_DEBUG) echo "<br />query site ".$blog->userblog_id.": ".$query."<br />count(posts): ".count($more_posts);
				
				foreach ($more_posts as $index => $more_post) { $more_posts[$index]['blog_id'] = $blog->userblog_id; }
				$posts = array_merge($posts, $more_posts);
			}
		}
	} else {
		if (WPS_DEBUG) echo "query: ".$query;
	}
	
	if ( $posts && $length ) {
		
		// Sort, count, limit & offset...
		$posts = __wps__sub_val_sort($posts, 'post_date', false);
		$count = count($posts);
		$posts = array_chunk ($posts, $length);
		$posts = $posts[ (int)round($offset/$length) ];
		$even = (($offset/2) == round($offset/2));
		
		if (WPS_DEBUG) echo "<br />".$count." posts to display, in batches of ".$length.", starting at ".$offset;

		// Display...
		foreach ($posts as $post) {
			
			if ( $even ) { $html .= '<div class="row'; } else { $html .= '<div class="row_odd'; }
			$html .= ' symposium_blogpost_row';
			if ( is_multisite() ) { $html.= ' symposium_blogpost_blog_'.$post['blog_id']; }
			$html .= '" style="display: block;"';
			$html .= '>';
			
			$row_content = '<div class="symposium_blogpost_info">';
			$row_content .= '<a href="'.get_permalink($post['ID']).'">';
			if ( $post['post_title'] ) { $row_content .= $post['post_title']; } else { $row_content .= __("(no title)"); }
			$row_content .= '</a>';
			
			$row_content .= '<div class="symposium_blogpost_date_time">';
			$row_content .= mysql2date(get_option('date_format').' '.get_option('time_format'), $post['post_date']);
			$row_content .= '</div></div>';
			
			// This filter allows admins to add anything, or completely replace the content of each row
			$html .= apply_filters ( 'symposium_blogpost_row_hook', $row_content, $post['ID'] );
			
			$html .= '</div>';
			$even = !$even;
		}
		
		if ($count > ($offset + $length) ) {
			$html .= "<div><a href='javascript:void(0)' id='showmore_blogpost'>".__("more...", "wp-symposium")."</a></div>";
		}
		
	} else {
		if (WPS_DEBUG) echo "<br />count(posts): ".count($posts)."<br />nr_of_posts: ".$nr_of_posts;
		
		$html .= __("Nothing to show, sorry.", "wp-symposium");
	}
	
	return $html;
}
?>
