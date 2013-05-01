<?php
/**
 * Handles rendering of form elements for plugin Options page.
 */
class Wdcp_AdminFormRenderer {
	var $_opts;

	function Wdcp_AdminFormRenderer () { $this->__construct(); }

	function __construct () {
		$this->_opts = is_network_admin() ? get_site_option('wdcp_options') : get_option('wdcp_options');
	}

	function _get_option ($name) {
		return @$this->_opts[$name];
	}

	function _create_text_box ($name, $value) {
		return "<input type='text' class='widefat' name='wdcp_options[{$name}]' id='{$name}' value='{$value}' />";
	}
	function _create_small_text_box ($name, $value) {
		return "<input type='text' name='wdcp_options[{$name}]' id='{$name}' size='3' value='{$value}' />";
	}
	function _create_checkbox ($name, $value) {
		return "<input type='hidden' name='wdcp_options[{$name}]' value='0' />" .
			"<input type='checkbox' name='wdcp_options[{$name}]' id='{$name}' value='1' " . ($value ? 'checked="checked" ' : '') . " /> ";
	}

	function create_facebook_app_box () {
		$site_opts = get_site_option('wdcp_options');
		$has_creds = is_network_admin() ? false : @$site_opts['fb_network_only'];
		if (!$has_creds) {
			printf(__(
				'<p><b>You must make a Facebook Application to start using Comments Plus.</b></p>' .
				'<p>Before we begin, you need to <a target="_blank" href="https://developers.facebook.com/apps">create a Facebook Application</a>.</p>' .
				'<p>To do so, follow these steps:</p>' .
				'<ol>' .
					'<li><a target="_blank" href="https://developers.facebook.com/apps">Create your application</a></li>' .
					'<li>Look for <strong>Site URL</strong> field in the <em>Website</em> tab and enter your site URL in this field: <code>%s</code></li>' .
					'<li>After this, go to the <a target="_blank" href="https://developers.facebook.com/apps">Facebook Application List page</a> and select your newly created application</li>' .
					'<li>Copy the values from these fields: <strong>App ID</strong>/<strong>API key</strong>, and <strong>Application Secret</strong>, and enter them here:</li>' .
				'</ol>',
			'wdcp'),
				get_bloginfo('url')
			);
			echo '<label for="fb_app_id">' . __('App ID/API key', 'wdcp') . '</label> ' .
				$this->_create_text_box('fb_app_id', $this->_get_option('fb_app_id')) .
			'<br />';
			echo '<label for="fb_app_secret">' . __('App Secret', 'wdcp') . '</label> ' .
				$this->_create_text_box('fb_app_secret', $this->_get_option('fb_app_secret')) .
			'<br />';
			echo '<p><a href="#wdcp-more_help-fb" class="wdcp-more_help-fb">' . __('More help', 'wdcp') . '</a></p>';
		} else {
			_e('<p><i>Your Network Admin already set this up for you</i></p>', 'wdcp');
		}
		if (is_network_admin()) {
			echo ''.
				$this->_create_checkbox('fb_network_only', $this->_get_option('fb_network_only')) .
				' <label for="fb_network_only">' . __('I want to use this app on all subsites too', 'wdcp') . '</label>' .
				'<div><small>' . __('Please, do <b>NOT</b> check this option if any of your sites use domain mapping.', 'wdcp') . '</small></div>' .
			'<br />';
		}
	}

	function create_skip_facebook_init_box () {
		echo
			$this->_create_checkbox('fb_skip_init', $this->_get_option('fb_skip_init')) .
			'<label for="fb_skip_init">' . __('Pages on my website already use javascript from Facebook', 'wdcp') . '</label> ' .
		"";
		echo "<p><small>" . __('If you already use a plugin or custom script to interact with Facebook, check this option', 'wdcp') . '</small></p>';
	}

	function create_twitter_app_box () {
		$site_opts = get_site_option('wdcp_options');
		$has_creds = is_network_admin() ? false : @$site_opts['tw_network_only'];
		if (!$has_creds) {
			printf(__(
				'<p><b>You must make a Twitter Application to start using Comments Plus.</b></p>' .
				'<p>Before we begin, you need to <a target="_blank" href="https://dev.twitter.com/apps/new">create a Twitter Application</a>.</p>' .
				'<p>To do so, follow these steps:</p>' .
				'<ol>' .
					'<li><a target="_blank" href="https://dev.twitter.com/apps/new">Create your application</a></li>' .
					'<li>Look for <strong>Callback URL</strong> field and enter your site URL in this field: <code>%s</code></li>' .
					'<li>Make sure you enable Read &amp; Write access level</li>' .
					'<li>After this, go to the <a target="_blank" href="https://dev.twitter.com/apps">Twitter Application List page</a> and select your newly created application</li>' .
					'<li>Copy the values from these fields: <strong>Consumer Key</strong> and <strong>Consumer Secret</strong>, and enter them here:</li>' .
				'</ol>',
			'wdcp'),
				get_bloginfo('url')
			);
			echo '<label for="tw_api_key">' . __('Consumer key', 'wdcp') . '</label> ' .
				$this->_create_text_box('tw_api_key', $this->_get_option('tw_api_key')) .
			'<br />';
			echo '<label for="tw_app_secret">' . __('Consumer secret', 'wdcp') . '</label> ' .
				$this->_create_text_box('tw_app_secret', $this->_get_option('tw_app_secret')) .
			'<br />';
		} else {
			_e('<p><i>Your Network Admin already set this up for you</i></p>', 'wdcp');
		}
	if (is_network_admin()) {
			echo ''.
				$this->_create_checkbox('tw_network_only', $this->_get_option('tw_network_only')) .
				' <label for="tw_network_only">' . __('I want to use this app on all subsites too', 'wdcp') . '</label>' .
				'<div><small>' . __('Please, do <b>NOT</b> check this option if any of your sites use domain mapping.', 'wdcp') . '</small></div>' .
			'<br />';
		}
	}

	function create_skip_twitter_init_box () {
		echo
			$this->_create_checkbox('tw_skip_init', $this->_get_option('tw_skip_init')) .
			'<label for="tw_skip_init">' . __('Pages on my website already use javascript from Twitter', 'wdcp') . '</label> ' .
		"";
		echo "<p><small>" . __('If you already use a plugin or custom script to interact with Twitter, check this option', 'wdcp') . '</small></p>';
	}

	function create_hooks_section () {
		_e(
			"<p>If you do not see Comments Plus on your pages, it is likely that your theme doesn't use the hooks we need to display our interface.</p><p>It's not a problem, though - here you can specify the hooks your theme does use.</p>",
		'wdcp');
	}

	function create_start_hook_box () {
		$value = $this->_get_option('begin_injection_hook');
		$value = $value ? $value : 'comment_form_before';
		echo $this->_create_text_box('begin_injection_hook', $value);
		echo '<div><small>' . __('This is the hook that starts your comments form interface. By default, we\'re using <code>comment_form_before</code>. To reset to default, delete all contents of this field and save changes.', 'wdcp') . '</small></div>';	    		 	 			 		  
	}

	function create_end_hook_box () {
		$value = $this->_get_option('finish_injection_hook');
		$value = $value ? $value : 'comment_form_after';
		echo $this->_create_text_box('finish_injection_hook', $value);
		echo '<div><small>' . __('This is the hook that ends your comments form interface. By default, we\'re using <code>comment_form_after</code>. To reset to default, delete all contents of this field and save changes.', 'wdcp') . '</small></div>';
	}

	function create_wp_icon_box () {
		echo '<label for="wp_icon">' . __('Icon URL:', 'wdcp') . '</label>' .
			$this->_create_text_box('wp_icon', $this->_get_option('wp_icon')) .
		"";
		echo '<div><small>' . __('Full URL of the icon you wish to use for your WP comments instead of the default one', 'wdcp') . '</small></div>';
		echo '<div><small>' . __('For best results, use a 16x16px image.', 'wdcp') . '</small></div>';
		echo
			$this->_create_checkbox('show_instructions', $this->_get_option('show_instructions')) .
			'<label for="show_instructions">' . __('Attempt to show or hide allowed tags text for WordPress comments', 'wdcp') . '</label> ' .
		"";
	}

	function create_skip_services_box () {
		$services = array (
			'wordpress' => __('WordPress', 'wdcp'),
			'twitter' => __('Twitter', 'wdcp'),
			'facebook' => __('Facebook', 'wdcp'),
			'google' => __('Google', 'wdcp'),
		);
		$skips = $this->_get_option('skip_services');
		$skips = $skips ? $skips : array();

		foreach ($services as $key=>$label) {
			$checked = in_array($key, $skips) ? 'checked="checked"' : '';
			echo "" .
				"<input type='checkbox' name='wdcp_options[skip_services][]' id='wdcp-skip-{$key}' value='$key' {$checked} />" .
				"&nbsp;" .
				"<label for='wdcp-skip-{$key}'>{$label}</label>" .
				"<br />" .
			"";
		}
		echo '<br />';
		echo
			$this->_create_checkbox('stretch_tabs', $this->_get_option('stretch_tabs')) .
			'<label for="stretch_tabs">' . __('Shrink or stretch provider selector tabs?', 'wdcp') . '</label> ' .
		"";
		echo '<br />';
		echo
			$this->_create_checkbox('dont_select_social_sharing', $this->_get_option('dont_select_social_sharing')) .
			'<label for="dont_select_social_sharing">' . __('Don\'t pre-select social comments sharing', 'wdcp') . '</label> ' .
		"";
	}

	function create_style_box () {
		echo '' .
			$this->_create_checkbox('skip_color_css', $this->_get_option('skip_color_css')) .
			'&nbsp;' .
			'<label for="skip_color_css">' . __('Let my theme determine colors', 'wdcp') . '</label>' .
			'<div><small>' . __('Use this option if your theme already has color definitions needed for Comments Plus in its\' stylesheets', 'wdcp') . '</small></div>' .
		'';
	}

	function create_plugins_box () {
		$all = Wdcp_PluginsHandler::get_all_plugins();
		$active = Wdcp_PluginsHandler::get_active_plugins();
		$sections = array('thead', 'tfoot');

		echo "<table class='widefat'>";
		foreach ($sections as $section) {
			echo "<{$section}>";
			echo '<tr>';
			echo '<th width="30%">' . __('Add-on name', 'wdcp') . '</th>';
			echo '<th>' . __('Add-on description', 'wdcp') . '</th>';
			echo '</tr>';
			echo "</{$section}>";
		}
		echo "<tbody>";
		foreach ($all as $plugin) {
			$plugin_data = Wdcp_PluginsHandler::get_plugin_info($plugin);
			if (!@$plugin_data['Name']) continue; // Require the name
			$is_active = in_array($plugin, $active);
			echo "<tr>";
			echo "<td width='30%'>";
			echo '<b id="' . esc_attr($plugin) . '">' . $plugin_data['Name'] . '</b>';
			echo "<br />";
			echo ($is_active
				?
				'<a href="#deactivate" class="wdcp_deactivate_plugin" wdcp:plugin_id="' . esc_attr($plugin) . '">' . __('Deactivate', 'wdcp') . '</a>'
				:
				'<a href="#activate" class="wdcp_activate_plugin" wdcp:plugin_id="' . esc_attr($plugin) . '">' . __('Activate', 'wdcp') . '</a>'
			);
			echo "</td>";
			echo '<td>' .
				$plugin_data['Description'] .
				'<br />' .
				sprintf(__('Version %s', 'wdcp'), $plugin_data['Version']) .
				'&nbsp;|&nbsp;' .
				sprintf(__('by %s', 'wdcp'), '<a href="' . $plugin_data['Plugin URI'] . '">' . $plugin_data['Author'] . '</a>') .
			'</td>';
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";

		echo <<<EOWdcpPluginJs
<script type="text/javascript">
(function ($) {
$(function () {
	$(".wdcp_activate_plugin").click(function () {
		var me = $(this);
		var plugin_id = me.attr("wdcp:plugin_id");
		$.post(ajaxurl, {"action": "wdcp_activate_plugin", "plugin": plugin_id}, function (data) {
			window.location.reload();
		}, 'json');
		return false;
	});
	$(".wdcp_deactivate_plugin").click(function () {
		var me = $(this);
		var plugin_id = me.attr("wdcp:plugin_id");
		$.post(ajaxurl, {"action": "wdcp_deactivate_plugin", "plugin": plugin_id}, function (data) {
			window.location.reload();
		}, 'json');
		return false;
	});
});
})(jQuery);
</script>
EOWdcpPluginJs;
	}

}