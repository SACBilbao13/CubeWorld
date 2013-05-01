<?php
/*
Plugin Name: Mention me
Description: Adds a pre-configured Twitter username to all messages posted to Twitter. You can set up the username in plugin settings.
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Wdcp_Twmm_AdminPages {

	private $_data;

	private function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Twmm_AdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wdcp-options-plugins_options', array($this, 'register_settings'));
		add_filter('wdcp-remote_post_data-twitter', array($this, 'process_data'));
	}

	function process_data ($data) {
		$username = $this->_data->get_option('twmm_username');
		if (!$username) return $data;

		$username = preg_match('/^@/', $username) ? $username : "@{$username}";
		$comment = $username . ' ' . $data['status'];
		$data = array(
			'status' => substr($comment, 0, 140),
		);
		return $data;
	}

	function register_settings () {
		add_settings_section('wdcp_twmm_settings', __('Mention me', 'wdcp'), create_function('', ''), 'wdcp_options');
		add_settings_field('wdcp_twmm_username', __('Twitter username', 'wdcp'), array($this, 'create_username_box'), 'wdcp_options', 'wdcp_twmm_settings');	    		 	 			 		  
	}

	function create_username_box () {
		$username = esc_attr($this->_data->get_option('twmm_username'));
		echo "@<input type='text' name='wdcp_options[twmm_username]' size='16' value='{$username}' />";
		echo '<div><small>' . __('This is the Twitter username that will be prepended to all outgoing Tweets.', 'wdcp') . '</small></div>';
	}
}

if (is_admin()) Wdcp_Twmm_AdminPages::serve();