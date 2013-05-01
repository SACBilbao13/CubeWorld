<?php
class Wdcp_Options {
	private $_data;
	private $_site;

	private $_fb;
	private $_tw;

	function __construct () {
		$this->_data = get_option('wdcp_options');
		$this->_site = get_site_option('wdcp_options');

		$this->_fb = array('fb_app_id', 'fb_app_secret');
		$this->_tw = array('tw_api_key', 'tw_app_secret');
	}

	public function get_option ($name) {
		if (!$name) return false;
		if (in_array($name, $this->_fb) && @$this->_site['fb_network_only']) {
			return @$this->_site[$name];
		}
		if (in_array($name, $this->_tw) && @$this->_site['tw_network_only']) {
			return @$this->_site[$name];
		}
		return @$this->_data[$name] ? @$this->_data[$name] : @$this->_site[$name];
	}

	/**
	 * Gets all stored options.
	 */
	function get_options () {
		return WP_NETWORK_ADMIN ? get_site_option('wdcp_options', array()) : get_option('wdcp_options', array());
	}

	/**
	 * Sets all stored options.
	 */
	function set_options ($opts) {
		return WP_NETWORK_ADMIN ? update_site_option('wdcp_options', $opts) : update_option('wdcp_options', $opts);	    		 	 			 		  
	}
}