<?php defined('WP_UNINSTALL_PLUGIN') or exit;

// Remove all plugin data from the database
global $wpdb;
$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '_gdd_speedy_page_redirect'");
delete_option('gdd_spr_version');