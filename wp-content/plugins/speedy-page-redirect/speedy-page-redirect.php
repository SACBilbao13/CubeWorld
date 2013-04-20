<?php defined('ABSPATH') or exit;
/*
Plugin Name: Speedy Page Redirect
Plugin URI: http://wordpress.org/extend/plugins/speedy-page-redirect/
Description: Redirect pages and posts to other locations.
Version: 0.3
Author: Geert De Deckere
Author URI: http://www.geertdedeckere.be/
License: GPLv2

Copyright (C) 2011  Geert De Deckere

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Start your engines!
new GDD_Speedy_Page_Redirect;

class GDD_Speedy_Page_Redirect {

	/**
	 * Plugin version number.
	 *
	 * @var string
	 */
	const VERSION = '0.3';

	/**
	 * List of post types for which to enable this plugin.
	 * Note: can be filtered via "gdd_spr_post_types".
	 *
	 * @var array
	 */
	public $post_types;

	/**
	 * List of redirection types: key = HTTP response status code, value = description.
	 * Note: can be filtered via "gdd_spr_statuses".
	 *
	 * @var array
	 */
	public $statuses;

	/**
	 * Redirection data from the postmeta table, structured by blog_id and post_id.
	 *
	 * @var array
	 */
	public $data;

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Run update routine if needed, also upon activation
		if (version_compare(self::VERSION, get_option('gdd_spr_version', 0), '>'))
		{
			$this->update();
		}

		// This init action should happen after register_post_type calls: priority > 10
		add_action('init', array($this, 'init'), 20);
	}

	/**
	 * Update the plugin to a newer version.
	 *
	 * @return void
	 */
	public function update()
	{
		// Store version of the installed plugin for future updates
		update_option('gdd_spr_version', self::VERSION);
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function init()
	{
		// Load translated strings
		load_plugin_textdomain('speedy-page-redirect', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');

		// Automatically include all public custom post types
		$this->post_types = array_merge(array('page' => 'page', 'post' => 'post'), get_post_types(array('_builtin' => FALSE)));

		// Allow user to modify the post types
		$this->post_types = apply_filters('gdd_spr_post_types', $this->post_types);

		// Avoid needless work
		if (empty($this->post_types))
			return;

		// Mirror the post types array so we can do fast isset() checks on the keys
		$this->post_types = array_combine($this->post_types, $this->post_types);

		// Array with types of redirects: key = HTTP response status code, value = description
		// Note: first element in the array will be selected by default
		$this->statuses = array(
			301 => sprintf(__('Permanent', 'speedy-page-redirect'), '301'),
			302 => sprintf(__('Temporary', 'speedy-page-redirect'), '302'),
		);

		// Allow user to modify the status list
		$this->statuses = apply_filters('gdd_spr_statuses', $this->statuses);

		// Add the link actions only for the applicable post types: pages, posts and/or custom post types
		if (isset($this->post_types['page']))
		{
			add_action('page_link', array($this, 'link'), 20, 2);
		}
		if (isset($this->post_types['post']))
		{
			add_action('post_link', array($this, 'link'), 20, 2);
		}
		if (array_diff($this->post_types, array('page', 'post')))
		{
			add_action('post_type_link', array($this, 'link'), 20, 2);
		}

		// Action for the actual redirect
		add_action('template_redirect', array($this, 'template_redirect'));

		// Stuff that's only required in the admin area
		if (is_admin())
		{
			// Meta box setup
			add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
			add_action('save_post', array($this, 'save_post'));
		}
	}

	/**
	 * Add meta boxes for page redirection to all applicable post types.
	 *
	 * @return void
	 */
	public function add_meta_boxes()
	{
		// Add meta box for each post type
		foreach ($this->post_types as $post_type)
		{
			add_meta_box('gdd_page_redirect', __('Page Redirect', 'speedy-page-redirect'), array($this, 'meta_box_show'), $post_type);
		}
	}

	/**
	 * Output the form for the page redirection meta box.
	 *
	 * @param object $post post object
	 * @return void
	 */
	public function meta_box_show($post)
	{
		// Default data entered in the form
		$default = array(
			'url_raw' => 'http://',
			'status' => 301,
		);

		// Load existing redirection data for this post if any
		$data = (array) $this->get_post_data($post->ID);

		// Overwrite default values with existing ones
		$values = array_merge($default, $data);

		// Add a hidden nonce field for security
		wp_nonce_field('gdd_spr_'.$post->ID, 'gdd_spr_nonce', FALSE);

		// Output the URL field
		echo '<p>';
		echo '<label for="gdd_spr_url">'.__('Destination URL:', 'speedy-page-redirect').'</label> ';
		echo '<input id="gdd_spr_url" name="gdd_spr_url" type="text" value="'.esc_url($values['url_raw']).'" size="50" style="width:80%">';
		echo '</p>';

		// Output the redirection type select list if needed
		if (count($this->statuses) > 1)
		{
			echo '<p>';
			echo '<label for="gdd_spr_status">'.__('Type of redirect:', 'speedy-page-redirect').'</label> ';
			echo '<select id="gdd_spr_status" name="gdd_spr_status">';
			foreach ($this->statuses as $status => $description)
			{
				echo '<option value="'.$status.'" '.selected($status, $values['status'], FALSE).' title="'.sprintf(esc_attr__('HTTP response status code: %s', 'speedy-page-redirect'), $status).'">';
				echo esc_html($description);
				echo '</option>';
			}
			echo '</select>';
			echo '</p>';
		}
	}

	/**
	 * Update post redirection data in database.
	 *
	 * @param integer $post_id post ID
	 * @return void
	 */
	public function save_post($post_id)
	{
		// Validate nonce
		if ( ! isset($_POST['gdd_spr_nonce']) || ! wp_verify_nonce($_POST['gdd_spr_nonce'], 'gdd_spr_'.$post_id))
			return;

		// Basic clean of the entered URL if any
		$url = (isset($_POST['gdd_spr_url'])) ? trim((string) $_POST['gdd_spr_url']) : '';

		// A URL was entered (standalone protocols like "http://" are considered emtpy)
		if ($url !== '' && ! preg_match('~^[-a-z0-9+.]++://$~i', $url))
		{
			// Prepare data array to store in the database
			$data['url'] = esc_url_raw($url);
			// Grab first status key from the list by default
			$data['status'] = (int) key($this->statuses);
			$data['status'] = ( ! empty($data['status'])) ? $data['status'] : 301;

			// Overwrite the default status with the selected one if any
			if (isset($_POST['gdd_spr_status']) && isset($this->statuses[(int) $_POST['gdd_spr_status']]))
			{
				$data['status'] = (int) $_POST['gdd_spr_status'];
			}

			// Save the data in the postmeta table
			update_post_meta($post_id, '_gdd_speedy_page_redirect', $data);
		}
		// No URL entered
		else
		{
			// Delete any possible previous data stored for this post
			delete_post_meta($post_id, '_gdd_speedy_page_redirect');
		}
	}

	/**
	 * Return the new destination URL of a post in case of a permanent redirect.
	 *
	 * @param string $url URL of the post
	 * @param integer|object $post post ID or post object
	 * @return string post URL
	 */
	public function link($url, $post)
	{
		// Only continue if page redirection is enabled for this post type
		if ( ! isset($this->post_types[(string) get_post_type($post)]))
			return $url;

		// page_link action returns ID, post_link action returns object
		$post_id = (isset($post->ID)) ? $post->ID : $post;

		// No redirection data found
		if ( ! $data = $this->get_post_data($post_id))
			return $url;

		// Only hard-code the destionation URL in case of a permanent redirect
		if ($data['status'] != 301)
			return $url;

		// Return the destination URL
		return $data['url'];
	}

	/**
	 * Perform the actual redirect if needed.
	 *
	 * @return void
	 */
	public function template_redirect()
	{
		global $post;

		// Redirects only apply to pages or single posts
		if ( ! is_page() && ! is_single())
			return;

		// Only continue if page redirection is enabled for this post type
		if ( ! isset($this->post_types[(string) get_post_type($post)]))
			return;

		// No redirection data found for this post
		if ( ! $data = $this->get_post_data($post->ID))
			return;

		// Finally do the redirect and quit
		wp_redirect($data['url'], $data['status']);
		exit;
	}

	/**
	 * Get redirection data for a post.
	 *
	 * @param integer|object $post post ID or post object
	 * @param integer|object $blog blog ID or blog object
	 * @return array|NULL post redirection data for the post
	 */
	public function get_post_data($post, $blog = NULL)
	{
		// Clean post ID
		$post_id = (int) ((isset($post->ID)) ? $post->ID : $post);

		// Clean blog ID
		if ( ! $blog_id = (int) ((isset($blog->blog_id)) ? $blog->blog_id : $blog))
		{
			// Use current blog ID by default
			global $blog_id;
		}

		// Load redirection data for this blog from the database
		if ( ! isset($this->data[$blog_id]))
		{
			// Load redirection data for all posts of this blog
			global $wpdb;
			$rows = $wpdb->get_results('SELECT post_id, meta_value FROM '.$wpdb->postmeta.' WHERE meta_key = "_gdd_speedy_page_redirect"');

			// Initialize redirect data for the blog
			$this->data[$blog_id] = array();

			foreach ($rows as $row)
			{
				// Unserialize data
				$data = unserialize($row->meta_value);

				// Store the originally saved URL as raw_url
				$data['url_raw'] = $data['url'];

				// Generate the full URL in case a relative URL is stored in the database
				if (substr($data['url'], 0, 1) === '/')
				{
					$data['url'] = trailingslashit(get_bloginfo('url')).ltrim($data['url'], '/');
				}

				// Cache redirection data in object property
				$this->data[$blog_id][(int) $row->post_id] = $data;
			}
		}

		// Return redirection data for post if any
		return (isset($this->data[$blog_id][$post_id])) ? $this->data[$blog_id][$post_id] : NULL;
	}

}