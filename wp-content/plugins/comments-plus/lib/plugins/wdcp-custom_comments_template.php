<?php
/*
Plugin Name: Custom Comments Template
Description: Using the custom template will override your themes default setup. We provide various default styles you can select from. You may also use those CSS styles within your own theme (found here <code>/plugins/comments-plus/css/themes/</code>) or you can simply move <code>lib/forms/wdcp-custom_comments_template.php</code> to your themes folder.
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Wdcp_Cct_Admin_Pages {

	private $_data;

	private function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Cct_Admin_Pages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wdcp-options-plugins_options', array($this, 'register_settings'));
	}

	function register_settings () {
		add_settings_section('wdcp_cct_settings', __('Custom Comments Template', 'wdcp'), create_function('', ''), 'wdcp_options');
		add_settings_field('wdcp_cct_theme', __('Use this style', 'wdcp'), array($this, 'create_theme_box'), 'wdcp_options', 'wdcp_cct_settings');	    		 	 			 		  
	}

	function create_theme_box () {
		$theme = $this->_data->get_option('cct_theme');
		$theme = $theme ? $theme : 'default';

		$override = $this->_data->get_option('cct_theme_override') ? 'checked="checked"' : '';

		$themes = array(
			'default' => __('Default', 'wdcp'),
			'wpmu' => __('WPMU.org', 'wdcp'),
			'shadow' => __('Shadows', 'wdcp'),
		);

		echo '<select name="wdcp_options[cct_theme]">';
		foreach ($themes as $key => $label) {
			$selected = ($theme == $key) ? 'selected="selected"' : '';
			echo "<option value='{$key}' {$selected}>{$label}&nbsp;</option>";
		}
		echo '</select>';

		echo '<input type="hidden" name="wdcp_options[cct_theme_override]" value="0" />';
		echo '<br />' .
			"<input type='checkbox' id='wdcp-cct_theme_override' name='wdcp_options[cct_theme_override]' value='1' {$override} />" .
			'&nbsp;' .
			'<label for="wdcp-cct_theme_override">' . __('Do not load custom comments template styles - my theme already has all the needed styles', 'wdcp') . '</label>' .
			'<div><small>' . __('If you check this option, no custom comments template style will be loaded and only the styles coming form your theme shall be applied.', 'wdcp') . '</small></div>' .
			'<div><small>' . sprintf(__('To change the actual markup of the custom template, copy this file to your theme directory: <code>%s</code>, then make your changes there', 'wdcp'), WDCP_PLUGIN_BASE_DIR . '/lib/forms/wdcp-custom_comments_template.php') . '</small></div>' .
		'';
	}

}

class Wdcp_Cct_Public_Pages {

	private $_data;

	private function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Cct_Public_Pages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wp_print_styles', array($this, 'css_load_styles'));
		add_filter('comments_template', array($this, 'load_comments_template'), 999);
	}

	function css_load_styles () {
		$theme = $this->_data->get_option('cct_theme');
		$theme = $theme ? preg_replace('/[^-_0-9a-z]/', '', strtolower($theme)) : 'default';
		$override = $this->_data->get_option('cct_theme_override');

		if (!current_theme_supports('wdcp-cct_theme') && !$override) {
			wp_enqueue_style('wdcp-cct_theme', WDCP_PLUGIN_URL . "/css/themes/{$theme}.css");
		}
	}

	function load_comments_template () {
		if (!is_singular()) return false;
		$theme_file = locate_template(array('wdcp-custom_comments_template.php'));

		return $theme_file ? $theme_file : WDCP_PLUGIN_BASE_DIR . '/lib/forms/wdcp-custom_comments_template.php';
	}

}

if (is_admin()) Wdcp_Cct_Admin_Pages::serve();
else Wdcp_Cct_Public_Pages::serve();