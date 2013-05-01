<?php
/*
Plugin Name: Comments Plus
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Description: Super-ifys comments on your site by adding ability to comment using facebook, twitter, and google accounts. Once activated, go to Settings &gt; Comments Plus to configure.
Version: 1.6.2
Text Domain: wdcp
Author: Incsub
Author URI: http://premium.wpmudev.org
WDP ID: 247

Copyright 2009-2011 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

///////////////////////////////////////////////////////////////////////////
/* -------------------- Update Notifications Notice -------------------- */
if ( !function_exists( 'wdp_un_check' ) ) {
  add_action( 'admin_notices', 'wdp_un_check', 5 );
  add_action( 'network_admin_notices', 'wdp_un_check', 5 );
  function wdp_un_check() {
    if ( class_exists( 'WPMUDEV_Update_Notifications' ) )
      return;

    if ( $delay = get_site_option( 'un_delay' ) ) {
      if ( $delay <= time() && current_user_can( 'install_plugins' ) )
      	echo '<div class="error fade"><p>' . __('Please install the latest version of <a href="http://premium.wpmudev.org/project/update-notifications/" title="Download Now &raquo;">our free Update Notifications plugin</a> which helps you stay up-to-date with the most stable, secure versions of WPMU DEV themes and plugins. <a href="http://premium.wpmudev.org/wpmu-dev/update-notifications-plugin-information/">More information &raquo;</a>', 'wpmudev') . '</a></p></div>';	    		 	 			 		  
	  } else {
			update_site_option( 'un_delay', strtotime( "+1 week" ) );
		}
	}
}
/* --------------------------------------------------------------------- */


define ('WDCP_PLUGIN_SELF_DIRNAME', basename(dirname(__FILE__)), true);
define ('WDCP_PROTOCOL', (@$_SERVER["HTTPS"] == 'on' ? 'https://' : 'http://'), true);

//Setup proper paths/URLs and load text domains
if (is_multisite() && defined('WPMU_PLUGIN_URL') && defined('WPMU_PLUGIN_DIR') && file_exists(WPMU_PLUGIN_DIR . '/' . basename(__FILE__))) {
	define ('WDCP_PLUGIN_LOCATION', 'mu-plugins', true);
	define ('WDCP_PLUGIN_BASE_DIR', WPMU_PLUGIN_DIR, true);
	define ('WDCP_PLUGIN_URL', str_replace('http://', (@$_SERVER["HTTPS"] == 'on' ? 'https://' : 'http://'), WPMU_PLUGIN_URL), true);
	$textdomain_handler = 'load_muplugin_textdomain';
} else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/' . WDCP_PLUGIN_SELF_DIRNAME . '/' . basename(__FILE__))) {
	define ('WDCP_PLUGIN_LOCATION', 'subfolder-plugins', true);
	define ('WDCP_PLUGIN_BASE_DIR', WP_PLUGIN_DIR . '/' . WDCP_PLUGIN_SELF_DIRNAME, true);
	define ('WDCP_PLUGIN_URL', str_replace('http://', (@$_SERVER["HTTPS"] == 'on' ? 'https://' : 'http://'), WP_PLUGIN_URL) . '/' . WDCP_PLUGIN_SELF_DIRNAME, true);
	$textdomain_handler = 'load_plugin_textdomain';
} else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/' . basename(__FILE__))) {
	define ('WDCP_PLUGIN_LOCATION', 'plugins', true);
	define ('WDCP_PLUGIN_BASE_DIR', WP_PLUGIN_DIR, true);
	define ('WDCP_PLUGIN_URL', str_replace('http://', (@$_SERVER["HTTPS"] == 'on' ? 'https://' : 'http://'), WP_PLUGIN_URL), true);
	$textdomain_handler = 'load_plugin_textdomain';
} else {
	// No textdomain is loaded because we can't determine the plugin location.
	// No point in trying to add textdomain to string and/or localizing it.
	wp_die(__('There was an issue determining where Comments Plus plugin is installed. Please reinstall.'));
}
$textdomain_handler('wdcp', false, WDCP_PLUGIN_SELF_DIRNAME . '/languages/');


require_once WDCP_PLUGIN_BASE_DIR . '/lib/class_wdcp_options.php';
require_once WDCP_PLUGIN_BASE_DIR . '/lib/class_wdcp_model.php';
require_once WDCP_PLUGIN_BASE_DIR . '/lib/class_wdcp_comments_worker.php';
require_once WDCP_PLUGIN_BASE_DIR . '/lib/class_wdcp_plugins_handler.php';

Wdcp_PluginsHandler::init();

function wdcp_initialize () {
	$data = new Wdcp_Options;
	define('WDCP_TW_API_KEY', $data->get_option('tw_api_key'));
	define('WDCP_CONSUMER_KEY', $data->get_option('tw_api_key'));
	define('WDCP_CONSUMER_SECRET', $data->get_option('tw_app_secret'));
	define('WDCP_SKIP_TWITTER', $data->get_option('tw_skip_init'));

	if (!defined('WDCP_TIMESTAMP_DELTA_THRESHOLD')) define('WDCP_TIMESTAMP_DELTA_THRESHOLD', 10, true);

	define('WDCP_APP_ID', $data->get_option('fb_app_id'));
	define('WDCP_APP_SECRET', $data->get_option('fb_app_secret'));
	if (!defined('WDCP_SKIP_FACEBOOK')) define('WDCP_SKIP_FACEBOOK', $data->get_option('fb_skip_init'));

	if (is_admin()) {
		require_once WDCP_PLUGIN_BASE_DIR . '/lib/class_wdcp_admin_form_renderer.php';
		require_once WDCP_PLUGIN_BASE_DIR . '/lib/class_wdcp_admin_pages.php';
		require_once WDCP_PLUGIN_BASE_DIR . '/lib/class_wdcp_contextual_help.php';
		require_once WDCP_PLUGIN_BASE_DIR . '/lib/class_wdcp_tutorial.php';
		Wdcp_AdminPages::serve();
		Wdcp_ContextualHelp::serve();
		Wdcp_Tutorial::serve();
	} else {
		require_once WDCP_PLUGIN_BASE_DIR . '/lib/class_wdcp_public_pages.php';
		Wdcp_PublicPages::serve();
	}
}
add_action('plugins_loaded', 'wdcp_initialize');