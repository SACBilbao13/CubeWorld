<?php
/*
Plugin Name: Link to Twitter profile
Description: Enabling this addon will force the plugn to always use Twitter profile URLs as websites for your Twitter commenters, regardles of their Twitter profile settings.
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Wdcp_Twltp_AdminPages {

	private $_model;

	private function __construct () {
		$this->_model = new Wdcp_Model;
	}

	public static function serve () {
		$me = new Wdcp_Twltp_AdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_filter('wdcp-comment_data-twitter', array($this, 'process_data'));
	}

	function process_data ($data) {
		$twitter_username = $this->_model->current_user_username('twitter');
		$data['comment_author_url'] = "https://twitter.com/#!/{$twitter_username}";	    		 	 			 		  
		return $data;
	}

}

if (is_admin()) Wdcp_Twltp_AdminPages::serve();