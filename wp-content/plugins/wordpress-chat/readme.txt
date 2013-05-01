=== Chat ===
Contributors: Paul Menard, mohanjith
Tags: chat, twitter, Facebook, short code
Requires at least: 3.0.0
Stable tag: trunk
Tested up to: 3.5

Allow your readers to chat with you

== Description ==

Add a chat to your blog and allow your readers to chat with you.

== Screenshots ==

1. In post chat
2. Chat widget

== ChangeLog ==
= 1.3.2.7 =
- Correct button label not properly wrapped for translation.
- Added logic to prevent page cache. Should now allow using cache plugins.

= 1.3.2.6 =
- Added logic to double check logged in status for WordPress users who log out via a separate window. http://premium.wpmudev.org/forums/topic/logout-leaves-user-with-chat-access-as-is-logged-in-and-produces-unexpected-avatar-display

= 1.3.2.5 =
- Corrected logic for TinyMCE/WYSIWYG JavaScript not loading when using non-standard WordPress directory structure.

= 1.3.2.4 =
- Corrected label on Chat Widget for 'show time'.
- Made bottom corner chat disabled by default.

= 1.3.2.3 =
- Corrected label on Chat Widget for 'show time'.
- Made bottom corner chat disabled by default.

= 1.3.2.3 =
- Corrections to logic for hiding chat based on authentication methods for bottom corner chat.

= 1.3.2.2 =
- Correct small typo in admin text.

= 1.3.2.1 =
- Correct conflict when Facebook SDK loading from other plugins.
- Added Message display limit when a user first loads the chat page. Should prevent loading hundreds of messages to open chats
- Changed logic on login options setting. When login option setting only 'WordPress user' the chat boxes will be hidden until the user logs into WordPress.

= 1.3.2 =
- Correct visibility issue with WYSIWYG/TinyMCE button for new posts http://premium.wpmudev.org/forums/topic/bug-on-inserting-chat

= 1.3.1 =
- More changes to query loop for new message and meta to improve server performance.
- Testing on WordPress 3.5

= 1.3.0.2 =
- Corrected issue where bottom corner chat was not resuming polling after being closed then opened.
- Removed some debug output from message replies.

= 1.3.0.2 =
- Corrected some undefined variables which throw Notices when full error reporting is enabled.

= 1.3.0.1 =
- Corrected some undefined variables in the widget.

= 1.3.0 =
- Added Advanced option to limit of TinyMCE button to selected post types.
- Added Advanced option to limit of TinyMCE button to selected user roles.
- Rewrote code messaging logic to limit polling. This should clear up many user reports or chat crashing servers.

= 1.2.0 =

* Corrected logic when using Facebook authentication only for bottom corner chat and not for inline chat. Which was causing endless refresh of page http://premium.wpmudev.org/forums/topic/wordpress-chat-endlessly-refreshes-for-facebook	    		 	 	  	 	  
* Renamed global plugin instance from $chat to $wpmudev_chat. https://app.asana.com/0/589152284006/1796940364279
* Added Chat Widget with some of the options. http://premium.wpmudev.org/forums/topic/chat-box-as-a-widget-instead-of-floating
* Added support for moderator to delete/undelete messages http://premium.wpmudev.org/forums/topic/moderate-chat-ban-users-delete-messages
* Added support to close/open chat session. Similar to WPMU DEV. Thanks Enzo.
* Corrected emoticons. Had two not properly displaying.
* Corrected issue where depending on the WordPress setup the trailing slash is removed from the base URL. Causing sound manager to not load. http://premium.wpmudev.org/forums/topic/soundmanager2swf-404-chat-plugin
* Added some color options for Row area background, Row item background, Row item border width, Row item border color. http://premium.wpmudev.org/forums/topic/moderate-chat-ban-users-delete-messages
* Switched plugin to use new WPMU DEV Dashboard plugin updates


= 1.1.0 =

* Recode Facebook authentication for OAuth 2.0 and PHP-SDK

= 1.0.9.1 =

* Twitter instructions updated

= 1.0.9 =

* Fixed: Archive and clear capabilties conflict

= 1.0.8 =

* Fixed: TwentyEleven header image covering chat window
* Fixed: In IE message text box loses focus

= 1.0.7 =

* Fixed: Wrong path for soundmanager2.swf
* Fixed: Scrolling issue
* Stop autoscrolling if the user scrolls to a particular position
* Fixed: Bottom corner chat size changes
* Highlight chat box in a different color when there is a new message
* Fixed: Prevent line breaks when enter key is pressed
* Fixed: Code tags instructions
* Fixed: Chat message encoding issue
* Do not include swf if sound is disabled
* Function split() is deprecated

= 1.0.6 =

* Fixed: Missing styles

= 1.0.5 =

* Improve host compatibility with login with Facebook
* Balance code tags
* Allow multiple links to be in the chat message

= 1.0.4 =

* Remove chat js if no chat is in the page
* Fixed: upgrade race

= 1.0.3 =

* Fixed: Multiple messages posted
* Added Moderators
* Notify user when offline

= 1.0.2 =

* Tested with WordPress 3.1
* Added Auto Update plugin installation check
* Fixed: mod_security issue
* Fixed: FB & Twitter button alignment
* Fixed: Setting height and width of a in post chat
* Fixed: Configure refresh interval

= 1.0.1 =

* Fixed: Parse error: syntax error, unexpected T_STATIC, expecting T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}'
* Fixed: Issue in displaying non ASCII characters (Ü,Ö,Ä,ü,ö,ä,...)
* Fixed: Not sufficient permissions to modify Chat plugin settings
* Fixed: Slashes in message and author name
* Fixed: Sound issues
* Fixed: IE 7 javascript errors
* Fixed: Timezone issue
* Fixed: Unicode characters issue
* Allow users with only edit_posts or edit_pages (not both) to add to posts
* Fixed: IE 8 javascript errors in wp-admin
* Allow admin to control the chat text color

= 1.0.0 =

* Initial release


137717-1367064930