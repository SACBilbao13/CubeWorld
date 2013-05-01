<?php
/*
Plugin Name: Facebook Featured post image
Description: Forces featured image to always show next to the posts on Facebook instead of relying on defaults. Also allows you to choose the image(s) used when there is no featured image available.
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Wdcp_Ffpi_AdminPages {

	private $_data;

	private function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Ffpi_AdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wdcp-options-plugins_options', array($this, 'register_settings'));
		add_filter('wdcp-post_to_facebook-data', array($this, 'process_data'), 10, 2);
	}

	function process_data ($data, $post_id) {
		$post_id = (int)$post_id;
		if (!$data || !$post_id) return $data;

		$forced_img = $this->_data->get_option('ffpi_forced_image');
		if ($forced_img) {
			$data['picture'] = $forced_img;
			return $data;
		}

		$img = false;
		$raw = wp_get_attachment_image_src(get_post_thumbnail_id($post_id));
		$img = $raw ? @$raw[0] : false;

		$img = $img ? $img : $this->_data->get_option('ffpi_fallback_image');

		if ($img) $data['picture'] = $img;
		return $data;
	}

	function register_settings () {
		add_settings_section('wdcp_ffpi_settings', __('Facebook Featured Post Image', 'wdcp'), create_function('', ''), 'wdcp_options');
		add_settings_field('wdcp_ffpi_image', __('Settings', 'wdcp'), array($this, 'create_settings_box'), 'wdcp_options', 'wdcp_ffpi_settings');	    		 	 			 		  
	}

	function create_settings_box () {
		$forced_img = $this->_data->get_option('ffpi_forced_image');
		$fallback_img = $this->_data->get_option('ffpi_fallback_image');

		$checked = $featured_img ? 'checked="checked"' : '';
		echo '' .
			'<label for="wdcp-ffpi-fallback_image">' . __('Fallback image', 'wdcp') . ':</label>' .
			"<input type='text' name='wdcp_options[ffpi_fallback_image]' class='widefat' id='wdcp-ffpi-fallback_image' value='{$fallback_img}' />" .
			'<div><small>' . __('By default, we will attempt to use post featured image for Facebook publishing.', 'wdcp') . '</small></div>' .
			'<div><small>' . __('If that fails, this is the image that will be used instead. Please, use full URL to image (e.g. <code>http://example.com/images/example.jpg</code>).', 'wdcp') . '</small></div>' .
		'';

		echo '<p><strong>' . __('&hellip;or&hellip;', 'wdcp') . '</strong></p>';

		echo '' .
			'<label for="wdcp-ffpi-forced_image">' . __('Always use this image', 'wdcp') . ':</label>' .
			"<input type='text' name='wdcp_options[ffpi_forced_image]' id='wdcp-ffpi-forced_image' class='widefat' value='{$forced_img}' />" .
			'<div><small>' . __('Please, use full URL to image (e.g. <code>http://example.com/images/example.jpg</code>). If set, this image will <b>always</b> be used.', 'wdcp') . '</small></div>' .
		'';
	}
}

if (is_admin()) Wdcp_Ffpi_AdminPages::serve();