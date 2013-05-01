<?php
/*
Plugin Name: Social Discussion
Description: Synchronizes the relevant discussion from social networks in a separate tab on your page. <br /><strong>Requires <em>Custom Comments Template</em> add-on to be activated.</strong>
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/


/**
 * Handles admin pages, settings and procedures.
 */
class Wdcp_Sd_AdminPages {

	private $_data;

	private function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Sd_AdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wdcp-options-plugins_options', array($this, 'register_settings'));

		$services = $this->_data->get_option('sd_services');
		$services = $services ? $services : array();
		if (in_array('facebook', $services)) {
			add_action('wdcp-remote_comment_posted-facebook', array($this, 'handle_facebook_comment_sent'), 10, 3);
		}
		if (in_array('twitter', $services)) {
			add_action('wdcp-remote_comment_posted-twitter', array($this, 'handle_twitter_comment_sent'), 10, 3);
		}
	}

/* ----- Comments posted handlers -----*/

	function handle_facebook_comment_sent ($comment_id, $result, $data) {
		$this->_handle_comment_sent($comment_id, 'facebook', $result['id'], $data);
	}

	function handle_twitter_comment_sent ($comment_id, $result, $data) {
		$this->_handle_comment_sent($comment_id, 'twitter', @$result->id_str, $data);
	}

	/**
	 * Adds Social Discussion root entry.
	 */
	private function _handle_comment_sent ($comment_id, $type, $remote_id, $data) {
		if (!$remote_id) return false;
		$post = array(
			'post_content' => $data['comment_content'],
			'post_type' => 'wdcp_social_discussion_root',
			'post_status' => 'publish',
		);
		$post_id = wp_insert_post($post);
		$meta = array(
			'sd_type' => $type,
			'remote_id' => $remote_id,
		);
		update_post_meta($post_id, 'wdcp_sd_meta', $meta);
		add_comment_meta($comment_id, 'wdcp_sd_root', $post_id) ;
	}

/* ----- Settings ----- */

	function register_settings () {
		add_settings_section('wdcp_sd_settings', __('Social Discussion', 'wdcp'), array($this, 'check_cct_presence'), 'wdcp_options');
		if (!class_exists('Wdcp_Cct_Admin_Pages')) return false;
		add_settings_field('wdcp_sd_services', __('Sync discussions from these services', 'wdcp'), array($this, 'create_services_box'), 'wdcp_options', 'wdcp_sd_settings');
		add_settings_field('wdcp_sd_default_service', __('Default discussion tab', 'wdcp'), array($this, 'create_default_service_box'), 'wdcp_options', 'wdcp_sd_settings');
		add_settings_field('wdcp_sd_schedule', __('Schedule', 'wdcp'), array($this, 'create_schedule_box'), 'wdcp_options', 'wdcp_sd_settings');
		add_settings_field('wdcp_sd_override_theme', __('Appearance', 'wdcp'), array($this, 'create_override_theme_box'), 'wdcp_options', 'wdcp_sd_settings');
	}

	function check_cct_presence () {
		if (class_exists('Wdcp_Cct_Admin_Pages')) return true;
		echo '<div class="error below-h2"><p>' . __('Please, activate the <b>Custom Comments Template</b> add-on.', 'wdcp') . '</p></div>';
	}

	function create_services_box () {
		$_services = array(
			'facebook' => __('Facebook', 'wdcp'),
			'twitter' => __('Twitter', 'wdcp'),
		);
		$services = $this->_data->get_option('sd_services');
		$services = $services ? $services : array();

		foreach ($_services as $service => $label) {
			$checked = in_array($service, $services) ? 'checked="checked"' : '';
			echo '' .
				"<input type='checkbox' name='wdcp_options[sd_services][]' value='{$service}' id='sd_services-{$service}' {$checked} />" .
				'&nbsp;' .
				"<label for='sd_services-{$service}'>{$label}</label>" .
			"<br />";
		}
		echo '<div><small>' . __('Please select service(s) you wish to sync social discussion with.', 'wdcp') . '</small></div>';
	}

	function create_default_service_box () {
		$_services = array(
			'facebook' => __('Facebook', 'wdcp'),
			'twitter' => __('Twitter', 'wdcp'),
			'comments' => __('WordPress Comments', 'wdcp'),
		);
		$default = $this->_data->get_option('sd_default_service');
		$default = $default ? $default : 'comments';

		foreach ($_services as $service => $label) {
			$checked = ($service == $default) ? 'checked="checked"' : '';
			echo '' .
				"<input type='radio' name='wdcp_options[sd_default_service]' value='{$service}' id='sd_default_service-{$service}' {$checked} />" .
				'&nbsp;' .
				"<label for='sd_default_service-{$service}'>{$label}</label>" .
			"<br />";
		}
		echo '<div><small>' . __('The discussion panel you select here will be open by default on page load.', 'wdcp') . '</small></div>';
	}

	function create_schedule_box () {
		$_schedules = array(
			'0' => __('Hourly', 'wdcp'),
			'10800' => __('Every 3 hours', 'wdcp'),
			'21600' => __('Every 6 hours', 'wdcp'),
			'43200' => __('Every 12 hours', 'wdcp'),
			'86400' => __('Daily', 'wdcp'),
		);
		$default = $this->_data->get_option('wdcp_sd_poll_interval');
		echo '<select name="wdcp_options[wdcp_sd_poll_interval]">';
		foreach ($_schedules as $lag => $lbl) {
			$sel = ($lag == $default) ? 'selected="selected"' : '';
			echo "<option value='{$lag}' {$sel}>{$lbl}&nbsp;</option>";
		}
		echo '</select>';
		echo '<div><small>' . __('Discussions from your selected networks will be synced this often with your other comments.', 'wdcp') . '</small></div>';

		// Limit
		$_limits = array(1, 5, 10, 15, 20, 25, 30, 40, 50);
		$limit = (int)$this->_data->get_option('sd_limit');
		$limit = $limit ? $limit : Wdcp_Sd_Importer::PROCESSING_SCOPE_LIMIT;
		echo '<label for="wdcp-sd_limit">' . __('Limit import to this many latest comments:', 'wdcp') . '</label> ';
		echo '<select name="wdcp_options[sd_limit]" id="wdcp-sd_limit">';
		foreach ($_limits as $lim) {
			$sel = ($lim == $limit) ? 'selected="selected"' : '';
			echo "<option value='{$lim}' {$sel}>{$lim}</option>";
		}
		echo '</select>';
		echo '<div><small>' . __('Discussion import takes time, so it is a good idea to limit its scope.', 'wdcp') . '</small></div>';
		echo '<div><small>' . __('This option lets you choose how many of your latest social comments will be polled for discussion updates.', 'wdcp') . '</small></div>';
	}

	function create_override_theme_box () {
		$checked = $this->_data->get_option('sd_theme_override') ? 'checked="checked"' : '';
		echo '' .
			'<input type="hidden" name="wdcp_options[sd_theme_override]" value="" />' .
			"<input type='checkbox' name='wdcp_options[sd_theme_override]' id='wdcp-sd_theme_override' value='1' {$checked} />" .
			'&nbsp' .
			'<label for="wdcp-sd_theme_override">' . __('Do not load styles - my theme already has all the styles I need', 'wdcp') . '</label>' .
			'<div><small>' . __('If you check this option, no social discussion style will be loaded.', 'wdcp') . '</small></div>' .
		'';
	}
}


/**
 * Handles public pages - appearance and requests.
 */
class Wdcp_Sd_PublicPages {

	private $_data;
	private $_db;
	private $_services;

	private function __construct () {
		global $wpdb;
		$this->_data = new Wdcp_Options;
		$this->_db = $wpdb;

		$services = $this->_data->get_option('sd_services');
		$services = $services ? $services : array();
		$services[] = 'comments';
		$this->_services = $services;
	}

	public static function serve () {
		$me = new Wdcp_Sd_PublicPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_filter('wdcp-wordpress_custom_icon_selector', array($this, 'add_custom_icon_selector'));
		add_action('wdcp-load_scripts-public', array($this, 'js_load_scripts'));
		add_action('wdcp-load_styles-public', array($this, 'css_load_styles'));
		add_action('wdcp-cct-comments_top', array($this, 'comments_top'));
		add_action('wdcp-cct-comments_bottom', array($this, 'comments_bottom'));
	}

	function add_custom_icon_selector ($selector) {
		$sd_selector = "#wdcp-sd-discussion_switcher li a#wdcp-sd-discussion-comments-switch";
		return $selector ? "{$selector}, {$sd_selector}" : $sd_selector;
	}

	function js_load_scripts () {
		$default = $this->_data->get_option('sd_default_service');
		$default = $default ? $default : 'comments';
		printf(
			'<script type="text/javascript">
				var _wdcp_sd = {
					"default_service": "%s"
				};
			</script>',
			$default
		);
		wp_enqueue_script('wdcp-sd-discussion', WDCP_PLUGIN_URL . '/js/discussion.js', array('jquery'));
	}

	function css_load_styles () {
		$override = $this->_data->get_option('sd_theme_override');
		if (!current_theme_supports('wdcp-sd-discussion') && !$override) {
			wp_enqueue_style('wdcp-sd-discussion', WDCP_PLUGIN_URL . '/css/discussion.css');
		}
	}

	function comments_top ($post_id) {
		echo '<ul id="wdcp-sd-discussion_switcher">';
		foreach ($this->_services as $service) {
			echo "<li><a href='#discussion-{$service}' data-discussion_service='{$service}' id='wdcp-sd-discussion-{$service}-switch'><span>" . ucfirst($service) . '</span></a></li>';
		}
		echo '</ul>';
		echo '<div style="clear:left"></div>';
		echo '<div id="wdcp-sd-discussion-comments" class="wdcp-sd-discussion">';
	}

	function comments_bottom ($post_id) {
		echo '</div>'; // #wdcp-sd-discussion-comments
		foreach ($this->_services as $service) {
			if ('comments' == $service) continue;
			echo "<div id='wdcp-sd-discussion-{$service}' class='wdcp-sd-discussion'>";
			$this->_get_discussion_for_service($post_id, $service);
			echo '</div>';
		}
	}

	private function _get_discussion_for_service ($post_id, $service) {
		$post_id = (int)$post_id;
		if (!$post_id) return false;
		$root_ids = $this->_db->get_col("SELECT meta_value FROM {$this->_db->comments} AS c, {$this->_db->commentmeta} AS mc WHERE mc.meta_key = 'wdcp_sd_root' AND c.comment_post_ID={$post_id} AND c.comment_ID = mc.comment_id");
		$root_ids = $root_ids ? $root_ids : array();
		switch ($service) {
			case 'facebook': return $this->_get_facebook_discussion($root_ids);
			case 'twitter': return $this->_get_twitter_discussion($root_ids);
		}
		return false;
	}

	private function _get_facebook_discussion ($post_ids) {
		foreach ($post_ids as $post_id) {
			$post = get_post($post_id);
			$meta = get_post_meta($post_id, 'wdcp_sd_meta', true);
			if ('facebook' != $meta['sd_type']) continue;
			$comments = $this->_db->get_results(
				"SELECT * FROM {$this->_db->comments} AS c, {$this->_db->commentmeta} AS mc WHERE mc.meta_key='wdcp_sd_facebook_remote_id' AND c.comment_post_ID={$post_id} AND c.comment_ID=mc.comment_id"
			);
			$this->_show_post_as_comment($post, 'facebook', $comments);
		}
	}

	private function _get_twitter_discussion ($post_ids) {
		foreach ($post_ids as $post_id) {
			$post = get_post($post_id);
			$meta = get_post_meta($post_id, 'wdcp_sd_meta', true);
			if ('twitter' != $meta['sd_type']) continue;
			$comments = $this->_db->get_results(
				"SELECT * FROM {$this->_db->comments} AS c, {$this->_db->commentmeta} AS mc WHERE mc.meta_key='wdcp_sd_twitter_remote_id' AND c.comment_post_ID={$post_id} AND c.comment_ID=mc.comment_id"
			);
			$this->_show_post_as_comment($post, 'twitter', $comments);
		}
	}

	private function _show_post_as_comment ($post, $type, $comments) {
		$post_id = (int)$post->ID;
		$comment = $this->_db->get_row("SELECT * FROM {$this->_db->comments} AS c, {$this->_db->commentmeta} AS mc WHERE mc.meta_key='wdcp_sd_root' AND mc.meta_value={$post_id} AND c.comment_ID=mc.comment_id");
		$meta = get_comment_meta($comment->comment_ID, 'wdcp_comment', true);
		if ('facebook' == $type) {
			$uid = $meta['wdcp_fb_author_id'];
			$avatar = "<img class='avatar avatar-40 photo' width='40' height='40' src='http://graph.facebook.com/{$uid}/picture' />";
		} else if ('twitter' == $type) {
			$url = $meta['wdcp_tw_avatar'];
			$avatar = "<img class='avatar avatar-40 photo' width='40' height='40' src='{$url}' />";
		}
		echo '<ul><li>';
		include WDCP_PLUGIN_BASE_DIR . '/lib/forms/wdcp-social_discussion_root_comment.php';

		echo '<ul>';
		if ('facebook' == $type) {
			foreach ($comments as $comment) {
				$uid = get_comment_meta($comment->comment_ID, 'wdcp_fb_author_id', true);
				$avatar = "<img class='avatar avatar-40 photo' width='40' height='40' src='http://graph.facebook.com/{$uid}/picture' />";
				include WDCP_PLUGIN_BASE_DIR . '/lib/forms/wdcp-social_discussion_comment.php';
			}
		} else if ('twitter' == $type) {
			foreach ($comments as $comment) {
				$url = esc_url(get_comment_meta($comment->comment_ID, 'wdcp_tw_avatar', true));
				$avatar = "<img class='avatar avatar-40 photo' width='40' height='40' src='{$url}' />";
				include WDCP_PLUGIN_BASE_DIR . '/lib/forms/wdcp-social_discussion_comment.php';
			}
		}
		echo '</ul>';

		echo '</li></ul>';
	}

}

/**
 * Handles comments import from supported social networks.
 */
class Wdcp_Sd_Importer {

	const PROCESSING_SCOPE_LIMIT = 10;

	private $_data;
	private $_db;
	private $_services;

	private function __construct () {
		global $wpdb;
		$this->_data = new Wdcp_Options;
		$this->_db = $wpdb;
	}

	public static function serve () {
		$me = new Wdcp_Sd_Importer;
		add_action('wdcp-sd_import_comments', array($me, 'import'));
		if (!wp_next_scheduled('wdcp-sd_import_comments')) wp_schedule_event(time()+600, 'hourly', 'wdcp-sd_import_comments');
		return $me;
	}

	public function import () {
		$services = $this->_data->get_option('sd_services');
		$services = $services ? $services : array();
		$this->_services = $services;
		$limit = (int)$this->_data->get_option('sd_limit');
		$limit = $limit ? $limit : self::PROCESSING_SCOPE_LIMIT;

		$post_ids = $this->_db->get_col("SELECT DISTINCT meta_value FROM {$this->_db->comments} AS c, {$this->_db->commentmeta} AS mc WHERE mc.meta_key = 'wdcp_sd_root' AND c.comment_ID = mc.comment_id ORDER BY c.comment_date LIMIT {$limit}");	    		 	 			 		  

		foreach ($post_ids as $post_id) {
			$this->_process_discussion($post_id);
		}
	}

	private function _process_discussion ($post_id) {
		$post_id = (int)$post_id;
		if (!$post_id) return false;

		$now = time();

		$meta = get_post_meta($post_id, 'wdcp_sd_meta', true);
		if (!isset($meta['sd_type']) || !in_array($meta['sd_type'], $this->_services)) return false; // Don't sync this

		$last_polled = (int)get_post_meta($post_id, 'wdcp_sd_last_polled', true);
		if ($last_polled + (int)$this->_data->get_option('wdcp_sd_poll_interval') > $now) return false; // No need to poll this item

		$this->_fetch_discussion($post_id, $meta['remote_id'], $meta['sd_type']);
		update_post_meta($post_id, 'wdcp_sd_last_polled', $now);
	}

	private function _fetch_discussion ($post_id, $remote_id, $type) {
		switch ($type) {
			case "facebook": return $this->_fetch_facebook_discussion($post_id, $remote_id);
			case "twitter": return $this->_fetch_twitter_discussion($post_id, $remote_id);
		}
		return false;
	}

	private function _fetch_facebook_discussion ($post_id, $item_id) {
		if (!$item_id) return false;
		$token = WDCP_APP_ID . '|' . WDCP_APP_SECRET;
		$res = wp_remote_get("https://graph.facebook.com/{$item_id}/comments?access_token={$token}", array(
			'method' 		=> 'GET',
			'timeout' 		=> '5',
			'redirection' 	=> '5',
			'user-agent' 	=> 'wdcp-sd',
			'blocking'		=> true,
			'compress'		=> false,
			'decompress'	=> true,
			'sslverify'		=> false
		));

		if (is_wp_error($res)) return false; // Request fail
		if ((int)$res['response']['code'] != 200) return false; // Request fail

		$body = @json_decode($res['body']);
		if (empty($body->data)) return false; // No data found

		foreach ($body->data as $item) {
			if ($this->_comment_already_imported($item->id, 'facebook')) continue; // We already have this comment, continue.
			$data = array(
				'comment_post_ID' => $post_id,
				'comment_author' => $item->from->name,
				'comment_author_url' => 'http://www.facebook.com/profile.php?id=' . $item->from->id,
				'comment_content' => $item->message,
				'comment_type' => 'wdcp_sd_imported',
				'comment_date' => date('Y-m-d H:i:s', strtotime($item->created_time)),
			);

			$meta = array (
				'wdcp_fb_author_id' => $item->from->id,
				'wdcp_sd_facebook_remote_id' => $item->id,
			);

			$comment_id = wp_insert_comment($data);
			if (!$comment_id) continue;

			foreach ($meta as $mkey => $mval) add_comment_meta($comment_id, $mkey, $mval);
		}
	}

	private function _fetch_twitter_discussion ($post_id, $item_id) {
		if (!$item_id) return false;
		$res = wp_remote_get(
			"http://api.twitter.com/1/statuses/show.json?id={$item_id}", array(
				'method' 		=> 'GET',
				'timeout' 		=> '5',
				'redirection' 	=> '5',
				'user-agent' 	=> 'wdcp-sd',
				'blocking'		=> true,
				'compress'		=> false,
				'decompress'	=> true,
				'sslverify'		=> false
			)
		);
		if (is_wp_error($res)) return false; // Request fail
		if ((int)$res['response']['code'] != 200) return false; // Request fail

		$tweet = @json_decode($res['body']);
		$user = $tweet->user->name;

		$res = wp_remote_get(
			"http://search.twitter.com/search.json?q=to:{$user}", array(
				'method' 		=> 'GET',
				'timeout' 		=> '5',
				'redirection' 	=> '5',
				'user-agent' 	=> 'wdcp-sd',
				'blocking'		=> true,
				'compress'		=> false,
				'decompress'	=> true,
				'sslverify'		=> false
			)
		);

		if (is_wp_error($res)) return false; // Request fail
		if ((int)$res['response']['code'] != 200) return false; // Request fail

		$body = @json_decode($res['body']);
		$results = @$body->results ? array_reverse($body->results) : array();

		foreach ($results as $item) {
			if ($this->_comment_already_imported($item->id_str, 'twitter')) continue; // We already have this comment, continue.
			$data = array(
				'comment_post_ID' => $post_id,
				'comment_author' => $item->from_user,
				'comment_author_url' => 'http://www.twitter.com/' . $item->from_user,
				'comment_content' => $item->text,
				'comment_type' => 'wdcp_sd_imported',
				'comment_date' => date('Y-m-d H:i:s', strtotime($item->created_at)),
			);

			$meta = array (
				'wdcp_tw_avatar' => $item->profile_image_url,
				'wdcp_sd_twitter_remote_id' => $item->id_str,
			);
			$comment_id = wp_insert_comment($data);
			if (!$comment_id) continue;

			foreach ($meta as $mkey => $mval) add_comment_meta($comment_id, $mkey, $mval);
		}

	}

	private function _comment_already_imported ($remote_id, $type) {
		$id_str = "wdcp_sd_{$type}_remote_id";
		$remote_id = esc_sql($remote_id);
		return $this->_db->get_var("SELECT comment_id FROM {$this->_db->commentmeta} WHERE meta_key='{$id_str}' AND meta_value='{$remote_id}'");
	}
}

Wdcp_Sd_Importer::serve();

if (is_admin()) Wdcp_Sd_AdminPages::serve();
else Wdcp_Sd_PublicPages::serve();