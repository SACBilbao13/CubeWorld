<?php
/*
Plugin Name: WordPress Chat
Plugin URI: http://premium.wpmudev.org/project/wordpress-chat-plugin
Description: Provides you with a fully featured chat area either in a post, page or bottom corner of your site - once activated configure <a href="options-general.php?page=chat">here</a> and drop into a post or page by clicking on the new chat icon in your post/page editor.
Author: Paul Menard (Incsub), S H Mohanjith (Incsub)
WDP ID: 159
Version: 1.3.2.7
Author URI: http://premium.wpmudev.org
Text Domain: wpmudev_chat
*/

/**
 * @global	object	$chat	Convenient access to the chat object
 */
//global $wpmudev_chat;

/**
 * Chat object (PHP4 compatible)
 *
 * Allow your readers to chat with you
 *
 * @since 1.0.1
 * @author S H Mohanjith <moha@mohanjith.net>
 */

include_once( dirname(__FILE__) . '/lib/dash-notices/wpmudev-dash-notification.php');

if (!class_exists('WPMUDEV_Chat')) {
class WPMUDEV_Chat {

	var $logger_fp;

	/**
	 * @todo Update version number for new releases
	 *
	 * @var		string	$chat_current_version	Current version
	 */
	var $chat_current_version = '1.3.2.7';

	/**
	 * @var		string	$translation_domain	Translation domain
	 */
	var $translation_domain = 'wpmudev_chat';

	/**
	 * @var		array	$auth_type_map		Authentication methods map
	 */
	var $auth_type_map = array(1 => 'current_user', 2 => 'network_user', 3 => 'facebook', 4 => 'twitter', 5 => 'public_user');

	/**
	 * @var		array	$fonts_list		List of fonts
	 */
	var $fonts_list = array(
		"Arial" => "Arial, Helvetica, sans-serif",
		"Arial Black" => "'Arial Black', Gadget, sans-serif",
		"Bookman Old Style" => "'Bookman Old Style', serif",
		"Comic Sans MS" => "'Comic Sans MS', cursive",
		"Courier" => "Courier, monospace",
		"Courier New" => "'Courier New', Courier, monospace",
		"Garamond" => "Garamond, serif",
		"Georgia" => "Georgia, serif",
		"Impact" => "Impact, Charcoal, sans-serif",
		"Lucida Console" => "'Lucida Console', Monaco, monospace",
		"Lucida Sans Unicode" => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
		"MS Sans Serif" => "'MS Sans Serif', Geneva, sans-serif",
		"MS Serif" => "'MS Serif', 'New York', sans-serif",
		"Palatino Linotype" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
		"Symbol" => "Symbol, sans-serif",
		"Tahoma" => "Tahoma, Geneva, sans-serif",
		"Times New Roman" => "'Times New Roman', Times, serif",
		"Trebuchet MS" => "'Trebuchet MS', Helvetica, sans-serif",
		"Verdana" => "Verdana, Geneva, sans-serif",
		"Webdings" => "Webdings, sans-serif",
		"Wingdings" => "Wingdings, 'Zapf Dingbats', sans-serif"
	);

	/**
	 * @var		array	$_chat_options			Consolidated options
	 */
	var $_chat_options = array();

	/**
	 * Get the table name with prefixes
	 *
	 * @global	object	$wpdb
	 * @param	string	$table	Table name
	 * @return	string			Table name complete with prefixes
	 */
	function tablename($table) {
		global $wpdb;
		// We use a single table for all chats accross the network
		return $wpdb->base_prefix.'chat_'.$table;
	}

	/**
	 * Initializing object
	 *
	 * Plugin register actions, filters and hooks.
	 */
	function WPMUDEV_Chat() {

		// Activation deactivation hooks

		register_activation_hook(__FILE__, array(&$this, 'install'));
		register_deactivation_hook(__FILE__, array(&$this, 'uninstall'));

		// Actions

		add_action('init', array(&$this, 'init'));
		add_action('wp_head', array(&$this, 'wp_head'), 1);
		add_action('wp_head', array(&$this, 'output_css'));
		add_action('wp_footer', array(&$this, 'wp_footer'), 1);
		add_action('get_footer', array(&$this, 'get_footer'));
		// add_action('admin_head', array($this, 'admin_head'));
		add_action('save_post', array(&$this, 'post_check'));
		add_action('edit_user_profile', array(&$this, 'profile'));
		add_action('show_user_profile', array(&$this, 'profile'));

		add_action('admin_print_styles-settings_page_chat', array(&$this, 'admin_styles'));
		add_action('admin_print_scripts-settings_page_chat', array(&$this, 'admin_scripts'));

		// Filters
		// From process.php
		add_action('wp_ajax_chatProcess', array(&$this, 'process'));
		add_action('wp_ajax_nopriv_chatProcess', array(&$this, 'process'));

		// Only authenticated users (admin) can clear and archive
		add_action('wp_ajax_chatArchive', array(&$this, 'archive'));
		add_action('wp_ajax_chatClear', array(&$this, 'clear'));

		add_action('wp_ajax_chatModerateDelete', array(&$this, 'chatModerateDelete'));
		add_action('wp_ajax_chatSessionStatusModerate', array(&$this, 'chatSessionStatusModerate'));

		// TinyMCE options
		add_action('wp_ajax_chatTinymceOptions', array(&$this, 'tinymce_options'));

		add_filter('wp_redirect', array(&$this, 'profile_process'), 1, 1);
		add_filter('admin_menu', array(&$this, 'admin_menu'));

		// White list the options to make sure non super admin can save chat options
		add_filter('whitelist_options', array(&$this, 'whitelist_options'));

		// Add shortcode
		add_shortcode('chat', array(&$this, 'process_shortcode'));

		$this->_chat_options['default'] = get_option('chat_default', array(
			'sound'							=> 'enabled',
			'avatar'						=> 'enabled',
			'emoticons'						=> 'disabled',
			'date_show'						=> 'disabled',
			'time_show'						=> 'disabled',
			'width'							=> '',
			'height'						=> '',
			'background_color'				=> '#FFFFFF',

			'background_row_area_color'		=>	'#F9F9F9',
			'background_row_color'			=>	'#FFFFFF',
			'row_border_color'				=>	'#CCCCCC',
			'row_border_width'				=>	'1px',
			'row_spacing'					=>	'5px',

			'background_highlighted_color'	=> '#FFFFFF',
			'date_color'					=> '#6699CC',
			'name_color'					=> '#666666',
			'moderator_name_color'			=> '#6699CC',
			'special_color'					=> '#660000',
			'text_color'					=> '#000000',
			'code_color'					=> '#FFFFCC',
			'font'							=> '',
			'font_size'						=> '12',
			'log_creation'					=> 'disabled',
			'log_display'					=> 'disabled',
			'log_limit'						=> '',
			'login_options'					=> array('current_user'),
			'moderator_roles'				=> array('administrator','editor','author'),
			'tinymce_roles'					=> array('administrator'),
			'tinymce_post_types'			=> array('post','page')
		));

		$this->_chat_options['site'] = get_option('chat_site', array(
			'site'							=> 'disabled',
			'sound'							=> 'enabled',
			'avatar'						=> 'enabled',
			'emoticons'						=> 'disabled',
			'date_show'						=> 'disabled',
			'time_show'						=> 'disabled',
			'width'							=> '',
			'height'						=> '',
			'border_color'					=> '#4b96e2',

			'background_row_area_color'		=>	'#F9F9F9',
			'background_row_color'			=>	'#FFFFFF',
			'row_border_color'				=>	'#CCCCCC',
			'row_border_width'				=>	'1px',
			'row_spacing'					=>	'5px',

			'border_highlighted_color' 		=> '#ff8400',
			'background_color'				=> '#FFFFFF',
			'date_color'					=> '#6699CC',
			'name_color'					=> '#666666',
			'moderator_name_color'			=> '#6699CC',
			'special_color'					=> '#660000',
			'text_color'					=> '#000000',
			'code_color'					=> '#FFFFCC',
			'font'							=> '',
			'font_size'						=> '12',
			'log_creation'					=> 'disabled',
			'log_display'					=> 'disabled',
			'log_limit'						=> '',
			'login_options'					=> array('current_user'),
			'moderator_roles'				=> array('administrator','editor','author')));
	}

	/**
	 * Activation hook
	 *
	 * Create tables if they don't exist and add plugin options
	 *
	 * @see		http://codex.wordpress.org/Function_Reference/register_activation_hook
	 *
	 * @global	object	$wpdb
	 */
	function install() {
		global $wpdb;

		/**
		 * WordPress database upgrade/creation functions
		 */
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		// Get the correct character collate
		if ( ! empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";

		if($wpdb->get_var("SHOW TABLES LIKE '". WPMUDEV_Chat::tablename('message') ."'") != WPMUDEV_Chat::tablename('message'))
		{
			// Setup chat message table
			$sql_main = "CREATE TABLE ".WPMUDEV_Chat::tablename('message')." (
							id BIGINT NOT NULL AUTO_INCREMENT,
							blog_id INT NOT NULL ,
							chat_id INT NOT NULL ,
							timestamp TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ,
							name VARCHAR( 255 ) CHARACTER SET utf8 NOT NULL ,
							avatar VARCHAR( 1024 ) CHARACTER SET utf8 NOT NULL ,
							message TEXT CHARACTER SET utf8 NOT NULL ,
							moderator ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' ,
							archived ENUM( 'yes', 'no', 'yes-deleted', 'no-deleted' ) NOT NULL DEFAULT 'no' ,
							PRIMARY KEY (`id`),
							KEY `blog_id` (`blog_id`),
							KEY `chat_id` (`chat_id`),
							KEY `timestamp` (`timestamp`),
							KEY `archived` (`archived`)
						) ENGINE = InnoDB {$charset_collate};";
			dbDelta($sql_main);
		} else {
			$wpdb->query("ALTER TABLE ".WPMUDEV_Chat::tablename('message')." CHANGE name name VARCHAR( 255 ) CHARACTER SET utf8 NOT NULL;");
			$wpdb->query("ALTER TABLE ".WPMUDEV_Chat::tablename('message')." CHANGE avatar avatar VARCHAR( 1024 ) CHARACTER SET utf8 NOT NULL;");
			$wpdb->query("ALTER TABLE ".WPMUDEV_Chat::tablename('message')." CHANGE message message TEXT CHARACTER SET utf8 NOT NULL;");
			$wpdb->query("ALTER TABLE ".WPMUDEV_Chat::tablename('message')." CHANGE archived archived ENUM( 'yes',  'no',  'yes-deleted', 'no-deleted' ) NOT NULL DEFAULT 'no';");

			if ($wpdb->get_var("SHOW COLUMNS FROM ".WPMUDEV_Chat::tablename('message')." LIKE 'moderator'") != 'moderator') {
				$wpdb->query("ALTER TABLE ".WPMUDEV_Chat::tablename('message')." ADD moderator ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' AFTER message;");
			}
		}

		if($wpdb->get_var("SHOW TABLES LIKE '". WPMUDEV_Chat::tablename('log') ."'") != WPMUDEV_Chat::tablename('log'))
		{

			// Setup the chat log table
			$sql_main = "CREATE TABLE ".WPMUDEV_Chat::tablename('log')." (
							id BIGINT NOT NULL AUTO_INCREMENT,
							blog_id INT NOT NULL ,
							chat_id INT NOT NULL ,
							start TIMESTAMP DEFAULT '0000-00-00 00:00:00' ,
							end TIMESTAMP DEFAULT '0000-00-00 00:00:00' ,
							created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
							PRIMARY KEY (`id`),
							KEY `blog_id` (`blog_id`),
							KEY `chat_id` (`chat_id`)
						) ENGINE = InnoDB {$charset_collate};";
			dbDelta($sql_main);
		}

		// Default chat options
		$this->_chat_options['default'] = array(
			'sound'								=> 'enabled',
			'avatar'							=> 'enabled',
			'emoticons'							=> 'disabled',
			'date_show'							=> 'disabled',
			'time_show'							=> 'disabled',
			'width'								=> '',
			'height'							=> '',
			'background_color'					=> '#FFFFFF',

			'background_row_area_color'			=>	'#F9F9F9',
			'background_row_color'				=>	'#FFFFFF',
			'row_border_color'					=>	'#CCCCCC',
			'row_border_width'					=>	'1px',
			'row_spacing'						=>	'5px',

			'background_highlighted_color'		=> '#FFFFFF',
			'date_color'						=> '#6699CC',
			'name_color'						=> '#666666',
			'moderator_name_color'				=> '#6699CC',
			'special_color'						=> '#660000',
			'text_color'						=> '#000000',
			'code_color'						=> '#FFFFCC',
			'font'								=> '',
			'font_size'							=> '12',
			'log_creation'						=> 'disabled',
			'log_display'						=> 'disabled',
			'log_limit'							=> '',
			'login_options'						=> array('current_user'),
			'moderator_roles'					=> array('administrator','editor','author'),
			'tinymce_roles'						=> array('administrator'),
			'tinymce_post_types'				=> array('post','page')
		);

		// Site wide chat options
		$this->_chat_options['site'] = array(
			'site'								=> 'disabled',
			'sound'								=> 'enabled',
			'avatar'							=> 'enabled',
			'emoticons'							=> 'disabled',
			'date_show'							=> 'disabled',
			'time_show'							=> 'disabled',
			'width'								=> '',
			'height'							=> '',
			'border_color'						=> '#4b96e2',
			'border_highlighted_color' 			=> '#ff8400',

			'background_row_area_color'			=>	'#F9F9F9',
			'background_row_color'				=>	'#FFFFFF',
			'row_border_color'					=>	'#CCCCCC',
			'row_border_width'					=>	'1px',
			'row_spacing'						=>	'5px',

			'background_color'					=> '#ffffff',
			'date_color'						=> '#6699CC',
			'name_color'						=> '#666666',
			'moderator_name_color'				=> '#6699CC',
			'special_color'						=> '#660000',
			'text_color'						=> '#000000',
			'code_color'						=> '#FFFFCC',
			'font'								=> '',
			'font_size'							=> '12',
			'log_creation'						=> 'disabled',
			'log_display'						=> 'disabled',
			'log_limit'							=> '',
			'login_options'						=> array('current_user'),
			'moderator_roles'					=> array('administrator','editor','author'));

		add_option('chat_default', $this->_chat_options['default']);
		add_option('chat_site', $this->_chat_options['site'], null, 'no');
	}

	/**
	 * Deactivation hook
	 *
	 * @see		http://codex.wordpress.org/Function_Reference/register_deactivation_hook
	 *
	 * @global	object	$wpdb
	 */
	function uninstall() {
		global $wpdb;
		// Nothing to do
	}

	/**
	 * Get chat options
	 *
	 * @param string $key
	 * @param mixed $default
	 * @param string $type
	 * @return mixed
	 */
	function get_option($key, $default = null, $type = 'default') {
		if (isset($this->_chat_options[$type][$key])) {
			return $this->_chat_options[$type][$key];
		} else {
			return get_option($key, $default);
		}
		return $default;
	}

	/**
	 * Initialize the plugin
	 *
	 * @see		http://codex.wordpress.org/Plugin_API/Action_Reference
	 * @see		http://adambrown.info/p/wp_hooks/hook/init
	 */
	function init() {
		global $chat_processed;

		$chat_processed = 0;

		if (preg_match('/mu\-plugin/', PLUGINDIR) > 0) {
			load_muplugin_textdomain($this->translation_domain, dirname(plugin_basename(__FILE__)).'/languages');
		} else {
			load_plugin_textdomain($this->translation_domain, false, dirname(plugin_basename(__FILE__)).'/languages');
		}

		wp_register_script('chat_soundmanager', plugins_url('/js/soundmanager2-nodebug-jsmin.js', __FILE__), array(), $this->chat_current_version, true);
		wp_register_script('jquery-cookie', plugins_url('/js/jquery-cookie.js', __FILE__), array('jquery'), $this->chat_current_version, true);
		wp_register_script('chat_js', plugins_url('/js/chat.js', __FILE__),
			array('jquery', 'jquery-cookie', 'chat_soundmanager'),
			$this->chat_current_version, true);

		if (is_admin()) {
			wp_register_script('farbtastic', plugins_url('/js/farbtastic.js', __FILE__), array('jquery'));
			wp_register_script('chat_admin_js', plugins_url('/js/chat-admin.js', __FILE__),
				array('jquery','jquery-cookie','jquery-ui-core','jquery-ui-tabs','farbtastic'), $this->chat_current_version, true);
			wp_register_style('chat_admin_css', plugins_url('/css/wp_admin.css', __FILE__), $this->chat_current_version);
		}

		if (is_admin()) {

			$post_id = $post = $post_type_object = null;
			$post_type = "post";
			if ( isset( $_GET['post_type']) ) {
				$post_type = $_GET['post_type'];
			} else {
				if ( isset( $_GET['post'] ) )
				 	$post_id = (int) $_GET['post'];
				elseif ( isset( $_POST['post_ID'] ) )
				 	$post_id = (int) $_POST['post_ID'];
				if ( $post_id ) {
					$post = get_post( $post_id );
					if ( $post ) {
						$post_type = $post->post_type;
					}
				}
			}

			if (get_current_user_id()) {
				$current_user = wp_get_current_user();
				if ((array_intersect($current_user->roles, $this->get_option('tinymce_roles', array('administrator') )))
				 && (in_array($post_type, $this->get_option('tinymce_post_types', array('post','page'))))) {
					add_filter("mce_external_plugins", array(&$this, "tinymce_add_plugin"));
					add_filter('mce_buttons', array(&$this,'tinymce_register_button'));
					add_filter('mce_external_languages', array(&$this,'tinymce_load_langs'));
				}
			}
		}

		if (in_array('facebook', $this->get_option('login_options', array('current_user'))) && (empty($_COOKIE['chat_stateless_user_type_104']))) {
			$this->authenticate_facebook();
		}
		// Need to stop any output until cookies are set
		ob_start();
	}

	/**
	 * Add the CSS (admin_head)
	 *
	 * @see		http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head-(plugin_page)
	 */
	function admin_styles() {
		wp_enqueue_style('chat_admin_css');
	}

	function admin_scripts() {
		wp_enqueue_script('jquery-cookie');
		wp_enqueue_script('farbtastic');
		wp_enqueue_script('chat_admin_js');
	}

	/**
	 * Add the admin menus
	 *
	 * @see		http://codex.wordpress.org/Adding_Administration_Menus
	 */
	function admin_menu() {
		add_options_page(__('Chat Plugin Options', $this->translation_domain), __('Chat', $this->translation_domain), 'manage_options', 'chat', array(&$this, 'plugin_options'));
	}

	/**
	 * Is Twitter setup complete
	 *
	 * @return	boolean			Is Twitter setup complete. true or false
	 */
	function is_twitter_setup() {
		if ($this->get_option('twitter_api_key', '') != '') {
			return true;
		}
		return false;
	}

	/**
	 * Is Facebook setup complete
	 *
	 * @todo	Validate the application ID and secret with Facebook
	 *
	 * @return	boolean			Is Facebook setup complete. true or false
	 */
	function is_facebook_setup() {
		if ($this->get_option('facebook_application_id', '') != '' && $this->get_option('facebook_application_secret', '') != '') {
			return true;
		}
		return false;
	}

	function get_footer() {
		global $chat_processed;

//		if (!$chat_processed) {
//			wp_deregister_script('jquery-cookie');
//			wp_deregister_script('chat_soundmanager');
//			wp_deregister_script('chat_js');
//		}
	}

	/**
	 * TinyMCE dialog content
	 */
	function tinymce_options() {
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<script type="text/javascript" src="../wp-includes/js/tinymce/tiny_mce_popup.js?ver=327-1235"></script>
				<script type="text/javascript" src="../wp-includes/js/tinymce/utils/mctabs.js?ver=327-1235"></script>
				<script type="text/javascript" src="../wp-includes/js/tinymce/utils/validate.js?ver=327-1235"></script>

				<script type="text/javascript" src="../wp-includes/js/tinymce/utils/form_utils.js?ver=327-1235"></script>
				<script type="text/javascript" src="../wp-includes/js/tinymce/utils/editable_selects.js?ver=327-1235"></script>

				<script type="text/javascript" src="../wp-includes/js/jquery/jquery.js"></script>

				<script type="text/javascript">
					var default_options = {
						id: "<?php print $this->get_last_chat_id(); ?>",
						sound: "<?php print $this->get_option('sound', 'enabled'); ?>",
						avatar: "<?php print $this->get_option('avatar', 'enabled'); ?>",
						emoticons: "<?php print $this->get_option('emoticons', 'disabled'); ?>",
						date_show: "<?php print $this->get_option('date_show', 'disabled'); ?>",
						time_show: "<?php print $this->get_option('time_show', 'disabled'); ?>",
						width: "<?php print $this->get_option('width', ''); ?>",
						height: "<?php print $this->get_option('height', ''); ?>",
						background_color: "<?php print $this->get_option('background_color', '#ffffff'); ?>",
						background_row_area_color: "<?php print $this->get_option('background_row_area_color', '#F9F9F9'); ?>",
						background_row_color: "<?php print $this->get_option('background_row_color', '#FFFFFF'); ?>",
						row_border_color: "<?php print $this->get_option('row_border_color', '#CCCCCC'); ?>",
						row_border_width: "<?php print $this->get_option('row_border_width', '1px'); ?>",
						row_spacing: "<?php print $this->get_option('row_spacing', '5px'); ?>",
						background_highlighted_color: "<?php print $this->get_option('background_highlighted_color', '#FFE9AB'); ?>",
						date_color: "<?php print $this->get_option('date_color', '#6699CC'); ?>",
						name_color: "<?php print $this->get_option('name_color', '#666666'); ?>",
						moderator_name_color: "<?php print $this->get_option('moderator_name_color', '#6699CC'); ?>",
						text_color: "<?php print $this->get_option('text_color', '#000000'); ?>",
						font: "<?php print $this->get_option('font', ''); ?>",
						font_size: "<?php print $this->get_option('font_size', '12'); ?>",
						log_creation: "<?php print $this->get_option('log_creation', 'disabled'); ?>",
						log_display: "<?php print $this->get_option('log_display', 'disabled'); ?>",
						log_limit: "<?php print $this->get_option('log_limit', ''); ?>",
						login_options: "<?php print join(',', $this->get_option('login_options', array('current_user'))); ?>",
						moderator_roles: "<?php print join(',', $this->get_option('moderator_roles', array('administrator','editor','author'))); ?>"
					};

					var current_options = {
						id: default_options.id+Math.floor(Math.random()*10),
						sound: "<?php print $this->get_option('sound', 'enabled'); ?>",
						avatar: "<?php print $this->get_option('avatar', 'enabled'); ?>",
						emoticons: "<?php print $this->get_option('emoticons', 'disabled'); ?>",
						date_show: "<?php print $this->get_option('date_show', 'disabled'); ?>",
						time_show: "<?php print $this->get_option('time_show', 'disabled'); ?>",
						width: "<?php print $this->get_option('width', ''); ?>",
						height: "<?php print $this->get_option('height', ''); ?>",
						background_color: "<?php print $this->get_option('background_color', '#ffffff'); ?>",
						background_row_area_color: "<?php print $this->get_option('background_row_area_color', '#F9F9F9'); ?>",
						background_row_color: "<?php print $this->get_option('background_row_color', '#FFFFFF'); ?>",
						row_border_color: "<?php print $this->get_option('row_border_color', '#CCCCCC'); ?>",
						row_border_width: "<?php print $this->get_option('row_border_width', '1px'); ?>",
						row_spacing: "<?php print $this->get_option('row_spacing', '5px'); ?>",
						background_highlighted_color: "<?php print $this->get_option('background_highlighted_color', '#FFE9AB'); ?>",
						date_color: "<?php print $this->get_option('date_color', '#6699CC'); ?>",
						name_color: "<?php print $this->get_option('name_color', '#666666'); ?>",
						moderator_name_color: "<?php print $this->get_option('moderator_name_color', '#6699CC'); ?>",
						text_color: "<?php print $this->get_option('text_color', '#000000'); ?>",
						font: "<?php print $this->get_option('font', ''); ?>",
						font_size: "<?php print $this->get_option('font_size', '12'); ?>",
						log_creation: "<?php print $this->get_option('log_creation', 'disabled'); ?>",
						log_display: "<?php print $this->get_option('log_display', 'disabled'); ?>",
						log_limit: "<?php print $this->get_option('log_limit', ''); ?>",
						login_options: "<?php print join(',', $this->get_option('login_options', array('current_user'))); ?>",
						moderator_roles: "<?php print join(',', $this->get_option('moderator_roles', array('administrator','editor','author'))); ?>"
					};

					parts = tinyMCEPopup.editor.selection.getContent().replace(' ]', '').replace('[', '').split(' ');
					old_string = '';

					if (!(parts.length > 1 && parts[0] == 'chat')) {
						_tmp = tinyMCEPopup.editor.getContent().split('[chat ');
						if (_tmp.length > 1) {
							_tmp1 = _tmp[1].split(' ]');
							old_string = '[chat '+_tmp1[0]+' ]';
							parts = (old_string).replace(' ]', '').replace('[', '').split(' ');
						}
					}

					if (parts.length > 1 && parts[0] == 'chat') {
						current_options.id = parts[1].replace('id="', '').replace('"', '');

						for (i=2; i<parts.length; i++) {
							attr_parts = parts[i].split('=');
							if (attr_parts.length > 1) {
								current_options[attr_parts[0]] = attr_parts[1].replace('"', '').replace('"', '');
							}
						}
					}

					var insertChat = function (ed) {
						output  ='[chat id="'+current_options.id+'" ';

						if (default_options.sound != jQuery.trim(jQuery('#chat_sound').val())) {
							output += 'sound="'+jQuery.trim(jQuery('#chat_sound').val())+'" ';
						}
						if (default_options.avatar != jQuery.trim(jQuery('#chat_avatar').val())) {
							output += 'avatar="'+jQuery.trim(jQuery('#chat_avatar').val())+'" ';
						}
						if (default_options.emoticons != jQuery.trim(jQuery('#chat_emoticons').val())) {
							output += 'emoticons="'+jQuery.trim(jQuery('#chat_emoticons').val())+'" ';
						}
						if (default_options.date_show != jQuery.trim(jQuery('#chat_date_show').val())) {
							output += 'date_show="'+jQuery.trim(jQuery('#chat_date_show').val())+'" ';
						}
						if (default_options.time_show != jQuery.trim(jQuery('#chat_time_show').val())) {
							output += 'time_show="'+jQuery.trim(jQuery('#chat_time_show').val())+'" ';
						}
						if (default_options.width != jQuery.trim(jQuery('#chat_width').val())) {
							output += 'width="'+jQuery.trim(jQuery('#chat_width').val())+'" ';
						}
						if (default_options.height != jQuery.trim(jQuery('#chat_height').val())) {
							output += 'height="'+jQuery.trim(jQuery('#chat_height').val())+'" ';
						}
						if (default_options.background_color != jQuery.trim(jQuery('#chat_background_color').val())) {
							output += 'background_color="'+jQuery.trim(jQuery('#chat_background_color').val())+'" ';
						}
						if (default_options.background_highlighted_color != jQuery.trim(jQuery('#chat_background_highlighted_color').val())) {
							output += 'background_highlighted_color="'+jQuery.trim(jQuery('#chat_background_highlighted_color').val())+'" ';
						}
						if (default_options.date_color != jQuery.trim(jQuery('#chat_date_color').val())) {
							output += 'date_color="'+jQuery.trim(jQuery('#chat_date_color').val())+'" ';
						}
						if (default_options.name_color != jQuery.trim(jQuery('#chat_name_color').val())) {
							output += 'name_color="'+jQuery.trim(jQuery('#chat_name_color').val())+'" ';
						}
						if (default_options.moderator_name_color != jQuery.trim(jQuery('#chat_moderator_name_color').val())) {
							output += 'moderator_name_color="'+jQuery.trim(jQuery('#chat_moderator_name_color').val())+'" ';
						}
						if (default_options.text_color != jQuery.trim(jQuery('#chat_text_color').val())) {
							output += 'text_color="'+jQuery.trim(jQuery('#chat_text_color').val())+'" ';
						}
						if (default_options.font != jQuery.trim(jQuery('#chat_font').val())) {
							output += 'font="'+jQuery.trim(jQuery('#chat_font').val())+'" ';
						}
						if (default_options.font_size != jQuery.trim(jQuery('#chat_font_size').val())) {
							output += 'font_size="'+jQuery.trim(jQuery('#chat_font_size').val())+'" ';
						}
						if (default_options.log_creation != jQuery.trim(jQuery('#chat_log_creation').val())) {
							output += 'log_creation="'+jQuery.trim(jQuery('#chat_log_creation').val())+'" ';
						}
						if (default_options.log_display != jQuery.trim(jQuery('#chat_log_display').val())) {
							output += 'log_display="'+jQuery.trim(jQuery('#chat_log_display').val())+'" ';
						}
						if (default_options.log_limit != jQuery.trim(jQuery('#chat_log_limit').val())) {
							output += 'log_limit="'+jQuery.trim(jQuery('#chat_log_limit').val())+'" ';
						}
						var chat_login_options_arr = [];
						jQuery('input[name=chat_login_options]:checked').each(function() {
							chat_login_options_arr.push(jQuery(this).val())
						});
						if (default_options.login_options != jQuery.trim(chat_login_options_arr.join(','))) {
							output += 'login_options="'+jQuery.trim(chat_login_options_arr.join(','))+'" ';
						}

						var chat_moderator_roles_arr = [];
						jQuery('input[name=chat_moderator_roles]:checked').each(function() {
							chat_moderator_roles_arr.push(jQuery(this).val())
						});
						if (default_options.moderator_roles != jQuery.trim(chat_moderator_roles_arr.join(','))) {
							output += 'moderator_roles="'+jQuery.trim(chat_moderator_roles_arr.join(','))+'" ';
						}

						output += ']';

						if (old_string == '') {
							tinyMCEPopup.execCommand('mceReplaceContent', false, output);
						} else {
							tinyMCEPopup.execCommand('mceSetContent', false, tinyMCEPopup.editor.getContent().replace(old_string, output));
						}

						// Return
						tinyMCEPopup.close();
					};
				</script>
				<style type="text/css">
				td.info {
					vertical-align: top;
					color: #777;
				}
				</style>

				<title>{#chat_dlg.title}</title>
			</head>
			<body style="display: none">
				<form onsubmit="insertChat();return false;" action="#">
					<div class="tabs">
						<ul>
							<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');generatePreview();" onmousedown="return false;">{#chat_dlg.general}</a></span></li>
							<li id="appearance_tab"><span><a href="javascript:mcTabs.displayTab('appearance_tab','appearance_panel');" onmousedown="return false;">{#chat_dlg.appearance}</a></span></li>
							<li id="logs_tab"><span><a href="javascript:mcTabs.displayTab('logs_tab','logs_panel');" onmousedown="return false;">{#chat_dlg.logs}</a></span></li>
							<li id="authentication_tab"><span><a href="javascript:mcTabs.displayTab('authentication_tab','authentication_panel');" onmousedown="return false;">{#chat_dlg.authentication}</a></span></li>
						</ul>
					</div>

					<div class="panel_wrapper">
						<div id="general_panel" class="panel current">
							<fieldset>
								<legend>{#chat_dlg.general}</legend>

								<table border="0" cellpadding="4" cellspacing="0">
									<tr>
										<td style="width: 35%"><label for="chat_sound">{#chat_dlg.sound}</label></td>
										<td style="width: 15%">
											<select id="chat_sound" name="chat_sound" >
												<option value="enabled" <?php print ($this->get_option('sound', 'enabled') == 'enabled')?'selected="selected"':''; ?>>{#chat_dlg.enabled}</option>
												<option value="disabled" <?php print ($this->get_option('sound', 'enabled') == 'disabled')?'selected="selected"':''; ?>>{#chat_dlg.disabled}</option>
											</select>
										</td>
										<td style="width: 50%" class="info"><?php _e("Play sound when a new message is received?", $this->translation_domain); ?></td>
									</tr>


									<tr>
										<td><label for="chat_avatar">{#chat_dlg.avatar}</label></td>
										<td>
											<select id="chat_avatar" name="chat_avatar" >
												<option value="enabled" <?php print ($this->get_option('avatar', 'enabled') == 'enabled')?'selected="selected"':''; ?>>{#chat_dlg.enabled}</option>
												<option value="disabled" <?php print ($this->get_option('avatar', 'enabled') == 'disabled')?'selected="selected"':''; ?>>{#chat_dlg.disabled}</option>
											</select>
										</td>
										<td class="info"><?php _e("Display the user's avatar with the message?", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_emoticons">{#chat_dlg.emoticons}</label></td>
										<td>
											<select id="chat_emoticons" name="chat_emoticons" >
												<option value="enabled" <?php print ($this->get_option('emoticons', 'disabled') == 'enabled')?'selected="selected"':''; ?>>{#chat_dlg.enabled}</option>
												<option value="disabled" <?php print ($this->get_option('emoticons', 'disabled') == 'disabled')?'selected="selected"':''; ?>>{#chat_dlg.disabled}</option>
											</select>
										</td>
										<td class="info"><?php _e("Display emoticons bar?", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_date_show">{#chat_dlg.show_date}</label></td>
										<td>
											<select id="chat_date_show" name="chat_date_show" >
												<option value="enabled" <?php print ($this->get_option('date_show', 'disabled') == 'enabled')?'selected="selected"':''; ?>>{#chat_dlg.enabled}</option>
												<option value="disabled" <?php print ($this->get_option('date_show', 'disabled') == 'disabled')?'selected="selected"':''; ?>>{#chat_dlg.disabled}</option>
											</select>
										</td>
										<td class="info"><?php _e("Display date the message was sent?", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_time_show">{#chat_dlg.show_time}</label></td>
										<td>
											<select id="chat_time_show" name="chat_time_show" >
												<option value="enabled" <?php print ($this->get_option('time_show', 'disabled') == 'enabled')?'selected="selected"':''; ?>>{#chat_dlg.enabled}</option>
												<option value="disabled" <?php print ($this->get_option('time_show', 'disabled') == 'disabled')?'selected="selected"':''; ?>>{#chat_dlg.disabled}</option>
											</select>
										</td>
										<td class="info"><?php _e("Display the time  the message was sent?", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_width">{#chat_dlg.dimensions}</label></td>
										<td>
											<input type="text" id="chat_width" name="chat_width" value="<?php print $this->get_option('width', ''); ?>" class="size" size="5" />W
											<input type="text" id="chat_height" name="chat_height" value="<?php print $this->get_option('height', ''); ?>" class="size" size="5" />H
										</td>
										<td class="info"><?php _e("Dimensions of the chat box. Include px, em, etc.", $this->translation_domain); ?></td>
									</tr>
								</table>
							</fieldset>
						</div>

						<div id="appearance_panel" class="panel">
							<fieldset>
								<legend>{#chat_dlg.colors}</legend>

								<table border="0" cellpadding="4" cellspacing="0">
									<tr>
										<td style="width: 35%"><label for="chat_background_color">{#chat_dlg.background}</label></td>
										<td style="width: 15%">
											<input type="text" id="chat_background_color" name="chat_background_color" value="<?php print $this->get_option('background_color', '#ffffff'); ?>" class="color" size="7" />
											<div class="color" id="chat_background_color_panel"></div>
										</td>
										<td style="width: 50%" class="info"><?php _e("Chat box background color", $this->translation_domain); ?></td>
									</tr>

<?php /* ?>
									<tr>
										<td><label for="chat_background_row_area_color">{#chat_dlg.background_row_area_color}</label></td>
										<td>
											<input type="text" id="chat_background_row_area_color" name="chat_default[background_row_area_color]"
												value="<?php print $this->get_option('background_row_area_color', '#F9F9F9'); ?>" class="color" size="7" />
											<div class="color" id="chat_background_row_area_color_panel"></div>
										</td>
										<td class="info"><?php _e("Background color of the message area", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_background_row_color">{#chat_dlg.background_row_color}</label></td>
										<td>
											<input type="text" id="chat_background_row_color" name="chatbackground_row_color"
												value="<?php print $this->get_option('background_row_color', '#FFFFFF'); ?>" class="color" size="7" />
											<div class="color" id="chat_background_row_color_panel"></div>
										</td>
										<td class="info"><?php _e("Background color of the message row items", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_row_border_color">{#chat_dlg.row_border_color}</label></td>
										<td>
											<input type="text" id="chat_row_border_color" name="chat_row_border_color"
												value="<?php print $this->get_option('row_border_color', '#CCCCCC'); ?>" class="color" size="7" />
											<div class="color" id="chat_row_border_color_panel"></div>
										</td>
										<td class="info"><?php _e("Border color of the message row items", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_row_border_width">{#chat_dlg.row_border_width}</label></td>
										<td>
											<input type="text" id="chat_row_border_width" name="chat_row_border_width"
												value="<?php print $this->get_option('row_border_width', '1px'); ?>" size="7" />
										</td>
										<td class="info"><?php _e("Border width of the message row items", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_row_spacing">{#chat_dlg.row_spacing}</label></td>
										<td>
											<input type="text" id="chat_row_spacing" name="chat__row_spacing"
												value="<?php print $this->get_option('row_spacing', '5px'); ?>" size="7" />
										</td>
										<td class="info"><?php _e("Spacing between row items", $this->translation_domain); ?></td>
									</tr>

<?php */ ?>

									<tr>
										<td><label for="chat_background_highlighted_color">{#chat_dlg.background_highlighted}</label></td>
										<td>
											<input type="text" id="chat_background_highlighted_color" name="chat_background_highlighted_color" value="<?php print $this->get_option('background_highlighted_color', '#ffe9ab'); ?>" class="color" size="7" />
											<div class="color" id="chat_background_highlighted_color_panel"></div>
										</td>
										<td class="info"><?php _e("Chat box background color when there is a new message", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_date_color">{#chat_dlg.date}</label></td>
										<td>
											<input type="text" id="chat_date_color" name="chat_date_color" value="<?php print $this->get_option('date_color', '#6699CC'); ?>" class="color" size="7" />
											<div class="color" id="chat_date_color_panel"></div>
										</td>
										<td class="info"><?php _e("Date background color", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_name_color">{#chat_dlg.name}</label></td>
										<td>
											<input type="text" id="chat_name_color" name="chat_name_color" value="<?php print $this->get_option('name_color', '#666666'); ?>" class="color" size="7" />
											<div class="color" id="chat_name_color_panel"></div>
										</td>
										<td class="info"><?php _e("Name background color", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_moderator_name_color">{#chat_dlg.moderator_name}</label></td>
										<td>
											<input type="text" id="chat_moderator_name_color" name="chat_moderator_name_color" value="<?php print $this->get_option('moderator_name_color', '#6699CC'); ?>" class="color" size="7" />
											<div class="color" id="chat_moderator_name_color_panel"></div>
										</td>
										<td class="info"><?php _e("Moderator Name background color", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_text_color">{#chat_dlg.text}</label></td>
										<td>
											<input type="text" id="chat_text_color" name="chat_text_color" value="<?php print $this->get_option('text_color', '#000000'); ?>" class="color" size="7" />
											<div class="color" id="chat_text_color_panel"></div>
										</td>
										<td class="info"><?php _e("Text color", $this->translation_domain); ?></td>
									</tr>
								</table>
							</fieldset>

							<fieldset>
								<legend>{#chat_dlg.fonts}</legend>

								<table border="0" cellpadding="4" cellspacing="0">

									<tr>
										<td style="width: 35%"><label for="chat_font">{#chat_dlg.font}</label></td>
										<td style="width: 15%">
											<select id="chat_font" name="chat_font" class="font" >
											<?php foreach ($this->fonts_list as $font_name => $font) { ?>
												<option value="<?php print $font; ?>" <?php print ($this->get_option('font', '') == $font)?'selected="selected"':''; ?> ><?php print $font_name; ?></option>
											<?php } ?>
											</select>
										</td>
										<td style="width: 50%" class="info"><?php _e("Chat box font", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_font_size">{#chat_dlg.font_size}</label></td>
										<td>
											<select id="chat_font_size" name="chat_font_size" class="font_size" >
											<?php for ($font_size=8; $font_size<21; $font_size++) { ?>
												<option value="<?php print $font_size; ?>" <?php print ($this->get_option('font_size', '12') == $font_size)?'selected="selected"':''; ?> ><?php print $font_size; ?></option>
											<?php } ?>
											</select> px
										</td>
										<td class="info"><?php _e("Chat box font size", $this->translation_domain); ?></td>
									</tr>
								</table>
							</fieldset>
						</div>

						<div id="logs_panel" class="panel">
							<fieldset>
								<legend>{#chat_dlg.logs}</legend>

								<table border="0" cellpadding="4" cellspacing="0">
									<tr>
										<td style="width: 35%"><label for="chat_log_creation">{#chat_dlg.creation}</label></td>
										<td style="width: 15%">
											<select id="chat_log_creation" name="chat_log_creation" >
												<option value="enabled" <?php print ($this->get_option('log_creation', 'disabled') == 'enabled')?'selected="selected"':''; ?>>{#chat_dlg.enabled}</option>
												<option value="disabled" <?php print ($this->get_option('log_creation', 'disabled') == 'disabled')?'selected="selected"':''; ?>>{#chat_dlg.disabled}</option>
											</select>
										</td>
										<td style="width: 50%" class="info"><?php _e("Log chat messages?", $this->translation_domain); ?></td>
									</tr>

									<tr>
										<td><label for="chat_log_display">{#chat_dlg.display}</label></td>
										<td>
											<select id="chat_log_display" name="chat_log_display" >
												<option value="enabled" <?php print ($this->get_option('log_display', 'disabled') == 'enabled')?'selected="selected"':''; ?>>{#chat_dlg.enabled}</option>
												<option value="disabled" <?php print ($this->get_option('log_display', 'disabled') == 'disabled')?'selected="selected"':''; ?>>{#chat_dlg.disabled}</option>
											</select>
										</td>
										<td class="info"><?php _e("Display chat logs?", $this->translation_domain); ?></td>
									</tr>

								</table>
							</fieldset>


							<fieldset>
								<legend><?php _e("Number of messages to display on initial page load", $this->translation_domain); ?></legend>
								<table border="0" cellpadding="4" cellspacing="0">
								<tr>
									<td><label for="chat_log_limit"><?php _e("Number of Messages", $this->translation_domain); ?></label></td>
									<td>
										<input type="text" id="chat_log_limit" name="chat_log_limit" size="5"
											value="<?php print ($this->get_option('log_limit', '')); ?>" />
									</td>
									<td class="info"><?php _e("set empty for all", $this->translation_domain); ?></td>
								</tr>
								<tr><td colspan="3"><?php _e("Note: This does not truncate the message in the database.", $this->translation_domain); ?></td></tr>
								</table>
							</fieldset>

						</div>

						<div id="authentication_panel" class="panel">
							<fieldset>
								<legend>{#chat_dlg.authentication}</legend>

								<table border="0" cellpadding="4" cellspacing="0">
									<tr>
										<td valign="top" style="width: 25%"><label for="chat_login_options">{#chat_dlg.login_options}</label></td>
										<td style="width: 35%">
											<lable><input type="checkbox" id="chat_login_options_current_user" name="chat_login_options" class="chat_login_options" value="current_user" <?php print (in_array('current_user', $this->get_option('login_options', array('current_user'))) > 0)?'checked="checked"':''; ?> /> <?php _e('WordPress user', $this->translation_domain); ?></label><br/>
											<?php if (is_multisite()) { ?>
											<lable><input type="checkbox" id="chat_login_options_network_user" name="chat_login_options" class="chat_login_options" value="network_user" <?php print (in_array('network_user', $this->get_option('login_options', array('current_user'))) > 0)?'checked="checked"':''; ?> /> <?php _e('Network user', $this->translation_domain); ?></label><br/>
											<?php } ?>
											<lable><input type="checkbox" id="chat_login_options_public_user" name="chat_login_options" class="chat_login_options" value="public_user" <?php print (in_array('public_user', $this->get_option('login_options', array('current_user'))) > 0)?'checked="checked"':''; ?> /> <?php _e('Public user', $this->translation_domain); ?></label><br/>
											<?php if ($this->is_twitter_setup()) { ?>
											<lable><input type="checkbox" id="chat_login_options_twitter" name="chat_login_options" class="chat_login_options" value="twitter" <?php print (!$this->is_twitter_setup())?'disabled="disabled"':''; ?> <?php print (in_array('twitter', $this->get_option('login_options', array('current_user'))) > 0)?'checked="checked"':''; ?> /> <?php _e('Twitter', $this->translation_domain); ?></label><br/>
											<?php } ?>
											<?php if ($this->is_facebook_setup()) { ?>
											<lable><input type="checkbox" id="chat_login_options_facebook" name="chat_login_options" class="chat_login_options" value="facebook" <?php print (!$this->is_facebook_setup())?'disabled="disabled"':''; ?> <?php print (in_array('facebook', $this->get_option('login_options', array('current_user'))) > 0)?'checked="checked"':''; ?> /> <?php _e('Facebook', $this->translation_domain); ?></label><br/>
											<?php } ?>
										</td>
										<td class="info" style="width: 45%"><?php _e("Authentication methods users can use. <strong>If only 'WordPress user' is selected then chat will be hidden from non-authenticated users.</strong>", $this->translation_domain); ?></td>
									</tr>
									<tr>
										<td valign="top"><label for="chat_moderator_roles">{#chat_dlg.moderator_roles}</label></td>
										<td>
											<?php
											//foreach (get_editable_roles() as $role => $details) {
											//	$name = translate_user_role($details['name'] );
											global $wp_roles;
											foreach ($wp_roles->role_names as $role => $name) {
											?>
											<lable><input type="checkbox" id="chat_moderator_roles_<?php print $role; ?>" name="chat_moderator_roles" class="chat_moderator_roles" value="<?php print $role; ?>" <?php print (in_array($role, $this->get_option('moderator_roles', array('administrator','editor','author'))) > 0)?'checked="checked"':''; ?> /> <?php _e($name, $this->translation_domain); ?></label><br/>
											<?php
											}
											?>
										</td>
										<td class="info"><?php _e("Select which roles are moderators", $this->translation_domain); ?></td>
									</tr>
								</table>
							</fieldset>
						</div>
					</div>

					<div class="mceActionPanel">
						<div style="float: left">
							<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
						</div>

						<div style="float: right">
							<input type="submit" id="insert" name="insert" value="{#insert}" />
						</div>
					</div>
				</form>
				<script type="text/javascript">
					jQuery(window).load(function() {
						for (attr in current_options) {
							if (attr == "id") continue;

							if (current_options[attr].match(',')) {
								jQuery("#chat_"+attr).val(current_options[attr].split(','));
							} else {
								jQuery("#chat_"+attr).val(current_options[attr]);
							}
						}
					});
				</script>
			</body>
		</html>
		<?php
		exit(0);
	}

	function whitelist_options($options) {
		$added = array( 'chat' => array( 'chat_default', 'chat_site' ) );
		$options = add_option_whitelist( $added, $options );
		return $options;
	}

	/**
	 * Plugin options
	 */
	function plugin_options() {
		?>
		<div class="wrap">
		<h2><?php _e('Chat Settings', $this->translation_domain); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields('chat'); ?>

			<div id="chat_tab_pane" class="chat_tab_pane">
				<ul>
					<li><a href="#chat_default_panel"><span><?php _e('In post chat options', $this->translation_domain); ?></span></a></li>
					<li><a href="#chat_site_panel"><span><?php _e('Bottom corner chat', $this->translation_domain); ?></span></a></li>
					<li><a href="#chat_twitter_api_panel"><span><?php _e('Twitter API', $this->translation_domain); ?></span></a></li>
					<li><a href="#chat_facebook_api_panel"><span><?php _e('Facebook API', $this->translation_domain); ?></span></a></li>
					<li><a href="#chat_advanced_panel"><span><?php _e('Advanced', $this->translation_domain); ?></span></a></li>
				</ul>

				<div id="chat_default_panel" class="chat_panel current">
					<p class="info"><?php _e('Default options for in post chat boxes.', $this->translation_domain); ?></p>
					<fieldset>
						<legend><?php _e('General', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td><label for="chat_sound"><?php _e('Sound', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_sound" name="chat_default[sound]" >
										<option value="enabled" <?php print ($this->get_option('sound', 'enabled') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('sound', 'enabled') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Play sound when a new message is received?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_avatar"><?php _e('Avatar', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_avatar" name="chat_default[avatar]" >
										<option value="enabled" <?php print ($this->get_option('avatar', 'enabled') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('avatar', 'enabled') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Display the user's avatar with the message?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_emoticons"><?php _e('Emoticons', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_emoticons" name="chat_default[emoticons]" >
										<option value="enabled" <?php print ($this->get_option('emoticons', 'disabled') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('emoticons', 'disabled') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Display emoticons bar?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_date_show"><?php _e('Show date', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_date_show" name="chat_default[date_show]" >
										<option value="enabled" <?php print ($this->get_option('date_show', 'disabled') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('date_show', 'disabled') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Display date the message was sent?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_time_show"><?php _e('Show time', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_time_show" name="chat_default[time_show]" >
										<option value="enabled" <?php print ($this->get_option('time_show', 'disabled') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('time_show', 'disabled') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Display the time  the message was sent?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_width"><?php _e('Dimensions', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_width" name="chat_default[width]" value="<?php print $this->get_option('width', '100%'); ?>" class="size" size="5" />W<br />
									<input type="text" id="chat_height" name="chat_default[height]" value="<?php print $this->get_option('height', '425px'); ?>" class="size" size="5" />H
								</td>
								<td class="info"><?php _e("Dimensions of the chat box. Include px, em, etc.", $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e('Colors', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td style="width: 35%"><label for="chat_background_color"><?php _e('Background', $this->translation_domain); ?></label></td>
								<td  style="width: 15%">
									<input type="text" id="chat_background_color" name="chat_default[background_color]" value="<?php print $this->get_option('background_color', '#ffffff'); ?>" class="color" size="7" />
									<div class="color" id="chat_background_color_panel"></div>
								</td>
								<td  style="width: 50%" class="info"><?php _e("Chat box background color", $this->translation_domain); ?></td>
							</tr>


							<tr>
								<td><label for="chat_background_row_area_color"><?php _e('Background Messages Area', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_background_row_area_color" name="chat_default[background_row_area_color]"
										value="<?php print $this->get_option('background_row_area_color', '#F9F9F9'); ?>" class="color" size="7" />
									<div class="color" id="chat_background_row_area_color_panel"></div>
								</td>
								<td class="info"><?php _e("Background color of the message area", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_background_row_color"><?php _e('Row items background', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_background_row_color" name="chat_default[background_row_color]"
										value="<?php print $this->get_option('background_row_color', '#FFFFFF'); ?>" class="color" size="7" />
									<div class="color" id="chat_background_row_color_panel"></div>
								</td>
								<td class="info"><?php _e("Background color of the message row items", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_row_border_color"><?php _e('Row items border', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_row_border_color" name="chat_default[row_border_color]"
										value="<?php print $this->get_option('row_border_color', '#CCCCCC'); ?>" class="color" size="7" />
									<div class="color" id="chat_row_border_color_panel"></div>
								</td>
								<td class="info"><?php _e("Border color of the message row items", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_row_border_width"><?php _e('Row items border width', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_row_border_width" name="chat_default[row_border_width]"
										value="<?php print $this->get_option('row_border_width', '1px'); ?>" size="7" />
								</td>
								<td class="info"><?php _e("Border width of the message row items", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_row_spacing"><?php _e('Row items spacing', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_row_spacing" name="chat_default[row_spacing]"
										value="<?php print $this->get_option('row_spacing', '5px'); ?>" size="7" />
								</td>
								<td class="info"><?php _e("Spacing between row items", $this->translation_domain); ?></td>
							</tr>



							<tr>
								<td><label for="chat_background_highlighted_color"><?php _e('Highlighted Background', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_background_highlighted_color" name="chat_default[background_highlighted_color]" value="<?php print $this->get_option('background_highlighted_color', '#FFE9AB'); ?>" class="color" size="7" />
									<div class="color" id="chat_background_highlighted_color_panel"></div>
								</td>
								<td class="info"><?php _e("Chat box background color when there is a new message", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_date_color"><?php _e('Date', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_date_color" name="chat_default[date_color]" value="<?php print $this->get_option('date_color', '#6699CC'); ?>" class="color" size="7" />
									<div class="color" id="chat_date_color_panel"></div>
								</td>
								<td class="info"><?php _e("Date and time background color", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_name_color"><?php _e('Name', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_name_color" name="chat_default[name_color]" value="<?php print $this->get_option('name_color', '#666666'); ?>" class="color" size="7" />
									<div class="color" id="chat_name_color_panel"></div>
								</td>
								<td class="info"><?php _e("Name background color", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_moderator_name_color"><?php _e('Moderator Name', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_moderator_name_color" name="chat_default[moderator_name_color]" value="<?php print $this->get_option('moderator_name_color', '#6699CC'); ?>" class="color" size="7" />
									<div class="color" id="chat_moderator_name_color_panel"></div>
								</td>
								<td class="info"><?php _e("Moderator Name background color", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_text_color"><?php _e('Text', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_text_color" name="chat_default[text_color]" value="<?php print $this->get_option('text_color', '#000000'); ?>" class="color" size="7" />
									<div class="color" id="chat_text_color_panel"></div>
								</td>
								<td class="info"><?php _e("Text color", $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e('Fonts', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td><label for="chat_font"><?php _e('Font', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_font" name="chat_default[font]" class="font" >
									<?php foreach ($this->fonts_list as $font_name => $font) { ?>
										<option value="<?php print $font; ?>" <?php print ($this->get_option('font', '') == $font)?'selected="selected"':''; ?>" ><?php print $font_name; ?></option>
									<?php } ?>
									</select>
								</td>
								<td class="info"><?php _e("Chat box font", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_font_size"><?php _e('Font size', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_font_size" name="chat_default[font_size]" class="font_size" >
									<?php for ($font_size=8; $font_size<21; $font_size++) { ?>
										<option value="<?php print $font_size; ?>" <?php print ($this->get_option('font_size', '12') == $font_size)?'selected="selected"':''; ?>" ><?php print $font_size; ?></option>
									<?php } ?>
									</select> px
								</td>
								<td class="info"><?php _e("Chat box font size", $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e('Logs', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td><label for="chat_log_creation"><?php _e('Creation', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_log_creation" name="chat_default[log_creation]" >
										<option value="enabled" <?php print ($this->get_option('log_creation', 'enabled') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('log_creation', 'enabled') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Log chat messages?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_log_display"><?php _e('Display', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_log_display" name="chat_default[log_display]" >
										<option value="enabled" <?php print ($this->get_option('log_display', 'enabled') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('log_display', 'enabled') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Display chat logs?", $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e("Number of messages to display on initial page load", $this->translation_domain); ?></legend>
						<table border="0" cellpadding="4" cellspacing="0">
						<tr>
							<td><label for="chat_log_limit"><?php _e("Number of Messages", $this->translation_domain); ?></label></td>
							<td>
								<input type="text" id="chat_log_limit" name="chat_default[log_limit]" size="5"
									value="<?php print ($this->get_option('log_limit', '')); ?>" />
							</td>
							<td class="info"><?php _e("set empty for all", $this->translation_domain); ?></td>
						</tr>
						<tr><td colspan="3"><?php _e("Note: This does not truncate the message in the database.", $this->translation_domain); ?></td></tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e('Authentication', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td valign="top" style="width: 25%"><label for="chat_login_options"><?php _e('Login options', $this->translation_domain); ?></label></td>
								<td style="width: 35%">
									<lable><input type="checkbox" id="chat_login_options_current_user" name="chat_default[login_options][]" class="chat_login_options" value="current_user" <?php print (in_array('current_user', $this->get_option('login_options', array('current_user'))) > 0)?'checked="checked"':''; ?> /> <?php _e('WordPress user', $this->translation_domain); ?></label><br/>
									<?php if (is_multisite()) { ?>
									<lable><input type="checkbox" id="chat_login_options_network_user" name="chat_default[login_options][]" class="chat_login_options" value="network_user" <?php print (in_array('network_user', $this->get_option('login_options', array('current_user'))) > 0)?'checked="checked"':''; ?> /> <?php _e('Network user', $this->translation_domain); ?></label><br/>
									<?php } ?>
									<lable><input type="checkbox" id="chat_login_options_public_user" name="chat_default[login_options][]" class="chat_login_options" value="public_user" <?php print (in_array('public_user', $this->get_option('login_options', array('current_user'))) > 0)?'checked="checked"':''; ?> /> <?php _e('Public user', $this->translation_domain); ?></label><br/>
									<lable><input type="checkbox" id="chat_login_options_twitter" name="chat_default[login_options][]" class="chat_login_options" value="twitter" <?php print (!$this->is_twitter_setup())?'disabled="disabled"':''; ?> <?php print (in_array('twitter', $this->get_option('login_options', array('current_user'))) > 0)?'checked="checked"':''; ?> /> <?php _e('Twitter', $this->translation_domain); ?></label><br/>
									<lable><input type="checkbox" id="chat_login_options_facebook" name="chat_default[login_options][]" class="chat_login_options" value="facebook" <?php print (!$this->is_facebook_setup())?'disabled="disabled"':''; ?> <?php print (in_array('facebook', $this->get_option('login_options', array('current_user'))) > 0)?'checked="checked"':''; ?> /> <?php _e('Facebook', $this->translation_domain); ?></label><br/>
								</td>
								<td class="info" style="width: 45%"><?php _e("Authentication methods users can use. <strong>If only 'WordPress user' is selected chat will be hidden from non-authenticated users.</strong>", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td valign="top"><label for="chat_moderator_roles"><?php _e('Moderator roles', $this->translation_domain); ?></label></td>
								<td>
									<?php
									//foreach (get_editable_roles() as $role => $details) {
									//	$name = translate_user_role($details['name'] );
									global $wp_roles;
									foreach ($wp_roles->role_names as $role => $name) {
									?>
									<lable><input type="checkbox" id="chat_moderator_roles_<?php print $role; ?>" name="chat_default[moderator_roles][]" class="chat_moderator_roles" value="<?php print $role; ?>" <?php print (in_array($role, $this->get_option('moderator_roles', array('administrator','editor','author'))) > 0)?'checked="checked"':''; ?> /> <?php _e($name, $this->translation_domain); ?></label><br/>
									<?php
									}
									?>
								</td>
								<td class="info"><?php _e("Select which roles are moderators", $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>
				</div>

				<div id="chat_site_panel" class="chat_panel current">
					<p class="info"><?php _e('Options for the bottom corner chat', $this->translation_domain); ?></p>
					<fieldset>
						<legend><?php _e('Main', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td><label for="chat_site_1"><?php _e('Show', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_site_1" name="chat_site[site]" >
										<option value="enabled" <?php print ($this->get_option('site', 'disabled', 'site') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('site', 'disabled', 'site') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Display bottom corner chat?", $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e('General', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">

							<tr>
								<td><label for="chat_sound_1"><?php _e('Sound', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_sound_1" name="chat_site[sound]" >
										<option value="enabled" <?php print ($this->get_option('sound', 'enabled', 'site') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('sound', 'enabled', 'site') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Play sound when a new message is received?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_avatar_1"><?php _e('Avatar', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_avatar_1" name="chat_site[avatar]" >
										<option value="enabled" <?php print ($this->get_option('avatar', 'enabled', 'site') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('avatar', 'enabled', 'site') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Display the user's avatar with the message?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_emoticons_1"><?php _e('Emoticons', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_emoticons_1" name="chat_site[emoticons]" >
										<option value="enabled" <?php print ($this->get_option('emoticons', 'enabled', 'site') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('emoticons', 'enabled', 'site') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Display emoticons bar?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_date_show_1"><?php _e('Show date', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_date_show_1" name="chat_site[date_show]" >
										<option value="enabled" <?php print ($this->get_option('date_show', 'enabled', 'site') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('date_show', 'enabled', 'site') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Display date the message was sent?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_time_show_1"><?php _e('Show time', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_time_show_1" name="chat_site[time_show]" >
										<option value="enabled" <?php print ($this->get_option('time_show', 'enabled', 'site') == 'enabled')?'selected="selected"':''; ?>><?php _e('Enabled', $this->translation_domain); ?></option>
										<option value="disabled" <?php print ($this->get_option('time_show', 'enabled', 'site') == 'disabled')?'selected="selected"':''; ?>><?php _e('Disabled', $this->translation_domain); ?></option>
									</select>
								</td>
								<td class="info"><?php _e("Display the time  the message was sent?", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_width_1"><?php _e('Dimensions', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_width_1" name="chat_site[width]" value="<?php print $this->get_option('width', '', 'site'); ?>" class="size" size="5" /> W<br />
									<input type="text" id="chat_height_1" name="chat_site[height]" value="<?php print $this->get_option('height', '', 'site'); ?>" class="size" size="5" /> H
								</td>
								<td class="info"><?php _e("Dimensions of the chat box. Include px, em, etc.", $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e('Colors', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td><label for="chat_border_color_1"><?php _e('Border', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id=chat_border_color_1 name="chat_site[border_color]" value="<?php print $this->get_option('border_color', '#4b96e2', 'site'); ?>" class="color" size="7" />
									<div class="color" id="chat_border_color_1_panel"></div>
								</td>
								<td class="info"><?php _e("Chat box border color", $this->translation_domain); ?></td>
							</tr>




							<tr>
								<td><label for="chat_background_row_area_color"><?php _e('Background Messages Area', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_background_row_area_color" name="chat_site[background_row_area_color]"
										value="<?php print $this->get_option('background_row_area_color', '#F9F9F9', 'site'); ?>" class="color" size="7" />
									<div class="color" id="chat_background_row_area_color_panel"></div>
								</td>
								<td class="info"><?php _e("Background color of the message area", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_background_row_color"><?php _e('Row items background', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_background_row_color" name="chat_site[background_row_color]"
										value="<?php print $this->get_option('background_row_color', '#FFFFFF', 'site'); ?>" class="color" size="7" />
									<div class="color" id="chat_background_row_color_panel"></div>
								</td>
								<td class="info"><?php _e("Background color of the message row items", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_row_border_color"><?php _e('Row items border', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_row_border_color" name="chat_site[row_border_color]"
										value="<?php print $this->get_option('row_border_color', '#CCCCCC', 'site'); ?>" class="color" size="7" />
									<div class="color" id="chat_row_border_color_panel"></div>
								</td>
								<td class="info"><?php _e("Border color of the message row items", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_row_border_width"><?php _e('Row items border width', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_row_border_width" name="chat_site[row_border_width]"
										value="<?php print $this->get_option('row_border_width', '1px', 'site'); ?>" size="7" />
								</td>
								<td class="info"><?php _e("Border width of the message row items", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_row_spacing"><?php _e('Row items spacing', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_row_spacing" name="chat_site[row_spacing]"
										value="<?php print $this->get_option('row_spacing', '5px', 'site'); ?>" size="7" />
								</td>
								<td class="info"><?php _e("Spacing between row items", $this->translation_domain); ?></td>
							</tr>




							<tr>
								<td><label for="chat_border_highlighted_color_1"><?php _e('Highlighted Border', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id=chat_border_highlighted_color_1 name="chat_site[border_highlighted_color]" value="<?php print $this->get_option('border_highlighted_color', '#ff8400', 'site'); ?>" class="color" size="7" />
									<div class="color" id="chat_border_highlighted_color_1_panel"></div>
								</td>
								<td class="info"><?php _e("Chat box border color when there is a new message", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_background_color_1"><?php _e('Background', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_background_color_1" name="chat_site[background_color]" value="<?php print $this->get_option('background_color', '#ffffff', 'site'); ?>" class="color" size="7" />
									<div class="color" id="chat_background_color_1_panel"></div>
								</td>
								<td class="info"><?php _e("Chat box background color", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_date_color"><?php _e('Date', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_date_color_1" name="chat_site[date_color]" value="<?php print $this->get_option('date_color', '#6699CC', 'site'); ?>" class="color" size="7" />
									<div class="color" id="chat_date_color_1_panel"></div>
								</td>
								<td class="info"><?php _e("Date and time background color", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_name_color"><?php _e('Name', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_name_color_1" name="chat_site[name_color]" value="<?php print $this->get_option('name_color', '#666666', 'site'); ?>" class="color" size="7" />
									<div class="color" id="chat_name_color_1_panel"></div>
								</td>
								<td class="info"><?php _e("Name background color", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_moderator_name_color"><?php _e('Moderator Name', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_moderator_name_color_1" name="chat_site[moderator_name_color]" value="<?php print $this->get_option('moderator_name_color', '#6699CC', 'site'); ?>" class="color" size="7" />
									<div class="color" id="chat_moderator_name_color_1_panel"></div>
								</td>
								<td class="info"><?php _e("Moderator Name background color", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_text_color"><?php _e('Text', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_text_color_1" name="chat_site[text_color]" value="<?php print $this->get_option('text_color', '#000000', 'site'); ?>" class="color" size="7" />
									<div class="color" id="chat_text_color_1_panel"></div>
								</td>
								<td class="info"><?php _e("Text color", $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e('Fonts', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td><label for="chat_font_1"><?php _e('Font', $this->translation_domain); ?></label></td>
								<td>
									<select id="chat_font_1" name="chat_site[font]" class="font" >
									<?php foreach ($this->fonts_list as $font_name => $font) { ?>
										<option value="<?php print $font; ?>" <?php print ($this->get_option('font', '', 'site') == $font)?'selected="selected"':''; ?>" ><?php print $font_name; ?></option>
									<?php } ?>
									</select>
								</td>
								<td class="info"><?php _e("Chat box font", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td><label for="chat_font_size_1"><?php _e('Font size', $this->translation_domain); ?></label></td>
								<td><select id="chat_font_size_1" name="chat_site[font_size]" class="font_size" >
									<?php for ($font_size=8; $font_size<21; $font_size++) { ?>
										<option value="<?php print $font_size; ?>" <?php print ($this->get_option('font_size', '12', 'site') == $font_size)?'selected="selected"':''; ?>" ><?php print $font_size; ?></option>
									<?php } ?>
									</select> px
								</td>
								<td class="info"><?php _e("Chat box font size", $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>
					<fieldset>
						<legend><?php _e("Number of messages to display on initial page load", $this->translation_domain); ?></legend>
						<table border="0" cellpadding="4" cellspacing="0">
						<tr>
							<td><label for="chat_log_limit"><?php _e("Number of Messages", $this->translation_domain); ?></label></td>
							<td>
								<input type="text" id="chat_log_limit" name="chat_site[log_limit]" size="5"
									value="<?php print ($this->get_option('log_limit', '', 'site')); ?>" />
							</td>
							<td class="info"><?php _e("set empty for all", $this->translation_domain); ?></td>
						</tr>
						<tr><td colspan="3"><?php _e("Note: This does not truncate the message in the database.", $this->translation_domain); ?></td></tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e('Authentication', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td valign="top" style="width: 25%"><label for="chat_login_options_1"><?php _e('Login options', $this->translation_domain); ?></label></td>
								<td style="width: 35%">
									<lable><input type="checkbox" id="chat_login_options_1_current_user" name="chat_site[login_options][]" class="chat_login_options" value="current_user" <?php print (in_array('current_user', $this->get_option('login_options', array('current_user'), 'site')) > 0)?'checked="checked"':''; ?> /> <?php _e('WordPress user', $this->translation_domain); ?></label><br/>
									<?php if (is_multisite()) { ?>
									<lable><input type="checkbox" id="chat_login_options_1_network_user" name="chat_site[login_options][]" class="chat_login_options" value="network_user" <?php print (in_array('network_user', $this->get_option('login_options', array('current_user'), 'site')) > 0)?'checked="checked"':''; ?> /> <?php _e('Network user', $this->translation_domain); ?></label><br/>
									<?php } ?>
									<lable><input type="checkbox" id="chat_login_options_1_public_user" name="chat_site[login_options][]" class="chat_login_options" value="public_user" <?php print (in_array('public_user', $this->get_option('login_options', array('current_user'), 'site')) > 0)?'checked="checked"':''; ?> /> <?php _e('Public user', $this->translation_domain); ?></label><br/>
									<lable><input type="checkbox" id="chat_login_options_1_twitter" name="chat_site[login_options][]" class="chat_login_options" value="twitter" <?php print (!$this->is_twitter_setup())?'disabled="disabled"':''; ?> <?php print (in_array('twitter', $this->get_option('login_options', array('current_user'), 'site')) > 0)?'checked="checked"':''; ?> /> <?php _e('Twitter', $this->translation_domain); ?></label><br/>
									<lable><input type="checkbox" id="chat_login_options_1_facebook" name="chat_site[login_options][]" class="chat_login_options" value="facebook" <?php print (!$this->is_facebook_setup())?'disabled="disabled"':''; ?> <?php print (in_array('facebook', $this->get_option('login_options', array('current_user'), 'site')) > 0)?'checked="checked"':''; ?> /> <?php _e('Facebook', $this->translation_domain); ?></label><br/>
								</td>
								<td class="info" style="width: 45%"><?php _e("Authentication methods users can use. <strong>If only 'WordPress user' is selected then chat will be hidden from non-authenticated users.</strong>", $this->translation_domain); ?></td>
							</tr>

							<tr>
								<td valign="top"><label for="chat_moderator_roles_1"><?php _e('Moderator roles', $this->translation_domain); ?></label></td>
								<td>
									<?php
									//foreach (get_editable_roles() as $role => $details) {
									//	$name = translate_user_role($details['name'] );
									global $wp_roles;
									foreach ($wp_roles->role_names as $role => $name) {
									?>
									<lable><input type="checkbox" id="chat_moderator_roles_1_<?php print $role; ?>" name="chat_site[moderator_roles][]" class="chat_moderator_roles" value="<?php print $role; ?>" <?php print (in_array($role, $this->get_option('moderator_roles', array('administrator','editor','author'), 'site')) > 0)?'checked="checked"':''; ?> /> <?php _e($name, $this->translation_domain); ?></label><br/>
									<?php
									}
									?>
								</td>
								<td class="info"><?php _e("Select which roles are moderators", $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>
				</div>

				<div id="chat_twitter_api_panel" class="chat_panel chat_auth_panel">
					<table border="0" cellpadding="4" cellspacing="0">
						<tr>
							<td><label for="chat_twitter_api_key"><?php _e('Consumer key', $this->translation_domain); ?></label></td>
							<td>
								<input type="text" id="chat_twitter_api_key" name="chat_default[twitter_api_key]" value="<?php print $this->get_option('twitter_api_key', ''); ?>" class="" size="40" />
							</td>
							<td class="info">
								<ol>
									<li><?php print sprintf(__('Register this site as an application on Twitter\'s <a target="_blank" href="%s">app registration page</a>', $this->translation_domain), "http://dev.twitter.com/apps/new"); ?></li>
									<li><?php _e('If you\'re not logged in, you can use your Twitter username and password', $this->translation_domain); ?></li>
			  						<li><?php _e('Your Application\'s Name will be what shows up after "via" in your twitter stream', $this->translation_domain); ?></li>
									<li><?php _e('Application Type should be set on Browser', $this->translation_domain); ?></li>
			   						<li><?php _e('The callback URL should be', $this->translation_domain); ?> <b><?php print get_bloginfo('url'); ?></b></li>
			   						<li><?php _e('Once you have registered your site as an application, you will be provided with @Anywhere API key.', $this->translation_domain); ?></li>
									<li><?php _e('Copy and paste them to the fields on the left', $this->translation_domain); ?></li>
								</ol>
							</td>
						</tr>
					</table>
				</div>

				<div id="chat_facebook_api_panel" class="chat_panel chat_auth_panel">
					<table border="0" cellpadding="4" cellspacing="0">
						<tr>
							<td><label for="chat_facebook_application_id"><?php _e('App id', $this->translation_domain); ?></label></td>
							<td>
								<input type="text" id="chat_facebook_application_id" name="chat_default[facebook_application_id]" value="<?php print $this->get_option('facebook_application_id', ''); ?>" class="" size="40" />
							</td>
							<td rowspan="2" class="info">
								<ol>
									<li><?php print sprintf(__('Register this site as an application on Facebook\'s <a target="_blank" href="%s">app registration page</a>', $this->translation_domain), 'http://www.facebook.com/developers/createapp.php'); ?></li>
									<li><?php _e('If you\'re not logged in, you can use your Facebook username and password', $this->translation_domain); ?></li>
			   						<li><?php _e('The site URL should be', $this->translation_domain); ?> <b><?php print get_bloginfo('url'); ?></b></li>
			   						<li><?php _e('Once you have registered your site as an application, you will be provided with a App ID and a App secret.', $this->translation_domain); ?></li>
									<li><?php _e('Copy and paste them to the fields on the left', $this->translation_domain); ?></li>
								</ol>
							</td>
						</tr>

						<tr>
							<td><label for="chat_facebook_application_secret"><?php _e('App secret', $this->translation_domain); ?></label></td>
							<td>
								<input type="text" id="chat_facebook_application_secret" name="chat_default[facebook_application_secret]" value="<?php print $this->get_option('facebook_application_secret', ''); ?>" class="" size="40" />
							</td>
						</tr>


						<tr>
							<td><label for="chat_facebook_active_in_site"><?php _e('Facebook Connect Used in Site? (e.g. Facebook Ultimate Connect)', $this->translation_domain); ?></label></td>
							<td>
								<label><input type="radio" id="chat_facebook_active_in_site" name="chat_default[facebook_active_in_site]" value="yes" <?php print ($this->get_option('facebook_active_in_site', 'no') == 'yes')?'checked="checked"':''; ?> class="" size="40" /> <?php _e('Yes', $this->translation_domain); ?></label>
								<label><input type="radio" id="chat_facebook_active_in_site" name="chat_default[facebook_active_in_site]" value="no" <?php print ($this->get_option('facebook_active_in_site', 'no') == 'no')?'checked="checked"':''; ?> class="" size="40" /> <?php _e('No', $this->translation_domain); ?></label>
							</td>
						</tr>
					</table>
				</div>

				<div id="chat_advanced_panel" class="chat_panel chat_advanced_panel">

					<fieldset>
						<legend><?php _e('Polling Interval', $this->translation_domain); ?></legend>
						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td><label for="chat_interval"><?php _e('Interval', $this->translation_domain); ?></label></td>
								<td>
									<input type="text" id="chat_interval" name="chat_default[interval]" value="<?php print $this->get_option('interval', 1); ?>" class="" size="2" /> (seconds)
								</td>
								<td class="info"><?php _e('Controls how often the chat polls for new messages.', $this->translation_domain); ?></td>
							</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e('WYSIWYG Chat button User Roles', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
						<tr>
							<td style="width: 40%">
								<?php
								//foreach (get_editable_roles() as $role => $details) {
								//	$name = translate_user_role($details['name'] );
								global $wp_roles;
								foreach ($wp_roles->role_names as $role => $name) {
								?>
								<lable><input type="checkbox" id="chat_tinymce_roles_<?php print $role; ?>" name="chat_default[tinymce_roles][]" class="chat_tinymce_roles" value="<?php print $role; ?>" <?php print (in_array($role, $this->get_option('tinymce_roles', array('administrator'))) > 0)?'checked="checked"':''; ?> /> <?php _e($name, $this->translation_domain); ?></label><br/>
								<?php
								}
								?>
							</td>
							<td style="width: 60%" class="info"><?php _e("Select which roles will see the Chat WYSIWYG button on the Post editor screen. Note the user must also have Edit capabilities for the Post type.", $this->translation_domain); ?></td>
						</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><?php _e('WYSIWYG Chat button Post Types', $this->translation_domain); ?></legend>

						<table border="0" cellpadding="4" cellspacing="0">
						<tr>
							<td style="width: 40%">
								<?php
								foreach ((array) get_post_types( array( 'show_ui' => true ), 'name' ) as $post_type => $details) {
								?>
								<lable><input type="checkbox" id="chat_tinymce_post_types_<?php print $post_type; ?>" name="chat_default[tinymce_post_types][]" class="chat_tinymce_roles" value="<?php print $post_type; ?>" <?php print (in_array($post_type, $this->get_option('tinymce_post_types', array('post','page'))) > 0)?'checked="checked"':''; ?> /> <?php echo $details->labels->name; ?></label><br/>
								<?php
								}
								?>
							</td>
							<td style="width: 60%" class="info"><?php _e("Select which Post Types will have the Chat WYSIWYG button available.", $this->translation_domain); ?></td>
						</tr>
						</table>
					</fieldset>

				</div>
			</div>

			<input type="hidden" name="page_options" value="chat_default,chat_site" />

			<p class="submit"><input type="submit" name="Submit"
				value="<?php _e('Save Changes', $this->translation_domain) ?>" /></p>
		</form>
		</div>
		<?php
	}

	/**
	 * Title filter
	 *
	 * @see		http://codex.wordpress.org/Function_Reference/wp_head
	 *
	 * @global	object	$current_user
	 * @global	object	$post
	 * @global	array	$chat_localized
	 * @param	string	$title
	 */
	function wp_head() {
		global $current_user, $post, $chat_localized;

		get_currentuserinfo();

		if ( !in_array('subscriber',$current_user->roles) ) {
			$vip = 'yes';
		} else {
			$vip = 'no';
		}

		$chat_sounds = get_user_meta($current_user->ID, 'chat_sounds', 'enabled');
		if (empty($chat_sounds)) {
			$chat_sounds = $this->get_option('sounds', "enabled");
		}

		if (!is_array($chat_localized)) {
			$chat_localized = array();
		}

		$chat_localized["url"] = site_url()."/wp-admin/admin-ajax.php";
		$chat_localized["plugin_url"] = plugins_url("/", __FILE__);
		$chat_localized["facebook_text_sign_out"] = __('Sign out of Facebook', $this->translation_domain);
		$chat_localized["twitter_text_sign_out"] = __('Sign out of Twitter', $this->translation_domain);
		$chat_localized["please_wait"] = __('Please wait...', $this->translation_domain);

		$chat_localized["minimize"] = __('Minimize', $this->translation_domain);
		$chat_localized["minimize_button"] = plugins_url('/images/16-square-blue-remove.png', __FILE__);
		$chat_localized["maximize"] = __('Maximize', $this->translation_domain);
		$chat_localized["maximize_button"] = plugins_url('/images/16-square-green-add.png', __FILE__);

		$chat_localized["interval"] = $this->get_option('interval', 1);

		if ( is_user_logged_in() ) {
			$chat_localized['name'] = $current_user->display_name;
			$chat_localized['vip'] = $vip;
			$chat_localized['sounds'] = $chat_sounds;
			$chat_localized['post_id'] = $post->ID;
		} else {
			$chat_localized['name'] = "";
			$chat_localized['vip'] = false;
			$chat_localized['sounds'] = "enabled";
			$chat_localized['post_id'] = $post->ID;
		}

		if ($this->get_option('twitter_api_key') != '') {
			$chat_localized["twitter_active"] = true;
			wp_enqueue_script('twitter', 'http://platform.twitter.com/anywhere.js?id='.$this->get_option('twitter_api_key').'&v=1');
		} else {
			$chat_localized["twitter_active"] = false;
		}

		if ($this->get_option('facebook_application_id') != '') {
			$chat_localized["facebook_active"] = true;
			$chat_localized["facebook_app_id"] = $this->get_option('facebook_application_id');
			if ($this->get_option('facebook_active_in_site') != 'yes') {
				$locale = get_locale();

				// We use 'facebook-all' to match our Ultimate Facebook plugin which enques the same script. Prevents enque duplication
				if (is_ssl()) {
					wp_enqueue_script('facebook-all', 'https://connect.facebook.net/'. $locale .'/all.js');
				} else {
					wp_enqueue_script('facebook-all', 'http://connect.facebook.net/'. $locale .'/all.js');
				}
			}
		} else {
			$chat_localized["facebook_active"] = false;
		}

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-cookie');
		wp_enqueue_script('chat_js');

		if ($this->get_option('site', 'enabled', 'site') == 'enabled') {
			$atts = array(
				'id' => 1,
				'sound' => $this->get_option('sound', 'enabled', 'site'),
				'avatar' => $this->get_option('avatar', 'enabled', 'site'),
				'emoticons' => $this->get_option('emoticons', 'enabled', 'site'),
				'date_show' => $this->get_option('date_show', 'disabled', 'site'),
				'time_show' => $this->get_option('time_show', 'disabled', 'site'),
				'width' => $this->get_option('width', '', 'site'),
				'height' => $this->get_option('height', '', 'site'),
				'background_color' => $this->get_option('background_color', '#ffffff', 'site'),
				'date_color' => $this->get_option('date_color', '#6699CC', 'site'),
				'name_color' => $this->get_option('name_color', '#666666', 'site'),
				'moderator_name_color' => $this->get_option('moderator_name_color', '#6699CC', 'site'),
				'text_color' => $this->get_option('text_color', '#000000', 'site'),
				'font' => $this->get_option('font', '', 'site'),
				'font_size' => $this->get_option('font_size', '', 'site'),
				'log_creation' => $this->get_option('log_creation', 'disabled', 'site'),
				'log_display' => $this->get_option('log_display', 'disabled', 'site'),
				'log_limit' => $this->get_option('log_limit', '', 'site'),
				'login_options' => join(',', $this->get_option('login_options', array('current_user'), 'site')),
				'moderator_roles' => join(',', $this->get_option('moderator_roles', array('administrator','editor','author'))),
			);

			if ((preg_match('/current_user/', $atts['login_options']) > 0)
			 && ($this->authenticate(preg_split('/,/', $atts['login_options'])) != 1)) {
				if ((preg_match('/public_user/', $atts['login_options']) == 0)
				 && (preg_match('/facebook/', $atts['login_options']) == 0)
				 && (preg_match('/twitter/', $atts['login_options']) == 0)) {
					;
				} else {
					$this->process_shortcode($atts);

				}
			}
		}
	}

	/**
	 * Check the post for the short code and mark it
	 *
	 * @deprecated	No longer relevant with site wide chat as well
	 */
	function post_check($post_ID) {
		$post = get_post($post_ID);
		if ( $post->post_content != str_replace('[chat', '', $post->post_content) ) {
			update_post_meta($post_ID, '_has_chat', 'yes');
		} else {
			delete_post_meta($post_ID, '_has_chat');
		}
	}

	/**
	 * Handle profile update
	 *
	 * @see		http://codex.wordpress.org/Function_Reference/wp_redirect
	 *
	 * @global	object	$current_user
	 * @param	string	$location
	 * @return	string	$location
	 */
	function profile_process($location) {
		global $current_user;
		if ( !empty( $_GET['user_id'] ) ) {
			$uid = $_GET['user_id'];
		} else {
			$uid = $current_user->ID;
		}
		if ( !empty( $_POST['chat_sounds'] ) ) {
			update_usermeta( $uid, 'chat_sounds', $_POST['chat_sounds'] );
		}
		return $location;
	}

	/**
	 * Add sound preferences to user profile
	 *
	 * @global	object	$current_user
	 */
	function profile() {
		global $current_user;

		if (!empty( $_GET['user_id'])) {
			$uid = $_GET['user_id'];
		} else {
			$uid = $current_user->ID;
		}

		$chat_sounds = get_user_meta( $uid, 'chat_sounds' );
		?>
	    <h3><?php _e('Chat Settings', $this->translation_domain); ?></h3>

	    <table class="form-table">
		    <tr>
		        <th><label for="chat_sounds"><?php _e('Chat sounds', $this->translation_domain); ?></label></th>
		        <td>
		            <select name="chat_sounds" id="chat_sounds">
		            	<option value="enabled"<?php if ( $chat_sounds == 'enabled' ) { echo ' selected="selected" '; } ?>><?php _e('Enabled', $this->translation_domain); ?></option>
		            	<option value="disabled"<?php if ( $chat_sounds == 'disabled' ) { echo ' selected="selected" '; } ?>><?php _e('Disabled', $this->translation_domain); ?></option>
		            </select>
		        </td>
		    </tr>
	    </table>
	    <?php
	}

	/**
	 * Output CSS
	 */
	function output_css() {
		echo '<link rel="stylesheet" href="' . plugins_url('/css/style.css', __FILE__) . '" type="text/css" />';
	}

	/**
	 * Authenticate user with Facebook
	 *
	 * We will also set the appropriate cookies
	 *
	 * @uses	get_facebook_cookie()
	 * @return	boolean					Is the user authenticated. true or false
	 */
	function authenticate_facebook() {

		// Include the Facebook OAuth 2.0 PHP-SDK
		if (!class_exists('Facebook')) {
			require_once( dirname(__FILE__) . '/lib/facebook-php-sdk/facebook.php');
		}
		$fb_app_id 		= $this->get_option('facebook_application_id', '');
		$fb_app_secret 	= $this->get_option('facebook_application_secret', '');

		if ((!empty($fb_app_id)) && (!empty($fb_app_secret))) {
			$facebook = new Facebook(array(
				'appId'  => $fb_app_id,
				'secret' => $fb_app_secret
			));
			$user = $facebook->getUser();
			//echo "user<pre>"; print_r($user); echo "</pre>";

			if ($user) {
				try {
					// Proceed knowing you have a logged in user who's authenticated.
			    	$fb_user_profile = $facebook->api('/me');
					if ($fb_user_profile) {
						//echo "fb_user_profile<pre>"; print_r($fb_user_profile); echo "</pre>";

						$fb_user_id = $fb_user_profile['id'];
						//echo "fb_user_id=[". $fb_user_id ."]<br />";

						$fb_user_name = $fb_user_profile['name'];
						//echo "fb_user_name=[". $fb_user_name ."]<br />";

						$fb_user_image = "http://graph.facebook.com/". $fb_user_id ."/picture";
						//echo "fb_user_image=[". $fb_user_image ."]<br />";

						$existing[] = 'facebook';

						setcookie('chat_stateless_user_type_104', join(',', $existing), time()+3600*24*7, '/');
						setcookie('chat_stateless_user_name_facebook', $fb_user_name, time()+3600*24*7, '/');
						setcookie('chat_stateless_user_image_facebook', $fb_user_image, time()+3600*24*7, '/');

						$_COOKIE['chat_stateless_user_type_104'] = join(',', $existing);
						$_COOKIE['chat_stateless_user_name_facebook'] = $fb_user_name;
						$_COOKIE['chat_stateless_user_image_facebook'] = $fb_user_image;
						return true;


					}
			  	} catch (FacebookApiException $e) {
					//echo "e<pre>"; print_r($e); echo "</pre>";
			    	//error_log($e);
			    	$user = null;
					return false;
			  	}
			}
		}
		return false;
	}

	/**
	 * Connect to the remote server and get the content
	 *
	 * @param	string	$scheme		Scheme
	 * @param	string	$host		Host
	 * @param	int	$port		Port
	 * @param	string	$filename	File name
	 *
	 * @return 	string			Response body
	 */
	function file_get_contents($scheme = '', $host, $port, $filename) {
		$response = wp_remote_get( "{$scheme}{$host}{$filename}" );
		if ( is_wp_error( $response ) || empty($response['body']) ) {
			if ($scheme == "https://") {
				$scheme = "ssl://";
			}
			$fp = fsockopen("{$scheme}{$host}", $port, $errno, $errstr, 30);
			if (!$fp) {
				trigger_error("$errstr ($errno)", E_USER_WARNING);
			} else {
				$out = "GET {$filename} HTTP/1.1\r\n";
				$out .= "Host: {$host}\r\n";
				$out .= "Connection: Close\r\n\r\n";
				fwrite($fp, $out);
				$content = "";
				$data = "";
				$content_found = false;
				while (!feof($fp)) {
					$data .= fgets($fp, 128);
				}
				fclose($fp);
			}
			$data_parts = preg_split('/(\n\n|\r\r|\r\n\r\n)/', $data);
			$content = $data_parts[1];
		} else {
			$content = $response['body'];
		}

		return $content;
	}

	/**
	 * Validate and return the Facebook cookie payload
	 *
	 * @see		http://developers.facebook.com/docs/guides/web#login
	 */
	function get_facebook_cookie() {
		$fb_app_id 		= $this->get_option('facebook_application_id', '');
		$fb_app_secret 	= $this->get_option('facebook_application_secret', '');

		if ((empty($fb_app_id)) || (empty($fb_app_secret)))
			return;

		$facebook = new Facebook(array(
		  'appId'  => $fb_app_id,
		  'secret' => $fb_app_secret
		));

		if (!isset($_COOKIE['fbsr_' . $app_id]))
			return;

		$args = array();
		parse_str(trim($_COOKIE['fbsr_' . $app_id], '\\"'), $args);
		ksort($args);
		$payload = '';

		foreach ($args as $key => $value) {
			if ($key != 'sig') {
				$payload .= $key . '=' . $value;
			}
		}

		if (md5($payload . $application_secret) != $args['sig']) {
			return null;
		}
		return $args;
	}

	function get_facebook_cookie_old1() {
		$app_id = $this->get_option('facebook_application_id', '');
		$application_secret = $this->get_option('facebook_application_secret', '');

		if (!isset($_COOKIE['fbsr_' . $app_id]))
			return;

		$args = array();
		parse_str(trim($_COOKIE['fbsr_' . $app_id], '\\"'), $args);
		ksort($args);
		$payload = '';

		foreach ($args as $key => $value) {
			if ($key != 'sig') {
				$payload .= $key . '=' . $value;
			}
		}

		if (md5($payload . $application_secret) != $args['sig']) {
			return null;
		}
		return $args;
	}

	/**
	 * Authenticate user
	 *
	 * @global	object	$current_user
	 * @param	array	$options	Login options
	 * @return	int			How the user was authenticated or false (1,2,3,4,5)
	 */
	function authenticate($options = array()) {
		global $current_user;

		// current user
		if (is_user_logged_in() && current_user_can('read')) {
			return 1;
		}

		// Network user
		else if (in_array('network_user', $options) && is_user_logged_in()) {
			return 2;
		}

		else if (isset($_COOKIE['chat_stateless_user_type_104'])) {
			if (in_array('facebook', $options)
				&& (preg_match('/facebook/', $_COOKIE['chat_stateless_user_type_104']) > 0 || $this->authenticate_facebook())) {
				return 3;
			}

			if (in_array('twitter', $options)
				&& isset($_COOKIE['chat_stateless_user_type_104']) && preg_match('/twitter/', $_COOKIE['chat_stateless_user_type_104']) > 0) {
					return 4;
			}
			if (in_array('public_user', $options)
				&& preg_match('/public_user/', $_COOKIE['chat_stateless_user_type_104']) > 0) {
				return 5;
			}
		}

		else if (isset($_COOKIE['chat_site_wide_state_104'])) {
			if (in_array('facebook', $options)
				&& (preg_match('/facebook/', $_COOKIE['chat_site_wide_state_104']) > 0 || $this->authenticate_facebook())) {
				return 3;
			}

			if (in_array('twitter', $options)
				&& isset($_COOKIE['chat_site_wide_state_104']) && preg_match('/twitter/', $_COOKIE['chat_stateless_user_type_104']) > 0) {
					return 4;
			}
			if (in_array('public_user', $options)
				&& preg_match('/public_user/', $_COOKIE['chat_site_wide_state_104']) > 0) {
				return 5;
			}
		}

		return false;
	}


	/**
	 * Get the user name
	 *
	 * So many loggin options, this will decide the display name of the user
	 *
	 * @global	object	$current_user
	 * @param	array	$options	Login options
	 * @return	string				User name or false
	 */
	function get_user_name($options = array()) {
		global $current_user;

		// current_user or network_user
		if ((is_user_logged_in() && current_user_can('read')) || (in_array('network_user', $options) && is_user_logged_in())) {
			return $current_user->display_name;
		}
		if (in_array('facebook', $options) && isset($_COOKIE['chat_stateless_user_type_104']) && (preg_match('/facebook/', $_COOKIE['chat_stateless_user_type_104']) > 0 || $this->authenticate_facebook())) {
			return $_COOKIE['chat_stateless_user_name_facebook'];
		}
		if (in_array('twitter', $options) && isset($_COOKIE['chat_stateless_user_type_104']) && preg_match('/twitter/', $_COOKIE['chat_stateless_user_type_104']) > 0) {
			return $_COOKIE['chat_stateless_user_name_twitter'];
		}
		if (in_array('public_user', $options) && isset($_COOKIE['chat_stateless_user_type_104']) && preg_match('/public_user/', $_COOKIE['chat_stateless_user_type_104']) > 0) {
			return $_COOKIE['chat_stateless_user_name_public_user'];
		}
		return false;
	}

	/**
	 * Do our magic in the footer and add the site wide chat
	 */
	function wp_footer() {
		global $chat_processed;

		$atts = array(
			'id' => 1,
			'sound' => $this->get_option('sound', 'enabled', 'site'),
			'avatar' => $this->get_option('avatar', 'enabled', 'site'),
			'emoticons' => $this->get_option('emoticons', 'enabled', 'site'),
			'date_show' => $this->get_option('date_show', 'disabled', 'site'),
			'time_show' => $this->get_option('time_show', 'disabled', 'site'),
			'width' => $this->get_option('width', '', 'site'),
			'height' => $this->get_option('height', '', 'site'),
			'background_color' => $this->get_option('background_color', '#ffffff', 'site'),
			'date_color' => $this->get_option('date_color', '#6699CC', 'site'),
			'name_color' => $this->get_option('name_color', '#666666', 'site'),
			'moderator_name_color' => $this->get_option('moderator_name_color', '#6699CC', 'site'),
			'text_color' => $this->get_option('text_color', '#000000', 'site'),
			'font' => $this->get_option('font', '', 'site'),
			'font_size' => $this->get_option('font_size', '', 'site'),
			'log_creation' => $this->get_option('log_creation', 'disabled', 'site'),
			'log_display' => $this->get_option('log_display', 'disabled', 'site'),
			'log_limit' => $this->get_option('log_limit', '', 'site'),
			'login_options' => join(',', $this->get_option('login_options', array('current_user'), 'site')),
			'moderator_roles' => join(',', $this->get_option('moderator_roles', array('administrator','editor','author'))),
		);
		//echo "atts<pre>"; print_r($atts); echo "</pre>";

		//echo "_COOKIE<pre>"; print_r($_COOKIE); echo "</pre>";
		if (isset($_COOKIE['chat_site_wide_state_104'])) {
			$bottom_corner_chat_state = $_COOKIE['chat_site_wide_state_104'];
		} else {
			$bottom_corner_chat_state = "closed";
		}

		if ($this->get_option('site', 'enabled', 'site') == 'enabled') {

			$show_chat_box = true;
			$site_login_options = $this->get_option('login_options', array('current_user'), 'site');
			$auth = $this->authenticate($site_login_options);
			if ($auth == 1) {			// current_user
				if (in_array('current_user', $site_login_options) === false) {
					$show_chat_box = false;
				}
			} else if ($auth == 2) {	//	network_user
				if (in_array('network_user', $site_login_options) === false) {
					$show_chat_box = false;
				}
			} else if ($auth == 3) {	//	facebook
				if (in_array('facebook', $site_login_options) === false) {
					$show_chat_box = false;
				}
			} else if ($auth == 4) {	//	twitter
				if (in_array('twitter', $site_login_options) === false) {
					$show_chat_box = false;
				}
			} else if ($auth == 5) {	//	public_user
				if (in_array('public_user', $site_login_options) === false) {
					$show_chat_box = false;
				}
			} else

			if ( (in_array('current_user', $site_login_options) !== false) && ($auth != 1) ) {
				if ((in_array('public_user', $site_login_options) === false)
			 		&& (in_array('facebook', $site_login_options) === false)
			 	    && (in_array('twitter', $site_login_options) === false)) {
					$show_chat_box = false;
				}
			}

			if ($show_chat_box == true) {
				$chat_processed = true;

				$width = $this->get_option('width', '', 'site');
				if (!empty($width)) {
					$width_str = 'width: '.$width;
					$width_style = '';
				} else {
					$width_style = ' free-width';
					$width_str = "300px";
				}
				echo '<style type="text/css">#chat-block-site.new_msg { background-color: '.$this->get_option('border_highlighted_color', '#4b96e2', 'site').' !important; }</style>';
				echo '<div id="chat-block-site" class="chat-block-site '. $bottom_corner_chat_state ." ". $width_style.'" style="'.$width_str.'; background-color: '.$this->get_option('border_color', '#4b96e2', 'site').';">';
				echo '<div id="chat-block-header" class="chat-block-header"><span class="chat-title-text">'.__('Chat', $this->translation_domain).'</span><span class="chat-prompt-text">'.__('Click here to chat!', $this->translation_domain).'</span>';
				echo '<img src="'.plugins_url('/images/16-square-green-add.png', __FILE__).'" alt="+" width="16" height="16" title="'.__('Maximize', $this->translation_domain).'" class="chat-toggle-button" id="chat-toggle-button" />';
				echo '</div>';
				echo '<div id="chat-block-inner" style="background: '.$this->get_option('background_color', '#ffffff', 'site').';">'.$this->process_shortcode($atts).'</div>';
				echo '</div>';
			}
		}

		if ($chat_processed) {

		}
	}

	/**
	 * Process short code
	 *
	 * @global	object	$post
	 * @global	array	$chat_localized	Localized strings and options
	 * @return	string					Content
	 */
	function process_shortcode($atts) {
		global $post, $chat_localized, $chat_processed;

		if (!defined('DONOTCACHEPAGE'))
			define('DONOTCACHEPAGE', '1');

		//echo "atts<pre>"; print_r($atts); echo "</pre>";
		//echo "atts-id=[". $atts['id'] ."]<br />";

		if (!isset($atts['id'])) return;

		if ($atts['id'] == 1) {
			$a = shortcode_atts(array(
				'id' 							=> 1,
				'sound' 						=> $this->get_option('sound', 'enabled', 'site'),
				'avatar' 						=> $this->get_option('avatar', 'enabled', 'site'),
				'emoticons' 					=> $this->get_option('emoticons', 'enabled', 'site'),
				'date_show' 					=> $this->get_option('date_show', 'disabled', 'site'),
				'time_show' 					=> $this->get_option('time_show', 'disabled', 'site'),
				'width' 						=> $this->get_option('width', '700px', 'site'),
				'height' 						=> $this->get_option('height', '425px', 'site'),
				'background_color' 				=> $this->get_option('background_color', '#ffffff', 'site'),

				'background_row_area_color'		=> $this->get_option('background_row_area_color', '#F9F9F9', 'site'),
				'background_row_color'			=> $this->get_option('background_row_color', '#FFFFFF', 'site'),
				'row_border_color'				=> $this->get_option('row_border_color', '#CCCCCC', 'site'),
				'row_border_width'				=> $this->get_option('row_border_width', '1px', 'site'),
				'row_spacing'					=> $this->get_option('row_spacing', '5px', 'site'),

				'background_highlighted_color' 	=> $this->get_option('background_highlighted_color', '#FFE9AB', 'site'),
				'date_color' 					=> $this->get_option('date_color', '#6699CC', 'site'),
				'name_color' 					=> $this->get_option('name_color', '#666666', 'site'),
				'moderator_name_color' 			=> $this->get_option('moderator_name_color', '#6699CC', 'site'),
				'text_color' 					=> $this->get_option('text_color', '#000000', 'site'),
				'font' 							=> $this->get_option('font', '', 'site'),
				'font_size' 					=> $this->get_option('font_size', '', 'site'),
				'log_creation' 					=> $this->get_option('log_creation', 'disabled', 'site'),
				'log_display' 					=> $this->get_option('log_display', 'disabled', 'site'),
				'log_limit' 					=> $this->get_option('log_limit', '', 'site'),
				'login_options' 				=> join(',', $this->get_option('login_options', array('current_user'), 'site')),
				'moderator_roles'	 			=> join(',', $this->get_option('moderator_roles', array('administrator','editor','author'), 'site')),
			), $atts);

		} else {
			$a = shortcode_atts(array(
				'id' 							=> 1,
				'sound' 						=> $this->get_option('sound', 'enabled'),
				'avatar' 						=> $this->get_option('avatar', 'enabled'),
				'emoticons' 					=> $this->get_option('emoticons', 'enabled'),
				'date_show' 					=> $this->get_option('date_show', 'disabled'),
				'time_show' 					=> $this->get_option('time_show', 'disabled'),
				'width' 						=> $this->get_option('width', '700px'),
				'height' 						=> $this->get_option('height', '425px'),
				'background_color' 				=> $this->get_option('background_color', '#ffffff'),

				'background_row_area_color'		=> $this->get_option('background_row_area_color', '#F9F9F9'),
				'background_row_color'			=> $this->get_option('background_row_color', '#FFFFFF'),
				'row_border_color'				=> $this->get_option('row_border_color', '#CCCCCC'),
				'row_border_width'				=> $this->get_option('row_border_width', '1px'),
				'row_spacing'					=> $this->get_option('row_spacing', '5px'),

				'background_highlighted_color' 	=> $this->get_option('background_highlighted_color', '#FFE9AB'),
				'date_color' 					=> $this->get_option('date_color', '#6699CC'),
				'name_color' 					=> $this->get_option('name_color', '#666666'),
				'moderator_name_color' 			=> $this->get_option('moderator_name_color', '#6699CC'),
				'text_color' 					=> $this->get_option('text_color', '#000000'),
				'font' 							=> $this->get_option('font', ''),
				'font_size' 					=> $this->get_option('font_size', ''),
				'log_creation' 					=> $this->get_option('log_creation', 'disabled'),
				'log_display' 					=> $this->get_option('log_display', 'disabled'),
				'log_limit' 					=> $this->get_option('log_limit', ''),
				'login_options' 				=> join(',', $this->get_option('login_options', array('current_user'))),
				'moderator_roles'	 			=> join(',', $this->get_option('moderator_roles', array('administrator','editor','author'))),
			), $atts);
		}

		//echo "login_options=[". $a['login_options'] ."]<br />";
		//echo "auth=[". $this->authenticate(preg_split('/,/', $a['login_options'])) ."]<br />";
		if ((preg_match('/current_user/', $a['login_options']) > 0)
		 && ($this->authenticate(preg_split('/,/', $a['login_options'])) != 1)) {
			if ((preg_match('/public_user/', $a['login_options']) == 0)
			 && (preg_match('/facebook/', $a['login_options']) == 0)
			 && (preg_match('/twitter/', $a['login_options']) == 0)) {
				return '';
			}
		}

		foreach ($a as $k=>$v) {
			$chat_localized[$k.'_'.$a['id']] = $v;
		}
		//echo "a<pre>"; print_r($a); echo "</pre>";

		$content = '';

		$font_style = "";

		if (!empty($a['font'])) {
			$font_style .= 'font-family: '.$a['font'].';';
		}
		if (!empty($a['font_size'])) {
			$font_style .= 'font-size: '.$a['font_size'].'px;';
		}

		$a['font_style'] = $font_style;

		if ($post && $post->ID) {
			$a['permalink'] = get_permalink($post->ID);
		} else {
			$a['permalink'] = "";
		}

		$chat_url = $_SERVER['REQUEST_URI'];
		$chat_url = rtrim($chat_url, "/");
		$chat_url = substr($chat_url, -8);

		if (empty($a['permalink']) || preg_match('/\?/', $a['permalink']) > 0) {
			$a['url_separator'] = "&";
		} else {
			$a['url_separator'] = "?";
		}

//		$a['smilies_list'] = array(':)', ':D', ':(', ':o', '8O', ':?', '8)', ':x', ':P', ':|', ';)', ':lol:', ':oops:', ':cry:', ':evil:', ':twisted:', ':roll:', ':!:', ':?:', ':idea:', ':arrow:', ':mrgreen:');

		$a['smilies_list'] = array(
				':smile:',
				':grin:',
				':sad:',
				':eek:',
				':shock:',
				':???:',
				':cool:',
				':mad:',
				':razz:',
				':neutral:',
				':wink:',
				':lol:',
				':oops:',
				':cry:',
				':evil:',
				':twisted:',
				':roll:',
				':!:',
				':?:',
				':idea:',
				':arrow:',
				':mrgreen:');

		$chat_localized['type_'.$a['id']] = $this->authenticate(preg_split('/,/', $a['login_options']));

		if ( $chat_localized['type_'.$a['id']] ) {
			$chat_localized['name_'.$a['id']] = $this->get_user_name(preg_split('/,/', $a['login_options']));
		}

		if (!$chat_localized['type_'.$a['id']] and $no_auth_template = locate_template('chat-inline-need-to-auth.php')) {
			require $no_auth_template;
		} else if ($chat_localized['type_'.$a['id']] and $auth_template = locate_template('chat-inline.php')) {
			require $auth_template;
		} else {
			$content .= $this->chat_box($a);
			$content .= $this->chat_wrap($a);
			$content .= $this->chat_area($a);

			if ( $chat_localized['type_'.$a['id']] ) {
				$content .= $this->chat_message_area($a);
			} else {
				if ($this->use_twitter_auth($a) or $this->use_facebook_auth($a) or $this->use_public_auth($a)) {
					if ($this->use_public_auth($a)) {
						$content .= '<div class="login-message">'.__('To get started just enter your email address and desired username',
							$this->translation_domain).': </div>';
						$content .= $this->chat_login_public($a, false);
					}
					if ($this->use_twitter_auth($a) or $this->use_facebook_auth($a)) {
						$content .= '<div class="login-message">'. __('Log in using your:', $this->translation_domain) .'</div>';
						$content .= '<div class="chat-login-wrap">';

						$content .= $this->chat_login_twitter($a, false);
						$content .= $this->chat_login_facebook($a, false);

						$content .= '</div>';
					}
				} else {
					$content .= '<div class="login-message"><strong>' . __('You must be logged in to participate in chats', $this->translation_domain) . '</strong></div>';
				}

				$content .= $this->chat_dummy_message_area($a, false);
			}

			if ( $a['log_display'] == 'enabled' &&  $a['id'] != 1) {
				$dates = $this->get_archives($a['id']);

				if ( $dates && is_array($dates) ) {
					$content .= '<br />';
					$content .= '<div class="chat-note"><p><strong>' . __('Chat Logs', $this->translation_domain) . '</strong></p></div>';
					$date_content = '';
					foreach ($dates as $date) {
						$date_content .= '<li><a class="chat-log-link" style="text-decoration: none;" href="' . $a['permalink'] . $a['url_separator'] . 'lid=' . $date->id . '">' . date_i18n(get_option('date_format').' '.get_option('time_format'), strtotime($date->start) + get_option('gmt_offset') * 3600, false) . ' - ' . date_i18n(get_option('date_format').' '.get_option('time_format'), strtotime($date->end) + get_option('gmt_offset') * 3600, false) . '</a>';	    		 	 	  	 	  
						if (isset($_GET['lid']) && $_GET['lid'] == $date->id) {
							$_POST['cid'] = $a['id'];
							$_POST['archived'] = array('yes', 'yes-deleted');
							$_POST['function'] = 'update';
							$_POST['since'] = strtotime($date->start);
							$_POST['end'] = strtotime($date->end);
							$_POST['background_row_area_color'] = $a['background_row_area_color'];
							$_POST['background_row_color'] = $a['background_row_color'];
							$_POST['row_border_color'] = $a['row_border_color'];
							$_POST['row_border_width'] = $a['row_border_width'];
							$_POST['row_spacing'] = $a['row_spacing'];
							$_POST['date_color'] = $a['date_color'];
							$_POST['name_color'] = $a['name_color'];
							$_POST['moderator_roles'] = $a['moderator_roles'];
							$_POST['moderator_name_color'] = $a['moderator_name_color'];
							$_POST['text_color'] = $a['text_color'];
							$_POST['date_show'] = $a['date_show'];
							$_POST['time_show'] = $a['time_show'];
							$_POST['avatar'] = $a['avatar'];

							//echo "a<pre>"; print_r($a); echo "</pre>";

							if ($this->is_moderator(preg_split('/,/', $a['moderator_roles'])))
								$chat_area_moderator = "chat-area-moderator";
							else
								$chat_area_moderator = "";

							$date_content .= '<div class="chat-wrap avatar-'.$a['avatar'].'" style="background-color: '.$a['background_row_area_color'].'; '.$a['font_style'].'"><div class="chat-area '. $chat_area_moderator .'" >';
							$date_content .= $this->process('yes');
							$date_content .= '</div></div>';
						}
						$date_content .= '</li>';
					}

					$content .= '<div id="chat-log-wrap-'.$a['id'].'" class="chat-log-wrap" style="background-color: '.$a['background_color'].'; '.$a['font_style'].'"><div id="chat-log-area-'.$a['id'].'" class="chat-log-area"><ul>' . $date_content . '</ul></div></div>';
				}
			}
			$content .= '<div class="chat-clear"></div></div>';
			$content .= '<style type="text/css">
				#chat-box-'.$a['id'].'.new_msg { background-color: '.$a['background_highlighted_color'].' !important; }
				#chat-box-'.$a['id'].' p { margin-bottom: '. $a['row_spacing'] .' !important; }
			</style>';
		}

		wp_localize_script('chat_js', 'chat_localized', $chat_localized);

		$chat_processed = 1;

		return $content;
	}

	function chat_message_area($a, $echo = false) {

		//$chat_status = get_post_meta($a['id'], "wpmudev_chat_status", true);
		$chat_status = $this->chatSessionStatusGet($a['id']);
		//if (!$chat_status) $chat_status = "open";

		$content = '';
		$content .= '<div class="chat-note"><p><strong>' . __('Message', $this->translation_domain) . '</strong></p></div>';

		$content .= '<form id="send-message-area"';

		if ($this->is_moderator(preg_split('/,/', $a['moderator_roles']))) {
			$content .= ' class="send-message-area-moderator" ';
		}
		$content .= '>';

		$content .= '<input type="hidden" name="chat-post-id" id="chat-post-id-'.$a['id'].'" value="'.$a['id'].'" class="chat-post-id" />';

		$content .= '<p class="chat-session-status-closed" style="text-align: center; font-weight:bold; ';	// note we don't close the " here!
		if (!$this->is_moderator(preg_split('/,/', $a['moderator_roles']))) {
			if ($chat_status != "open") {
				$content .= ' display: none; ';
			}
		}
		$content .= '">'. __('The Moderator has closed this chat session', $this->translation_domain) .'</p>';

		$content .= '<div class="chat-tool-bar-wrap"';
		if (!$this->is_moderator(preg_split('/,/', $a['moderator_roles']))) {
			if ($chat_status != "open") {
				$content .= ' style="display: none;" ';
			}
		}
		$content .= '><div class="chat-note">';

		if ($a['emoticons'] == 'enabled') {
			$content .= '<div id="chat-emoticons-list-'.$a['id'].'" class="chat-emoticons-list chat-tool-bar">';
			foreach ($a['smilies_list'] as $smilie) {
				$content .= convert_smilies($smilie);
			}
			$content .= '</div>';
		}

		$content .= '<div class="chat-clear"></div></div></div>';

		// $this->chat_send_form();

		$content .= '<div class="chat-send-wrap-container"';
		if (!$this->is_moderator(preg_split('/,/', $a['moderator_roles']))) {
			if ($chat_status != "open") {
				$content .= ' style="display:none" ';
			}
		}
		$content .= '>';

		$content .= '<div class="chat-clear"></div>';
		$content .= '<div class="chat-send-wrap">';
		$content .= '<textarea id="chat-send-'.$a['id'].'" class="chat-send"></textarea>';


		$content .= '<p class="chat-session-status-open" ';
		if ($chat_status != "open") {
			$content .= ' style="display:none" ';
		}
		$content .= '>' . __('"Enter" to send. Place code in between [code] tags', $this->translation_domain) . '</p>';
		$content .= '</div>';

		if ( $this->authenticate(preg_split('/,/', $a['login_options'])) > 2 ) {
			$content .= '<div class="chat-note"><input type="button" value="'. __('Logout', $this->translation_domain) .'" name="chat-logout-submit" class="chat-logout-submit" id="chat-logout-submit-'.$a['id'].'" /></div>';
		}
		$content .= '</div>';
		$content .= '<div class="chat-tool-bar-wrap"><div class="chat-note">';

		// $this->chat_after_toolbar();
		if ($this->is_moderator(preg_split('/,/', $a['moderator_roles']))) {
			$content .= '<div id="chat-log-actions-'.$a['id'].'" class="chat-log-actions chat-tool-bar">';

			if ($chat_status == "open") {
				$content .= '<input type="button" value="'. __('Close Chat', $this->translation_domain) .'" name="chat-session-close" class="chat-session-status" id="chat-session-close-'.$a['id'].'" />';
				$content .= '<input type="button" style="display: none;" value="'. __('Open Chat', $this->translation_domain) .'" name="chat-session-open" class="chat-session-status" id="chat-session-open-'.$a['id'].'" />';
			} else {
				$content .= '<input type="button" style="display: none;" value="'. __('Close Chat', $this->translation_domain) .'" name="chat-session-close" class="chat-session-status" id="chat-session-close-'.$a['id'].'" />';
				$content .= '<input type="button" value="'. __('Open Chat', $this->translation_domain) .'" name="chat-session-open" class="chat-session-status" id="chat-session-open-'.$a['id'].'" />';
			}

			if ($a['log_creation'] == 'enabled' && $a['id'] != 1) {
				$content .= '<input type="button" value="'. __('Archive', $this->translation_domain) .'" name="chat-archive" class="chat-archive" id="chat-archive-'.$a['id'].'" />';
			}
			$content .= '<input type="button" value="'. __('Clear', $this->translation_domain) .'" name="chat-clear" class="chat-clear" id="chat-clear-'.$a['id'].'" />';
			$content .= '</div>';
		}

		$content .= '<div class="chat-clear"></div></div></div>';

		$content .= '</form>';
		if ($echo) {
			echo $content;
		}
		return $content;
	}

	function chat_box($a, $echo = false) {
		global $post;

		$content = '';

		if ($this->is_moderator(preg_split('/,/', $a['moderator_roles'])))
			$chat_box_moderator = "chat-box-moderator";
		else
			$chat_box_moderator = "";

		if ($post) {
			$content = '<div id="chat-box-'.$a['id'].'" class="chat-box '. $chat_box_moderator.'" style="width: '.$a['width'].' !important; background-color: '.$a['background_color'].'; '.$a['font_style'].'" >';
		} else {
			$content = '<div id="chat-box-'.$a['id'].'" class="chat-box '. $chat_box_moderator. '" style="width: '.$a['width'].' !important; height: '.$a['height'].' !important; background-color: '.$a['background_color'].'; '.$a['font_style'].'" >';
		}

		if ($echo) {
			echo $content;
		}
		return $content;
	}

	function chat_wrap($a, $echo = false) {
		$content = '';

		$content .= '<div id="chat-wrap-'.$a['id'].'" class="chat-wrap avatar-'.$a['avatar'].'" >';

		if ($echo) {
			echo $content;
		}
		return $content;
	}

	function chat_area($a, $echo = false) {
		global $post;

		//echo "a<pre>"; print_r($a); echo "</pre>";
		$content = '';

		if ($this->is_moderator(preg_split('/,/', $a['moderator_roles'])))
			$chat_area_moderator = "chat-area-moderator";
		else
			$chat_area_moderator = "";
		if ($post) {
			$content .= '<div id="chat-area-'.$a['id'].'" class="chat-area '. $chat_area_moderator .'" style="background-color: '.$a['background_row_area_color'].' !important;; height: '.$a['height'].' !important;" ><div class="chat-scroll-height"></div></div></div>';
		} else {
			$content .= '<div id="chat-area-'.$a['id'].'" class="chat-area" ><div class="chat-scroll-height"></div></div></div>';
		}

		if ($echo) {
			echo $content;
		}
		return $content;
	}

	function use_public_auth($a) {
		return (preg_match('/public_user/', $a['login_options']) > 0);
	}
	function use_facebook_auth($a) {
		return (preg_match('/facebook/', $a['login_options']) > 0 && ($this->get_option('facebook_application_id') != ''));
	}


	function use_twitter_auth($a) {
		return (preg_match('/twitter/', $a['login_options']) > 0 && ($this->get_option('twitter_api_key') != ''));
	}

	function chat_dummy_message_area($a, $echo = true) {
		$content = '';
		if ($this->use_facebook_auth($a)) {
			$content .= '<form id="send-message-area">';
			$content .= '<input type="hidden" name="chat-post-id" id="chat-post-id-'.$a['id'].'" value="'.$a['id'].'" class="chat-post-id" />';
			$content .= '</form>';
		}
		if ($echo) {
			echo $content;
		}
		return $content;
	}

	function chat_login_public($a, $echo = true) {
		$content = '';
		$content .= '<form id="chat-login-'.$a['id'].'" class="chat-login">';
		$content .= '<div id="chat-login-wrap-'.$a['id'].'" class="chat-login-wrap">';
		$content .= wp_nonce_field('chat-nonce','chat-nonce');
		$content .= '<label class="chat-login-name-label" for="chat-login-name-'.$a['id'].'">'.__('Name', $this->translation_domain) . '</label> <input id="chat-login-name-'.$a['id'].'" name="chat-login-name" class="chat-login-name" type="text" /> ';
		$content .= '<label class="chat-login-email-label" for="chat-login-email-'.$a['id'].'">' . __('E-mail', $this->translation_domain) . '</label> <input id="chat-login-email-'.$a['id'].'" name="chat-login-email" class="chat-login-email" type="text" /> ';
		$content .= '<input type="submit" value="'. __('Login', $this->translation_domain) .'" name="chat-login-submit" id="chat-login-submit-'.$a['id'].'" />';
		$content .= '</div>';
		$content .= '</form>';

		if ($echo) {
			echo $content;
		}
		return $content;
	}

	function chat_login_facebook($a, $echo = true) {
		$content = '';
		if ($this->use_facebook_auth($a)) {
			$content .= '<span id="chat-facebook-signin-btn-'.$a['id'].'" class="chat-auth-button chat-facebook-signin-btn"></span>';
		}
		if ($echo) {
			echo $content;
		}
		return $content;
	}

	function chat_login_twitter($a, $echo = true) {
		$content = '';
		if ($this->use_twitter_auth($a)) {
			$content .= '<span id="chat-twitter-signin-btn-'.$a['id'].'" class="chat-auth-button chat-twitter-signin-btn"></span>';
		}
		if ($echo) {
			echo $content;
		}
		return $content;
	}

	/**
	 * @see		http://codex.wordpress.org/TinyMCE_Custom_Buttons
	 */
	function tinymce_register_button($buttons) {
		array_push($buttons, "separator", "chat");
		return $buttons;
	}

	/**
	 * @see		http://codex.wordpress.org/TinyMCE_Custom_Buttons
	 */
	function tinymce_load_langs($langs) {
		$langs["chat"] =  plugins_url('/tinymce/langs/langs.php', __FILE__);
		return $langs;
	}

	/**
	 * @see		http://codex.wordpress.org/TinyMCE_Custom_Buttons
	 */
	function tinymce_add_plugin($plugin_array) {
		$plugin_array['chat'] = plugins_url('/tinymce/editor_plugin.js', __FILE__);
		return $plugin_array;
	}

	/**
	 * Process chat requests
	 *
	 * Mostly copied from process.php
	 *
	 * @global	object	$current_user
	 * @param	string	$return		Return? 'yes' or 'no'
	 * @return	string			If $return is yes will return the output else echo
	 */
	function process($return = 'no') {
		global $current_user;
		get_currentuserinfo();

		$function = $_POST['function'];

		if ( empty($function) ) {
			$function = $_GET['function'];
		}

		$log = array();

		//$this->start_logger();

		switch($function) {
			case 'meta':

				$this->add_logger_entry('starting chat '. $function);

				if (isset($_POST['cid'])) {
					$chat_id = $_POST['cid'];

					if (isset($_POST['chatMetaData']))
						$chatMetaData = $_POST['chatMetaData'];
					else
						$chatMetaData = array();

					if (!isset($chatMetaData[$chat_id])) {
						$chatMetaData[$chat_id] = array();
					}

					//echo "_POST<pre>"; print_r($_POST); echo "</pre>";
					//$chat_status = $this->chatSessionStatusGet($chat_id);
					//echo "chat_status=[". $chat_status ."]<br />";
					//die();

					if (!isset($chatMetaData[$chat_id]['status'])) {
						$chatMetaData[$chat_id]['status'] = ''; //$this->chatSessionStatusGet($chat_id);
					}

					if ((!isset($chatMetaData[$chat_id]['rows'])) || (!is_array($chatMetaData[$chat_id]['rows']))) {
						$chatMetaData[$chat_id]['rows'] = array();
					} else {
						foreach($chatMetaData[$chat_id]['rows'] as $_key => $_val) {
							$chatMetaData[$chat_id]['rows'][$_key] = intval($_val);
						}
					}

			        $chatPollTime = time();
			        while((time() - $chatPollTime) <= 15) {

						//$this->add_logger_entry($function .' calling chatSessionStatusGet '. $function);
						$chat_status = $this->chatSessionStatusGet($chat_id);
						//$this->add_logger_entry($function .' returned chatSessionStatusGet '. $function);

						if ($chat_status !== $chatMetaData[$chat_id]['status']) {
							$chatMetaData[$chat_id]['status'] = $chat_status;
							echo json_encode($chatMetaData);
							die();
						} else {
							$chatMetaData[$chat_id]['status'] = $this->chatSessionStatusGet($chat_id);
						}

						//$this->add_logger_entry($function .' calling get_messages');
						$rows = $this->get_messages($chat_id, 0, 0, array('no', 'no-deleted'), 0, true, 0);
						//$this->add_logger_entry($function .' returned get_messages');

						if (($rows) && (count($rows))) {

							if (isset($rows_timestamps)) unset($rows_timestamps);
							$rows_timestamps = array();

							foreach($rows as $row) {
								if ($row->archived == "no-deleted")
									$rows_timestamps[] = intval(strtotime($row->timestamp));
							}

							if (count($chatMetaData[$chat_id]['rows']) != count($rows_timestamps)) {
								$chatMetaData[$chat_id]['rows'] = $rows_timestamps;
								echo json_encode($chatMetaData);
								exit(0);

							} else {
								if (array_diff($rows_timestamps, $chatMetaData[$chat_id]['rows'])) {
									$chatMetaData[$chat_id]['rows'] = $rows_timestamps;
									$chatMetaData[$chat_id]['rows'] = array_unique($chatMetaData[$chat_id]['rows'],
										SORT_NUMERIC);

									sort($chatMetaData[$chat_id]['rows'], SORT_NUMERIC);

									echo json_encode($chatMetaData);
									exit(0);
								}
							}
						}
						//sleep(1);
						break;
					}
					echo json_encode($chatMetaData);
					exit(0);
				}
				break;

			case 'update':

				if (isset($_POST['cid']))
					$chat_id = $_POST['cid'];

				if (isset($_POST['since']))
					$since = $_POST['since'];

				if (isset($_POST['since_id']))
					$since_id = $_POST['since_id'];
				else
					$since_id = 0;

				if ($since_id == 0) {
					if (isset($_POST['log_limit']))
						$log_limit = $_POST['log_limit'];
					else
						$log_limit = 0;
				} else {
					$log_limit = 0;
				}

    			$end = isset($_POST['end'])?$_POST['end']:0;
    			$archived = isset($_POST['archived']) ? $_POST['archived'] : array('no', 'no-deleted');
    			//$archived = isset($_POST['archived']) ? $_POST['archived'] : array('no');
				$name = isset($_POST['name'])?$_POST['name']:md5('wordpress-chat');
				$name = htmlentities(strip_tags($name));

		        $chatPollTime = time();
		        //while((time() - $chatPollTime) <= 15) {
					//$this->add_logger_entry($function .' calling get_messages');
					$rows = $this->get_messages($chat_id, $since, $end, $archived, $since_id, false, $log_limit);
					//echo "rows<pre>"; print_r($rows); echo "</pre>";

					//$this->add_logger_entry($function .' returned get_messages');
					//if (($rows) && (count($rows))) break;

					//sleep(1);
					//break;
				//}

				if ($rows) {
					$text = array();

					$new_message = false;
		    		//echo "this<pre>"; print_r($this); echo "</pre>";

					if ($this->is_moderator(preg_split('/,/', $_POST['moderator_roles'])))
						$_user_is_moderator = true;
					else
						$_user_is_moderator = false;

					foreach ($rows as $row) {

						$message = stripslashes($row->message);
    					$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

    					if(($message) != "\n" && ($message) != "<br />" && ($message) != "") {
							if(preg_match_all($reg_exUrl, $message, $urls) && isset($urls[0]) && count($urls[0]) > 0) {
								foreach ($urls[0] as $url) {
									$message = str_replace($url, '<a href="'.$url.'" target="_blank">'.$url.'</a>', $message);
								}
							}
    					}

    					$message = preg_replace(array('/\[code\]/','/\[\/code\]/'), array('<code style="background: '.
							$this->get_option('code_color', '#FFFFCC').'; padding: 4px 8px;">', '</code>'), $message);

						$code_start_count = preg_match_all('/<code/i', $message, $code_starts);
						$code_end_count = preg_match_all('/<\/code>/i', $message, $code_ends);

						if ($code_start_count > $code_end_count) {
							$code_diff = $code_start_count - $code_end_count;

							for ($i=0; $i<$code_diff; $i++) {
								$message .= '</code>';
							}

						} else {
							$code_diff = $code_end_count - $code_start_count;

							for ($i=0; $i<$code_diff; $i++) {
								$message = '<code>'.$message;
							}
						}

    					$message = str_replace("\n", "<br />", $message);

    					$prepend = "";

						$prepend .= '<div class="chat-actions" style="float: left; width: 50px; margin-right: 5px">';
    					if ($_POST['avatar'] == 'enabled') {

    						if (preg_match('/@/', $row->avatar)) {
    							$avatar = get_avatar($row->avatar, 50, null, $row->name);
    						} else {
    							$avatar = "<img alt='{$row->name}' src='{$row->avatar}' class='avatar photo' />";
    						}
    						$prepend .= '<a class="chat-user-avatar" title="'. $row->name .
								'" href="@'. $row->name .'">'. "$avatar " .'</a>';
    					}

						if ( ($_user_is_moderator) && (!isset($_GET['lid'])) ) {
							$prepend .= '<div class="chat-admin-actions">';
							if (($row->archived == "no-deleted") || ($row->archived == "yes-deleted")) {
								$prepend .= '<a class="chat-admin-actions chat-admin-actions-undelete" title="" href="#"><span class="action">'.
									__('undelete', $this->translation_domain) .'</span></a>';
							} else {
								$prepend .= '<a class="chat-admin-actions chat-admin-actions-delete" title="" href="#"><span class="action">'.
									__('delete', $this->translation_domain) .'</span></a>';
							}
							$prepend .= '</div>';
						}

						$prepend .= '</div>';

    					if ($_POST['date_show'] == 'enabled') {
    						$prepend .= ' <span class="date" style="background: '.$_POST['date_color'].';">'. date_i18n(get_option('date_format'),
 								strtotime($row->timestamp) + get_option('gmt_offset') * 3600, false) . '</span>';
    					}

    					if ($_POST['time_show'] == 'enabled') {
    						$prepend .= ' <span class="time" style="background: '.$_POST['date_color'].';">'. date_i18n(get_option('time_format'),
 								strtotime($row->timestamp) + get_option('gmt_offset') * 3600, false) . '</span>';
    					}

						if ($row->moderator == 'yes') {
							$name_color = $_POST['moderator_name_color'];
						} else {
							$name_color = $_POST['name_color'];
						}

    					$prepend .= '<a class="chat-user-avatar" title="'. $row->name . '" href="@'. $row->name .'"> <span class="name" style="background: '.$name_color.';">'.stripslashes($row->name).'</span></a>';

    					$text[$row->id] = ' <div id="row-'. strtotime($row->timestamp). '" class="row"
							style="background-color:'. $_POST['background_row_color'].' !important; border:'. $_POST['row_border_width'] .' solid '. $_POST['row_border_color'].' !important;">'. $prepend .'<span class="message"
							style="color: '. $_POST['text_color'] .'">'. convert_smilies($message) .'</span><div class="chat-clear"></div></div>';
    					$last_check = $row->timestamp;

						if ($name != $row->name) {
							$new_message = true;
						}

						$log['text'] = $text;
						$log['time'] = strtotime($last_check)+1;
						$log['new_message'] = $new_message;
	    			}
				}

				$rows_deleted = $this->get_messages($chat_id, 0, 0, array('no-deleted'), 0, false, 0);
				if (($rows_deleted) && (count($rows_deleted))) {
					//echo "rows_deleted<pre>"; print_r($rows_deleted); echo "</pre>";
					$rows_timestamps = array();

					foreach($rows_deleted as $row) {
						if ($row->archived == "no-deleted")
							$rows_timestamps[] = intval(strtotime($row->timestamp));
					}
					$log['deleted-rows'] = $rows_timestamps;
					//echo "log<pre>"; print_r($log); echo "</pre>";
				}
				$log['status'] = $this->chatSessionStatusGet($chat_id);
				break;

			case 'send':
				// Double check the user's authentication. Seems some users can login with multiple tabs. If they log out of one tab they
				// should not be able to post via the other tab.
				if (isset($_POST['type'])) {
					if ( $_POST['type'] == "1" ) {
						if ( !is_user_logged_in() ) {
							die();
						}
					} else {
						//echo "_COOKIE<pre>"; print_r($_COOKIE); echo "</pre>";
						if ((!isset($_COOKIE['chat_stateless_user_type_104'])) || (empty($_COOKIE['chat_stateless_user_type_104']))) {
							die();
						}
					}
				}
				$chat_id = $_POST['cid'];
				$name = strip_tags($_POST['name']);
				$avatar = (isset($_COOKIE['chat_stateless_user_image_'.$this->auth_type_map[$_POST['type']]]) && !empty($_COOKIE['chat_stateless_user_image_'.$this->auth_type_map[$_POST['type']]]))?$_COOKIE['chat_stateless_user_image_'.$this->auth_type_map[$_POST['type']]]:$current_user->user_email;
				$message = $_POST['message'];

				$moderator_roles = explode(',', $_POST['moderator_roles']);
				$moderator = $this->is_moderator($moderator_roles);

				$smessage = base64_decode($message);

	   			$smessage = preg_replace(array('/<code>/','/<\/code>/'), array('[code]', '[/code]'), $smessage);

				$smessage = strip_tags($smessage);

				//$this->add_logger_entry($function .' returned send_message');
				$this->send_message($chat_id, $name, $avatar, base64_encode($smessage), $moderator);
				//$this->add_logger_entry($function .' returned send_message');
				break;
		}

		if ($return == 'yes') {
			if (isset($log['text']) && is_array($log['text'])) {
				return "<p>".join("</p><p>", $log['text'])."</p>";
			} else {
				return "";
			}
		} else {
			echo json_encode($log);
			exit(0);
		}
	}

	/**
	 * Test whether logged in user is a moderator
	 *
	 * @param	Array	$moderator_roles Moderator roles
	 * @return	bool	$moderator	 True if moderator False if not
	 */
	function is_moderator($moderator_roles) {
		global $current_user;

		if ($current_user->ID) {
			foreach ($moderator_roles as $role) {
				if (in_array($role, $current_user->roles)) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Get message
	 *
	 * @global	object	$wpdb
	 * @global	int		$blog_id
	 * @param	int		$chat_id	Chat ID
	 * @param	int		$since		Start Unix timestamp
	 * @param	int		$end		End Unix timestamp
	 * @param	string	$archived	Archived? 'yes' or 'no'
	 */
	function get_messages($chat_id, $since = 0, $end = 0, $archived = array('no'), $since_id = false, $row_id_only = false, $log_limit = 0) {
		global $wpdb, $blog_id;

		$chat_id = $wpdb->escape($chat_id);
		$archived = $wpdb->escape($archived);
		$since_id = $wpdb->escape($since_id);

		if (empty($end)) {
			$end = time();
		}

		$start = date('Y-m-d H:i:s', $since);
		$end = date('Y-m-d H:i:s', $end);

		if ($since_id == false) {
			$since_id = 0;
		} else {
			$start = date('Y-m-d H:i:s', 0);
		}

		if (!is_array($archived))
			$archived = array($archived);
		$archived_str = "";

		foreach($archived as  $_val) {
			if (strlen($archived_str)) $archived_str .= ",";
			$archived_str .= "'". $_val ."'";
		}

		if ($row_id_only == false) {
			$sql_str = "SELECT * FROM `".WPMUDEV_Chat::tablename('message')."` WHERE blog_id = '". $blog_id ."' AND chat_id = '". $chat_id ."' AND archived IN ( ". $archived_str ." ) AND timestamp BETWEEN '". $start ."' AND '". $end ."' AND id > ". $since_id ." ORDER BY timestamp DESC";
		} else {
			$sql_str = "SELECT id, timestamp FROM `".WPMUDEV_Chat::tablename('message')."` WHERE blog_id = '". $blog_id ."' AND chat_id = '". $chat_id ."' AND archived IN ( ". $archived_str ." ) AND timestamp BETWEEN '". $start ."' AND '". $end ."' AND id > ". $since_id ." ORDER BY timestamp DESC";
		}
		if (intval($log_limit)) {
			$sql_str .= " LIMIT ". intval($log_limit);
		}

		//echo "sql_str=[". $sql_str ."]<br />";
		$this->add_logger_entry(__FUNCTION__ .' SQL ['. $sql_str.']');

		$results = $wpdb->get_results( $sql_str );
		krsort($results);
		return $results;
	}

	/**
	 * Send the message
	 *
	 * @global	object	$wpdb
	 * @global	int	$blog_id
	 * @param	int	$chat_id	Chat ID
	 * @param	string	$name		Name
	 * @param	string	$avatar		URL or e-mail
	 * @param	string	$message	Payload message
	 * @param	string	$moderator	Moderator
	 */
	function send_message($chat_id, $name, $avatar, $message, $moderator) {
		global $wpdb, $blog_id;

		$wpdb->real_escape = true;

		$time_stamp = date("Y-m-d H:i:s");

		$chat_id = $wpdb->_real_escape($chat_id);
		$name = $wpdb->_real_escape(trim(base64_decode($name)));
		$avatar = $wpdb->_real_escape(trim($avatar));
		$message = $wpdb->_real_escape(trim(base64_decode($message)));
		$moderator_str = 'no';

		if (empty($message)) {
			return false;
		}
		if ($moderator) {
			$moderator_str = 'yes';
		}

		$sql_str = "INSERT INTO ".WPMUDEV_Chat::tablename('message')."
					(blog_id, chat_id, timestamp, name, avatar, message, archived, moderator)
					VALUES ('$blog_id', '$chat_id', '$time_stamp', '$name', '$avatar', '$message', 'no', '$moderator_str');";
		$this->add_logger_entry(__FUNCTION__ .' SQL ['. $sql_str.']');

		return $wpdb->query($sql_str);
	}

	/**
	 * Get the last chat id for the given blog
	 *
	 * @global	object	$wpdb
	 * @global	int		$blog_id
	 */
	function get_last_chat_id() {
		global $wpdb, $blog_id;

		$last_id = $wpdb->get_var("SELECT chat_id FROM `".WPMUDEV_Chat::tablename('message')."` WHERE blog_id = '{$blog_id}' ORDER BY chat_id DESC LIMIT 1");

		if ($last_id) {
			return substr($last_id, 0, -1);
		}
		return 1;
	}

	/**
	 * Clear a chat log
	 *
	 * @global	object	$wpdb
	 * @global	int		$blog_id
	 */
	function clear() {
		global $wpdb, $blog_id;

		$since = date('Y-m-d H:i:s', $_POST['since']);
		$chat_id = $wpdb->escape($_POST['cid']);
		$option_type = ($_POST['cid'] == 1)?'site':'default';

		if ($this->is_moderator($this->get_option('moderator_roles', array('administrator','editor','author'), $option_type))) {
			$wpdb->query("DELETE FROM `".WPMUDEV_Chat::tablename('message')."` WHERE blog_id = '{$blog_id}' AND chat_id = '{$chat_id}' AND timestamp <= '{$since}' AND archived IN ('no', 'no-deleted');");
		}
		exit(0);
	}

	/**
	 * Archive a chat log
	 *
	 * @global	object	$wpdb
	 * @global	int		$blog_id
	 */
	function archive() {
		global $wpdb, $blog_id;

		$since = date('Y-m-d H:i:s', $_POST['since']);
		$chat_id = $wpdb->escape($_POST['cid']);
		$created = date('Y-m-d H:i:s');
		$option_type = ($_POST['cid'] == 1)?'site':'default';

		if ($this->is_moderator($this->get_option('moderator_roles', array('administrator','editor','author'), $option_type))) {
			$start = $wpdb->get_var("SELECT timestamp FROM `".WPMUDEV_Chat::tablename('message')."` WHERE blog_id = '{$blog_id}' AND chat_id = '{$chat_id}' AND timestamp <= '{$since}' AND archived IN ('no', 'no-deleted') ORDER BY timestamp ASC LIMIT 1;");
			$end = $wpdb->get_var("SELECT timestamp FROM `".WPMUDEV_Chat::tablename('message')."` WHERE blog_id = '{$blog_id}' AND chat_id = '{$chat_id}' AND timestamp <= '{$since}' AND archived IN ('no', 'no-deleted') ORDER BY timestamp DESC LIMIT 1;");

			$wpdb->query("UPDATE `".WPMUDEV_Chat::tablename('message')."` set archived = 'yes' WHERE blog_id = '{$blog_id}' AND chat_id = '{$chat_id}' AND timestamp BETWEEN '{$start}' AND '{$end}' AND archived = 'no';");

			$wpdb->query("UPDATE `".WPMUDEV_Chat::tablename('message')."` set archived = 'yes-deleted' WHERE blog_id = '{$blog_id}' AND chat_id = '{$chat_id}' AND timestamp BETWEEN '{$start}' AND '{$end}' AND archived = 'no-deleted';");

			$wpdb->query("INSERT INTO ".WPMUDEV_Chat::tablename('log')."
						(blog_id, chat_id, start, end, created)
						VALUES ('$blog_id', '$chat_id', '$start', '$end', '$created');");
		}

		exit(0);
	}

	/**
	 * Get a list of archives for the given chat
	 *
	 * @global	object	$wpdb
	 * @global	int		$blog_id
	 * @param	int		$chat_id	Chat ID
	 * @return	array				List of archives
	 */
	function get_archives($chat_id) {
		global $wpdb, $blog_id;

		$chat_id = $wpdb->escape($chat_id);

		return $wpdb->get_results(
			"SELECT * FROM `".WPMUDEV_Chat::tablename('log')."` WHERE blog_id = '$blog_id' AND chat_id = '$chat_id' ORDER BY created ASC;"
		);
	}

	function chatModerateDelete() {
		global $wpdb, $blog_id;

		if (isset($_POST['cid']))
			$chat_id = intval($_POST['cid']);
		if (isset($_POST['row_id']))
			$row_id = intval($_POST['row_id']);
		if (isset($_POST['moderate_action']))
			$moderate_action = esc_attr($_POST['moderate_action']);

		if ( ($chat_id > 0) && ($row_id > 0) && (strlen($moderate_action)) ) {

			$row_date  = date('Y-m-d H:i:s', $row_id);
			//echo "row_date=[". $row_date ."]<br />";

			$sql_str = "SELECT id, archived FROM `"	.WPMUDEV_Chat::tablename('message')
				."` WHERE blog_id = '". $blog_id ."' AND chat_id = '". $chat_id ."' AND timestamp = '". $row_date ."' LIMIT 1;";
			//echo "sql_str=[". $sql_str ."]<br />";

			$chat_row = $wpdb->get_row($sql_str);
			//echo "chat_row<pre>"; print_r($chat_row); echo "</pre>";
			if (($chat_row) && (isset($chat_row->archived))) {
				$chat_row_archived_new = '';

				if ($moderate_action == "delete") {
					if ($chat_row->archived == "yes") {
						$chat_row_archived_new = 'yes-deleted';
					} else if ($chat_row->archived == "no") {
						$chat_row_archived_new = 'no-deleted';
					}
				} else if ($moderate_action == "undelete") {
					if ($chat_row->archived == "yes-deleted") {
						$chat_row_archived_new = 'yes';
					} else if ($chat_row->archived == "no-deleted") {
						$chat_row_archived_new = 'no';
					}
				}

				if (strlen($chat_row_archived_new)) {
					$sql_str = "UPDATE `".WPMUDEV_Chat::tablename('message')
						."` SET archived='". $chat_row_archived_new
						."' WHERE id=". $chat_row->id ." AND blog_id = '". $blog_id ."' AND chat_id = '". $chat_id ."' LIMIT 1;";

					//echo "sql_str=[". $sql_str ."]<br />";
					$wpdb->get_results( $sql_str );
					echo 1;
					die();
				}
			}
		}
	}

	function chatSessionStatusModerate() {

		$chat_id = 0;

		if (isset($_POST['cid']))
			$chat_id = intval($_POST['cid']);

		if ($chat_id > 0) {
			//echo "chat_id=[". $chat_id ."]<br />";

			if (isset($_POST['chat_session_status'])) {
				$chat_session_status = esc_attr($_POST['chat_session_status']);
				//echo "chat_session_status=[". $chat_session_status ."]<br />";

				if ($chat_session_status == "chat-session-close")
					$chat_session_status = "closed";
				else if ($chat_session_status == "chat-session-open")
					$chat_session_status = "open";
				//echo "chat_session_status=[". $chat_session_status ."]<br />";

				$options = get_option('wordpress-chat-session-status', $this->chatSessionStatusDefault());
				$options[$chat_id] = $chat_session_status;
				//echo "options<pre>"; print_r($options); echo "</pre>";
				$update_ret = update_option('wordpress-chat-session-status', $options);
				if ($update_ret === true)
					echo "1";
			}
			die();
		}
	}

	function chatSessionStatusDefault() {
		return array();
	}

	function chatSessionStatusGet($chat_id = 0) {

		if (!$chat_id) {
			if (isset($_POST['cid']))
				$chat_id = intval($_POST['cid']);
		} else {
			$chat_id = intval($chat_id);
		}

		if ($chat_id > 0) {
			global $wpdb;
			// We can't use the get/update options. Since we are polling the options table and a different process is updating via AJAX we would
			// need to wait for the cache to timeout. So we check the database directly.
			//$options = get_option('wordpress-chat-session-status', $this->chatSessionStatusDefault());

			$sql_str = $wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name = '%s' LIMIT 1", "wordpress-chat-session-status");
			$this->add_logger_entry(__FUNCTION__ .' SQL ['. $sql_str. ']');
			$row = $wpdb->get_col( $sql_str );
			$this->add_logger_entry(__FUNCTION__ .' returned');
			if ($row) {
				$options = unserialize($row[0]);
				if (!isset($options[$chat_id]))  {
					return "closed";
				} else {
					if (($options[$chat_id] == "open") || ($options[$chat_id] == "closed")) return $options[$chat_id];
					else return "closed";
				}
			}
		}
		return "closed";
	}

	function start_logger() {

		if (isset($_POST['cid'])) {
			$chat_id = intval($_POST['cid']);

			$plugin_path = dirname(__FILE__) ."/_logs";
			//echo "plugin_path=[". $plugin_path ."]<br />";
			//die();

			@mkdir($plugin_path, 0777, true);
			$this->logger_fp = fopen($plugin_path ."/log_". $chat_id .".log", "a+");
		}
	}

	function add_logger_entry($message='') {
		if ($this->logger_fp) {
			fwrite($this->logger_fp, date('Y-m-d H:i:s', time()) .": ". $message ."\r\n");
			fflush($this->logger_fp);
		}
	}
}
} // End of class_exists()

// Lets get things started
$wpmudev_chat = new WPMUDEV_Chat();

if (!class_exists('WPMUDEVChatWidget')) {

	class WPMUDEVChatWidget extends WP_Widget {

		function WPMUDEVChatWidget () {
			global $wpmudev_chat;

			$widget_ops = array('classname' => __CLASS__, 'description' => __('WPMU DEV Chat Widget.', $wpmudev_chat->translation_domain));
			parent::WP_Widget(__CLASS__, __('WPMU DEV Chat Widget', $wpmudev_chat->translation_domain), $widget_ops);
		}

		function form($instance) {
			global $wpmudev_chat;

			// Set defaults
			// ...
			$defaults = array(
				'title' 		=> 	'',
				'id'			=>	'',
				'height'		=>	'300px',
				'sound'			=>	'disabled',
				'avatar'		=>	'disabled',
				'date_show'		=>	'disabled',
				'time_show'		=>	'disabled'
			);

			$instance = wp_parse_args( (array) $instance, $defaults );
			//echo "instance<pre>"; print_r($instance); echo "</pre>";

			if (empty($instance['height'])) {
				$instance['height'] = "300px";
			}

			if ($instance['sound'] == "enabled")
				$widget_sound = 'checked="checked"';
			else
			 	$widget_sound = '';

			if ($instance['avatar'] == "enabled")
				$widget_avatar = 'checked="checked"';
			else
			 	$widget_avatar = '';

			if ($instance['date_show'] == "enabled")
				$widget_date = 'checked="checked"';
			else
			 	$widget_date = '';

			if ($instance['time_show'] == "enabled")
				$widget_time = 'checked="checked"';
			else
			 	$widget_time = '';

			?>
			<input type="hidden" name="<?php echo $this->get_field_name('id'); ?>" id="<?php echo $this->get_field_id('id'); ?>"
				class="widefat" value="<?php echo $instance['id'] ?> "/>
			<p>
				<label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Title:', $wpmudev_chat->translation_domain); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>"
					class="widefat" value="<?php echo $instance['title'] ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php
					_e('Height for widget:', $wpmudev_chat->translation_domain); ?></label>

				<input type="text" id="<?php echo $this->get_field_id( 'height' ); ?>" value="<?php echo $instance['height']; ?>"
					name="<?php echo $this->get_field_name( 'height'); ?>" class="widefat" style="width:100%;" />
					<span class="description"><?php _e('The width will be 100% of the widget area', $wpmudev_chat->translation_domain); ?></span>
			</p>

			<p>
				<input type="checkbox" class="checkbox" <?php echo $widget_sound; ?> id="<?php echo $this->get_field_id( 'sound' ); ?>"
					value="<?php echo $instance['sound']; ?>"
					name="<?php echo $this->get_field_name( 'sound'); ?>" /> <label for="<?php echo $this->get_field_id( 'sound' ); ?>"><?php
						_e('Play Sound on new messages', $wpmudev_chat->translation_domain); ?></label><br />

				<input type="checkbox" class="checkbox" <?php echo $widget_avatar; ?> id="<?php echo $this->get_field_id( 'avatar' ); ?>"
					value="<?php echo $instance['avatar']; ?>"
					name="<?php echo $this->get_field_name( 'avatar'); ?>" /> <label for="<?php echo $this->get_field_id( 'avatar' ); ?>"><?php
						_e('Show User Avatars', $wpmudev_chat->translation_domain); ?></label><br />

				<input type="checkbox" class="checkbox" <?php echo $widget_date; ?> id="<?php echo $this->get_field_id( 'date_show' ); ?>"
					value="<?php echo $instance['date_show']; ?>"
					name="<?php echo $this->get_field_name( 'date_show'); ?>" /> <label for="<?php echo $this->get_field_id( 'date_show' ); ?>"><?php
						_e('Show Date', $wpmudev_chat->translation_domain); ?></label><br />

				<input type="checkbox" class="checkbox" <?php echo $widget_time; ?> id="<?php echo $this->get_field_id( 'time_show' ); ?>"
					value="<?php echo $instance['time_show']; ?>"
					name="<?php echo $this->get_field_name( 'time_show'); ?>" /> <label for="<?php echo $this->get_field_id( 'time_show' ); ?>"><?php
						_e('Show Time', $wpmudev_chat->translation_domain); ?></label>
			</p>

			<?php
		}

		function update($new_instance, $old_instance) {
			global $wpmudev_chat;

			//echo "new_instance<pre>"; print_r($new_instance); echo "</pre>";
			//echo "old_instance<pre>"; print_r($old_instance); echo "</pre>";
			//die();

			$instance = $old_instance;

			$instance['title'] 			= strip_tags($new_instance['title']);

			if ((!empty($new_instance['id'])) && (intval($new_instance['id'])))
				$instance['id'] 		= intval($new_instance['id']);
			else {
				$last_chat_id = $wpmudev_chat->get_last_chat_id();
				$instance['id']	=  rand($last_chat_id+1, $last_chat_id*1000);
			}

			if (isset($new_instance['height']))
				$instance['height'] 	= esc_attr($new_instance['height']);
			else
				$instance['height']		= '300px';

			if (isset($new_instance['sound']))
				$instance['sound'] 		= 'enabled';
			else
				$instance['sound']		= 'disabled';

			if (isset($new_instance['avatar']))
				$instance['avatar'] 	= 'enabled';
			else
				$instance['avatar']		= 'disabled';

			if (isset($new_instance['date_show']))
				$instance['date_show'] 	= 'enabled';
			else
				$instance['date_show']		= 'disabled';

			if (isset($new_instance['time_show']))
				$instance['time_show'] 	= 'enabled';
			else
				$instance['time_show']		= 'disabled';



			return $instance;
		}

		function widget($args, $instance) {
			global $wpmudev_chat;

			extract($args);

			echo $before_widget;

			$title = apply_filters('widget_title', $instance['title']);
			if ($title) echo $before_title . $title . $after_title;

			echo do_shortcode('[chat id="'. $instance['id'] .'" width="100%" height="'. $instance['height'] .'"  sound="'. $instance['sound'] .'" avatar="'. $instance['avatar'].'" emoticons="disabled" date_show="'. $instance['date_show'].'" time_show="'.$instance['time_show'].'" ]');

			echo $after_widget;

		}
	}
}

function wpmudev_chat_widget_init_proc() {
	register_widget('WPMUDEVChatWidget');
}
add_action( 'widgets_init', 'wpmudev_chat_widget_init_proc');