<?php
/**
 * Contextual help implementation.
 */

class Wdcp_ContextualHelp {

	private $_help;

	private $_pages = array(
		'list', 'edit', 'get_started', 'settings',
	);

	private $_sidebar = '';

	private function __construct () {
		if (!class_exists('WpmuDev_ContextualHelp')) require_once WDCP_PLUGIN_BASE_DIR . '/lib/external/class_wd_contextual_help.php';
		$this->_help = new WpmuDev_ContextualHelp();
		$this->_set_up_sidebar();
	}

	public static function serve () {
		$me = new Wdcp_ContextualHelp;
		$me->_initialize();
	}

	private function _set_up_sidebar () {
		$this->_sidebar = '<h4>' . __('Comments Plus', 'wdcp') . '</h4>';
		if (defined('WPMUDEV_REMOVE_BRANDING') && constant('WPMUDEV_REMOVE_BRANDING')) {
			$this->_sidebar .= '<p>' . __('The Comments Plus Plugin effectively allows you to combine comments from Facebook, Twitter and Google services with your standard WordPress comments, rather than picking just one.', 'wdcp') . '</p>';	    		 	 			 		  
		} else {
				$this->_sidebar .= '<ul>' .
					'<li><a href="http://premium.wpmudev.org/project/comments-plus" target="_blank">' . __('Project page', 'wdcp') . '</a></li>' .
					'<li><a href="http://premium.wpmudev.org/project/comments-plus/installation/" target="_blank">' . __('Installation and instructions page', 'wdcp') . '</a></li>' .
					'<li><a href="http://premium.wpmudev.org/forums/tags/comments-plus" target="_blank">' . __('Support forum', 'wdcp') . '</a></li>' .
				'</ul>' .
			'';
		}
	}

	private function _initialize () {
		foreach ($this->_pages as $page) {
			$method = "_add_{$page}_page_help";
			if (method_exists($this, $method)) $this->$method();
		}
		$this->_help->initialize();
	}

	private function _add_settings_page_help () {
		$help_items = array(
			array(
				'id' => 'wdcp-intro',
				'title' => __('Intro', 'wdcp'),
				'content' => '<p>' . __('This is where you configure <b>Comments Plus</b> plugin for your site', 'wdcp') . '</p>',
			),
			array(
				'id' => 'wdcp-general',
				'title' => __('General Info', 'wdcp'),
				'content' => '' .
					'<p>' . __('The Comments Plus Plugin effectively allows you to combine comments from Facebook, Twitter and Google services with your standard WordPress comments, rather than picking just one', 'wdcp') . '</p>' .
				''
			),
			array(
				'id' => 'wdcp-fb-setup',
				'title' => __('Setting Facebook API settings', 'wdcp'),
				'content' => '' .
					'<p>' . __('Follow these steps to set up <em>App ID/API key</em> and <em>App Secret</em> fields', 'wdcp') . '</p>' .
					'<ol>' .
						'<li>' . __('<a target="_blank" href="https://developers.facebook.com/apps">Create a Facebook Application</a>', 'wdcp') . '</li>' .
						'<li>' . sprintf(__('Your Facebook App setup should look similar to this:<br /><img src="%s" />', 'wdcp'), WDCP_PLUGIN_URL . '/img/fb-setup.png') . '</li>' .
					'</ol>' .
				''
			),
			array(
				'id' => 'wdcp-tutorial',
				'title' => __('Tutorial', 'wdcp'),
				'content' => '' .
					'<p>' .
						__('Tutorial dialogs will guide you through the important bits.', 'wdcp') .
					'</p>' .
					'<p><a href="#" class="wdcp-restart_tutorial">' . __('Restart the tutorial', 'wdcp') . '</a></p>',
			),
		);
		$this->_help->add_page('settings_page_wdcp', $help_items, $this->_sidebar, true);
		$this->_help->add_page('settings_page_wdcp-network', $help_items, $this->_sidebar, true);
	}
}