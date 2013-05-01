<?php
/*
Plugin Name: Facebook Locale
Description: Forces your selection for localized Facebook scripts. By default, the plugin will try to auto-detect your locale settings and load the appropriate API scripts. With this add-on, you can explicitly tell which language you want to use for communicating with Facebook.
Plugin URI: http://premium.wpmudev.org/project/comments-plus
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Wdcp_Fbl_AdminPages {

	private $_data;

	private function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Fbl_AdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wdcp-options-plugins_options', array($this, 'register_settings'));
	}

	function register_settings () {
		add_settings_section('wdcp_fbl_settings', __('Facebook Locale', 'wdcp'), array($this, 'create_notice_box'), 'wdcp_options');
		add_settings_field('wdcp_fbl_username', __('Select your locale', 'wdcp'), array($this, 'create_locale_box'), 'wdcp_options', 'wdcp_fbl_settings');
	}

	function create_notice_box () {
		echo '<em>' . __('By default, the plugin will try to auto-detect your locale settings and load the appropriate API scripts. However, not all locales are supported by Facebook, so your milleage may vary. With this option, you can explicitly tell which language you want to use for communicating with Facebook.', 'wdcp') . '</em>';	    		 	 			 		  
	}

	function create_locale_box () {
		$locale = esc_attr($this->_data->get_option('fbl_locale'));
		$fb_locales = array(
			__("Automatic detection", 'wdcp') => '',
			"Afrikaans" => "af_ZA",
			"Arabic" => "ar_AR",
			"Azeri" => "az_AZ",
			"Belarusian" => "be_BY",
			"Bulgarian" => "bg_BG",
			"Bengali" => "bn_IN",
			"Bosnian" => "bs_BA",
			"Catalan" => "ca_ES",
			"Czech" => "cs_CZ",
			"Welsh" => "cy_GB",
			"Danish" => "da_DK",
			"German" => "de_DE",
			"Greek" => "el_GR",
			"English (UK)" => "en_GB",
			"English (Pirate)" => "en_PI",
			"English (Upside Down)" => "en_UD",
			"English (US)" => "en_US",
			"Esperanto" => "eo_EO",
			"Spanish (Spain)" => "es_ES",
			"Spanish" => "es_LA",
			"Estonian" => "et_EE",
			"Basque" => "eu_ES",
			"Persian" => "fa_IR",
			"Leet Speak" => "fb_LT",
			"Finnish" => "fi_FI",
			"Faroese" => "fo_FO",
			"French (Canada)" => "fr_CA",
			"French (France)" => "fr_FR",
			"Frisian" => "fy_NL",
			"Irish" => "ga_IE",
			"Galician" => "gl_ES",
			"Hebrew" => "he_IL",
			"Hindi" => "hi_IN",
			"Croatian" => "hr_HR",
			"Hungarian" => "hu_HU",
			"Armenian" => "hy_AM",
			"Indonesian" => "id_ID",
			"Icelandic" => "is_IS",
			"Italian" => "it_IT",
			"Japanese" => "ja_JP",
			"Georgian" => "ka_GE",
			"Korean" => "ko_KR",
			"Kurdish" => "ku_TR",
			"Latin" => "la_VA",
			"Lithuanian" => "lt_LT",
			"Latvian" => "lv_LV",
			"Macedonian" => "mk_MK",
			"Malayalam" => "ml_IN",
			"Malay" => "ms_MY",
			"Norwegian (bokmal)" => "nb_NO",
			"Nepali" => "ne_NP",
			"Dutch" => "nl_NL",
			"Norwegian (nynorsk)" => "nn_NO",
			"Punjabi" => "pa_IN",
			"Polish" => "pl_PL",
			"Pashto" => "ps_AF",
			"Portuguese (Brazil)" => "pt_BR",
			"Portuguese (Portugal)" => "pt_PT",
			"Romanian" => "ro_RO",
			"Russian" => "ru_RU",
			"Slovak" => "sk_SK",
			"Slovenian" => "sl_SI",
			"Albanian" => "sq_AL",
			"Serbian" => "sr_RS",
			"Swedish" => "sv_SE",
			"Swahili" => "sw_KE",
			"Tamil" => "ta_IN",
			"Telugu" => "te_IN",
			"Thai" => "th_TH",
			"Filipino" => "tl_PH",
			"Turkish" => "tr_TR",
			"Ukrainian" => "uk_UA",
			"Vietnamese" => "vi_VN",
			"Simplified Chinese (China)" => "zh_CN",
			"Traditional Chinese (Hong Kong)" => "zh_HK",
			"Traditional Chinese (Taiwan)" => "zh_TW",
		);
		echo "<select name='wdcp_options[fbl_locale]'>";
		foreach ($fb_locales as $label => $loc) {
			$checked = ($locale == $loc) ? 'selected="selected"' : '';
			echo "<option value='{$loc}' {$checked}>{$label}</option>";
		}
		echo "</select>";
	}
}


class Wdcp_Fbl_PublicPages {

	private $_data;

	private function __construct () {
		$this->_data = new Wdcp_Options;
	}

	public static function serve () {
		$me = new Wdcp_Fbl_PublicPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wdcp-locale-facebook_locale', array($this, 'apply_locale'));
	}

	function apply_locale ($original_locale) {
		$locale = $this->_data->get_option('fbl_locale');
		return $locale
			? esc_attr($locale)
			: $original_locale
		;
	}
}

if (is_admin()) Wdcp_Fbl_AdminPages::serve();
else Wdcp_Fbl_PublicPages::serve();