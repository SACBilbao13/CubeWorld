<?php
/*
Plugin Name: Alternative Facebook Initialization
Description: Activate this add-on to solve some of the Facebook javascript initialization conflicts with other plugins.
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Wdcp_Afi_PublicPages {

	private function __construct () {}

	public static function serve () {
		$me = new Wdcp_Afi_PublicPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_filter('wdcp-service_initialization-facebook', array($this, 'handle_initialization'));	    		 	 			 		  
	}

	function handle_initialization () {
		return "<script>
		window.fbAsyncInit = function() {
			FB.init({
				appId: '" . WDCP_APP_ID . "',
				status: true,
				cookie: true,
				xfbml: true,
				oauth: true
			});
		};
		if (typeof FB != 'undefined') FB.init({
			appId: '" . WDCP_APP_ID . "',
			status: true,
			cookie: true,
			xfbml: true,
			oauth: true
		});
		</script>";
	}

}

if (is_admin()) {
} else Wdcp_Afi_PublicPages::serve();