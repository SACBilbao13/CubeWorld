<?php

class Wdcp_Tutorial {

	private $_setup_tutorial;
	private $_settings_url;
	private $_setup_steps = array(
		'facebook',
		'hooks',
		'addons',
	);


	private function __construct () {
		if (!class_exists('Pointer_Tutorial')) require_once WDCP_PLUGIN_BASE_DIR . '/lib/external/pointers_tutorial.php';
		$this->_setup_tutorial = new Pointer_Tutorial('wdcp-setup', __('Setup tutorial', 'wdcp'), false, false);
		$this->_setup_tutorial->add_icon('');
		$this->_settings_url = is_network_admin() ? network_admin_url('settings.php?page=wdcp') : admin_url('options-general.php?page=wdcp');	    		 	 			 		  
	}

	public static function serve () {
		$me = new Wdcp_Tutorial;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('admin_init', array($this, 'process_tutorial'));
		add_action('wp_ajax_wdcp_restart_tutorial', array($this, 'json_restart_tutorial'));
	}

	function process_tutorial () {
		/*
		global $hook_suffix;
		if ('settings_page_wdcp' == $hook_suffix) $this->_init_tutorial($this->_setup_steps);
		if (defined('DOING_AJAX')) {
			$this->_init_tutorial($this->_setup_steps);
		}
		 */
		$this->_init_tutorial($this->_setup_steps);
		$this->_setup_tutorial->initialize();
	}

	function json_restart_tutorial () {
		$this->restart();
		die;
	}

	public function restart () {
		$tutorial = "_setup_tutorial";
		if (isset($this->$tutorial)) return $this->$tutorial->restart();
	}

	private function _init_tutorial ($steps) {
		$this->_setup_tutorial->set_capability('manage_options');

		foreach ($steps as $step) {
			$call_step = "add_{$step}_step";
			if (method_exists($this, $call_step)) $this->$call_step();
		}
	}

/* ----- Setup Steps ----- */

	function add_facebook_step () {
		$this->_setup_tutorial->add_step(
			$this->_settings_url, 'settings_page_wdcp',
			'#fb_app_id',
			__('Facebook App ID', 'wdcp'),
			array(
				'content' => '<p>' .
					esc_js(__('Follow the steps to create your Facebook App, then paste its ID here.', 'wdcp')) .
					'<br /><a href="#wdcp-more_help-fb" class="wdcp-more_help-fb">' . __('More help', 'wdcp') . '</a>' .
				'</p>',
				'position' => array('edge' => 'top', 'align' => 'left'),
			)
		);
		$this->_setup_tutorial->add_step(
			$this->_settings_url, 'settings_page_wdcp',
			'#fb_app_secret',
			__('Facebook App Secret', 'wdcp'),
			array(
				'content' => '<p>' .
					esc_js(__('Follow the steps to create your Facebook App, then paste its Secret key here.', 'wdcp')) .
					'<br /><a href="#wdcp-more_help-fb" class="wdcp-more_help-fb">' . __('More help', 'wdcp') . '</a>' .
				'</p>',
				'position' => array('edge' => 'top', 'align' => 'left'),
			)
		);
		$this->_setup_tutorial->add_step(
			$this->_settings_url, 'settings_page_wdcp',
			'#fb_skip_init',
			__('Facebook javascript loading', 'wdcp'),
			array(
				'content' => '' .
					'<p>' .
						esc_js(__('Check this option if your page already includes javascript from Facebook.', 'wdcp')) .
					'</p>' .
					'<p>' .
						esc_js(__('Alternatively, you may want to activate the Alternative Facebook Initialization add-on (below).', 'wdcp')) .
					'</p>' .
				'',
				'position' => array('edge' => 'top', 'align' => 'left'),
			)
		);
	}

	function add_hooks_step () {
		$this->_setup_tutorial->add_step(
			$this->_settings_url, 'settings_page_wdcp',
			'#begin_injection_hook',
			__('Comments form opening hook', 'wdcp'),
			array(
				'content' => '' .
					'<p>' .
						esc_js(__('If you do not see Comments Plus on your pages, it is likely that your theme does not use the hooks we need to display our interface. Use this field to set up custom hooks.', 'wdcp')) .
					'</p>' .
					'<p>' .
						esc_js(__('Alternatively, you may want to leave this value at default setting and activate the Custom Comments Template add-on instead (below).', 'wdcp')) .
					'</p>' .
				'',
				'position' => array('edge' => 'top', 'align' => 'left'),
			)
		);
		$this->_setup_tutorial->add_step(
			$this->_settings_url, 'settings_page_wdcp',
			'#finish_injection_hook',
			__('Comments form closing hook', 'wdcp'),
			array(
				'content' => '' .
					'<p>' .
						esc_js(__('If you do not see Comments Plus on your pages, it is likely that your theme does not use the hooks we need to display our interface. Use this field to set up custom hooks.', 'wdcp')) .
					'</p>' .
					'<p>' .
						esc_js(__('Alternatively, you may want to leave this value at default setting and activate the Custom Comments Template add-on instead (below).', 'wdcp')) .
					'</p>' .
				'',
				'position' => array('edge' => 'top', 'align' => 'left'),
			)
		);
	}

	function add_addons_step () {
		$this->_setup_tutorial->add_step(
			$this->_settings_url, 'settings_page_wdcp',
			'#wdcp-alter_fb_init',
			__('Alternative Facebook Initialization', 'wdcp'),
			array(
				'content' => '' .
					'<p>' .
						esc_js(__('Activating this add-on may help solving conflicts with other Facebook-related plugins.', 'wdcp')) .
					'</p>' .
				'',
				'position' => array('edge' => 'top', 'align' => 'left'),
			)
		);
		$this->_setup_tutorial->add_step(
			$this->_settings_url, 'settings_page_wdcp',
			'#wdcp-custom_comments_template',
			__('Custom Comments Template', 'wdcp'),
			array(
				'content' => '' .
					'<p>' .
						esc_js(__('Activating this add-on may help with resolving issues with your theme.', 'wdcp')) .
					'</p>' .
				'',
				'position' => array('edge' => 'top', 'align' => 'left'),
			)
		);
		$this->_setup_tutorial->add_step(
			$this->_settings_url, 'settings_page_wdcp',
			'#wdcp-tw_fake_email',
			__('Fake Twitter Email', 'wdcp'),
			array(
				'content' => '' .
					'<p>' .
						esc_js(__('Activating this add-on may help with comment approval issues with WordPress for your Twitter commenters.', 'wdcp')) .
					'</p>' .
				'',
				'position' => array('edge' => 'top', 'align' => 'left'),
			)
		);
	}


}