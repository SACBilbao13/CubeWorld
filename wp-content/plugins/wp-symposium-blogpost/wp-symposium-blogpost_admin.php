<?php
/*    Copyright 2012  Guillaume Assire aka AlphaGolf (alphagolf@rocketmail.com)

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

  	if (!current_user_can('manage_options'))  {
    	wp_die( __('You do not have sufficient permissions to access this page.') );
  	}
	
	global $wpdb, $current_user, $wp_roles;
	
 	// Get the list of post types: 'post', 'page' and CPTs
	$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'names' );
	array_unshift($post_types, 'post', 'page');
	
	// See if the admin has saved settings, update them
	if ( isset($_POST["symposium_update"]) && $_POST["symposium_update"] == 'symposium_blogpost_menu' ) {
		
		$level = ( is_array($_POST["level"]) ) ? implode(",", $_POST["level"]) : "";
		update_option('symposium_blogpost_show_post', $level);
		
		update_option('symposium_blogpost_menu_item', array(
			'own_profile' => isset($_POST["own_profile"]) ? $_POST["own_profile"] : '', 
			'own_profile_text' => isset($_POST["own_profile_text"]) ? $_POST["own_profile_text"] : '', 
			'others_profile' => isset($_POST["others_profile"]) ? $_POST["others_profile"] : '', 
			'others_profile_text' => isset($_POST["others_profile_text"]) ? $_POST["others_profile_text"] : ''
		) );
		
		if ( is_multisite() && isset($_POST["list_sites"]) ) {
			$list_sites = ( is_array($_POST["list_sites"]) ) ? implode(",", $_POST["list_sites"]) : "";
			update_option('symposium_blogpost_list_sites', $list_sites);
		}
		
		if ( isset($_POST["nr_of_posts"]) && ($_POST["nr_of_posts"] != '') && is_numeric($_POST["nr_of_posts"]) ) {
			$nr_of_posts = intval ($_POST['nr_of_posts'] );
			if ( $nr_of_posts > 0 ) { update_option('symposium_blogpost_nr_of_posts', $nr_of_posts ); }
		}
		
		update_option('symposium_blogpost_rewrite_author_link', isset($_POST["rewrite_author_link"]) ? $_POST["rewrite_author_link"] : '');
		update_option('symposium_blogpost_rewrite_commenter_link', isset($_POST["rewrite_commenter_link"]) ? $_POST["rewrite_commenter_link"] : '');
		update_option('symposium_blogpost_profile_page', isset($_POST["profile_page"]) ? $_POST["profile_page"] : '');
		update_option('symposium_blogpost_add_post_to_activity', isset($_POST["add_post_to_activity"]) ? $_POST["add_post_to_activity"] : '');
		foreach ($post_types as $post_type ) {
			update_option('symposium_blogpost_add_'.$post_type.'_comment_to_activity', isset($_POST["add_".$post_type."_comment_to_activity"]) ? $_POST["add_".$post_type."_comment_to_activity"] : '');
		}
		update_option('symposium_blogpost_anonymous_comment_to_activity', isset($_POST["anonymous_comment_to_activity"]) ? $_POST["anonymous_comment_to_activity"] : '');
		
	}
	
	// Get data to show
	$all_roles = $wp_roles->roles;
	(array)$show_posts = explode( ",", get_option('symposium_blogpost_show_post', '') );
	(array)$menu_item = get_option('symposium_blogpost_menu_item', array('own_profile' => 'on', 'own_profile_text' => __('My Blog Posts', 'wp-symposium-blogpost'), 'others_profile' => 'on', 'others_profile_text' => __('Blog Posts', 'wp-symposium-blogpost')));
	
	echo '<div class="wrap">';
  	
	  	echo '<div id="icon-themes" class="icon32"><br /></div>';
	  	echo '<h2>'.__('Blog Posts', 'wp-symposium-blogpost').'</h2>';
		
		?>
		<div class="metabox-holder"><div id="toc" class="postbox">
			
			<form method="post" action=""> 
				<input type="hidden" name="symposium_update" value="symposium_blogpost_menu">
				
				<table class="form-table"> 
				
				<tr valign="top">
					<td scope="row"><label for="level_to_add"><?php echo __('Add Blog Posts to Profile menu', 'wp-symposium-blogpost'); ?></label></td>
					<td>
						<span class="description"><?php echo __('Choose roles for which a menu entry will be added to the WP Symposium Profile Page of a member, listing all blog posts published by this member', 'wp-symposium-blogpost') . '...<br />'; ?></span>

<?php					foreach ($all_roles as $key => $role) {
							echo '<input type="checkbox" class="wps_blogpost_'.$key.'" name="level[]" value="'.$key.'"';
							if ( in_array( strtolower($key), $show_posts ) ) { echo ' CHECKED'; }
							echo '><span class="description"> '.$role['name'].'</span><br />'; /* */
						} ?>
				
					</td> 
				</tr>
				
				<td scope="row">&nbsp;</td>
				<td>
				<?php if (get_option(WPS_OPTIONS_PREFIX.'_profile_menu_type')) { ?>
					<span class="description"><?php echo __('You have activated the horizontal menu for the WP Symposium Profile page. The Blog Posts menu items will be displayed only for the roles checked above, so make sure your menu remains consistent for roles that are not checked.', 'wp-symposium-blogpost') . '<br />'; ?></span>
<?php				if ( $menu_item['own_profile'] == "on" ) { echo '<input type="hidden" name="own_profile" id="own_profile" value="on" />'; }
					echo '<input type="hidden" name="own_profile_text" id="own_profile_text"  value="'.$menu_item['own_profile_text'].'" />';
					if ( $menu_item['others_profile'] == "on" ) { echo '<input type="hidden" name="others_profile" id="others_profile" value="on" />'; }
					echo '<input type="hidden" name="others_profile_text" id="others_profile_text"  value="'.$menu_item['others_profile_text'].'" />'; ?>
				<?php  } else { ?>
					<span class="description"><?php echo __('Which items should be added to the vertical menu, at the WP Symposium Profile page ?', 'wp-symposium-blogpost') . '...<br />'; ?></span>
					<table>
						<tr style="font-weight:bold;">
<?php						echo '<td>'.__('Menu Item', 'wp-symposium').'</td>';
							echo '<td>'.__('Own Page', 'wp-symposium').'</td>';
							echo '<td>'.__('Own Page Text', 'wp-symposium').'</td>';
							echo '<td>'.__('Other Members', 'wp-symposium').'</td>';
							echo '<td>'.__('Other Members Text', 'wp-symposium').'</td>'; ?>
						</tr>
						<tr>
<?php						echo '<td><span class="description">'.__('Blog Posts', 'wp-symposium-blogpost').'</span></td>';
							echo '<td align="center"><input type="checkbox" name="own_profile" id="own_profile"';
							if ( $menu_item['own_profile'] == "on" ) { echo " CHECKED"; }
							echo '/></td>';
							echo '<td><input name="own_profile_text" type="text" id="own_profile_text"  value="'.$menu_item['own_profile_text'].'" /></td>';
							echo '<td align="center"><input type="checkbox" name="others_profile" id="others_profile"';
							if ( $menu_item['others_profile'] == "on" ) { echo " CHECKED"; }
							echo '/></td>';
							echo '<td><input name="others_profile_text" type="text" id="others_profile_text"  value="'.$menu_item['others_profile_text'].'" /></td>'; ?>
						</tr>
					</table>
				<?php } ?>
				</td>
				
				
				<?php if ( is_multisite() ) {
				// Get data to show
				(array)$blog_list = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."blogs ORDER BY blog_id", ARRAY_A );
				(array)$sites = explode (',', get_option('symposium_blogpost_list_sites', '') ); ?>
				
				<tr valign="top"> 
					<td scope="row">&nbsp;</td>
					<td>
						<span class="description"><?php echo __('Choose blogs of the network for which posts will be displayed on this site, at the WP Symposium Profile Page of these members', 'wp-symposium-blogpost') . '...<br />'; ?></span>
						
<?php					foreach ($blog_list as $blog) {
							$blog_details = get_blog_details($blog['blog_id']);
							echo '<input type="checkbox" name="list_sites['.$blog['blog_id'].']" value="'.$blog['blog_id'].'"';
							if ( in_array( $blog['blog_id'], $sites ) ) { echo ' CHECKED'; }
							echo '/>';
							echo '<span class="description"> '.$blog_details->blogname.' '.__('at', 'wp-symposium-blogpost').' '.$blog['domain'].$blog['path'].'</span><br />';
						} ?>
					</td> 
				</tr>
				<?php } ?>
				
				<tr valign="top"> 
					<td scope="row">&nbsp;</td>
					<td>
						<input type="text" name="nr_of_posts" id="nr_of_posts" value="<?php echo get_option('symposium_blogpost_nr_of_posts', '10'); ?>" />
						<span class="description"><?php echo __('Number of posts to show on a member\'s profile page, when first called, and then each time \'more\' is pressed', 'wp-symposium-blogpost'); ?></span>
					</td>
				</tr>
				
				<tr valign="top"> 
					<td scope="row"><label><?php echo __('Add Profile menu to Blog Posts', 'wp-symposium-blogpost'); ?></label></td>
					<td>
						<input type="checkbox" name="rewrite_author_link" id="rewrite_author_link" <?php if (get_option('symposium_blogpost_rewrite_author_link', 'on') == "on") { echo "CHECKED"; } ?>/>
						<span class="description"><?php echo __('Rewrite the authors URL in their posts meta, to link to their WPS profile page? NB: your theme must use get_author_posts_url() for this feature to work', 'wp-symposium-blogpost'); ?></span>
					</td> 
				</tr>
				
				<tr valign="top"> 
					<td scope="row">&nbsp;</td>
					<td>
						<select name="profile_page"><?php $profile_page = get_option('symposium_blogpost_profile_page', 'default'); ?>
							<option value='default' SELECTED><?php echo __('WPS default Profile view', 'wp-symposium-blogpost'); ?></option>
							<option value='blogpost'<?php if ( $profile_page == 'blogpost' ) { echo ' SELECTED'; } ?>><?php echo __('Blog Posts', 'wp-symposium-blogpost'); ?></option>
						</select><span class="description"><?php echo __('If checked above, which WPS Profile page should be linked to blog posts meta? NB: WPS default Profile page is defined in', 'wp-symposium-blogpost') . ' Symposium > ' . __('Profile', 'wp-symposium') . ' > ' . __('Default profile view', 'wp-symposium'); ?></span>
					</td>
				</tr>
				
				<tr valign="top"> 
					<td scope="row">&nbsp;</td>
					<td>
						<input type="checkbox" name="rewrite_commenter_link" id="rewrite_commenter_link" <?php if (get_option('symposium_blogpost_rewrite_commenter_link', 'off') == "on") { echo "CHECKED"; } ?>/>
						<span class="description"><?php echo __('Add / rewrite the URL for comments authors, to link to their WPS profile page? NB: your theme must use comments_template() for this feature to work.', 'wp-symposium-blogpost') . ' '. __('Moreover this is based on email address, make sure your comment form is password-protected, or activate only with comments moderation.', 'wp-symposium-blogpost'); ?></span>
					</td> 
				</tr>
				
				<tr valign="top"> 
					<td scope="row"><label><?php echo __('Add Posts to Activity', 'wp-symposium-blogpost'); ?></label></td>
					<td>
						<input type="checkbox" name="add_post_to_activity" id="add_post_to_activity" <?php if (get_option('symposium_blogpost_add_post_to_activity', 'on') == "on") { echo "CHECKED"; } ?>/>
						<span class="description"><?php echo __('Add one row to the Symposium Activity of an author each time he/she publishes a new post in the blog?', 'wp-symposium-blogpost'); ?></span>
					</td> 
				</tr>
				
				<tr valign="top"> 
					<td scope="row"><label><?php echo __('Add Comments to Activity', 'wp-symposium-blogpost'); ?></label></td>
					<td>
						<span class="description"><?php echo __('Add one row to the Symposium Activity of a member each time he/she publishes a new comment to a:', 'wp-symposium-blogpost'); ?></span>
						<br />
<?php						foreach ($post_types as $post_type ) {
							echo '<input type="checkbox" name="add_'.$post_type.'_comment_to_activity" id="add_'.$post_type.'_comment_to_activity" ';
							if (get_option('symposium_blogpost_add_'.$post_type.'_comment_to_activity', 'on') == "on") { echo "CHECKED"; }
							echo '/><span class="description"> '.$post_type.'</span><br />';
						} ?>
					</td> 
				</tr>
				
				<tr valign="top"> 
					<td scope="row">&nbsp;</td>
					<td>
						<input type="checkbox" name="anonymous_comment_to_activity" id="anonymous_comment_to_activity" <?php if (get_option('symposium_blogpost_anonymous_comment_to_activity', 'on') == "on") { echo "CHECKED"; } ?>/>
						<span class="description"><?php echo __('Add comments to members Activity, even when posting as visitors i.e. not logged in, based on email address? NB: make sure your comments are password-protected, or activate only with comments moderation.', 'wp-symposium-blogpost'); ?></span>
					</td> 
				</tr>
				
				</table> 	
			 
				<p class="submit" style='margin-left:6px;'> 
				<input type="submit" name="Submit" class="button-primary" value="<?php echo __('Save Changes', 'wp-symposium'); ?>" /> 
				</p> 
			</form> 

		</div></div>	
		
		<?php

	echo '</div>';
	
//}
?>
