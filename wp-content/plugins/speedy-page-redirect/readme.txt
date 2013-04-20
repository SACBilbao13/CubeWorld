=== Speedy Page Redirect ===
Contributors: GeertDD
Tags: redirect, redirection, forward, url, 301, 302
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 0.3

Redirect pages and posts to other locations.

== Description ==

This plugin adds a meta box to your page and post screens. You can enter a new destination URL to which the page will be redirected.

= Features =
* Choose between permanent (301) and temporary (302) redirects.
* Support for custom post types out of the box.
* Filters for customizing some settings.
* Compatible with WP Multisite.
* Fully translatable. Included languages: English, Dutch.

== Installation ==

1. Upload the `speedy-page-redirect` folder to your `/wp-content/plugins/` directory.
1. Activate the plugin through the “Plugins” menu in WordPress.

== Frequently Asked Questions ==

= Can you create redirects relative to the site's URL? =

Yes. In the “Destination URL” field, just start your URL with a forward slash instead of “http://”. The site address, set in Settings > General, will automatically be prepended.

= Is it possible to choose which post types Speedy Page Redirect applies to? =

Yes. By default “page”, “post” and all public custom post types are taken into account. You can customize this selection via the `gdd_spr_post_types` filter. It should return an array with the applicable post types.

Example:

`add_filter('gdd_spr_post_types', 'gdd_spr_post_types');
function gdd_spr_post_types($post_types)
{
	// Disable redirection for the "book" post type
	unset($post_types['book']);
	return $post_types;
}`

= Is it possible to customize the types of HTTP redirects to choose from? =

Yes. By default you can choose from a 301 (permanent) and 302 (temporary) redirect. To customize this list, a filter called `gdd_spr_statuses` is available. It should return an array with the keys corresponding to the HTTP response codes. The array values are descriptions used in the dropdown list.

Note: if the statuses list only contains a single entry, the dropdown list is automatically omitted from the meta box.

Example:

`add_filter('gdd_spr_statuses', 'gdd_spr_statuses');
function gdd_spr_statuses($statuses)
{
	// Remove temporary redirection from the list
	unset($statuses[302]);
	return $statuses;
}`

== Screenshots ==

1. The Speedy Page Redirect meta box

== Changelog ==

= 0.3 =
* Uninstalling now removes all plugin data from the database.
* Expanded documentation/FAQ.
* Hide redirection dropdown list if only a single option is available.
* First entry in the statuses list will be used as default redirection type.

= 0.2.1 =
* Fixed "undefined index" error.
* Fixed loading of language file.

= 0.2 =
* Relative URLs are now supported (start with a slash).
* Entering a protocol only is considered empty input.

= 0.1 =
* Initial release.
