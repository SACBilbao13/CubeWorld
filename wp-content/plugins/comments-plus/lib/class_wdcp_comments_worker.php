<?php
class Wdcp_CommentsWorker {

	var $model;
	var $data;

	function Wdcp_CommentsWorker () { $this->__construct(); }

	function __construct () {
		$this->model = new Wdcp_Model;
		$this->data = new Wdcp_Options;
	}

	function js_load_scripts () {
		wp_enqueue_script('jquery');
		if (!apply_filters('wdcp-script_inclusion-facebook', WDCP_SKIP_FACEBOOK)) {
			$locale = defined('WDCP_FACEBOOK_LOCALE') && WDCP_FACEBOOK_LOCALE
				? WDCP_FACEBOOK_LOCALE : preg_replace('/-/', '_', get_locale())
			;
			$locale = apply_filters('wdcp-locale-facebook_locale', $locale);
			wp_enqueue_script('facebook-all', WDCP_PROTOCOL . 'connect.facebook.net/' . $locale . '/all.js');
		}
		if (!apply_filters('wdcp-script_inclusion-twitter', WDCP_SKIP_TWITTER)) {
			wp_enqueue_script('twitter-anywhere', WDCP_PROTOCOL . 'platform.twitter.com/anywhere.js?id=' . WDCP_TW_API_KEY . '&v=1');
		}
		wp_enqueue_script('wdcp_comments', WDCP_PLUGIN_URL . '/js/comments.js', array('jquery'));
		wp_enqueue_script('wdcp_twitter', WDCP_PLUGIN_URL . '/js/twitter.js', array('jquery', 'wdcp_comments'));
		wp_enqueue_script('wdcp_facebook', WDCP_PLUGIN_URL . '/js/facebook.js', array('jquery', 'wdcp_comments'));
		wp_enqueue_script('wdcp_google', WDCP_PLUGIN_URL . '/js/google.js', array('jquery', 'wdcp_comments'));

		printf(
			//'<script type="text/javascript">var _wdcp_post_id="%d";</script>',
			//get_the_ID()
			'<script type="text/javascript">var _wdcp_data={"post_id": %d, "fit_tabs": %d, "text": {"reply": "%s", "cancel_reply": "%s"}};</script>',
			get_the_ID(), (int)$this->data->get_option('stretch_tabs'),
			esc_js(__('Reply', 'wdcp')), esc_js(__('Cancel reply', 'wdcp'))
		);
	}

	function css_load_styles () {
		$skip = $this->data->get_option('skip_color_css');
		wp_enqueue_style('wdcp_comments', WDCP_PLUGIN_URL . '/css/comments.css');
		if (!current_theme_supports('wdcp_comments-specific') && !$skip) {
			wp_enqueue_style('wdcp_comments-specific', WDCP_PLUGIN_URL . '/css/comments-specific.css');
		}

		$icon = $this->data->get_option('wp_icon');
		if ($icon) {
			$selector = apply_filters('wdcp-wordpress_custom_icon_selector', 'ul#all-comment-providers li a#comment-provider-wordpress-link');
			printf(
				'<style type="text/css">
					%s {
						background-image: url(%s) !important;
					}
				</style>', $selector, $icon);
		}
	}

	function header_dependencies () {
		echo $this->_prepare_header_dependencies();
	}

	function begin_injection () {
		$skips = (array)$this->data->get_option('skip_services');
		$instructions = $this->data->get_option('show_instructions') ? '' : 'no-instructions';

		if (!in_array('facebook', $skips)) $fb_html = $this->_prepare_facebook_comments();
		if (!in_array('twitter', $skips)) $tw_html = $this->_prepare_twitter_comments();
		if (!in_array('google', $skips)) $gg_html = $this->_prepare_google_comments();

		if (!in_array('wordpress', $skips)) {
			$default_name = defined('WDCP_DEFAULT_WP_PROVIDER_NAME') && WDCP_DEFAULT_WP_PROVIDER_NAME ? WDCP_DEFAULT_WP_PROVIDER_NAME : get_bloginfo('name');
			$default_name = $default_name ? $default_name : 'WordPress';
			$wp_name = $this->model->current_user_logged_in('wordpress')
				? $this->model->current_user_name('wordpress')
				: apply_filters('wdcp-providers-wordpress-name', $default_name)
			;
		}
		if (!in_array('twitter', $skips)) $tw_name = $this->model->current_user_logged_in('twitter') ? $this->model->current_user_name('twitter') : apply_filters('wdcp-providers-twitter-name', 'Twitter');
		if (!in_array('facebook', $skips)) $fb_name = $this->model->current_user_logged_in('facebook') ? $this->model->current_user_name('facebook') : apply_filters('wdcp-providers-facebook-name', 'Facebook');
		if (!in_array('google', $skips)) $gg_name = $this->model->current_user_logged_in('google') ? $this->model->current_user_name('google') : apply_filters('wdcp-providers-google-name', 'Google');
		echo "
		<div id='comment-providers-select-message'>" . __("Click on a tab to select how you'd like to leave your comment", 'wdcp') . "</div>
		<div id='comment-providers'><a name='comments-plus-form'></a>
			<ul id='all-comment-providers'>";
		if (!in_array('wordpress', $skips)) echo "<li><a id='comment-provider-wordpress-link' href='#comment-provider-wordpress'><span>$wp_name</span></a></li>";
		if (!in_array('twitter', $skips)) echo "<li><a id='comment-provider-twitter-link' href='#comment-provider-twitter'><span>$tw_name</span></a></li>";
		if (!in_array('facebook', $skips)) echo "<li><a id='comment-provider-facebook-link' href='#comment-provider-facebook'><span>$fb_name</span></a></li>";
		if (!in_array('google', $skips)) echo "<li><a id='comment-provider-google-link' href='#comment-provider-google'><span>$gg_name</span></a></li>";
		echo "</ul>";
		if (!in_array('facebook', $skips)) echo "<div class='comment-provider' id='comment-provider-facebook'>$fb_html</div>";
		if (!in_array('twitter', $skips)) echo "<div class='comment-provider' id='comment-provider-twitter'>$tw_html</div>";
		if (!in_array('google', $skips)) echo "<div class='comment-provider' id='comment-provider-google'>$gg_html</div>";
		echo "<div class='comment-provider {$instructions}' id='comment-provider-wordpress'>";
	}

	function finish_injection () {
		echo "</div> <!-- Wordpress provider -->";
		echo "</div> <!-- #comment-providers -->";
	}

	function footer_dependencies () {
		echo $this->_prepare_footer_dependencies();
	}

	function replace_avatars ($avatar, $comment) {
		if (!is_object($comment) || !isset($comment->comment_ID)) return $avatar;
		$fb_uid = false;

		$meta = get_comment_meta($comment->comment_ID, 'wdcp_comment', true);
		if (!$meta) return $avatar;

		$fb_uid = @$meta['wdcp_fb_author_id'];
		if (!$fb_uid) {
			$tw_avatar = @$meta['wdcp_tw_avatar'];
			if (!$tw_avatar) return $avatar;
			return "<img class='avatar avatar-40 photo' width='40' height='40' src='{$tw_avatar}' />";
		}

		return "<img class='avatar avatar-40 photo' width='40' height='40' src='". WDCP_PROTOCOL . "graph.facebook.com/{$fb_uid}/picture' />";
	}

/*** Privates ***/

	function _prepare_header_dependencies () {
	}

	function _prepare_facebook_comments () {
		if (!$this->model->current_user_logged_in('facebook')) return $this->_prepare_facebook_login();
		$preselect = $this->data->get_option('dont_select_social_sharing') ? '' : 'checked="checked"';
		$disconnect = __('Disconnect', 'wdcp');
		return "
			<p>" . __('Connected as', 'wdcp') . " <b class='connected-as'>" . $this->model->current_user_name('facebook') . "</b>. <a class='comment-provider-logout' href='#'>{$disconnect}</a></p>
			<textarea id='facebook-comment' rows='8' cols='45' rows='6'></textarea>
			<p><label for='post-on-facebook'><input type='checkbox' id='post-on-facebook' value='1' {$preselect} /> " . __("Post my comment on my wall", "wdcp"). "</label></p>
			<p><a class='button' href='#' id='send-facebook-comment'>" . sprintf(__('Comment via %s', 'wdcp'), 'Facebook') . "</a></p>
		";
	}

	function _prepare_facebook_login () {
		return "<img src='" . WDCP_PLUGIN_URL . "/img/fb-login.png' style='position:absolute;left:-1200000000px;display:none' />" . '<div class="comment-provider-login-button" id="login-with-facebook"><a href="#" title="' . __('Login with Facebook', 'wdcp') . '"><span>Login</span></a></div>';
	}

	function _prepare_google_comments () {
		if (!$this->model->current_user_logged_in('google')) return $this->_prepare_google_login();
		$disconnect = __('Disconnect', 'wdcp');
		return "
			<p>" . __('Connected as', 'wdcp') . " <b class='connected-as'>" . $this->model->current_user_name('google') . "</b>. <a class='comment-provider-logout' href='#'>{$disconnect}</a></p>
			<textarea id='google-comment' rows='8' cols='45' rows='6'></textarea>
			<p><a class='button' href='#' id='send-google-comment'>" . sprintf(__('Comment via %s', 'wdcp'), 'Google') . "</a></p>
		";
	}

	function _prepare_google_login () {
		$href = WDCP_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		return "<img src='" . WDCP_PLUGIN_URL . "/img/gg-login.png' style='position:absolute;left:-1200000000px;display:none' />" . '<div class="comment-provider-login-button" id="login-with-google"><a href="' . $href . '" title="' . __('Login with Google', 'wdcp') . '"><span>Login</span></a></div>';
	}

	function _prepare_twitter_comments () {
		if (!$this->model->current_user_logged_in('twitter')) return $this->_prepare_twitter_login();
		$preselect = $this->data->get_option('dont_select_social_sharing') ? '' : 'checked="checked"';
		$disconnect = __('Disconnect', 'wdcp');
		return "
			<p>" . __('Connected as', 'wdcp') . " <b class='connected-as'>" . $this->model->current_user_name('twitter') . "</b>. <a class='comment-provider-logout' href='#'>{$disconnect}</a></p>
			<textarea id='twitter-comment' rows='8' cols='45' rows='6'></textarea>
			<p><label for='post-on-twitter'><input type='checkbox' id='post-on-twitter' value='1' {$preselect} /> " . __("Post my comment on Twitter", "wdcp"). "</label></p>
			<p><a class='button' href='#' id='send-twitter-comment'>" . sprintf(__('Comment via %s', 'wdcp'), 'Twitter') . "</a></p>
		";
	}

	function _prepare_twitter_login () {
		$href = WDCP_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		return "<img src='" . WDCP_PLUGIN_URL . "/img/tw-login.png' style='position:absolute;left:-1200000000px;display:none' />" . '<div class="comment-provider-login-button" id="login-with-twitter"><a href="' . $href . '" title="' . __('Login with Twitter', 'wdcp') . '"><span>Login</span></a></div>';	    		 	 			 		  
	}

	function _prepare_footer_dependencies () {
		if (WDCP_SKIP_FACEBOOK) $fb_part = ''; // Solve possible UFb conflict
		else $fb_part = "<div id='fb-root'></div>" .
			"<script>
			FB.init({
				appId: '" . WDCP_APP_ID . "',
				status: true,
				cookie: true,
				xfbml: true,
				oauth: true
			});
			</script>";

		$tw_part = sprintf(
			'<script type="text/javascript">jQuery(function () { if ("undefined" != typeof twttr && twttr.anywhere && twttr.anywhere.config) twttr.anywhere.config({ callbackURL: "%s" }); });</script>',
			get_permalink()
		);
		$fb_part = apply_filters('wdcp-service_initialization-facebook', $fb_part);
		$tw_part = apply_filters('wdcp-service_initialization-twitter', $tw_part);
		return "{$fb_part}{$tw_part}";
	}

}