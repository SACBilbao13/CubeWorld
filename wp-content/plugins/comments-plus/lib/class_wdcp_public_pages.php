<?php
/**
 * Handles generic public functionality
 * and delegates to the worker.
 */
class Wdcp_PublicPages {
	var $worker;
	var $data;

	function Wdcp_PublicPages () { $this->__construct(); }

	function __construct () {
		if ($this->_load_dependencies()) {
			$this->data = new Wdcp_Options;
			$this->worker = new Wdcp_CommentsWorker;
		} // else...
	}

	function _load_dependencies () {
		if (!class_exists('Wdcp_CommentsWorker')) require_once WDCP_PLUGIN_BASE_DIR . '/lib/class_wdcp_comments_worker.php';	    		 	 			 		  
		return (class_exists('Wdcp_CommentsWorker'));
	}

	/**
	 * Main entry point.
	 *
	 * @static
	 */
	function serve () {
		$me = new Wdcp_PublicPages;
		$me->add_hooks();
	}

	function js_load_scripts () {
		printf(
			'<script type="text/javascript">var _wdcp_ajax_url="%s";</script>',
			 admin_url('admin-ajax.php')
		);
		do_action('wdcp-load_scripts-public');
	}

	function css_load_styles () {
		do_action('wdcp-load_styles-public');
	}

	function check_if_wordpress_provider_allowed ($comment) {
		$skips = (array)$this->data->get_option('skip_services');
		if (!in_array('wordpress', $skips)) return $comment;

		if (!isset($comment['_wdcp_provider'])) return array();
		return $comment;
	}

	function reset_preferred_provider ($data) {
		//if (!isset($_COOKIE["wdcp_preferred_provider"])) return $data;
		setcookie("wdcp_preferred_provider", "comment-provider-wordpress", strtotime("+1 year"), "/");

		return $data;
	}

	public function get_footer_hook () {
		$hook = defined('WDCP_FOOTER_DEPENDENCIES_HOOK') && WDCP_FOOTER_DEPENDENCIES_HOOK
			? WDCP_FOOTER_DEPENDENCIES_HOOK
			: 'get_footer'
		;
		return apply_filters('wdcp-core-hooks-footer_dependencies', $hook);
	}

	function add_hooks () {
		add_action('wp_print_scripts', array($this, 'js_load_scripts'));
		add_action('wp_print_styles', array($this, 'css_load_styles'));

		// Bind worker handlers
		add_action('wp_print_scripts', array($this->worker, 'js_load_scripts'));
		add_action('wp_print_styles', array($this->worker, 'css_load_styles'));

		add_filter('preprocess_comment', array($this, 'check_if_wordpress_provider_allowed'));
		add_filter('preprocess_comment', array($this, 'reset_preferred_provider'));

		$start_hook = $this->data->get_option('begin_injection_hook');
		$end_hook = $this->data->get_option('finish_injection_hook');
		$begin_injection_hook = $start_hook ? $start_hook : 'comment_form_before';
		$finish_injection_hook = $end_hook ? $end_hook : 'comment_form_after';
		add_action('wp_head', array($this->worker, 'header_dependencies'));
		add_filter($begin_injection_hook, array($this->worker, 'begin_injection'));
		add_filter($finish_injection_hook, array($this->worker, 'finish_injection'));
		add_action($this->get_footer_hook(), array($this->worker, 'footer_dependencies'));

		add_filter('get_avatar', array($this->worker, 'replace_avatars'), 10, 2);

	}
}