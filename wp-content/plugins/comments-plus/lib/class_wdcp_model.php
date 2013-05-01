<?php
class Wdcp_Model {

	var $twitter;
	var $facebook;

	var $_facebook_user_cache = false;
	var $_twitter_user_cache = false;
	var $_google_user_cache = false;

	function Wdcp_Model () { $this->__construct(); }

	function __construct () {
		if ($this->_load_dependencies()) {
			if (!(defined('WDCP_FACEBOOK_SSL_CERTIFICATE') && WDCP_FACEBOOK_SSL_CERTIFICATE)) {
				if (isset(Facebook::$CURL_OPTS)) {
					Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 0;
					Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = 0;
				}
			}
			$this->facebook = new Facebook(array(
				'appId' => WDCP_APP_ID,
				'secret' => WDCP_APP_SECRET,
				'cookie' => true,
			));
			$this->twitter = new TwitterOAuth(WDCP_CONSUMER_KEY, WDCP_CONSUMER_SECRET);
			$this->openid = new LightOpenID;

			$this->_initialize();
		} // else...
	}

	function _load_dependencies () {
		if (!class_exists('TwitterOAuth')) require_once WDCP_PLUGIN_BASE_DIR . '/lib/external/twitter/twitteroauth.php';
		if (!class_exists('Facebook')) require_once WDCP_PLUGIN_BASE_DIR . '/lib/external/facebook/facebook.php';
		if (!class_exists('LightOpenID')) require_once WDCP_PLUGIN_BASE_DIR . '/lib/external/lightopenid/openid.php';
		return (
			class_exists('Facebook') &&
			class_exists('TwitterOAuth') &&
			class_exists('LightOpenID')
		);
	}

	/**
	 * Initializes external API handlers
	 *
	 * @access private
	 */
	function _initialize () {
		if (!session_id()) session_start();
		// Facebook
		try {
			if ($this->facebook->getUser()) {
				$_SESSION['wdcp_facebook_user_cache'] = isset($_SESSION['wdcp_facebook_user_cache'])
					? $_SESSION['wdcp_facebook_user_cache'] : $this->facebook->api('/me');
				$this->_facebook_user_cache = $_SESSION['wdcp_facebook_user_cache'];
			}
		} catch (Exception $e) {}

		// Google
		$this->openid->identity = 'https://www.google.com/accounts/o8/id';
		$this->openid->required = array('namePerson/first', 'namePerson/last', 'contact/email');
		if (!empty($_REQUEST['openid_ns'])) {
			$cache = $this->openid->getAttributes();
			if (isset($cache['namePerson/first']) || isset($cache['namePerson/last']) || isset($cache['contact/email'])) {
				$_SESSION['wdcp_google_user_cache'] = $cache;
			}
		}
		$this->_google_user_cache = !empty($_SESSION['wdcp_google_user_cache']) ? $_SESSION['wdcp_google_user_cache'] : false;

		// Twitter
		add_filter('wdcp-oauth-twitter-generate_timestamp', array($this, 'set_up_twitter_timestamp_delta'));
		if (isset($_REQUEST['oauth_verifier']) && !isset($_SESSION['wdcp_twitter_user_cache']['_done'])) {
			$_SESSION['wdcp_twitter_user_cache']['token']['oauth_token'] = $_REQUEST['oauth_token'];
			$verifier = $_REQUEST['oauth_verifier'];
			$token = $_SESSION['wdcp_twitter_user_cache']['token'];

			$this->twitter = new TwitterOAuth(WDCP_CONSUMER_KEY, WDCP_CONSUMER_SECRET, $token['oauth_token'], $token['oauth_token_secret']);
			$_SESSION['wdcp_twitter_user_cache']['access_token'] = $this->twitter->getAccessToken($verifier);
			$_SESSION['wdcp_twitter_user_cache']['verifier'] = $verifier;

			$response = $this->twitter->get('account/verify_credentials');
			if ($response->id_str) {
				$_SESSION['wdcp_twitter_user_cache']['user'] = array (
					'id' => $response->id_str,
					'name' => $response->name,
					'username' => $response->screen_name,
					'url' => $response->url,
					'image' => $response->profile_image_url,
				);
				$this->_twitter_user_cache = !empty($_SESSION['wdcp_twitter_user_cache']['user']) ? $_SESSION['wdcp_twitter_user_cache']['user'] : false;
			}
			$_SESSION['wdcp_twitter_user_cache']['_done'] = true;
		} else if (!empty($_SESSION['wdcp_twitter_user_cache']['access_token'])) {
			$token = $_SESSION['wdcp_twitter_user_cache']['access_token'];
			$this->twitter = new TwitterOAuth(WDCP_CONSUMER_KEY, WDCP_CONSUMER_SECRET, $token['oauth_token'], $token['oauth_token_secret']);
			$this->_twitter_user_cache = !empty($_SESSION['wdcp_twitter_user_cache']['user']) ? $_SESSION['wdcp_twitter_user_cache']['user'] : false;
		}
	}

	function set_up_twitter_timestamp_delta ($time) {
		$timestamp_delta = get_site_option('wdcp_twitter_timestamp_delta_fix', false);
		return !empty($timestamp_delta)
			? $time + $timestamp_delta
			: $time
		;
	}

	function current_user_logged_in ($provider) {
		$provider = esc_html(trim(strtolower($provider)));
		switch ($provider) {
			case "wordpress":
				return is_user_logged_in();
			case "facebook":
				return $this->facebook->getUser() ? true : false;
			case "twitter":
				return isset($_SESSION['wdcp_twitter_user_cache']['access_token']) ? true : false;
			case "google":
				return isset($_SESSION['wdcp_google_user_cache']) ? true : false;
		}
		return false;
	}

	function current_user_id ($provider) {
		$provider = esc_html(trim(strtolower($provider)));
		switch ($provider) {
			case "wordpress":
				global $current_user;
				return $current_user->ID;
			case "facebook":
				return @$this->facebook->getUser();
			case "twitter":
				return $this->_twitter_user_cache['id'];
			case "google":
				return $this->openid->identity;
		}
		return false;
	}

	function current_user_name ($provider) {
		$provider = esc_html(trim(strtolower($provider)));
		switch ($provider) {
			case "wordpress":
				global $current_user;
				return $current_user->user_login;
			case "facebook":
				return @$this->_facebook_user_cache['name'];
			case "twitter":
				return $this->_twitter_user_cache['name'];
			case "google":
				return @$this->_google_user_cache['namePerson/first'] . ' ' . @$this->_google_user_cache['namePerson/last'];
		}
		return false;
	}

	function current_user_username ($provider) {
		$provider = esc_html(trim(strtolower($provider)));
		switch ($provider) {
			case "wordpress":
				global $current_user;
				return $current_user->user_login;
			case "facebook":
				return @$this->_facebook_user_cache['email'];
			case "twitter":
				return $this->_twitter_user_cache['username'];
			case "google":
				return @$this->_google_user_cache['contact/email'];
		}
		return false;
	}

	function current_user_email ($provider) {
		$provider = esc_html(trim(strtolower($provider)));
		switch ($provider) {
			case "wordpress":
				global $current_user;
				return $current_user->user_email;
			case "facebook":
				return @$this->_facebook_user_cache['email'];
			case "twitter":
				return '';
			case "google":
				return @$this->_google_user_cache['contact/email'];
		}
	}

	function current_user_url ($provider) {
		$provider = esc_html(trim(strtolower($provider)));
		switch ($provider) {
			case "wordpress":
				return site_url();
			case "facebook":
				return 'http://www.facebook.com/profile.php?id=' . $this->current_user_id('facebook');
			case "twitter":
				return $this->_twitter_user_cache['url'];
		}
		return false;
	}

	function facebook_logout_user () {
		$_SESSION['wdcp_facebook_user_cache'] = false;
		$this->facebook->destroySession();
		unset($_SESSION['wdcp_facebook_user_cache']);
	}

	function get_google_auth_url ($url) {
		$this->openid->returnUrl = $url;
		$this->openid->realm = WDCP_PROTOCOL . $_SERVER['HTTP_HOST'];
		return $this->openid->authUrl();
	}
	function google_logout_user () {
		$_SESSION['wdcp_google_user_cache'] = false;
		unset($_SESSION['wdcp_google_user_cache']);
	}

	function twitter_avatar () {
		return $this->_twitter_user_cache['image'];
	}
	function get_twitter_auth_url ($url) {
		if (isset($_SESSION['wdcp_twitter_user_cache']['oauth_url'])) {
			$query = parse_url($_SESSION['wdcp_twitter_user_cache']['oauth_url'], PHP_URL_QUERY);
			parse_str($query, $url_test);
			if (!empty($url_test['oauth_token'])) return $_SESSION['wdcp_twitter_user_cache']['oauth_url'];

			// Here we are, meaning we have erroneous URL cached. Clear and proceed
			unset($_SESSION['wdcp_twitter_user_cache']['oauth_url']);
		}
		$tw_token = $this->twitter->getRequestToken($url);
		if (empty($tw_token['oauth_token'])) {
			return false;
		}

		$tw_url = $this->twitter->getAuthorizeURL($tw_token['oauth_token']);
		$_SESSION['wdcp_twitter_user_cache']['token'] = $tw_token;
		$_SESSION['wdcp_twitter_user_cache']['oauth_url'] = $tw_url;
		return $tw_url;
	}
	function twitter_logout_user () {
		$_SESSION['wdcp_twitter_user_cache'] = false;
		unset($_SESSION['wdcp_twitter_user_cache']);
	}

/*** Posting ***/

	function post_to_facebook ($data) {
		$fb_uid = $this->current_user_id('facebook');
		$post_id = (int)$_POST['post_id'];
		$post = get_post($post_id);
		$data['comment'] = stripslashes($data['comment']); // Forcing stripslashes
		$send = apply_filters('wdcp-post_to_facebook-data', array(
			'caption' => substr($data['comment'], 0, 999),
			'message' => substr($data['comment'], 0, 999),
			'link' => apply_filters('wdcp-remote_post_data-post_url', apply_filters('wdcp-remote_post_data-facebook-post_url', get_permalink($post_id), $post_id), $post_id),
			'name' => $post->post_title,
			'description' => get_option('blogdescription'),
		), $post_id);
		try {
			$ret = $this->facebook->api('/' . $fb_uid . '/feed/', 'POST', $send);
		} catch (Exception $e) {
			return false;
		}
		return $ret; // $ret['id']
	}

	function post_to_twitter ($data) {
		$post_id = (int)$data['post_id'];
		$link = apply_filters('wdcp-remote_post_data-post_url', apply_filters('wdcp-remote_post_data-twitter-post_url', get_permalink($post_id), $post_id), $post_id);	    		 	 			 		  
		$send = apply_filters('wdcp-remote_post_data-twitter', array(
			'status' => substr($link . ' ' . $data['comment'], 0, 140),
		), $data);
		try {
			$ret = $this->twitter->post('statuses/update', $send);
		} catch (Exception $e) {
			return false;
		}
		return $ret; // $ret->id_str
	}
}