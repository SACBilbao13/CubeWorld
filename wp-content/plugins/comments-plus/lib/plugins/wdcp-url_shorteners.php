<?php
/*
Plugin Name: Short URLs
Description: Integrates an URL shortening service call to your outgoing URLs.
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Wdcp_Ussc_AdminPages {

	private $_data;
	private $_processor;
	private $_services = array(
		'facebook' => 'Facebook',
		'twitter' => 'Twitter',
	);

	private function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Ussc_AdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wdcp-options-plugins_options', array($this, 'register_settings'));

		$processor_class = $this->_data->get_option('ussc_process_with');
		if (!class_exists($processor_class)) return false; // Stop binding right now, nothing to do.
		if (!is_subclass_of($processor_class, 'Wdcp_Ussc_BaseProvider')) return false; // o.0
		$this->_processor = new $processor_class;

		if ($this->_data->get_option('ussc_no_cache')) {
			$this->_processor->set_no_caching();
		}

		$services = $this->_get_services();
		foreach ($this->_services as $service => $label) {
			if (!in_array($service, $services)) continue;
			add_filter("wdcp-remote_post_data-{$service}-post_url", array($this, 'process_data'), 10, 2);
		}
	}

	function process_data ($original, $post_id) {
		$url = $this->_processor->get_short_url($original, $post_id);
		return $url ? $url : $original;
	}

	function register_settings () {
		add_settings_section('wdcp_ussc_settings', __('URL shortening', 'wdcp'), create_function('', ''), 'wdcp_options');
		add_settings_field('wdcp_ussc_use_on_service', __('Process URLs posted to', 'wdcp'), array($this, 'create_use_on_box'), 'wdcp_options', 'wdcp_ussc_settings');
		add_settings_field('wdcp_ussc_process_with_service', __('Process with', 'wdcp'), array($this, 'create_process_with_box'), 'wdcp_options', 'wdcp_ussc_settings');	    		 	 			 		  
	}

	function create_use_on_box () {
		$sel = $this->_get_services();
		foreach ($this->_services as $service => $label) {
			$checked = in_array($service, $sel) ? 'checked="checked"' : '';
			echo "<input type='checkbox' name='wdcp_options[ussc_use_on][{$service}]' id='wdcp-ussc_use_on-{$service}' value='{$service}' {$checked} />" .
				"&nbsp;" .
				"<label for='wdcp-ussc_use_on-{$service}'>{$label}</label>" .
			"<br />";
		}
	}

	function create_process_with_box () {
		$service = $this->_data->get_option('ussc_process_with');
		$all_classes = get_declared_classes();
		foreach ($all_classes as $class) {
			if (!is_subclass_of($class, 'Wdcp_Ussc_BaseProvider')) continue;
			$key = strtolower($class);
			$label = call_user_func(array($class, 'get_name'));
			$checked = ($service == $key) ? 'checked="checked"' : '';
			echo "<input type='radio' name='wdcp_options[ussc_process_with]' id='wdcp-ussc_process_with-{$key}' value='{$key}' {$checked} />" .
				"&nbsp;" .
				"<label for='wdcp-ussc_process_with-{$key}'>{$label}</label>" .
			"<br />";
		}

		// Caching
		$cache = $this->_data->get_option('ussc_no_cache');
		$checked = $cache ? 'checked="checked"' : '';
		echo "<input type='hidden' name='wdcp_options[ussc_no_cache]' value='' />" .
			"<input type='checkbox' name='wdcp_options[ussc_no_cache]' id='wdcp-ussc_no_cache' value='1' {$checked} />" .
			"&nbsp;" .
		"<label for='wdcp-ussc_no_cache'>" . __('Prevent shortened URL caching', 'wdcp') . "</label>";

		echo '<p>' .
			"<label for='wdcp-ussc_bitly_username'>" . __('Your bit.ly Username', 'wdcp') . "</label>" .
			"<input type='text' class='widefat' name='wdcp_options[ussc_bitly_username]' id='wdcp-ussc_bitly_username' value='" . esc_attr($this->_data->get_option("ussc_bitly_username")) . "' />" .
			"<br />" .
			"<label for='wdcp-ussc_bitly_key'>" . __('Your bit.ly API Key', 'wdcp') . "</label>" .
			"<input type='text' class='widefat' name='wdcp_options[ussc_bitly_key]' id='wdcp-ussc_bitly_key' value='" . esc_attr($this->_data->get_option("ussc_bitly_key")) . "' />" .
			'<br />' . sprintf(__('Get your bit.ly API info <a href="%s">here</a>', 'wdcp'), 'http://bitly.com/a/your_api_key');
		'</p>';
	}

	private function _get_services () {
		$services = $this->_data->get_option('ussc_use_on');
		return $services ? $services : array();
	}
}

abstract class Wdcp_Ussc_BaseProvider {

	protected $_http_args = array(
		"method" => "GET",
		"timeout" => 5,
		"redirection" => 5,
		"user-agent" => "wdcp-ussc",
		"sslverify" => false,
	);
	private $_use_cache = true;
	private $_cache_key = 'wdcp-ussc-short_url';


	public function set_no_caching () {
		$this->_use_cache = false;
	}
	public function parse_response ($body) { return $body; }
	public function get_short_url ($url, $post_id) { return $this->_remote_request(urlencode($url), $post_id); }

	public static function get_name () { throw new Exception('Child class needs to implement this'); }
	abstract public function get_service_url ();

	protected function _remote_request ($url, $post_id) {
		// First, check cache
		if ($this->_use_cache) {
			$cached = get_post_meta($post_id, $this->_cache_key, true);
			if ($cached) return $cached;
		}
		// No cache - request fresh url
		$url = sprintf($this->get_service_url(), $url);
		$page = wp_remote_request($url, $this->_http_args);
		if(is_wp_error($page)) return false; // Request fail
		if ((int)$page['response']['code'] != 200) return false; // Request fail
		$short = $this->parse_response($page['body']);

		// All good - update cache, and return
		if ($this->_use_cache && $short) update_post_meta($post_id, $this->_cache_key, $short);
		return $short;
	}
}

class Wdcp_Ussc_IsGdProvider extends Wdcp_Ussc_BaseProvider {

	public static function get_name () { return 'is.gd'; }
	public function get_service_url () { return 'http://is.gd/create.php?format=simple&url=%s'; }
}

class Wdcp_Ussc_BitLyProvider extends Wdcp_Ussc_BaseProvider {

	private $_data;
	public function __construct () { $this->_data = new Wdcp_Options; }
	public static function get_name () { return 'bit.ly'; }

	public function get_service_url () {
		return sprintf(
			'http://api.bitly.com/v3/shorten?login=o_3f15m2diqp&apiKey=R_a35cd0f711f1fa44c6d39ecd48b571ac',
			$this->_data->get_option('ussc_bitly_username'),
			$this->_data->get_option('ussc_bitly_key')
		) . '&longUrl=%s';
	}

	public function parse_response ($body) {
		if (!$body) return false;
		$resp = @json_decode($body, true);
		if (200 != @$resp['status_code']) return false;
		return @$resp['data']['url'];
	}
}

if (is_admin()) Wdcp_Ussc_AdminPages::serve();