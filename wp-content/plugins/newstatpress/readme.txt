=== Plugin Name ===
Contributors: ice00
Donate link: http://newstatpress.altervista.org
Tags: stats,statistics,widget,admin,sidebar,visits,visitors,pageview,user,agent,referrer,post,posts,spy,statistiche,ip2nation,country
Requires at least: 2.1
Tested up to: 3.5.1
Stable Tag: 0.5.8

NewStatPress is a new version of StatPress (that was the first real-time plugin dedicated to the management of statistics about blog visits).

== Description ==

A real-time plugin dedicated to the management of statistics about blog visits. It collects information about visitors, spiders, search keywords, feeds, browsers etc.

This project borned for improving the Daniele Lippi's StarPress plugin adding a new history features and make it less db consuming.

Once the plugin NewStatPress has been activated it immediately starts to collect statistics information.
Using NewStatPress you could spy your visitors while they are surfing your blog or check which are the preferred pages, posts and categories.
In the Dashboard menu you will find the NewStatPress page where you could look up the statistics (overview or detailed).
NewStatPress also includes a widget one can possibly add to a sidebar (or easy PHP code if you can't use widgets!).

Note: you must disable the original StatPress plugin when activating this, as it use the same table of StatPress for storing data in DB (copy the data to another table will be very space consuming for your site, so it was better to use the same table)

= Support =

Check at  http://newstatpress.altervista.org

= What's new? =

Simple adding index to database and changes some data fields for better database storing (from here http://www.poundbangwhack.com/2010/07/03/improve-the-performance-of-the-wordpress-plugin-statpress-and-your-blog/ where some modification comes from)

= Ban IP =

You could ban IP list from stats editing def/banips.dat file.

= DB Table maintenance =

NewStatPress can automatically delete older records to allow the insertion of newer records when limited space is present.
This features is left as original StatPress but it will be replaced by the history data instead.

= NewStatPress Widget / NewStatPress_Print function =

Widget is customizable. These are the available variables:

* %thistotalvisits% - this page, total visits
* %alltotalvisits% - all page, total visits
* %totalpageviews% - total pages view 
* %todaytotalpageviews% -  total pages view today
* %since% - Date of the first hit
* %visits% - Today visits
* %totalvisits% - Total visits
* %os% - Operative system
* %browser% - Browser
* %ip% - IP address
* %visitorsonline% - Counts all online visitors
* %usersonline% - Counts logged online visitors
* %toppost% - The most viewed Post
* %topbrowser% - The most used Browser
* %topos% - The most used O.S.
* %topsearch% - The most used search terms

Now you could add these values everywhere! NewStatPress offers a new PHP function *NewStatPress_Print()*.
* i.e. NewStatPress_Print("%totalvisits% total visits.");

New sperimental functions: place this command [NewStatPress: xxx] every were in your Wordpress blog pages and you will have the graph about the xxx function.

Available functions are:
 *  [NewStatPress: Overview]
 *  [NewStatPress: Top days]
 *  [NewStatPress: O.S.] 
 *  [NewStatPress: Browser]
 *  [NewStatPress: Feeds]
 *  [NewStatPress: Search Engine]
 *  [NewStatPress: Search terms]
 *  [NewStatPress: Top referrer]
 *  [NewStatPress: Languages]
 *  [NewStatPress: Spider]
 *  [NewStatPress: Top Pages]
 *  [NewStatPress: Top Days - Unique visitors]
 *  [NewStatPress: Top Days - Pageviews]
 *  [NewStatPress: Top IPs - Pageviews]

== Installation ==

Upload "newstatpress" directory in wp-content/plugins/ . Then just activate it on your plugin management page.
You are ready!!!


= Update =

* Deactivate NewStatPress plugin (no data lost!)
* Backup ALL your data
* Backup your custom DEFs files
* Override "newstatpress" directory in wp-content/plugins/
* Restore your custom DEFs files
* Re-activate it on your plugin management page
* In the Dashboard click "NewStatPress", then "NewStatPressUpdate" and wait until it will add/update db's content

== Frequently Asked Questions ==

= I've a problem. Where can I get help? =

Check at http://newstatpress.altervista.org

== Screenshots ==

Check at http://newstatpress.altervista.org

== Changelog ==

= 0.1.0 =

* Adds index onto Statpress 1.4.1 table for improve velocity
* Changes data type of some fields for saving space
* Let the images to be visible even for relocated blog
* Makes the update of search engine more quick

= 0.1.1 =

* Reactivate translactions
* Add more OS (MacOSX variants, Android)
* Add more Browser (Firefox 4, IE 9)

= 0.1.2 =

* Add images for new browser
* Better polish translation by Pawel Dworniak
* Separate iPhone/iPad/iPod devices

= 0.1.3 =

* Reactivate visitors/user online with unix timestamp

= 0.1.4 =

* Fix fromDate calculation

= 0.1.5 =

* Open link in new tab/window (thanks to Sisko)
* New displays of data for spy function (thanks to Sisko)
* Added %alltotalvisits%

= 0.1.6 =

* Add option for not track given IPs (from wp_slimstat)
* update Italian translation

= 0.1.7 =

* Let Search function to works again (thank to Ladislav)

= 0.1.8 =

* Add option for not track given permalinks (from wp_slimstat)

= 0.1.9 =

* make all reports in details to have the number of entries you want
* Add [NewStatPress: xxx] experimantal function for having report into wordpress page
* Add %totalpageviews% - total pages view

= 0.2.0 =

* Add new OS (+44), browsers (+52) and spiders (+71) (from statpress-visitors)

= 0.2.1 =

* Add new OS (+3), browsers (+6)
* Add Simplified Chinese translation (thanks to Christopher Meng)

= 0.2.2 =

* Add source for Simplified Chinese translation (thanks to Christopher Meng)
* Fix nb_NO over no_NO locale 
* Lots of php warnings fixed in source

= 0.2.3 =

* Fixing charaters after closing tags

= 0.2.4 =

* Add option for show dashboard widget
* Add dashboard widget (thanks to Maurice Cramer). Look into the blog for details.

= 0.2.5 =

* Fix total since in overwiew (thanks to Maurice Cramer)
* Add in the option the ability to update in a range of date (migrated from stapress-visitors)

= 0.2.6 =

* Fix missing browser image and IE aligment failure in spy section (thanks to Maurice Cramer)
* Add new browser definitions (+2)

= 0.2.7 =

* Replace deprecate PHP eregi function with preg_match
* In spy show local nation images (taken from statpress-visitors)
* New spy function taken from statpress-visitors (for beta testing)
* Use image for newstatpress menu in administration of wordpress (taken from statpress-visitors)

= 0.2.8 =

* Fix blog redirector in NewStatPressBlog (Tracker ID 137)
* Fix nation image display in spy (thanks to Maurice Cramer)

= 0.2.9 =

* Fix export action not catched (Tracker ID 146)

= 0.3.0 =

* Add new browser definitions (+1)
* Create credits menu (to be completed)

= 0.3.1 =

* Fix *deprecated* register_sidebar_widget (traker ID 142)
* Fix php warning into dashboard widget

= 0.3.2 =

* Add new OS (+1), browsers (+7), spiders (+1)

= 0.3.3 =

* Updating French translation (thanks to shilom)
* Inserting credits table

= 0.3.4 =

* Add Lithuanian translation (thanks to Vincent G)

= 0.3.5 =

* Updating French translation (thanks to shilom)
* Fix dashboard and overview translation

= 0.3.6 =

* Add Overview for [NewStatPress: xxx] command (tracker id 166)

= 0.3.7 =

* Fix introduced bug with previous function added of image visualization (tracker id 192)

= 0.3.8 =

* Code restructuration
* Add browser (+1)

= 0.3.9 =

* Add the abilities to not monitoring only a given list of names 
 (so, administrators can still monitoring authenticated users) for users that are logged (tracker id 201)
* Update Italian locale

= 0.4.0 =

* Fix total since date bug (tracker id 216)

= 0.4.1 =

* Improve control before insert (maybe help for bug id 221)

= 0.4.2 =

* Update Simplified Chinese translation (thanks to Christopher Meng)
* Add tab delimiter for export function (thanks to Ruud van der Veen)

= 0.4.3 =

* Add spiders images (from statpress-visitors)
* Initial porting of spy bot (from statpress-visitors)
* Add browser (+2)

= 0.4.4 =

* Add browser (+2)
* Fix spider images (bug id 236 and 237)

= 0.4.5 =

* Update Russian translation (thanks to godOFslaves)
* update Italian translation

= 0.4.6 =

* Add browser (+3), OS (+1)

= 0.4.7 =

* Trace even IPv6 address (note: look at http://newstatpress.altervista.org/?p=261 for more details before apply this update)
* Remove some Strict Standards PHP warnings (Only variables should be passed by reference in...)
* Remove a Notice (Trying to get property of non-object in...)
* Avoid use of $_SERVER['SCRIPT_NAME'] as no all server handle it correctly (tracker id 258)

= 0.4.8 =

* Add browser (+3), OS (+1)

= 0.4.9 =

* Show graphs under the table to stay into column (Google charts will be updated to SVG graphics soon)

= 0.5.0 =

* Add searchengine (+1)

= 0.5.1 =

* Add Slovak translation  (thanks to Branco - WebHostingGeeks.com http://webhostinggeeks.com/blog/) 
* Fix mising msn spider images (thanks to Christian)

= 0.5.2 =

* Fix inherited bug about CDIR comparison in blocking IP function
* Add %topsearch% for getting the top search term (it implements partially the tracker ID 297 and 243)
* Fix bug ID 299
* Add missing yahoo feedseacker spider image

= 0.5.3 =

* Fix collision of permalinksEnabled with statpress-visitors
* Increase size from 50 to 250 into details visualization
* New Browser (+17),New OS (+2), fix bug id 315

= 0.5.4 =

* Add Hungarian translation (Thanks to Peter Bago)

= 0.5.5 =

* Porting the GeoMap chart to the new Google API: Geochart.
  Map is rendered into an iframe with SVG or VML. 
  See http://newstatpress.altervista.org/?p=373 for an example

= 0.5.6 =

* Use .html instead of .frame as some browsers did not interpret it correctly

= 0.5.7 =

* Porting the 3D charts to the new Google API: Piechart.
  Chart is rendered into an iframe with SVG or VML. 
  See http://newstatpress.altervista.org/?p=381 for an example

= 0.5.8 =

* Remove Undefined offset in referrer if debug is on
* Add %todaytotalpageviews% - total pages view today
* Add Os (+3)

== Upgrade Notice ==

= 0.1.0 =

* released 19/03/2011

= 0.1.1 =

* released 22/03/2011

= 0.1.2 =

* released 23/03/2011

= 0.1.3 =

* released 23/04/2011

= 0.1.4 =

* released 24/04/2011

= 0.1.5 =

* released 12/05/2011

= 0.1.6 =

* released 15/05/2011

= 0.1.7 =

* released 29/05/2011

= 0.1.8 =

* released 23/06/2011

= 0.1.9 =

* released 10/09/2011

= 0.2.0 =

* released 15/10/2011

= 0.2.1 =

* released 21/12/2011

= 0.2.2 =

* released 11/01/2012

= 0.2.3 =

* released 12/01/2012

= 0.2.4 =

* released 18/01/2012

= 0.2.5 =

* released 28/01/2012

= 0.2.6 =

* released 01/02/2012

= 0.2.7 =

* released 04/02/2012

= 0.2.8 =

* released 17/02/2012

= 0.2.9 =

* released 01/03/2012

= 0.3.0 =

* released 14/03/2012

= 0.3.1 =

* released 21/04/2012

= 0.3.2 =

* released 26/04/2012

= 0.3.3 =

* released 29/04/2012

= 0.3.4 =

* released 02/05/2012

= 0.3.5 =

* released 16/05/2012

= 0.3.6 =

* released 01/06/2012

= 0.3.7 =

* released 02/06/2012

= 0.3.8 =

* released 10/06/2012

= 0.3.9 =

* released 01/07/2012

= 0.4.0 =

* released 02/07/2012

= 0.4.1 =

* released 12/07/2012

= 0.4.2 =

* released 15/07/2012

= 0.4.3 =

* released 22/07/2012

= 0.4.4 =

* released 01/09/2012

= 0.4.5 =

* released 23/09/2012

= 0.4.6 =

* released 21/10/2012

= 0.4.7 =

* released 02/11/2012

= 0.4.8 =

* released 09/12/2012

= 0.4.9 =

* released 27/12/2012

= 0.5.0 =

* released 29/12/2012

= 0.5.1 =

* released 13/01/2013

= 0.5.2 =

* released 12/02/2013

= 0.5.3 =

* released 15/02/2013

= 0.5.4 =

* released 02/03/2013

= 0.5.5 =

* released 14/03/2013

= 0.5.6 =

* released 14/03/2013

= 0.5.7 =

* released 16/03/2013

= 0.5.8 =

* released 27/04/2013