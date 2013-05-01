<?php
/*
Plugin Name: Troubleshooter
Description: Activate this add-on to troubleshoot possible configuration issues.
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Wdcp_Debugger {

	private $_data;

	private function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Debugger;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wdcp-options-plugins_options', array($this, 'register_settings'));

		add_action('wp_ajax_wdcp_dbg_twitter_timestamp', array($this, 'json_twitter_timestamp_test'));
		add_action('wp_ajax_wdcp_dbg_google_mod_security', array($this, 'json_google_response_test'));
	}

	function register_settings () {
		add_settings_section('wdcp_dbg_debugger', __('Troubleshooter', 'wdcp'), create_function('', ''), 'wdcp_options');
		add_settings_field('wdcp_dbg_general', __('General', 'wdcp'), array($this, 'create_general_box'), 'wdcp_options', 'wdcp_dbg_debugger');
		add_settings_field('wdcp_dbg_google', __('Google', 'wdcp'), array($this, 'create_google_box'), 'wdcp_options', 'wdcp_dbg_debugger');
		add_settings_field('wdcp_dbg_twitter', __('Twitter', 'wdcp'), array($this, 'create_twitter_box'), 'wdcp_options', 'wdcp_dbg_debugger');
	}

	function create_general_box () {
		$all_good = true;
		$has_curl = function_exists('curl_init');
		if (!$has_curl) {
			echo '<div class="error below-h2"><p>' . __('The required cURL extension seems to be missing.', 'wdcp') . '</p></div>';
			$all_good = false;
		}
		$model = new Wdcp_Model;
		if (!class_exists('TwitterOAuth')) {
			echo '<div class="error below-h2"><p>' . __('There seems to be problems with the required TwitterOAuth class.', 'wdcp') . '</p></div>';	    		 	 			 		  
			$all_good = false;
		}
		if (!class_exists('Facebook')) {
			echo '<div class="error below-h2"><p>' . __('There seems to be problems with the required Facebook class.', 'wdcp') . '</p></div>';
			$all_good = false;
		}
		if (!class_exists('LightOpenID')) {
			echo '<div class="error below-h2"><p>' . __('There seems to be problems with the required LightOpenID class.', 'wdcp') . '</p></div>';
			$all_good = false;
		}
		if (!class_exists('OAuthRequest') || !method_exists('OAuthRequest', 'generate_raw_timestamp')) {
			echo '<div class="error below-h2"><p>' . __('There seems to be problems with the required OAuthRequest class.', 'wdcp') . '</p></div>';
			$all_good = false;
		}
		if ($all_good) {
			echo '<p>' . __('Basic prerequisites seem to be in order.', 'wdcp') . '</p>';
		}
	}

	function create_google_box () {
		echo '<a href="#google-request" id="wdcp-dbg-google-request">' . __('Check response handling prerequisites', 'wdcp') . '</a>';
		echo '<div id="wdcp-dbg-google-status"></div>';
		echo <<<EoGoogleJs
<script>
(function ($) {

function status (html) {
	$("#wdcp-dbg-google-status").html(html);
}

function loading () {
	status("Please, wait");
}

function done (response) {
	var msg = '';
	if (response && response.msg) {
		msg = response.msg;
	}
	status(msg);
}

$(function () {
	$("#wdcp-dbg-google-request").on("click", function () {
		loading();
		$.post(ajaxurl, {
			"action": "wdcp_dbg_google_mod_security"
		}, done, 'json');
		return false;
	});
});
})(jQuery);
</script>
EoGoogleJs;
	}

	function create_twitter_box () {
		echo '<a href="#twitter-request" id="wdcp-dbg-twitter-request">' . __('Check timestamps', 'wdcp') . '</a>';
		echo '<div id="wdcp-dbg-twitter-status"></div>';
		echo <<<EoTwitterJs
<script>
(function ($) {

function status (html) {
	$("#wdcp-dbg-twitter-status").html(html);
}

function loading () {
	status("Please, wait");
}

function done (response) {
	var msg = '';
	if (response && response.msg) {
		msg = response.msg;
	}
	status(msg);
}

$(function () {
	$("#wdcp-dbg-twitter-request").on("click", function () {
		loading();
		$.post(ajaxurl, {
			"action": "wdcp_dbg_twitter_timestamp"
		}, done, 'json');
		return false;
	});
});
})(jQuery);
</script>
EoTwitterJs;
	}

	function json_twitter_timestamp_test () {
		$test_time = OAuthRequest::generate_raw_timestamp();
		$test_url = "https://api.twitter.com/1/help/test.json";

		$request = wp_remote_get($test_url, array('sslverify' => false));
		$headers = wp_remote_retrieve_headers($request);
		if (!empty($headers['date'])) {
			$twitter_time = strtotime($headers['date']);
			$delta = $twitter_time - $test_time;
			if (abs($delta) > WDCP_TIMESTAMP_DELTA_THRESHOLD) {
				update_site_option('wdcp_twitter_timestamp_delta_fix', $delta);
				die(json_encode(array(
					'status' => 0,
					'msg' => sprintf(__('There seems to be some differences in Twitter and your notion of time, by %d sec. This should now be fixed.', 'wdcp'), $delta),
				)));
			} else {
				update_site_option('wdcp_twitter_timestamp_delta_fix', 0);
				die(json_encode(array(
					'status' => 1,
					'msg' => __('Timestamp settings seem to be within acceptable limits', 'wdcp'),
				)));
			}
		}

		die(json_encode(array(
			'status' => 1,
			'msg' => __('Could not determine Twitter time, assuming everything is OK.', 'wdcp'),
		)));
	}

	function json_google_response_test () {
		$test_url = add_query_arg('test', 'http://www.google.com', home_url());
		$request = wp_remote_get($test_url, array('sslverify' => false));
		if (200 != wp_remote_retrieve_response_code($request)) {
			die(json_encode(array(
				'status' => 0,
				'msg' => __('URLs in query strings seem not to be allowed, which will cause issues with Google OpenID authentication.', 'wdcp'),
			)));
		} else {
			die(json_encode(array(
				'status' => 1,
				'msg' => __('Response handling seems to be under control', 'wdcp'),
			)));
		}
	}
}
Wdcp_Debugger::serve();