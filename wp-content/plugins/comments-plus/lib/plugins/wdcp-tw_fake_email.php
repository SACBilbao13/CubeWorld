<?php
/*
Plugin Name: Fake Twitter Email
Description: Twitter doesn't allow access to users' emails, which may cause comment approval issues with WordPress. This add-on will associate unique fake email addresses with Twitter commenters to help with this issue.
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Wdcp_Fte_AdminPages {

	private function __construct () {}

	public static function serve () {
		$me = new Wdcp_Fte_AdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_filter('wdcp-comment_data-twitter', array($this, 'process_data'));
	}

	function process_data ($data) {
		$uid = @$data['comment_author'];
		if (!$uid) return $data;

		$uid = preg_replace('/[^-_a-zA-Z0-9]/', '', $uid);
		$domain = preg_replace('/www\./', '', parse_url(site_url(), PHP_URL_HOST));	    		 	 			 		  
		@$data['comment_author_email'] = "{$uid}@twitter.{$domain}";
		return $data;
	}

}

if (is_admin()) Wdcp_Fte_AdminPages::serve();