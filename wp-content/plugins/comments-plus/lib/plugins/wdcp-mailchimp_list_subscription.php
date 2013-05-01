<?php
/*
Plugin Name: MailChimp List Subscription
Description: Adds a checkbox to facilitate subscribing your commenters to your existing MailChimp list.
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Wdcp_Mcls_Mailchimp_Worker {

	private $_data;

	public function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Mcls_Mailchimp_Worker;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('comment_post', array($this, 'mailchimp_signup'));
	}

	function mailchimp_signup ($comment_id) {
		if (!@$_POST['wdcp-mcls-subscribe']) return false;

		$current = $this->_data->get_option('mcls_list');
		if (!$current) return false;

		$comment = get_comment($comment_id);
		$email = $comment->comment_author_email;
		if (!is_email($email)) return false;

		list($server, $key) = $this->get_parsed_key();
		if (!$server || !$key) return false;

		$resp = wp_remote_get("http://{$server}.api.mailchimp.com/1.3/?method=listSubscribe&apikey={$key}&id={$current}&email_address={$email}");
		if(is_wp_error($resp)) return false; // Request fail
		if ((int)$resp['response']['code'] != 200) return false; // Request fail

		$subscribed = get_option("wdcp-mcls-{$current}");
		$subscribed = is_array($subscribed) ? $subscribed : array();
		$subscribed[] = $email;
		update_option("wdcp-mcls-{$current}", array_unique($subscribed));

	}

	public function get_parsed_key () {
		$err = array(false,false);
		$key = $this->_data->get_option('mcls_apikey');
		if (preg_match('/-/', $key)) list($key, $server) = explode('-', $key);
		else return err;
		if (!$key || !$server) return $err;
		return array($server, $key);
	}
}

class Wdcp_Mcls_Admin_Pages {

	private $_data;
	private $_worker;

	private function __construct () {
		$this->_data = new Wdcp_Options;
		$this->_worker = new Wdcp_Mcls_Mailchimp_Worker;
	}

	public static function serve () {
		$me = new Wdcp_Mcls_Admin_Pages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wdcp-options-plugins_options', array($this, 'register_settings'));
		add_action('wdcp-plugin_settings-javascript_init', array($this, 'add_javascript'));
		add_action('wp_ajax_wdcp_mcls_refresh_lists', array($this, 'json_refresh_lists'));
	}

	function register_settings () {
		add_settings_section('wdcp_mcls_settings', __('MailChimp Settings', 'wdcp'), create_function('', ''), 'wdcp_options');
		add_settings_field('wdcp_mcls_apikey', __('API key', 'wdcp'), array($this, 'create_apikey_box'), 'wdcp_options', 'wdcp_mcls_settings');
		add_settings_field('wdcp_mcls_lists', __('Lists', 'wdcp'), array($this, 'create_lists_box'), 'wdcp_options', 'wdcp_mcls_settings');
	}

	function create_apikey_box () {
		$key = $this->_data->get_option('mcls_apikey');
		echo '<input type="text" class="widefat" name="wdcp_options[mcls_apikey]" value="' . esc_attr($key) . '" />';
	}

	function create_lists_box () {
		$key = $this->_data->get_option('mcls_apikey');
		if (!$key) {
			echo '<div class="error below-h2"><p>' . __('Please set up your API key in the field above, and save the settings.', 'wdcp') . '</p></div>';	    		 	 			 		  
			return false;
		}

		echo $this->_generate_lists_output();
		echo '<a href="#mcls-refresh" id="wdcp-mcls-refresh">' . __('Refresh', 'wdcp') . '</a>';
		echo '<div><small>' . __('Select a list you wish to offer subscriptions to.', 'wdcp') . '</small></div>';
	}

	function json_refresh_lists () {
		echo $this->_generate_lists_output();
		die;
	}

	function add_javascript () {
		$loading = WDCP_PLUGIN_URL . '/img/loading.gif';
		echo <<<EOMclsAdminJs
function mcls_refresh_lists () {
	$("#wdcp-mcls-refresh").parents('td:first').find("select").remove();
	$("#wdcp-mcls-refresh").hide().after('<img src="{$loading}" id="wdcp-mcls-spinner" />');
	$.post(ajaxurl, {
		"action": "wdcp_mcls_refresh_lists",
	}, function (data) {
		$("#wdcp-mcls-spinner").remove();
		$("#wdcp-mcls-refresh").show().before(data);
	});
	return false;
}
$("#wdcp-mcls-refresh").click(mcls_refresh_lists);
EOMclsAdminJs;
	}

	private function _refresh_lists () {
		list($server, $key) = $this->_worker->get_parsed_key();
		$resp = wp_remote_get("http://{$server}.api.mailchimp.com/1.3/?method=lists&apikey={$key}");
		if(is_wp_error($resp)) return false; // Request fail
		if ((int)$resp['response']['code'] != 200) return false; // Request fail

		$lists = json_decode($resp['body']);
		$store = array();
		if (isset($lists->data) && $lists->data) foreach ($lists->data as $list) {
			$store[] = array(
				'id' => $list->id,
				'name' => $list->name,
				'created' => strtotime($list->date_created),
			);
		}
		return $store;
	}

	private function _generate_lists_output () {
		$out = '<select name="wdcp_options[mcls_list]">';
		$lists = $this->_refresh_lists();
		$current = $this->_data->get_option('mcls_list');
		if ($lists) foreach ($lists as $list) {
			$selected = $list['id'] == $current ? 'selected="selected"' : '';
			$out .= '<option value="' . esc_attr($list['id']) . '" ' . $selected . '>' . $list['name'] . '</option>';
		}
		$out .= '</select>';
		return $out;
	}

}

class Wdcp_Mcls_Public_Pages {

	private $_data;

	private function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Mcls_Public_Pages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		$end_hook = $this->_data->get_option('finish_injection_hook');
		$finish_injection_hook = $end_hook ? $end_hook : 'comment_form_after';
		add_action($finish_injection_hook, array($this, 'show_subscription_checkbox'));
	}

	function show_subscription_checkbox () {
		$current = $this->_data->get_option('mcls_list');
		if (!$current) return false;

		$subscribed = get_option("wdcp-mcls-{$current}");
		$subscribed = is_array($subscribed) ? $subscribed : array();

		$providers = array('wordpress', 'google', 'facebook');
		$model = new Wdcp_Model;
		foreach ($providers as $provider) {
			$email = $model->current_user_email($provider);
			if ($email && in_array($email, $subscribed)) return false; // Already subscribed
		}

		$label = apply_filters('wdcp-mcls-checkbox_label_text', __('Subscribe me to the newsletter', 'wdcp'));
		echo '<p class="wdcp-mcls-subscription">' .
			'<input type="checkbox" name="wdcp-mcls-subscribe" id="wdcp-mcls-subscribe" value="1" /> <label for="wdcp-mcls-subscribe">' . $label . '</label>' .
		'</p>';
		echo <<<EOMclsPublicJs
<script type="text/javascript">
(function ($) {
$(document).bind('wdcp_preprocess_comment_data', function (e, to_send) {
	if (!$("#wdcp-mcls-subscribe").length) return false;
	if (!$("#wdcp-mcls-subscribe").is(":visible")) return false;
	to_send["wdcp-mcls-subscribe"] = $("#wdcp-mcls-subscribe").is(":checked") ? 1 : 0;
});
})(jQuery);
</script>
EOMclsPublicJs;
	}

}

if (is_admin()) Wdcp_Mcls_Admin_Pages::serve();
else Wdcp_Mcls_Public_Pages::serve();

Wdcp_Mcls_Mailchimp_Worker::serve();