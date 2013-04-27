<?php
/*
Plugin Name: NewStatPress
Plugin URI: http://newstatpress.altervista.org
Description: Real time stats for your Wordpress blog
Version: 0.5.8
Author: Stefano Tognon (from Daniele Lippi works)
Author URI: http://newstatpress.altervista.org
*/

$_NEWSTATPRESS['version']='0.5.8';
$_NEWSTATPRESS['feedtype']='';

/**
 * Get the url of the plugin
 *
 * @return the url of the plugin
 */
function PluginUrl() {
  //Try to use WP API if possible, introduced in WP 2.6
  if (function_exists('plugins_url')) return trailingslashit(plugins_url(basename(dirname(__FILE__))));

  //Try to find manually... can't work if wp-content was renamed or is redirected
  $path = dirname(__FILE__);
  $path = str_replace("\\","/",$path);
  $path = trailingslashit(get_bloginfo('wpurl')) . trailingslashit(substr($path,strpos($path,"wp-content/")));
  return $path;
}

/**
 * Add pages with NewStatPress commands
 */
function iri_add_pages() {
  # Create/update table if it not exists
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    iri_NewStatPress_CreateTable();
  }

  # add submenu
  $mincap=get_option('newstatpress_mincap');
  if($mincap == '') {
    $mincap="level_8";
  }

  // ORIG   add_submenu_page('index.php', 'StatPress', 'StatPress', 8, 'statpress', 'iriNewStatPress');
  add_menu_page('NewStatPress', 'NewStatPress', $mincap, __FILE__, 'iriNewStatPress', plugins_url('newstatpress/images/stat.png',dirname(plugin_basename(__FILE__))));
  add_submenu_page(__FILE__, __('Overview','newstatpress'), __('Overview','newstatpress'), $mincap, __FILE__, 'iriNewStatPress');
  add_submenu_page(__FILE__, __('Details','newstatpress'), __('Details','newstatpress'), $mincap, __FILE__ . '&newstatpress_action=details', 'iriNewStatPress');
  add_submenu_page(__FILE__, __('Spy','newstatpress'), __('Spy','newstatpress'), $mincap, __FILE__ . '&newstatpress_action=spy', 'iriNewStatPress');
  add_submenu_page(__FILE__, __('New Spy','newstatpress'), __('New Spy','newstatpress'), $mincap, __FILE__ . '&newstatpress_action=newspy', 'iriNewStatPress');
  add_submenu_page(__FILE__, __('Spy Bot','newstatpress'), __('Spy Bot','newstatpress'), $mincap, __FILE__ . '&newstatpress_action=spybot', 'iriNewStatPress');
  add_submenu_page(__FILE__, __('Search','newstatpress'), __('Search','newstatpress'), $mincap, __FILE__ . '&newstatpress_action=search', 'iriNewStatPress');
  add_submenu_page(__FILE__, __('Export','newstatpress'), __('Export','newstatpress'), $mincap, __FILE__ . '&newstatpress_action=export', 'iriNewStatPress');
  add_submenu_page(__FILE__, __('Options','newstatpress'), __('Options','newstatpress'), $mincap, __FILE__ . '&newstatpress_action=options', 'iriNewStatPress');
  add_submenu_page(__FILE__, __('NewStatPressUpdate','newstatpress'), __('NewStatPressUpdate','newstatpress'), $mincap, __FILE__ . '&newstatpress_action=up', 'iriNewStatPress');  
  add_submenu_page(__FILE__, __('NewStatpress blog','newstatpress'), __('NewStatpress blog','newstatpress'), $mincap,  __FILE__ . '&newstatpress_action=redirect', 'iriNewStatPress');
  add_submenu_page(__FILE__, __('Credits','newstatpress'), __('Credits','newstatpress'), $mincap,  __FILE__ . '&newstatpress_action=credits', 'iriNewStatPress');
}

/**
 * General function for calling the action that user had choice
 */
function iriNewStatPress() {
  ?>
  <?php
    if(isset($_GET['newstatpress_action'])){
      if ($_GET['newstatpress_action'] == 'export') {
        iriNewStatPressExport();
      } elseif ($_GET['newstatpress_action'] == 'up') {
          iriNewStatPressUpdate();
      } elseif ($_GET['newstatpress_action'] == 'spy') {
          iriNewStatPressSpy();
      } elseif ($_GET['newstatpress_action'] == 'newspy') {
          iriNewStatPressNewSpy();
      } elseif ($_GET['newstatpress_action'] == 'spybot') {
          iriNewStatPressSpyBot();
      } elseif ($_GET['newstatpress_action'] == 'search') {
           iriNewStatPressSearch();
      } elseif ($_GET['newstatpress_action'] == 'details') {
           iriNewStatPressDetails();
      } elseif ($_GET['newstatpress_action'] == 'options') {
           iriNewStatPressOptions();
      } elseif ($_GET['newstatpress_action'] == 'redirect') {
           iriNewStatPressRedirect();      
      } elseif ($_GET['newstatpress_action'] == 'credits') {
           iriNewStatPressCredits();
      } 
   } else iriNewStatPressMain();
}

/**
 * Redirect the wordpress page to the newstatpress blog
 */
function iriNewStatPressRedirect() {
  echo "<script language=javascript>window.location.href= 'http://newstatpress.altervista.org'</script>";
}

/**
 * Filter the given value for preventing XSS attacks
 *
 * @param _value the value to filter
 * @return filtered value
 */
function iriNewStatPress_filter_for_xss($_value){
  $_value=trim($_value);

  // Avoid XSS attacks
  $clean_value = preg_replace('/[^a-zA-Z0-9\,\.\/\ \-\_\?=&;]/', '', $_value);
  if (strlen($_value)==0){
    return array();
  } else {
      $array_values = explode(',',$clean_value);
      array_walk($array_values, 'iriNewStatPress_trim_value');
      return $array_values;
    }
}

/**
 * Trim the given string
 */
function iriNewStatPress_trim_value(&$value) { 
  $value = trim($value); 
}

/**
 * Generate HTML for option menu in Wordpress
 */
function iriNewStatPressOptions() {
  if(isset($_POST['saveit']) && $_POST['saveit'] == 'yes') {
    if (isset($_POST['newstatpress_collectloggeduser'])) update_option('newstatpress_collectloggeduser', $_POST['newstatpress_collectloggeduser']);
    else update_option('newstatpress_collectloggeduser', null);
    update_option('newstatpress_ip_per_page_newspy', $_POST['newstatpress_ip_per_page_newspy']);
    update_option('newstatpress_visits_per_ip_newspy', $_POST['newstatpress_visits_per_ip_newspy']);
    update_option('newstatpress_bot_per_page_spybot', $_POST['newstatpress_bot_per_page_spybot']);
    update_option('newstatpress_visits_per_bot_spybot', $_POST['newstatpress_visits_per_bot_spybot']);
    update_option('newstatpress_autodelete', $_POST['newstatpress_autodelete']);
    update_option('newstatpress_daysinoverviewgraph', $_POST['newstatpress_daysinoverviewgraph']);
    update_option('newstatpress_mincap', $_POST['newstatpress_mincap']);
    if (isset($_POST['newstatpress_donotcollectspider'])) update_option('newstatpress_donotcollectspider', $_POST['newstatpress_donotcollectspider']);
    else update_option('newstatpress_donotcollectspider', null);
    if (isset($_POST['newstatpress_cryptip'])) update_option('newstatpress_cryptip', $_POST['newstatpress_cryptip']);
    else update_option('newstatpress_cryptip', null);
    if (isset($_POST['newstatpress_dashboard'])) update_option('newstatpress_dashboard', $_POST['newstatpress_dashboard']);
    else update_option('newstatpress_dashboard', null);
    update_option('newstatpress_ignore_users', iriNewStatPress_filter_for_xss($_POST['newstatpress_ignore_users']));
    update_option('newstatpress_ignore_ip', iriNewStatPress_filter_for_xss($_POST['newstatpress_ignore_ip']));
    update_option('newstatpress_ignore_permalink', iriNewStatPress_filter_for_xss($_POST['newstatpress_ignore_permalink']));
    update_option('newstatpress_el_top_days', $_POST['newstatpress_el_top_days']);
    update_option('newstatpress_el_os', $_POST['newstatpress_el_os']);
    update_option('newstatpress_el_browser', $_POST['newstatpress_el_browser']);
    update_option('newstatpress_el_feed', $_POST['newstatpress_el_feed']);
    update_option('newstatpress_el_searchengine', $_POST['newstatpress_el_searchengine']);
    update_option('newstatpress_el_search', $_POST['newstatpress_el_search']);
    update_option('newstatpress_el_referrer', $_POST['newstatpress_el_referrer']);
    update_option('newstatpress_el_languages', $_POST['newstatpress_el_languages']);
    update_option('newstatpress_el_spiders', $_POST['newstatpress_el_spiders']);
    update_option('newstatpress_el_pages', $_POST['newstatpress_el_pages']);
    update_option('newstatpress_el_visitors', $_POST['newstatpress_el_visitors']);
    update_option('newstatpress_el_daypages', $_POST['newstatpress_el_daypages']);
    update_option('newstatpress_el_ippages', $_POST['newstatpress_el_ippages']);
    update_option('newstatpress_updateint', $_POST['newstatpress_updateint']);

    # update database too
    iri_NewStatPress_CreateTable();
    print "<br /><div class='updated'><p>".__('Saved','newstatpress')."!</p></div>";
  } else {
      ?>
      <div class='wrap'><h2><?php _e('Options','newstatpress'); ?></h2>
      <form method=post><table width=100%>
      <?php
        print "<tr><td><input type=checkbox name='newstatpress_collectloggeduser' value='checked' ".get_option('newstatpress_collectloggeduser')."> ".__('Collect data about logged users, too.','newstatpress')."</td></tr>";
        print "<tr><td><input type=checkbox name='newstatpress_donotcollectspider' value='checked' ".get_option('newstatpress_donotcollectspider')."> ".__('Do not collect spiders visits','newstatpress')."</td></tr>";
        print "<tr><td><input type=checkbox name='newstatpress_cryptip' value='checked' ".get_option('newstatpress_cryptip')."> ".__('Crypt IP addresses','newstatpress')."</td></tr>";
        print "<tr><td><input type=checkbox name='newstatpress_dashboard' value='checked' ".get_option('newstatpress_dashboard')."> ".__('Show NewStatPress dashboard widget','newstatpress')."</td></tr>";
      ?>

      <tr><td><?php _e('New Spy: number of IP per page','newstatpress'); ?>
        <select name="newstatpress_ip_per_page_newspy">          
          <option value="20" <?php if(get_option('newstatpress_ip_per_page_newspy') == "20") print "selected"; ?>>20</option>
          <option value="50" <?php if(get_option('newstatpress_ip_per_page_newspy') == "50") print "selected"; ?>>50</option>
          <option value="100" <?php if(get_option('newstatpress_ip_per_page_newspy') == "100") print "selected"; ?>>100</option>          
        </select></td></tr>

      <tr><td><?php _e('New Spy: number of visits for IP','newstatpress'); ?>
        <select name="newstatpress_visits_per_ip_newspy">          
          <option value="20" <?php if(get_option('newstatpress_visits_per_ip_newspy') == "20") print "selected"; ?>>20</option>
          <option value="50" <?php if(get_option('newstatpress_visits_per_ip_newspy') == "50") print "selected"; ?>>50</option>
          <option value="100" <?php if(get_option('newstatpress_visits_per_ip_newspy') == "100") print "selected"; ?>>100</option>          
        </select></td></tr>

      <tr><td><?php _e('Spy Bot: number of bot per page','newstatpress'); ?>
        <select name="newstatpress_bot_per_page_spybot">          
          <option value="20" <?php if(get_option('newstatpress_bot_per_page_spybot') == "20") print "selected"; ?>>20</option>
          <option value="50" <?php if(get_option('newstatpress_bot_per_page_spybot') == "50") print "selected"; ?>>50</option>
          <option value="100" <?php if(get_option('newstatpress_bot_per_page_spybot') == "100") print "selected"; ?>>100</option>          
        </select></td></tr>

      <tr><td><?php _e('Spy Bot: number of bot for IP','newstatpress'); ?>
        <select name="newstatpress_visits_per_bot_spybot">          
          <option value="20" <?php if(get_option('newstatpress_visits_per_bot_spybot') == "20") print "selected"; ?>>20</option>
          <option value="50" <?php if(get_option('newstatpress_visits_per_bot_spybot') == "50") print "selected"; ?>>50</option>
          <option value="100" <?php if(get_option('newstatpress_visits_per_bot_spybot') == "100") print "selected"; ?>>100</option>          
        </select></td></tr>

      <tr><td><?php _e('Automatically delete visits older than','newstatpress'); ?>
        <select name="newstatpress_autodelete">
          <option value="" <?php if(get_option('newstatpress_autodelete') =='' ) print "selected"; ?>><?php _e('Never delete!','newstatpress'); ?></option>
          <option value="1 month" <?php if(get_option('newstatpress_autodelete') == "1 month") print "selected"; ?>>1 <?php _e('month','newstatpress'); ?></option>
          <option value="3 months" <?php if(get_option('newstatpress_autodelete') == "3 months") print "selected"; ?>>3 <?php _e('months','newstatpress'); ?></option>
          <option value="6 months" <?php if(get_option('newstatpress_autodelete') == "6 months") print "selected"; ?>>6 <?php _e('months','newstatpress'); ?></option>
          <option value="1 year" <?php if(get_option('newstatpress_autodelete') == "1 year") print "selected"; ?>>1 <?php _e('year','newstatpress'); ?></option>
        </select></td></tr>

      <tr><td><?php _e('Days in Overview graph','newstatpress'); ?>
        <select name="newstatpress_daysinoverviewgraph">
          <option value="7" <?php if(get_option('newstatpress_daysinoverviewgraph') == 7) print "selected"; ?>>7</option>
          <option value="10" <?php if(get_option('newstatpress_daysinoverviewgraph') == 10) print "selected"; ?>>10</option>
          <option value="20" <?php if(get_option('newstatpress_daysinoverviewgraph') == 20) print "selected"; ?>>20</option>
          <option value="30" <?php if(get_option('newstatpress_daysinoverviewgraph') == 30) print "selected"; ?>>30</option>
          <option value="50" <?php if(get_option('newstatpress_daysinoverviewgraph') == 50) print "selected"; ?>>50</option>
        </select></td></tr>

      <tr><td><?php _e('Minimum capability to view stats','newstatpress'); ?>
        <select name="newstatpress_mincap">
         <?php iri_dropdown_caps(get_option('newstatpress_mincap')); ?>
        </select> 
        <a href="http://codex.wordpress.org/Roles_and_Capabilities" target="_blank"><?php _e("more info",'newstatpress'); ?></a>
        </td></tr>

      <tr><td><hr></hr></td></tr>

      <tr><td>
        <h3><label for="newstatpress_ignore_users"><?php _e('Logged users to ignore','newstatpress') ?></label></h3>
        <p><?php _e("Enter a list of users you don't want to track, separated by commas, even if collect data about logged users is on",'newstatpress') ?></p>
        <p><textarea class="large-text code" cols="50" rows="1" name="newstatpress_ignore_users" id="newstatpress_ignore_users">
              <?php echo implode(',', get_option('newstatpress_ignore_users',array())) ?>
            </textarea></p>
        </td></tr>

      <tr><td>
        <h3><label for="newstatpress_ignore_ip"><?php _e('IP addresses to ignore','newstatpress') ?></label></h3>
        <p><?php _e("Enter a list of networks you don't want to track, separated by commas. Each network <strong>must</strong> be defined using the CIDR notation (i.e. <em>192.168.1.1/24</em>). If the format is incorrect, NewStatPress may not track pageviews properly.",'newstatpress') ?></p>
        <p><textarea class="large-text code" cols="50" rows="1" name="newstatpress_ignore_ip" id="newstatpress_ignore_ip">
              <?php echo implode(',', get_option('newstatpress_ignore_ip',array())) ?>
            </textarea></p>
        </td></tr>

      <tr><td>
        <h3><label for="newstatpress_ignore_permalink"><?php _e('Pages and posts to ignore','newstatpress') ?></label></h3>
        <p><?php _e("Enter a list of permalinks you don't want to track, separated by commas. You should omit the domain name from these resources: <em>/about, p=1</em>, etc. NewStatPress will ignore all the pageviews whose permalink <strong>contains</strong> at least one of them.",'newstatpress') ?></p>
        <p><textarea class="large-text code" cols="50" rows="1" name="newstatpress_ignore_permalink" id="newstatpress_ignore_permalink">
              <?php echo implode(',', get_option('newstatpress_ignore_permalink',array())) ?>
            </textarea></p>
        </td></tr>

      <tr><td><hr></hr></td></tr>

      <tr>
        <td>
      <h3><label for="newstatpress_details_options"><?php _e('Details options','newstatpress') ?></label></h3>
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_top_days"><?php _e('Elements in Top days (default 5)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_top_days" value="<?php echo (get_option('newstatpress_el_top_days')=='') ? 5:get_option('newstatpress_el_top_days'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_os"><?php _e('Elements in O.S. (default 10)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_os" value="<?php echo (get_option('newstatpress_el_os')=='') ? 10:get_option('newstatpress_el_os'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_browser"><?php _e('Elements in Browser (default 10)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_browser" value="<?php echo (get_option('newstatpress_el_browser')=='') ? 10:get_option('newstatpress_el_browser'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_feed"><?php _e('Elements in Feed (default 5)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_feed" value="<?php echo (get_option('newstatpress_el_feed')=='') ? 5:get_option('newstatpress_el_feed'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_searchengine"><?php _e('Elements in Search Engines (default 10)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_searchengine" value="<?php echo (get_option('newstatpress_el_searchengine')=='') ? 10:get_option('newstatpress_el_searchengine'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_search"><?php _e('Elements in Top Search Terms (default 20)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_search" value="<?php echo (get_option('newstatpress_el_search')=='') ? 20:get_option('newstatpress_el_search'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_referrer"><?php _e('Elements in Top Refferer (default 10)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_referrer" value="<?php echo (get_option('newstatpress_el_referrer')=='') ? 10:get_option('newstatpress_el_referrer'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_languages"><?php _e('Elements in Countries/Languages (default 20)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_languages" value="<?php echo (get_option('newstatpress_el_languages')=='') ? 20:get_option('newstatpress_el_languages'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_spiders"><?php _e('Elements in Spiders (default 10)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_spiders" value="<?php echo (get_option('newstatpress_el_spiders')=='') ? 10:get_option('newstatpress_el_spiders'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_pages"><?php _e('Elements in Top Pages (default 5)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_pages" value="<?php echo (get_option('newstatpress_el_pages')=='') ? 5:get_option('newstatpress_el_pages'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_visitors"><?php _e('Elements in Top Days - Unique visitors (default 5)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_visitors" value="<?php echo (get_option('newstatpress_el_visitors')=='') ? 5:get_option('newstatpress_el_visitors'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_daypages"><?php _e('Elements in Top Days - Pageviews (default 5)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_daypages" value="<?php echo (get_option('newstatpress_el_daypages')=='') ? 5:get_option('newstatpress_el_daypages'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr>
        <td>
        <label for="newstatpress_el_ippages"><?php _e('Elements in Top IPs - Pageviews (default 5)','newstatpress') ?></label>
        <input type="text" name="newstatpress_el_ippages" value="<?php echo (get_option('newstatpress_el_ippages')=='') ? 5:get_option('newstatpress_el_ippages'); ?>" size="3" maxlength="3" />
       </td></tr>

      <tr><td><hr></hr></td></tr>

      <tr>
       <td>
        <h2><?php _e('Database update option','newstatpress'); ?></h3>
       </td></tr>
      <tr>
       <td>
         <p><?php _e("Select the interval of date from today you want to use for updating your database with new definitions. More it is big and more times and resources it require. You can choose to not update some fields if you want.",'newstatpress') ?></p>
       </td></tr>

      <tr>
       <td>
        <?php _e('Update data in the given period','newstatpress'); ?>
        <select name="newstatpress_updateint">
          <option value="" <?php if(get_option('newstatpress_updateint') =='' ) print "selected"; ?>><?php _e('All!','newstatpress'); ?></option>
          <option value="1 week" <?php if(get_option('newstatpress_updateint') == "1 week") print "selected"; ?>>1 <?php _e('week','newstatpress'); ?></option>
          <option value="2 weeks" <?php if(get_option('newstatpress_updateint') == "2 weeks") print "selected"; ?>>2 <?php _e('weeks','newstatpress'); ?></option>
          <option value="3 weeks" <?php if(get_option('newstatpress_updateint') == "3 weeks") print "selected"; ?>>3 <?php _e('weeks','newstatpress'); ?></option>
          <option value="1 month" <?php if(get_option('newstatpress_updateint') == "1 month") print "selected"; ?>>1 <?php _e('month','newstatpress'); ?></option>
          <option value="2 months" <?php if(get_option('newstatpress_updateint') == "2 months") print "selected"; ?>>2 <?php _e('months','newstatpress'); ?></option>
          <option value="3 months" <?php if(get_option('newstatpress_updateint') == "3 months") print "selected"; ?>>3 <?php _e('months','newstatpress'); ?></option>
          <option value="6 months" <?php if(get_option('newstatpress_updateint') == "6 months") print "selected"; ?>>6 <?php _e('months','newstatpress'); ?></option>
          <option value="9 months" <?php if(get_option('newstatpress_updateint') == "9 months") print "selected"; ?>>9 <?php _e('months','newstatpress'); ?></option>
          <option value="1 year" <?php if(get_option('newstatpress_updateint') == "1 year") print "selected"; ?>>1 <?php _e('year','newstatpress'); ?></option>
        </select></td></tr>
       </td></tr>

      <tr><td><hr></hr></td></tr>

      <tr><td><br><input type=submit value="<?php _e('Save options','newstatpress'); ?>"></td></tr>
      </tr>
      </table>
      <input type=hidden name=saveit value=yes>
      <input type=hidden name=page value=newstatpress><input type=hidden name=newstatpress_action value=options>
      </form>
      </div>
      <?php
    } 
}


function iri_dropdown_caps( $default = false ) {
  global $wp_roles;
  $role = get_role('administrator');
  foreach($role->capabilities as $cap => $grant) {
    print "<option ";
    if($default == $cap) { print "selected "; }
    print ">$cap</option>";
  }
}

/**
 * Show credits about this plugin
 */
function iriNewStatPressCredits() {
?>
  <div class='wrap'><h2><?php _e('Credits','newstatpress'); ?></h2>
  <table border="1">
   <tr>
    <th>People</th>
    <th>Description</th>
    <th>Link</th>
   </tr>
   <tr>
    <td>Stefano Tognon</td>
    <td>NewStatPress develoup</td>
    <td><a href="http://newstatpress.altervista.org">NewStatPress site</a></td>
   </tr>
   <tr>
    <td>Daniele Lippi</td>
    <td>Original StatPress develoup</td>
    <td><a href="http://wordpress.org/extend/plugins/statpress/">StatPress site</a></td>
   </tr>
   <tr>
    <td>Pawel Dworniak</td>
    <td>Better polish translation</td>
    <td></td>
   </tr>
   <tr>
    <td>Sisko</td>
    <td>Open link in new tab/window<br>
        New displays of data for spy function<br>
    </td>
    <td></td>
   </tr>
   <tr>
    <td>from wp_slimstat</td>
    <td>Add option for not track given IPs<br>
        Add option for not track given permalinks
    </td>
    <td></td>
   </tr>
   <tr>
    <td>Ladislav</td>
    <td>Let Search function to works again</td>
    <td></td>
   </tr>
   <tr>
    <td>from statpress-visitors</td>
    <td>Add new OS (+44), browsers (+52) and spiders (+71)<br>
        Add in the option the ability to update in a range of date<br>
        New spy and bot
    </td>
    <td></td>
   </tr>
   <tr>
    <td>Christopher Meng</td>
    <td>Add Simplified Chinese translation</td>
    <td><a href="http://cicku.me">cicku.me</a></td>
   </tr>
   <tr>
    <td>Maurice Cramer</td>
    <td>Add dashboard widget<br>
        Fix total since in overwiew<br>
        Fix missing browser image and IE aligment failure in spy section<br>
        Fix nation image display in spy
    </td>
    <td></td>
   </tr>
   <tr>
    <td>shilom</td>
    <td>Updating French translation</td>
    <td></td>
   </tr>
   <tr>
    <td>Vincent G</td>
    <td>Add Lithuanian translation</td>
    <td><a href="http://www.Host1Free.com">Host1Free (Free Hosting)</a></td>
   </tr>
   <tr>
    <td>Ruud van der Veen</td>
    <td>Add tab delimiter for exporting data</td>
    <td></td>
   </tr>
   <tr>
    <td>godOFslaves</td>
    <td>Update Russian translation</td>
    <td><a href="http://www.htconexapk.ru">www.htconexapk.ru</a></td>
   </tr>
   <tr>
    <td>Branco</td>
    <td>Add Slovak translation</td>
    <td><a href="http://webhostinggeeks.com/blog/">WebHostingGeeks.com</a></td>
   </tr>
   <tr>
    <td>Peter Bago</td>
    <td>Add Hungarian translation</td>
    <td><a href="http://webrestaurator.hu">webrestaurator.hu</a></td>
   </tr>
  </table>
  </div>
<?php
}


function iriNewStatPressExport() {
?>
	<div class='wrap'><h2><?php _e('Export stats to text file','newstatpress'); ?> (csv)</h2>
	<form method=get><table>
	<tr><td><?php _e('From','newstatpress'); ?></td><td><input type=text name=from> (YYYYMMDD)</td></tr>
	<tr><td><?php _e('To','newstatpress'); ?></td><td><input type=text name=to> (YYYYMMDD)</td></tr>
	<tr><td><?php _e('Fields delimiter','newstatpress'); ?></td><td><select name=del><option>,</option><option>tab</option><option>;</option><option>|</option></select></tr>
	<tr><td></td><td><input type=submit value=<?php _e('Export','newstatpress'); ?>></td></tr>
	<input type=hidden name=page value=newstatpress><input type=hidden name=newstatpress_action value=exportnow>
	</table></form>
	</div>
<?php
}

/**
 * Check and export if capability of user allow that
 */
function iri_checkExport(){
  if (isset($_GET['newstatpress_action']) && $_GET['newstatpress_action'] == 'exportnow') {
    $mincap=get_option('newstatpress_mincap');
    if ($mincap == '') $mincap = "level_8";
    if ( current_user_can( $mincap ) ) {
      iriNewStatPressExportNow();
    } 
  }
}

/**
 * Export the NewStatPress data
 */
function iriNewStatPressExportNow() {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";
  $filename=get_bloginfo('title' )."-newstatpress_".$_GET['from']."-".$_GET['to'].".csv";
  header('Content-Description: File Transfer');
  header("Content-Disposition: attachment; filename=$filename");
  header('Content-Type: text/plain charset=' . get_option('blog_charset'), true);
  $qry = $wpdb->get_results(
    "SELECT * 
     FROM $table_name 
     WHERE 
       date>='".(date("Ymd",strtotime(substr($_GET['from'],0,8))))."' AND 
       date<='".(date("Ymd",strtotime(substr($_GET['to'],0,8))))."';
    ");
  $del=substr($_GET['del'],0,1);
  if ($del=="t") { 
    $del="\t"; 
  }
  print "date".$del."time".$del."ip".$del."urlrequested".$del."agent".$del."referrer".$del."search".$del."nation".$del."os".$del."browser".$del."searchengine".$del."spider".$del."feed\n";
  foreach ($qry as $rk) {
    print '"'.$rk->date.'"'.$del.'"'.$rk->time.'"'.$del.'"'.$rk->ip.'"'.$del.'"'.$rk->urlrequested.'"'.$del.'"'.$rk->agent.'"'.$del.'"'.$rk->referrer.'"'.$del.'"'.$rk->search.'"'.$del.'"'.$rk->nation.'"'.$del.'"'.$rk->os.'"'.$del.'"'.$rk->browser.'"'.$del.'"'.$rk->searchengine.'"'.$del.'"'.$rk->spider.'"'.$del.'"'.$rk->feed.'"'."\n";
  }
  die();
}

/**
 * Show overwiew
 */
function iriNewStatPressMain() {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";

  iriOverview();

  $_newstatpress_url=PluginUrl();

  $querylimit="LIMIT 10";
    
  # Tabella Last hits
  print "<div class='wrap'><h2>". __('Last hits','newstatpress'). "</h2><table class='widefat'><thead><tr><th scope='col'>". __('Date','newstatpress'). "</th><th scope='col'>". __('Time','newstatpress'). "</th><th scope='col'>IP</th><th scope='col'>". __('Country','newstatpress').'/'.__('Language','newstatpress'). "</th><th scope='col'>". __('Page','newstatpress'). "</th><th scope='col'>Feed</th><th></th><th scope='col' style='width:120px;'>OS</th><th></th><th scope='col' style='width:120px;'>Browser</th></tr></thead>";
  print "<tbody id='the-list'>";	

  $fivesdrafts = $wpdb->get_results("
    SELECT * 
    FROM $table_name 
    WHERE (os<>'' OR feed<>'') 
    ORDER bY id DESC $querylimit
  ");
  foreach ($fivesdrafts as $fivesdraft) {
    print "<tr>";
    print "<td>". irihdate($fivesdraft->date) ."</td>";
    print "<td>". $fivesdraft->time ."</td>";
    print "<td>". $fivesdraft->ip ."</td>";
    print "<td>". $fivesdraft->nation ."</td>";
    print "<td>". iri_NewStatPress_Abbrevia(iri_NewStatPress_Decode($fivesdraft->urlrequested),30) ."</td>";
    print "<td>". $fivesdraft->feed . "</td>";
    if($fivesdraft->os != '') {
      $img=str_replace(" ","_",strtolower($fivesdraft->os)).".png";
      print "<td><IMG style='border:0px;width:16px;height:16px;' SRC='".$_newstatpress_url."/images/os/$img'> </td>";
    } else {
        print "<td></td>";
      }
    print "<td>". $fivesdraft->os . "</td>";
    if($fivesdraft->browser != '') {
      $img=str_replace(" ","",strtolower($fivesdraft->browser)).".png";
      print "<td><IMG style='border:0px;width:16px;height:16px;' SRC='".$_newstatpress_url."/images/browsers/$img'></td>";
    } else {
       print "<td></td>";
    }
    print "<td>".$fivesdraft->browser."</td></tr>\n";
    print "</tr>";
  }
  print "</table></div>";


  # Last Search terms
  print "<div class='wrap'><h2>" . __('Last search terms','newstatpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>".__('Date','newstatpress')."</th><th scope='col'>".__('Time','newstatpress')."</th><th scope='col'>".__('Terms','newstatpress')."</th><th scope='col'>". __('Engine','newstatpress'). "</th><th scope='col'>". __('Result','newstatpress'). "</th></tr></thead>";
  print "<tbody id='the-list'>";	
  $qry = $wpdb->get_results("
    SELECT date,time,referrer,urlrequested,search,searchengine 
    FROM $table_name 
    WHERE search<>'' 
    ORDER BY id DESC $querylimit
  ");
  foreach ($qry as $rk) {
    print "<tr><td>".irihdate($rk->date)."</td><td>".$rk->time."</td><td><a href='".$rk->referrer."' target='_blank'>".$rk->search."</a></td><td>".$rk->searchengine."</td><td><a href='".get_bloginfo('url')."/?".$rk->urlrequested."' target='_blank'>". __('page viewed','newstatpress'). "</a></td></tr>\n";
  }
  print "</table></div>";

  # Referrer
  print "<div class='wrap'><h2>".__('Last referrers','newstatpress')."</h2><table class='widefat'><thead><tr><th scope='col'>".__('Date','newstatpress')."</th><th scope='col'>".__('Time','newstatpress')."</th><th scope='col'>".__('URL','newstatpress')."</th><th scope='col'>".__('Result','newstatpress')."</th></tr></thead>";
  print "<tbody id='the-list'>";	
  $qry = $wpdb->get_results("
    SELECT date,time,referrer,urlrequested 
    FROM $table_name 
    WHERE 
     ((referrer NOT LIKE '".get_option('home')."%') AND 
      (referrer <>'') AND 
      (searchengine='')
     ) ORDER BY id DESC $querylimit
  ");
  foreach ($qry as $rk) {
    print "<tr><td>".irihdate($rk->date)."</td><td>".$rk->time."</td><td><a href='".$rk->referrer."' target='_blank'>".iri_NewStatPress_Abbrevia($rk->referrer,80)."</a></td><td><a href='".get_bloginfo('url')."/?".$rk->urlrequested."'  target='_blank'>". __('page viewed','newstatpress'). "</a></td></tr>\n";
  }
  print "</table></div>";


  # Last Agents
  print "<div class='wrap'><h2>".__('Last agents','newstatpress')."</h2><table class='widefat'><thead><tr><th scope='col'>".__('Agent','newstatpress')."</th><th scope='col'></th><th scope='col' style='width:120px;'>OS</th><th scope='col'></th><th scope='col' style='width:120px;'>Browser/Spider</th></tr></thead>";
  print "<tbody id='the-list'>";	
  $qry = $wpdb->get_results("
    SELECT agent,os,browser,spider 
    FROM $table_name 
    GROUP BY agent,os,browser,spider 
    ORDER BY id DESC $querylimit
  ");
  foreach ($qry as $rk) {
    print "<tr><td>".$rk->agent."</td>";
    if($rk->os != '') {
      $img=str_replace(" ","_",strtolower($rk->os)).".png";
      print "<td><IMG style='border:0px;width:16px;height:16px;' SRC='".$_newstatpress_url."/images/os/$img'> </td>";
    } else {
        print "<td></td>";
      }
    print "<td>". $rk->os . "</td>";
    if($rk->browser != '') {
      $img=str_replace(" ","",strtolower($rk->browser)).".png";
      print "<td><IMG style='border:0px;width:16px;height:16px;' SRC='".$_newstatpress_url."/images/browsers/$img'></td>";
    } else {
        print "<td></td>";
      }
    print "<td>".$rk->browser." ".$rk->spider."</td></tr>\n";
  }
  print "</table></div>";


  # Last pages
  print "<div class='wrap'><h2>".__('Last pages','newstatpress')."</h2><table class='widefat'><thead><tr><th scope='col'>".__('Date','newstatpress')."</th><th scope='col'>".__('Time','newstatpress')."</th><th scope='col'>".__('Page','newstatpress')."</th><th scope='col' style='width:17px;'></th><th scope='col' style='width:120px;'>".__('OS','newstatpress')."</th><th style='width:17px;'></th><th scope='col' style='width:120px;'>".__('Browser','newstatpress')."</th></tr></thead>";
  print "<tbody id='the-list'>";	
  $qry = $wpdb->get_results("
    SELECT date,time,urlrequested,os,browser,spider 
    FROM $table_name 
    WHERE (spider='' AND feed='') 
    ORDER BY id DESC $querylimit
  ");
  foreach ($qry as $rk) {
    print "<tr><td>".irihdate($rk->date)."</td><td>".$rk->time."</td><td>".iri_NewStatPress_Abbrevia(iri_NewStatPress_Decode($rk->urlrequested),60)."</td>";
    if($rk->os != '') {
      $img=str_replace(" ","_",strtolower($rk->os)).".png";
      print "<td><IMG style='border:0px;width:16px;height:16px;' SRC='".$_newstatpress_url."/images/os/$img'> </td>";
    } else {
        print "<td></td>";
      }
    print "<td>". $rk->os . "</td>";
    if($rk->browser != '') {
      $img=str_replace(" ","",strtolower($rk->browser)).".png";
      print "<td><IMG style='border:0px;width:16px;height:16px;' SRC='".$_newstatpress_url."/images/browsers/$img'></td>";
    } else {
        print "<td></td>";
      }
    print "<td>".$rk->browser." ".$rk->spider."</td></tr>\n";
  }
  print "</table></div>";


  # Last Spiders
  print "<div class='wrap'><h2>".__('Last spiders','newstatpress')."</h2><table class='widefat'><thead><tr><th scope='col'>".__('Date','newstatpress')."</th><th scope='col'>".__('Time','newstatpress')."</th><th scope='col'></th><th scope='col'>".__('Spider','newstatpress')."</th><th scope='col'>".__('Agent','newstatpress')."</th></tr></thead>";
  print "<tbody id='the-list'>";	
  $qry = $wpdb->get_results("
    SELECT date,time,agent,os,browser,spider 
    FROM $table_name 
    WHERE (spider<>'') 
    ORDER BY id DESC $querylimit
  ");
  foreach ($qry as $rk) {
    print "<tr><td>".irihdate($rk->date)."</td><td>".$rk->time."</td>";
    if($rk->spider != '') {
      $img=str_replace(" ","_",strtolower($rk->spider)).".png";
      print "<td><IMG style='border:0px;height:16px;' SRC='".$_newstatpress_url."/images/spider/$img'> </td>";
    } else print "<td></td>";
    print "<td>".$rk->spider."</td><td> ".$rk->agent."</td></tr>\n";
  }
  print "</table></div>";

  print "<br />";
  print "&nbsp;<i>StatPress table size: <b>".iritablesize($wpdb->prefix . "statpress")."</b></i><br />";
  print "&nbsp;<i>StatPress current time: <b>".current_time('mysql')."</b></i><br />";
  print "&nbsp;<i>RSS2 url: <b>".get_bloginfo('rss2_url').' ('.iriNewStatPress_extractfeedreq(get_bloginfo('rss2_url')).")</b></i><br />"; 
}

/**
 * Extract the feed from the given url
 *
 * @param url the url to parse
 * @return the extracted url 
 */
function iriNewStatPress_extractfeedreq($url) {
  list($null,$q)=explode("?",$url);
  if (strpos($q, "&")!== false) list($res,$null)=explode("&",$q);
  else $res=$q;
  return $res;
}

function iriNewStatPressDetails() {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";

  $querylimit="LIMIT 10";

  # Top days
  iriValueTable2("date","Top days",(get_option('newstatpress_el_top_days')=='') ? 5:get_option('newstatpress_el_top_days'));

  # O.S.
  iriValueTable2("os","O.S.",(get_option('newstatpress_el_os')=='') ? 10:get_option('newstatpress_el_os'),"","","AND feed='' AND spider='' AND os<>''");

  # Browser
  iriValueTable2("browser","Browser",(get_option('newstatpress_el_browser')=='') ? 10:get_option('newstatpress_el_browser'),"","","AND feed='' AND spider='' AND browser<>''");	

  # Feeds
  iriValueTable2("feed","Feeds",(get_option('newstatpress_el_feed')=='') ? 5:get_option('newstatpress_el_feed'),"","","AND feed<>''");
    
  # SE
  iriValueTable2("searchengine","Search engines",(get_option('newstatpress_el_searchengine')=='') ? 10:get_option('newstatpress_el_searchengine'),"","","AND searchengine<>''");

  # Search terms
  iriValueTable2("search","Top search terms",(get_option('newstatpress_el_search')=='') ? 20:get_option('newstatpress_el_search'),"","","AND search<>''");

  # Top referrer
  iriValueTable2("referrer","Top referrer",(get_option('newstatpress_el_referrer')=='') ? 10:get_option('newstatpress_el_referrer'),"","","AND referrer<>'' AND referrer NOT LIKE '%".get_bloginfo('url')."%'");
 
  # Languages
  iriValueTable2("nation","Countries/Languages",(get_option('newstatpress_el_languages')=='') ? 20:get_option('newstatpress_el_languages'),"","","AND nation<>'' AND spider=''");

  # Spider
  iriValueTable2("spider","Spiders",(get_option('newstatpress_el_spiders')=='') ? 10:get_option('newstatpress_el_spiders'),"","","AND spider<>''");

  # Top Pages
  iriValueTable2("urlrequested","Top pages",(get_option('newstatpress_el_pages')=='') ? 5:get_option('newstatpress_el_pages'),"","urlrequested","AND feed='' and spider=''");

  # Top Days - Unique visitors
  iriValueTable2("date","Top Days - Unique visitors",(get_option('newstatpress_el_visitors')=='') ? 5:get_option('newstatpress_el_visitors'),"distinct","ip","AND feed='' and spider=''"); /* Maddler 04112007: required patching iriValueTable */

  # Top Days - Pageviews
  iriValueTable2("date","Top Days - Pageviews",(get_option('newstatpress_el_daypages')=='') ? 5:get_option('newstatpress_el_daypages'),"","urlrequested","AND feed='' and spider=''"); /* Maddler 04112007: required patching iriValueTable */

  # Top IPs - Pageviews
  iriValueTable2("ip","Top IPs - Pageviews",(get_option('newstatpress_el_ippages')=='') ? 5:get_option('newstatpress_el_ippages'),"","urlrequested","AND feed='' and spider=''"); /* Maddler 04112007: required patching iriValueTable */
}


/**
 * Converte da data us to default format di Wordpress
 *
 * @param dt the date to convert
 * @return converted data
 */
function newstatpress_hdate($dt = "00000000") {
  return mysql2date(get_option('date_format'), my_substr($dt, 0, 4) . "-" . my_substr($dt, 4, 2) . "-" . my_substr($dt, 6, 2));
}

/**
 * Decode the url in a better manner
 */
function newstatpress_Decode($out_url) {
  if(!iriNewStatPressPermalinksEnabled()) {
    if ($out_url == '') $out_url = __('Page', 'newstatpress') . ": Home";
    if (my_substr($out_url, 0, 4) == "cat=") $out_url = __('Category', 'statpress') . ": " . get_cat_name(my_substr($out_url, 4));
    if (my_substr($out_url, 0, 2) == "m=") $out_url = __('Calendar', 'newstatpress') . ": " . my_substr($out_url, 6, 2) . "/" . my_substr($out_url, 2, 4);
    if (my_substr($out_url, 0, 2) == "s=") $out_url = __('Search', 'newstatpress') . ": " . my_substr($out_url, 2);
    if (my_substr($out_url, 0, 2) == "p=") {
      $subOut=my_substr($out_url, 2);
      $post_id_7 = get_post($subOut, ARRAY_A);
      $out_url = $post_id_7['post_title'];
    }
    if (my_substr($out_url, 0, 8) == "page_id=") {
      $subOut=my_substr($out_url, 8);
      $post_id_7 = get_page($subOut, ARRAY_A);
      $out_url = __('Page', 'newstatpress') . ": " . $post_id_7['post_title'];
    }
 } else {
     if ($out_url == '') $out_url = __('Page', 'newstatpress') . ": Home";
     else if (my_substr($out_url, 0, 9) == "category/") $out_url = __('Category', 'newstatpress') . ": " . get_cat_name(my_substr($out_url, 9));
          else if (my_substr($out_url, 0, 2) == "s=") $out_url = __('Search', 'newstatpress') . ": " . my_substr($out_url, 2);
               else if (my_substr($out_url, 0, 2) == "p=") {
                      // not working yet 
                      $subOut=my_substr($out_url, 2);
                      $post_id_7 = get_post($subOut, ARRAY_A);
                      $out_url = $post_id_7['post_title'];
                    } else if (my_substr($out_url, 0, 8) == "page_id=") { 
                             // not working yet
                             $subOut=my_substr($out_url, 8);
                             $post_id_7 = get_page($subOut, ARRAY_A);
                             $out_url = __('Page', 'newstatpress') . ": " . $post_id_7['post_title'];
                           }
   }
   return $out_url;
}

/**
 * Get true if permalink is enabled in Wordpress
 * (taken in statpress-visitors)
 *
 * @return true if permalink is enabled in Wordpress
 */
function iriNewStatPressPermalinksEnabled() { 
  global $wpdb;
      
  $result = $wpdb->get_row('SELECT `option_value` FROM `' . $wpdb->prefix . 'options` WHERE `option_name` = "permalink_structure"');
  if ($result->option_value != '') return true;
  else return false;
}

/** 
 * PHP 4 compatible mb_substr function
 * (taken in statpress-visitors)
 */
function my_substr($str, $x, $y = 0) {
  if($y == 0) $y = strlen($str) - $x;
  if(function_exists('mb_substr'))
  return mb_substr($str, $x, $y);
  else
 return substr($str, $x, $y);
}

/**
 * Display links for group of pages
 *
 * @param NP the group of pages
 * @param pp the page to show
 * @param action the action
 */
function newstatpress_print_pp_link($NP,$pp,$action) {
  // For all pages ($NP) Display first 3 pages, 3 pages before current page($pp), 3 pages after current page , each 25 pages and the 3 last pages for($action)
  $GUIL1 = FALSE;
  $GUIL2 = FALSE;// suspension points  not writed  style='border:0px;width:16px;height:16px;   style="border:0px;width:16px;height:16px;"
  if ($NP >1) {
    print "<font size='1'>".__('period of days','newstatpress')." : </font>";
    for ($i = 1; $i <= $NP; $i++) {
      if ($i <= $NP) { 
        // $page is not the last page
        if($i == $pp) echo " [{$i}] "; // $page is current page
        else { 
          // Not the current page Hyperlink them
          if (($i <= 3) or (($i >= $pp-3) and ($i <= $pp+3)) or ($i >= $NP-3) or is_int($i/100)) { 
            echo '<a href="?page=newstatpress/newstatpress.php&newstatpress_action='.$action.'&pp=' . $i .'">' . $i . '</a> ';
          } else { 
              if (($GUIL1 == FALSE) OR ($i==$pp+4)) {
                echo "..."; 
                $GUIL1 = TRUE;
              }
              if ($i == $pp-4) echo ".."; 
              if (is_int(($i-1)/100)) echo "."; 
              if ($i == $NP-4) echo "..";   
              // suspension points writed
            }
         }
      }
    }
  }
}

/**
 * Display links for group of pages
 *
 * @param NP the group of pages
 * @param pp the page to show
 * @param action the action
 * @param NA group
 * @param pa current page
 */
function newstatpress_print_pp_pa_link($NP,$pp,$action,$NA,$pa) {   
  if ($NP<>0) newstatpress_print_pp_link($NP,$pp,$action);

  // For all pages ($NP) display first 5 pages, 3 pages before current page($pa), 3 pages after current page , 3 last pages 
  $GUIL1 = FALSE;// suspension points not writed
  $GUIL2 = FALSE;

  echo '<table width="100%" border="0"><tr></tr></table>';
  if ($NA >1 ) {
    echo "<font size='1'>".__('Pages','newstatpress')." : </font>";
    for ($j = 1; $j <= $NA; $j++) {
      if ($j <= $NA) {  // $i is not the last Articles page
        if($j == $pa)  // $i is current page
          echo " [{$j}] ";
        else { // Not the current page Hyperlink them
          if (($j <= 5) or (( $j>=$pa-2) and ($j <= $pa+2)) or ($j >= $NA-2)) 
            echo '<a href="?page=newstatpress/newstatpress.php&newstatpress_action='.$action.'&pp=' . $pp . '&pa='. $j . '">' . $j . '</a> ';
          else { 
            if ($GUIL1 == FALSE) echo "... "; $GUIL1 = TRUE;
            if (($j == $pa+4) and ($GUIL2 == FALSE)) {
              echo " ... "; 
              $GUIL2 = TRUE;
            }
            // suspension points writed
          }
        }
      }
    }
  }
}

/**
 * Get page period taken in statpress-visitors
 */
function newstatpress_page_periode() { 
  // pp is the display page periode 
  if(isset($_GET['pp'])) { 
    // Get Current page periode from URL
    $periode = $_GET['pp'];
    if($periode <= 0)
      // Periode is less than 0 then set it to 1
      $periode = 1;
  } else
      // URL does not show the page set it to 1
      $periode = 1;
  return $periode;
}

/**
 * Get page post taken in statprss-visitors
 */
function newstatpress_page_posts() {
  global $wpdb;
  // pa is the display pages Articles
  if(isset($_GET['pa'])) {
    // Get Current page Articles from URL 
    $pageA = $_GET['pa']; 
    if($pageA <= 0) 
      // Article is less than 0 then set it to 1
      $pageA = 1;
  } else  
      // URL does not show the Article set it to 1
      $pageA = 1;
  return $pageA;
}

/**
 * New spy function taken in statpress-visitors
 */
function iriNewStatPressNewSpy() {
  global $wpdb;
  $action="newspy";
  $table_name = $wpdb->prefix . "statpress";
 
  // number of IP or bot by page
  $LIMIT = get_option('newstatpress_ip_per_page_newspy');
  $LIMIT_PROOF = get_option('newstatpress_visits_per_ip_newspy');
  if ($LIMIT == 0) $LIMIT = 20;
  if ($LIMIT_PROOF == 0) $LIMIT_PROOF = 20;

  $pp = newstatpress_page_periode();

  // Number of distinct ip (unique visitors)
  $NumIP = $wpdb->get_var("
    SELECT count(distinct ip) 
    FROM $table_name 
    WHERE spider=''"
  );
  $NP = ceil($NumIP/$LIMIT);
  $LimitValue = ($pp * $LIMIT) - $LIMIT;
        
  $sql = "
    SELECT *
    FROM $table_name as T1
    JOIN
      (SELECT max(id) as MaxId,min(id) as MinId,ip, nation 
       FROM $table_name 
       WHERE spider='' 
       GROUP BY ip 
       ORDER BY MaxId 
       DESC LIMIT $LimitValue, $LIMIT ) as T2
    ON T1.ip = T2.ip 
    WHERE id BETWEEN MinId AND MaxId
    ORDER BY MaxId DESC, id DESC
  ";

  $qry = $wpdb->get_results($sql);

  echo "<div class='wrap'><h2>" . __('Visitor Spy', 'newstatpress') . "</h2>";
?>
<script>
function ttogle(thediv){
if (document.getElementById(thediv).style.display=="inline") {
document.getElementById(thediv).style.display="none"
} else {document.getElementById(thediv).style.display="inline"}
}
</script>
<?php    
  $ip = 0;
  $num_row=0;
  echo'<div id="paginating" align="center">';
  newstatpress_print_pp_link($NP,$pp,$action);
  echo'</div><table id="mainspytab" name="mainspytab" width="99%" border="0" cellspacing="0" cellpadding="4">';    
  foreach ($qry as $rk) {
    // Visitor Spy
    if ($ip <> $rk->ip) { 
      //this is the first time these ip appear, print informations
      echo "<tr><td colspan='2' bgcolor='#dedede'><div align='left'>";  
      $title='';
      $id ='';
      ///if ($rk->country <> '') { 
      ///  $img=strtolower($rk->country).".png"; 
      ///  $lines = file(ABSPATH.'wp-content/plugins/'.dirname(dirname(dirname(plugin_basename(__FILE__)))) .'/def/domain.dat');
      ///  foreach($lines as $line_num => $country) { 
      ///    list($id,$title)=explode("|",$country);
      ///    if($id===strtolower($rk->country)) break;
      ///  }  
      ///  echo "http country <IMG style='border:0px;height:16px;' alt='".$title."' title='".$title."' SRC='" .plugins_url('newstatpress/images/domain/'.$img, dirname(dirname(dirname(__FILE__)))). "'>  ";
      ///} else
        if($rk->nation <> '') { 
          // the nation exist
          $img=strtolower($rk->nation).".png"; 
          $lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/domain.dat');
          foreach($lines as $line_num => $nation) { 
            list($id,$title)=explode("|",$nation);
            if($id===$rk->nation) break;
          }  
          print "".__('Http domain', 'newstatpress')." <IMG style='border:0px;height:16px;' alt='".$title."' title='".$title."' SRC='" .plugins_url('newstatpress/images/domain/'.$img, dirname(plugin_basename(__FILE__))). "'>  ";

        } else {
            $ch = curl_init('http://api.hostip.info/country.php?ip='.$rk->ip);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch); 
            $output .=".png";
            $output = strtolower($output);
            curl_close($ch);
            print "".__('Hostip country','newstatpress'). "<IMG style='border:0px;width:18;height:12px;' alt='".$title."' title='".$title."' SRC='" .plugins_url('newstatpress/images/domain/'.$output, dirname(plugin_basename(__FILE__))). "'>  ";
      }

        print "<strong><span><font size='2' color='#7b7b7b'>".$rk->ip."</font></span></strong> ";
        print "<span style='color:#006dca;cursor:pointer;border-bottom:1px dotted #AFD5F9;font-size:8pt;' onClick=ttogle('".$rk->ip."');>".__('more info','newstatpress')."</span></div>";
        print "<div id='".$rk->ip."' name='".$rk->ip."'>";

        if(get_option('newstatpress_cryptip')!='checked') {
          print "<br><iframe style='overflow:hidden;border:0px;width:100%;height:60px;font-family:helvetica;padding:0;' scrolling='no' marginwidth=0 marginheight=0 src=http://api.hostip.info/get_html.php?ip=".$rk->ip."></iframe>";
        }
        print "<br><small><span style='font-weight:700;'>OS or device:</span> ".$rk->os."</small>";
        print "<br><small><span style='font-weight:700;'>DNS Name:</span> ".gethostbyaddr($rk->ip)."</small>";
        print "<br><small><span style='font-weight:700;'>Browser:</span> ".$rk->browser."</small>";
        print "<br><small><span style='font-weight:700;'>Browser Detail:</span> ".$rk->agent."</small>";
        print "<br><br></div>";
        print "<script>document.getElementById('".$rk->ip."').style.display='none';</script>";
        print "</td></tr>";  


        echo "<td valign='top' width='151'><div><font size='1' color='#3B3B3B'><strong>" . newstatpress_hdate($rk->date) . " " . $rk->time . "</strong></font></div></td>
              <td>" . newstatpress_Decode($rk->urlrequested) ."";
        if ($rk->searchengine != '') print "<br><small>".__('arrived from','newstatpress')." <b>" . $rk->searchengine . "</b> ".__('searching','newstatpress')." <a href='" . $rk->referrer . "' target=_blank>" . urldecode($rk->search) . "</a></small>";
        elseif ($rk->referrer != '' && strpos($rk->referrer, get_option('home')) === false) print "<br><small>".__('arrived from','newstatpress')." <a href='" . $rk->referrer . "' target=_blank>" . $rk->referrer . "</a></small>";
        echo "</div></td></tr>\n";
        $ip=$rk->ip;
        $num_row = 1;
    } elseif ($num_row < $LIMIT_PROOF) { 
        echo "<tr><td valign='top' width='151'><div><font size='1' color='#3B3B3B'><strong>" . newstatpress_hdate($rk->date) . " " . $rk->time . "</strong></font></div></td>
              <td><div>" . newstatpress_Decode($rk->urlrequested) . "";
        if ($rk->searchengine != '') print "<br><small>".__('arrived from','newstatpress')." <b>" . $rk->searchengine . "</b> ".__('searching','newstatpress')." <a href='" . $rk->referrer . "' target=_blank>" . urldecode($rk->search) . "</a></small>";
        elseif ($rk->referrer != '' && strpos($rk->referrer, get_option('home')) === false) print "<br><small>".__('arrived from','newstatpress')." <a href='" . $rk->referrer . "' target=_blank>" . $rk->referrer . "</a></small>";
        $num_row += 1;
        echo "</div></td></tr>\n";
      }
   }
   echo "</div></td></tr>\n</table>";   
   newstatpress_print_pp_link($NP,$pp,$action);
   echo "</div>";
} 

/**
 * New spy bot function taken in statpress-visitors
 */
function iriNewStatPressSpyBot() {
  global $wpdb;

  $action="spybot";
  $table_name = $wpdb->prefix . "statpress";
 
  $LIMIT = get_option('newstatpress_bot_per_page_spybot');
  $LIMIT_PROOF = get_option('newstatpress_visits_per_bot_spybot');

  if ($LIMIT ==0) $LIMIT = 10;
  if ($LIMIT_PROOF == 0) $LIMIT_PROOF = 30;

  $pa = newstatpress_page_posts();
  $LimitValue = ($pa * $LIMIT) - $LIMIT;

  // limit the search 7 days ago
  $day_ago = gmdate('Ymd', current_time('timestamp') - 7*86400);	   
  $MinId = $wpdb->get_var("
    SELECT min(id) as MinId 
    FROM $table_name 
    WHERE date > $day_ago
  ");

  // Number of distinct spiders after $day_ago
  $Num = $wpdb->get_var("
    SELECT count(distinct spider) 
    FROM $table_name 
    WHERE 
      spider<>'' AND 
      id >$MinId
  ");
  $NA = ceil($Num/$LIMIT);

  echo "<div class='wrap'><h2>" . __('Spy Bot', 'newstatpress') . "</h2>";

  // selection of spider, group by spider, order by most recently visit (last id in the table)
  $sql = "
    SELECT *
    FROM $table_name as T1
    JOIN
    (SELECT spider,max(id) as MaxId 
     FROM $table_name 
     WHERE spider<>'' 
     GROUP BY spider 
     ORDER BY MaxId 
     DESC LIMIT $LimitValue, $LIMIT 
    ) as T2
    ON T1.spider = T2.spider
    WHERE T1.id > $MinId
    ORDER BY MaxId DESC, id DESC
  ";
  $qry = $wpdb->get_results($sql);

  echo '<div align="center">';
  newstatpress_print_pp_pa_link (0,0,$action,$NA,$pa);
  echo '</div><div align="left">';
?>
<script>
function ttogle(thediv){
if (document.getElementById(thediv).style.display=="inline") {
document.getElementById(thediv).style.display="none"
} else {document.getElementById(thediv).style.display="inline"}
}
</script>
<table id="mainspytab" name="mainspytab" width="99%" border="0" cellspacing="0" cellpadding="4"><div align='left'>
<?php        
  $spider="robot";
  $num_row=0;
  foreach ($qry as $rk) {  // Bot Spy
    if ($robot <> $rk->spider) {
      echo "<div align='left'>
            <tr>
            <td colspan='2' bgcolor='#dedede'>";
      $img=str_replace(" ","_",strtolower($rk->spider));
      $img=str_replace('.','',$img).".png";
      $lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/spider.dat');
      foreach($lines as $line_num => $spider) { //seeks the tooltip corresponding to the photo
        list($title,$id)=explode("|",$spider);
        if($title==$rk->spider) break; // break, the tooltip ($title) is found
      }
      echo "<IMG style='border:0px;height:16px;align:left;' alt='".$title."' title='".$title."' SRC='" .plugins_url('newstatpress/images/spider/'.$img, dirname(plugin_basename(__FILE__))). "'>
            <span style='color:#006dca;cursor:pointer;border-bottom:1px dotted #AFD5F9;font-size:8pt;' onClick=ttogle('" . $img . "');>http more info</span>
            <div id='" . $img . "' name='" . $img . "'><br /><small>" . $rk->ip . "</small><br><small>" . $rk->agent . "<br /></small></div>
            <script>document.getElementById('" . $img . "').style.display='none';</script>
            </tr>
            <tr><td valign='top' width='170'><div><font size='1' color='#3B3B3B'><strong>" . newstatpress_hdate($rk->date) . " " . $rk->time . "</strong></font></div></td>
            <td><div>" . newstatpress_Decode($rk->urlrequested) . "</div></td></tr>";
      $robot=$rk->spider;
      $num_row=1;
    } elseif ($num_row < $LIMIT_PROOF) {
        echo "<tr>
              <td valign='top' width='170'><div><font size='1' color='#3B3B3B'><strong>" . newstatpress_hdate($rk->date) . " " . $rk->time . "</strong></font></div></td>
              <td><div>" . newstatpress_Decode($rk->urlrequested) . "</div></td></tr>";
        $num_row+=1;
      }
      echo "</div></td></tr>\n";
  }
  echo "</table>"; 
  newstatpress_print_pp_pa_link (0,0,$action,$NA,$pa);
  echo "</div>";
}

/**
 * Newstatpress spy function
 */
function iriNewStatPressSpy() {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";

  # Spy
  $today = gmdate('Ymd', current_time('timestamp'));
  $yesterday = gmdate('Ymd', current_time('timestamp')-86400);
  print "<div class='wrap'><h2>".__('Spy','newstatpress')."</h2>";
  $sql="
    SELECT ip,nation,os,browser,agent 
    FROM $table_name 
    WHERE 
      spider='' AND 
      feed='' AND 
      date BETWEEN '$yesterday' AND '$today' 
    GROUP BY ip ORDER BY id DESC LIMIT 20";
  $qry = $wpdb->get_results($sql);

?>
<script>
function ttogle(thediv){
if (document.getElementById(thediv).style.display=="inline") {
document.getElementById(thediv).style.display="none"
} else {document.getElementById(thediv).style.display="inline"}
}
</script>
<div>
<table id="mainspytab" name="mainspytab" width="99%" border="0" cellspacing="0" cellpadding="4">
<?php
  foreach ($qry as $rk) {
    print "<tr><td colspan='2' bgcolor='#dedede'><div align='left'>";

    if($rk->nation <> '') { 
      // the nation exist
      $img=strtolower($rk->nation).".png"; 
      $lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/domain.dat');
      foreach($lines as $line_num => $nation) { 
        list($id,$title)=explode("|",$nation);
        if($id===$rk->nation) break;
      }  
      echo "<IMG style='border:0px;height:16px;' alt='".$title."' title='".$title."' SRC='" .plugins_url('newstatpress/images/domain/'.$img, dirname(plugin_basename(__FILE__))). "'>  ";
    } else {
        $ch = curl_init('http://api.hostip.info/country.php?ip='.$rk->ip);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch); 
        $output .=".png";
        $output = strtolower($output);
        curl_close($ch);
        echo "<IMG style='border:0px;width:18;height:12px;' alt='".$title."' title='".$title."' SRC='" .plugins_url('newstatpress/images/domain/'.$output, dirname(plugin_basename(__FILE__))). "'>  "; 
      }

    
    print " <strong><span><font size='2' color='#7b7b7b'>".$rk->ip."</font></span></strong> ";
    print "<span style='color:#006dca;cursor:pointer;border-bottom:1px dotted #AFD5F9;font-size:8pt;' onClick=ttogle('".$rk->ip."');>".__('more info','newstatpress')."</span></div>";
    print "<div id='".$rk->ip."' name='".$rk->ip."'>";
    if(get_option('newstatpress_cryptip')!='checked') {
      print "<br><iframe style='overflow:hidden;border:0px;width:100%;height:60px;font-family:helvetica;padding:0;' scrolling='no' marginwidth=0 marginheight=0 src=http://api.hostip.info/get_html.php?ip=".$rk->ip."></iframe>";
    }
    print "<br><small><span style='font-weight:700;'>OS or device:</span> ".$rk->os."</small>";
    print "<br><small><span style='font-weight:700;'>DNS Name:</span> ".gethostbyaddr($rk->ip)."</small>";
    print "<br><small><span style='font-weight:700;'>Browser:</span> ".$rk->browser."</small>";
    print "<br><small><span style='font-weight:700;'>Browser Detail:</span> ".$rk->agent."</small>";
    print "<br><br></div>";
    print "<script>document.getElementById('".$rk->ip."').style.display='none';</script>";
    print "</td></tr>";
    $qry2=$wpdb->get_results("
      SELECT * 
      FROM $table_name 
      WHERE 
        ip='".$rk->ip."' AND 
        (date BETWEEN '$yesterday' AND '$today') 
      ORDER BY id 
      LIMIT 10"
    );
    foreach ($qry2 as $details) {
      print "<tr>";
      print "<td valign='top' width='151'><div><font size='1' color='#3B3B3B'><strong>".irihdate($details->date)." ".$details->time."</strong></font></div></td>";
      print "<td><div><a href='".get_bloginfo('url')."/?".$details->urlrequested."' target='_blank'>".iri_NewStatPress_Decode($details->urlrequested)."</a>";
      if($details->searchengine != '') {
        print "<br><small>".__('arrived from','newstatpress')." <b>".$details->searchengine."</b> ".__('searching','newstatpress')." <a href='".$details->referrer."' target='_blank'>".$details->search."</a></small>";
      } elseif($details->referrer != '' && strpos($details->referrer,get_option('home'))===FALSE) {
          print "<br><small>".__('arrived from','newstatpress')." <a href='".$details->referrer."' target='_blank'>".$details->referrer."</a></small>";
        }
      print "</div></td>";
      print "</tr>\n";
    }
  }
?>
</table>
</div>
<?php
}


/**
 * Check if the argoument is an IP addresses
 * 
 * @param ip the ip to check
 * @return TRUE if it is an ip
 */
function iri_CheckIP($ip) {
  return ( ! preg_match( "/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip)) ? FALSE : TRUE;
}

function iriNewStatPressSearch($what='') {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";

  $f['urlrequested']=__('URL Requested','newstatpress');
  $f['agent']=__('Agent','newstatpress');
  $f['referrer']=__('Referrer','newstatpress');
  $f['search']=__('Search terms','newstatpress');
  $f['searchengine']=__('Search engine','newstatpress');
  $f['os']=__('Operative system','newstatpress');	
  $f['browser']="Browser";
  $f['spider']="Spider";
  $f['ip']="IP";
?>
  <div class='wrap'><h2><?php _e('Search','newstatpress'); ?></h2>
  <form method=get><table>
  <?php
    for($i=1;$i<=3;$i++) {
      print "<tr>";
      print "<td>".__('Field','newstatpress')." <select name=where$i><option value=''></option>";
      foreach ( array_keys($f) as $k ) {
        print "<option value='$k'";
        if($_GET["where$i"] == $k) { print " SELECTED "; }
        print ">".$f[$k]."</option>";
      }
      print "</select></td>";
      if (isset($_GET["groupby$i"])) print "<td><input type=checkbox name=groupby$i value='checked' ".$_GET["groupby$i"]."> ".__('Group by','newstatpress')."</td>";
      else print "<td><input type=checkbox name=groupby$i value='checked' "."> ".__('Group by','newstatpress')."</td>";

      if (isset($_GET["sortby$i"])) print "<td><input type=checkbox name=sortby$i value='checked' ".$_GET["sortby$i"]."> ".__('Sort by','newstatpress')."</td>";
      else print "<td><input type=checkbox name=sortby$i value='checked' "."> ".__('Sort by','newstatpress')."</td>";

      print "<td>, ".__('if contains','newstatpress')." <input type=text name=what$i value='".$_GET["what$i"]."'></td>";
      print "</tr>";
    }
?>
  </table>
  <br>
  <table>
   <tr>
     <td>
       <table>
         <tr><td><input type=checkbox name=oderbycount value=checked <?php print $_GET['oderbycount'] ?>> <?php _e('sort by count if grouped','newstatpress'); ?></td></tr>
         <tr><td><input type=checkbox name=spider value=checked <?php print $_GET['spider'] ?>> <?php _e('include spiders/crawlers/bot','newstatpress'); ?></td></tr>
         <tr><td><input type=checkbox name=feed value=checked <?php print $_GET['feed'] ?>> <?php _e('include feed','newstatpress'); ?></td></tr>
       </table>
     </td>
     <td width=15> </td>
     <td>
       <table>
         <tr>
           <td><?php _e('Limit results to','newstatpress'); ?>
             <select name=limitquery><?php if($_GET['limitquery'] >0) { print "<option>".$_GET['limitquery']."</option>";} ?><option>1</option><option>5</option><option>10</option><option>20</option><option>50</option></select>
           </td>
         </tr>
         <tr><td>&nbsp;</td></tr>
         <tr>
          <td align=right><input type=submit value=<?php _e('Search','newstatpress'); ?> name=searchsubmit></td>
         </tr>
       </table>
     </td>
    </tr>		
   </table>	
   <input type=hidden name=page value='newstatpress/newstatpress.php'><input type=hidden name=newstatpress_action value=search>
  </form><br>
<?php

 if(isset($_GET['searchsubmit'])) {
   # query builder
   $qry="";
   # FIELDS
   $fields="";
   for($i=1;$i<=3;$i++) {
     if($_GET["where$i"] != '') {
       $fields.=$_GET["where$i"].",";
     }
   }
   $fields=rtrim($fields,",");
   # WHERE
   $where="WHERE 1=1";

   if (!isset($_GET['spider'])) { $where.=" AND spider=''"; }
   else if($_GET['spider'] != 'checked') { $where.=" AND spider=''"; }

   if (!isset($_GET['feed'])) { $where.=" AND feed=''"; }
   else if($_GET['feed'] != 'checked') { $where.=" AND feed=''"; }

   for($i=1;$i<=3;$i++) {
     if(($_GET["what$i"] != '') && ($_GET["where$i"] != '')) {
       $where.=" AND ".$_GET["where$i"]." LIKE '%".$_GET["what$i"]."%'";
     }
   }
   # ORDER BY
   $orderby="";
   for($i=1;$i<=3;$i++) {
     if (isset($_GET["sortby$i"]) && ($_GET["sortby$i"] == 'checked') && ($_GET["where$i"] != '')) {
       $orderby.=$_GET["where$i"].',';
     }
   }

   # GROUP BY
   $groupby="";
   for($i=1;$i<=3;$i++) {
     if(isset($_GET["groupby$i"]) && ($_GET["groupby$i"] == 'checked') && ($_GET["where$i"] != '')) {
       $groupby.=$_GET["where$i"].',';
     }
   }
   if($groupby != '') {
     $groupby="GROUP BY ".rtrim($groupby,',');
     $fields.=",count(*) as totale";
     if(isset($_GET["oderbycount"]) && $_GET['oderbycount'] == 'checked') { $orderby="totale DESC,".$orderby; }
   }

   if($orderby != '') { $orderby="ORDER BY ".rtrim($orderby,','); }
 
   $limit="LIMIT ".$_GET['limitquery'];

   # Results
   print "<h2>".__('Results','newstatpress')."</h2>";
   $sql="SELECT $fields FROM $table_name $where $groupby $orderby $limit;";
   //print "$sql<br>";
   print "<table class='widefat'><thead><tr>";
   for($i=1;$i<=3;$i++) { 
     if($_GET["where$i"] != '') { print "<th scope='col'>".ucfirst($_GET["where$i"])."</th>"; }
   }
   if($groupby != '') { print "<th scope='col'>".__('Count','newstatpress')."</th>"; }
     print "</tr></thead><tbody id='the-list'>";	
     $qry=$wpdb->get_results($sql,ARRAY_N);
     foreach ($qry as $rk) {
       print "<tr>";
       for($i=1;$i<=3;$i++) {
         print "<td>";
         if($_GET["where$i"] == 'urlrequested') { print iri_NewStatPress_Decode($rk[$i-1]); }
         else { if(isset($rk[$i-1])) print $rk[$i-1]; }
         print "</td>";
       }
         print "</tr>";
     }
     print "</table>";
     print "<br /><br /><font size=1 color=gray>sql: $sql</font></div>";
  }
}

function iri_NewStatPress_Abbrevia($s,$c) {
	$res=""; if(strlen($s)>$c) { $res="..."; }
	return substr($s,0,$c).$res;
	
}

/**
 * Decode the given url
 *
 * @param out_url the given url to decode
 * @return the decoded url
 */
function iri_NewStatPress_Decode($out_url) {
  if($out_url == '') { $out_url=__('Page','newstatpress').": Home"; }
  if(substr($out_url,0,4)=="cat=") { $out_url=__('Category','newstatpress').": ".get_cat_name(substr($out_url,4)); }
  if(substr($out_url,0,2)=="m=") { $out_url=__('Calendar','newstatpress').": ".substr($out_url,6,2)."/".substr($out_url,2,4); }
  if(substr($out_url,0,2)=="s=") { $out_url=__('Search','newstatpress').": ".substr($out_url,2); }
  if(substr($out_url,0,2)=="p=") {
    $subOut=substr($out_url,2);
    $post_id_7 = get_post($subOut, ARRAY_A);
    $out_url = $post_id_7['post_title'];
  }
  if(substr($out_url,0,8)=="page_id=") {
    $subOut=substr($out_url,8);
    $post_id_7=get_page($subOut, ARRAY_A);
    $out_url = __('Page','newstatpress').": ".$post_id_7['post_title'];
  }
  return $out_url;
}


function iri_NewStatPress_URL() {
    $urlRequested = (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '' );
	if ( $urlRequested == "" ) { // SEO problem!
	    $urlRequested = (isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '' );
	}
	if(substr($urlRequested,0,2) == '/?') { $urlRequested=substr($urlRequested,2); }
	if($urlRequested == '/') { $urlRequested=''; }
	return $urlRequested;
}


# Converte da data us to default format di Wordpress
function irihdate($dt = "00000000") {
	return mysql2date(get_option('date_format'), substr($dt,0,4)."-".substr($dt,4,2)."-".substr($dt,6,2));
}


function iritablesize($table) {
	global $wpdb;
	$res = $wpdb->get_results("SHOW TABLE STATUS LIKE '$table'");
	foreach ($res as $fstatus) {
		$data_lenght = $fstatus->Data_length;
		$data_rows = $fstatus->Rows;
	}
	return number_format(($data_lenght/1024/1024), 2, ",", " ")." Mb ($data_rows records)";
}

function iriindextablesize($table) {
	global $wpdb;
	$res = $wpdb->get_results("SHOW TABLE STATUS LIKE '$table'");
	foreach ($res as $fstatus) {
		$index_lenght = $fstatus->Index_length;
	}
	return number_format(($index_lenght/1024/1024), 2, ",", " ")." Mb";
}

/**
 * Get google url query for geo data
 *
 * @param data_array the array of data_array
 * @return the url with data
 */
function iriGetGoogleGeo($data_array) {
  if(empty($data_array)) { return ''; }
  // get hash
  foreach($data_array as $key => $value ) {
    $values[] = $value;
    $labels[] = $key;
  }
  return "?cht=Country&chd=".(implode(",",$values))."&chlt=Popularity&chld=".(implode(",",$labels));
}

/**
 * Get google url query for pie data
 *
 * @param data_array the array of data_array
 * @param title the title to use
 * @return the url with data
 */
function iriGetGooglePie($title, $data_array) {
  if(empty($data_array)) { return ''; }
  // get hash
  foreach($data_array as $key => $value ) {
    $values[] = $value;
    $labels[] = $key;
  }

  $data=$chartData."&chxt=y&chxl=0:|0|".$maxValue;
  //return "<img src=http://chart.apis.google.com/chart?chtt=".urlencode($title)."&cht=p3&chs=$size&chd=".$data."&chl=".urlencode(implode("|",$labels)).">";

  return "?title=".$title."&chd=".(implode(",",$values))."&chl=".urlencode(implode("|",$labels));
}

function iriValueTable2($fld,$fldtitle,$limit = 0,$param = "", $queryfld = "", $exclude= "", $print = TRUE) {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";

  if ($queryfld == '') { 
    $queryfld = $fld; 
  }
  $text = "<div class='wrap'><table class='widefat'><thead><tr><th scope='col' style='width:80%;'><h2>$fldtitle</h2></th><th scope='col' style='width:20%;text-align:center;'>".__('Visits','newstatpress')."</th><th></th></tr></thead>";
  $rks = $wpdb->get_var("
     SELECT count($param $queryfld) as rks 
     FROM $table_name 
     WHERE 1=1 $exclude;
  "); 

  if($rks > 0) {
    $sql="
      SELECT count($param $queryfld) as pageview, $fld 
      FROM $table_name 
      WHERE 1=1 $exclude 
      GROUP BY $fld 
      ORDER BY pageview DESC
    ";
    if($limit > 0) { 
      $sql=$sql." LIMIT $limit"; 
    }
    $qry = $wpdb->get_results($sql);
    $tdwidth=450;

    // Collects data
    $data=array();
    foreach ($qry as $rk) {
      $pc=round(($rk->pageview*100/$rks),1);
      if($fld == 'nation') { $rk->$fld = strtoupper($rk->$fld); }
      if($fld == 'date') { $rk->$fld = irihdate($rk->$fld); }
      if($fld == 'urlrequested') { $rk->$fld = iri_NewStatPress_Decode($rk->$fld); }
      $data[substr($rk->$fld,0,250)]=$rk->pageview;
    }
  }

  // Draw table body
  $text = $text."<tbody id='the-list'>";
  if($rks > 0) {  // Chart!
    if($fld == 'nation') {
      // inser geochart of nation
      $chart="<iframe ";
      $chart = $chart." src=\"".plugins_url('newstatpress')."/includes/geocharts.html".iriGetGoogleGeo($data)."\"";
      $chart = $chart." class=\"framebox\"";
      $chart = $chart."  style=\"width: 100%; height: 550px;\">";
      $chart = $chart."  <p>[This section requires a browser that supports iframes.]</p>";
      $chart = $chart."</iframe>";

    } else {
      $chart="<iframe ";
      $chart = $chart." src=\"".plugins_url('newstatpress')."/includes/piecharts.html".iriGetGooglePie($fldtitle, $data)."\"";
      $chart = $chart." class=\"framebox\"";
      $chart = $chart."  style=\"width: 100%; height: 550px;\">";
      $chart = $chart."  <p>[This section requires a browser that supports iframes.]</p>";
      $chart = $chart."</iframe>";

      }
    #$text = $text. "<tr><td></td><td></td><td rowspan='".($limit+2)."'>$chart</td></tr>";
    foreach ($data as $key => $value) {
      $text = $text."<tr><td style='width:80%;overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>".$key;
      $text = $text."</td><td style='width:20%;text-align:center;'>".$value."</td>";
      $text = $text."</tr>";
    }
    $text = $text. "<tr><td colspan=2 style='width:100%;'>$chart</td></tr>";
  }
  $text = $text."</tbody></table></div><br>\n";
  if ($print) print $text;
  else return $text;
}


function iriGetLanguage($accepted) {
	return substr($accepted,0,2);
}


function iriGetQueryPairs($url){
$parsed_url = parse_url($url);
$tab=parse_url($url);
$host = $tab['host'];
if(key_exists("query",$tab)){
 $query=$tab["query"];
 return explode("&",$query);
}
else{return null;}
}


/**
 * Get OS from the given argument
 * 
 * @param arg the argument to parse for OS
 * @return the OS find in configuration file
 */
function iriGetOS($arg) {
  $arg=str_replace(" ","",$arg);
  $lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/os.dat');
  foreach($lines as $line_num => $os) {
    list($nome_os,$id_os)=explode("|",$os);
    if(strpos($arg,$id_os)===FALSE) continue;
    return $nome_os;     // fount
  }
  return '';
}

/**
 * Get Browser from the given argument
 * 
 * @param arg the argument to parse for Brower
 * @return the Browser find in configuration file
 */
function iriGetBrowser($arg) {
  $arg=str_replace(" ","",$arg);
  $lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/browser.dat');
  foreach($lines as $line_num => $browser) {
    list($nome,$id)=explode("|",$browser);
    if(strpos($arg,$id)===FALSE) continue;
    return $nome;     // fount
  }
  return '';
}

/**
 * Check if the given ip is to ban
 *
 * @param arg the ip to check
 * @return '' id the address is banned
 */
function iriCheckBanIP($arg){
  $lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/banips.dat');
  foreach($lines as $line_num => $banip) {
    if(strpos($arg,rtrim($banip,"\n"))===FALSE) continue;
    return ''; // this is banned
  }
  return $arg;
}

function iriGetSE($referrer = null){
	$key = null;
	$lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/searchengines.dat');
	foreach($lines as $line_num => $se) {
		list($nome,$url,$key)=explode("|",$se);
		if(strpos($referrer,$url)===FALSE) continue;
		# trovato se
		$variables = iriGetQueryPairs(html_entity_decode($referrer));
		$i = count($variables);
		while($i--){
		   $tab=explode("=",$variables[$i]);
			   if($tab[0] == $key){return ($nome."|".urldecode($tab[1]));}
		}
	}
	return null;
}

/**
 * Get the spider from the given agent
 *
 * @param agent the agent string
 * @return agent the fount agent
 */
function iriGetSpider($agent = null){
  $agent=str_replace(" ","",$agent);
  $key = null;
  $lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/spider.dat');
  foreach($lines as $line_num => $spider) {
    list($nome,$key)=explode("|",$spider);
    if(strpos($agent,$key)===FALSE) continue;
    # fount
    return $nome;
  }
  return null;
}

function iri_NewStatPress_lastmonth() {
  $ta = getdate(current_time('timestamp'));
    
  $year = $ta['year'];
  $month = $ta['mon'];
    
  --$month; // go back 1 month
    
  if( $month === 0 ): // if this month is Jan
    --$year; // go back a year
    $month = 12; // last month is Dec
  endif;
    
  // return in format 'YYYYMM'
  return sprintf( $year.'%02d', $month); 
}

/**
 * Create or update the table
 */
function iri_NewStatPress_CreateTable() {
  global $wpdb;
  global $wp_db_version;
  $table_name = $wpdb->prefix . "statpress";
  $sql_createtable = "
    CREATE TABLE " . $table_name . " (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      date int(8),
      time time,
      ip varchar(39),
      urlrequested varchar(250),
      agent varchar(250),
      referrer varchar(250),
      search varchar(250),
      nation varchar(2),
      os varchar(30),
      browser varchar(32),
      searchengine varchar(16),
      spider varchar(32),
      feed varchar(8),
      user varchar(16),
      timestamp timestamp DEFAULT 0,
      UNIQUE KEY id (id),
      index spider_nation (spider, nation),  
      index ip_date (ip, date),  
      index agent (agent),  
      index search (search),  
      index referrer (referrer),  
      index feed_spider_os (feed, spider, os),  
      index os (os),  
      index date_feed_spider (date, feed, spider),  
      index feed_spider_browser (feed, spider, browser),  
      index browser (browser)
    );";
  if($wp_db_version >= 5540) $page = 'wp-admin/includes/upgrade.php';  
  else $page = 'wp-admin/upgrade'.'-functions.php';

  require_once(ABSPATH . $page);
  dbDelta($sql_createtable);	
}

function iri_NewStatPress_is_feed($url) {
	if (stristr($url,get_bloginfo('rdf_url')) != FALSE) { return 'RDF'; }
	if (stristr($url,get_bloginfo('rss2_url')) != FALSE) { return 'RSS2'; }
	if (stristr($url,get_bloginfo('rss_url')) != FALSE) { return 'RSS'; }
	if (stristr($url,get_bloginfo('atom_url')) != FALSE) { return 'ATOM'; }
	if (stristr($url,get_bloginfo('comments_rss2_url')) != FALSE) { return 'COMMENT'; }
	if (stristr($url,get_bloginfo('comments_atom_url')) != FALSE) { return 'COMMENT'; }
	if (stristr($url,'wp-feed.php') != FALSE) { return 'RSS2'; }
	if (stristr($url,'/feed/') != FALSE) { return 'RSS2'; }
	return '';
}

/**
 * Insert statistic into the database
 */
function iriStatAppend() {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";
  global $userdata;
  global $_STATPRESS;

  get_currentuserinfo();
  $feed='';

  // Time
  $timestamp  = current_time('timestamp');
  $vdate  = gmdate("Ymd",$timestamp);
  $vtime  = gmdate("H:i:s",$timestamp);
  $timestamp = date('Y-m-d H:i:s', $timestamp);

  // IP
  $ipAddress = $_SERVER['REMOTE_ADDR'];

  // Is this IP blacklisted from file?
  if(iriCheckBanIP($ipAddress) == '') { return ''; }

  // Is this IP blacklisted from user?
  $to_ignore = get_option('newstatpress_ignore_ip', array());
  foreach($to_ignore as $a_ip_range){
    list ($ip_to_ignore, $mask) = @explode("/", trim($a_ip_range));
    if (empty($mask)) $mask = 32;
    $long_ip_to_ignore = ip2long($ip_to_ignore);
    $long_mask = bindec( str_pad('', $mask, '1') . str_pad('', 32-$mask, '0') );
    $long_masked_user_ip = ip2long($ipAddress) & $long_mask;
    $long_masked_ip_to_ignore = $long_ip_to_ignore & $long_mask;
    if ($long_masked_user_ip == $long_masked_ip_to_ignore) { return ''; }
  }

  if(get_option('newstatpress_cryptip')=='checked') {
    $ipAddress = crypt($ipAddress,'newstatpress');
  }

  // URL (requested)
  $urlRequested=iri_NewStatPress_URL();
  if (preg_match("/.ico$/i", $urlRequested)) { return ''; }
  if (preg_match("/favicon.ico/i", $urlRequested)) { return ''; }
  if (preg_match("/.css$/i", $urlRequested)) { return ''; }
  if (preg_match("/.js$/i", $urlRequested)) { return ''; }
  if (stristr($urlRequested,"/wp-content/plugins") != FALSE) { return ''; }
  if (stristr($urlRequested,"/wp-content/themes") != FALSE) { return ''; }
  if (stristr($urlRequested,"/wp-admin/") != FALSE) { return ''; }

  // Is a given permalink blacklisted?
  $to_ignore = get_option('newstatpress_ignore_permalink', array());
    foreach($to_ignore as $a_filter){
    if (!empty($urlRequested) && strpos($urlRequested, $a_filter) === 0) { return ''; }
  }

  $referrer = (isset($_SERVER['HTTP_REFERER']) ? htmlentities($_SERVER['HTTP_REFERER']) : '');
  $userAgent = (isset($_SERVER['HTTP_USER_AGENT']) ? htmlentities($_SERVER['HTTP_USER_AGENT']) : '');
  $spider=iriGetSpider($userAgent);

  if(($spider != '') and (get_option('newstatpress_donotcollectspider')=='checked')) { return ''; }
    
  if($spider != '') {
    $os=''; $browser='';
  } else {
      // Trap feeds
      $feed=iri_NewStatPress_is_feed(get_bloginfo('url').$_SERVER['REQUEST_URI']);
      // Get OS and browser
      $os=iriGetOS($userAgent);
      $browser=iriGetBrowser($userAgent);

     if (isset($refferer)) {
      list($searchengine,$search_phrase)=explode("|",iriGetSE($referrer));
     } else {
         $searchengine='';
         $search_phrase='';
       }
    }

  // Country (ip2nation table) or language
  $countrylang="";
  if($wpdb->get_var("SHOW TABLES LIKE 'ip2nation'") == 'ip2nation') {
    $sql='SELECT * 
          FROM ip2nation 
          WHERE ip < INET_ATON("'.$ipAddress.'") 
          ORDER BY ip DESC 
          LIMIT 0,1';
    $qry = $wpdb->get_row($sql);
    $countrylang=$qry->country;
  }

  if($countrylang == '') {
    $countrylang=iriGetLanguage($_SERVER['HTTP_ACCEPT_LANGUAGE']);
  }

  // Auto-delete visits if...
  if(get_option('newstatpress_autodelete') != '') {
    $t=gmdate("Ymd",strtotime('-'.get_option('newstatpress_autodelete')));
    $results =$wpdb->query( "DELETE FROM " . $table_name . " WHERE date < '" . $t . "'");
  }
  if ((!is_user_logged_in()) OR (get_option('newstatpress_collectloggeduser')=='checked')) {
    if (is_user_logged_in() AND (get_option('newstatpress_collectloggeduser')=='checked')) {
      $current_user = wp_get_current_user();

      // Is a given name to ignore?
      $to_ignore = get_option('newstatpress_ignore_users', array());
      foreach($to_ignore as $a_filter) {
        if ($current_user->user_login == $a_filter) { return ''; }
      }
    }

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
      iri_NewStatPress_CreateTable();
    }

    $insert = 
      "INSERT INTO " . $table_name . "(
        date, 
        time, 
        ip, 
        urlrequested, 
        agent, 
        referrer, 
        search,
        nation,
        os, 
        browser,
        searchengine,
        spider,
        feed,
        user,
        timestamp
       ) VALUES (
        '$vdate',
        '$vtime',
        '$ipAddress',
        '$urlRequested',
        '".addslashes(strip_tags($userAgent))."',
        '$referrer','" .
        addslashes(strip_tags($search_phrase))."',
        '".$countrylang."',
        '$os',
        '$browser',
        '$searchengine',
        '$spider',
        '$feed',
        '$userdata->user_login', 
        '$timestamp'
       )";
    $results = $wpdb->query( $insert );
  }
}


/**
 * Get the days a use has choice for updating the database
 *
 * @return the number of days of -1 for all days
 */
function iriNewStatPressDays() {
  $days=-1;     // infinite in the past

  // Get Current page periode from URL
  $updateint =get_option('newstatpress_updateint');
    
  if     ($updateint=="1 week") $days=7;
  elseif ($updateint=="2 weeks") $days=14;
  elseif ($updateint=="3 weeks") $days=21;
  elseif ($updateint=="1 month") $days=30;
  elseif ($updateint=="2 months") $days=60;
  elseif ($updateint=="3 months") $days=90;
  elseif ($updateint=="6 months") $days=180;
  elseif ($updateint=="9 months") $days=270;
  elseif ($updateint=="1 year") $days=365;

  return $days;  
}

/**
 * Performes database update with new definitions
 */
function iriNewStatPressUpdate() {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";

  $wpdb->flush();     // flush for counting right the queries
  $start_time = microtime(true);

  $days=iriNewStatPressDays();  // get the number of days for the update
  
  $to_date  = gmdate("Ymd",current_time('timestamp'));

  if ($days==-1) $from_date= "19990101";   // use a date where this plugin was not present
  else $from_date = gmdate('Ymd', current_time('timestamp')-86400*$days);

  $_newstatpress_url=PluginUrl();

  $wpdb->show_errors();

  print "<div class='wrap'><table class='widefat'><thead><tr><th scope='col'><h2>".__('Updating...','newstatpress')."</h2></th><th scope='col' style='width:150px;'>".__('Size','newstatpress')."</th><th scope='col' style='width:100px;'>".__('Result','newstatpress')."</th><th></th></tr></thead>";
  print "<tbody id='the-list'>";

  # check if ip2nation .sql file exists
  if(file_exists(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/ip2nation.sql')) {
    print "<tr><td>ip2nation.sql</td>";
    $FP = fopen (ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/ip2nation.sql', 'r' ); 
    $READ = fread ( $FP, filesize (ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/ip2nation.sql') ); 
    $READ = explode ( ";\n", $READ ); 
    foreach ( $READ as $RED ) { 
      if($RES != '') { $wpdb->query($RED); }
    } 
    print "<td>".iritablesize("ip2nation")."</td>";
    print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";
  }

  # update table
  print "<tr><td>Struct $table_name</td>";
  iri_NewStatPress_CreateTable();
  print "<td>".iritablesize($wpdb->prefix."statpress")."</td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";

  print "<tr><td>Index $table_name</td>";
  print "<td>".iriindextablesize($wpdb->prefix."statpress")."</td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";

  # Update Feed
  print "<tr><td>Feeds</td>";
  $wpdb->query("
    UPDATE $table_name 
    SET feed='' 
    WHERE date BETWEEN $from_date AND $to_date;"
  );

  # not standard
  $wpdb->query("
    UPDATE $table_name 
    SET feed='RSS2' 
    WHERE 
      urlrequested LIKE '%/feed/%' AND
      date BETWEEN $from_date AND $to_date;"
  );

  $wpdb->query("
    UPDATE $table_name 
    SET feed='RSS2' 
    WHERE 
      urlrequested LIKE '%wp-feed.php%' AND
      date BETWEEN $from_date AND $to_date;"    
  );

  # standard blog info urls
  $s=iriNewStatPress_extractfeedreq(get_bloginfo('comments_atom_url'));
  if($s != '') {
    $wpdb->query("
      UPDATE $table_name 
      SET feed='COMMENT' 
      WHERE 
        INSTR(urlrequested,'$s')>0 AND
        date BETWEEN $from_date AND $to_date;"
    );
  }
  $s=iriNewStatPress_extractfeedreq(get_bloginfo('comments_rss2_url'));
  if($s != '') {
    $wpdb->query("
      UPDATE $table_name 
      SET feed='COMMENT' 
      WHERE 
        INSTR(urlrequested,'$s')>0 AND
        date BETWEEN $from_date AND $to_date;"
    );
  }
  $s=iriNewStatPress_extractfeedreq(get_bloginfo('atom_url'));
  if($s != '') {
    $wpdb->query("
      UPDATE $table_name 
      SET feed='ATOM' 
      WHERE 
        INSTR(urlrequested,'$s')>0 AND
        date BETWEEN $from_date AND $to_date;"
    );
  }
  $s=iriNewStatPress_extractfeedreq(get_bloginfo('rdf_url'));
  if($s != '') {
    $wpdb->query("
      UPDATE $table_name 
      SET feed='RDF'  
      WHERE 
        INSTR(urlrequested,'$s')>0 AND
        date BETWEEN $from_date AND $to_date;"
    );
  }
  $s=iriNewStatPress_extractfeedreq(get_bloginfo('rss_url'));
  if($s != '') {
    $wpdb->query("
      UPDATE $table_name 
      SET feed='RSS'  
      WHERE 
        INSTR(urlrequested,'$s')>0 AND
        date BETWEEN $from_date AND $to_date;"
    );
  }
  $s=iriNewStatPress_extractfeedreq(get_bloginfo('rss2_url'));
  if($s != '') {
    $wpdb->query("
      UPDATE $table_name 
      SET feed='RSS2' 
      WHERE 
        INSTR(urlrequested,'$s')>0 AND
        date BETWEEN $from_date AND $to_date;"
    );
  }

  $wpdb->query("
    UPDATE $table_name 
    SET feed = '' 
    WHERE 
      isnull(feed) AND
      date BETWEEN $from_date AND $to_date;"
   );

  print "<td></td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";

  # Update OS
  print "<tr><td>OSes</td>";
  $wpdb->query("
    UPDATE $table_name 
    SET os = ''
    WHERE date BETWEEN $from_date AND $to_date;"
  );
  $lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/os.dat');
  foreach($lines as $line_num => $os) {
    list($nome_os,$id_os)=explode("|",$os);
    $qry="
      UPDATE $table_name 
      SET os = '$nome_os' 
      WHERE 
        os='' AND 
        replace(agent,' ','') LIKE '%".$id_os."%' AND
        date BETWEEN $from_date AND $to_date;";
    $wpdb->query($qry);
  }
  print "<td></td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";


  # Update Browser
  print "<tr><td>Browsers</td>";
  $wpdb->query("
    UPDATE $table_name 
    SET browser = ''
    WHERE date BETWEEN $from_date AND $to_date;"
  );
  $lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/browser.dat');
  foreach($lines as $line_num => $browser) {
    list($nome,$id)=explode("|",$browser);
    $qry="
      UPDATE $table_name 
      SET browser = '$nome' 
      WHERE 
        browser='' AND 
        replace(agent,' ','') LIKE '%".$id."%' AND
        date BETWEEN $from_date AND $to_date;";
    $wpdb->query($qry);
  }
  print "<td></td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";


  # Update Spider
  print "<tr><td>Spiders</td>";
  $wpdb->query("
    UPDATE $table_name 
    SET spider = ''
    WHERE date BETWEEN $from_date AND $to_date;"
  );
  $lines = file(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/def/spider.dat');
  foreach($lines as $line_num => $spider) {
    list($nome,$id)=explode("|",$spider);
    $qry="
      UPDATE $table_name 
      SET spider = '$nome',os='',browser='' 
      WHERE 
        spider='' AND 
        replace(agent,' ','') LIKE '%".$id."%' AND
        date BETWEEN $from_date AND $to_date;";
    $wpdb->query($qry);
  }
  print "<td></td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";


  # Update Search engine
  print "<tr><td>Search engines</td>";
  $wpdb->query("
    UPDATE $table_name 
    SET searchengine = '', search=''
    WHERE date BETWEEN $from_date AND $to_date;");
  $qry = $wpdb->get_results("
    SELECT id, referrer 
    FROM $table_name 
    WHERE 
      length(referrer)!=0 AND
      date BETWEEN $from_date AND $to_date");
  foreach ($qry as $rk) {
    list($searchengine,$search_phrase)=explode("|",iriGetSE($rk->referrer));
    if($searchengine <> '') {
      $q="
        UPDATE $table_name 
        SET searchengine = '$searchengine', search='".addslashes($search_phrase)."' 
        WHERE 
          id=".$rk->id." AND
          date BETWEEN $from_date AND $to_date;";
      $wpdb->query($q);
    }
  }
  print "<td></td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";

  $end_time = microtime(true);
  $sql_queries=$wpdb->num_queries;

  # Final statistics
  print "<tr><td>Final Struct $table_name</td>";
  print "<td>".iritablesize($wpdb->prefix."statpress")."</td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";

  print "<tr><td>Final Index $table_name</td>";
  print "<td>".iriindextablesize($wpdb->prefix."statpress")."</td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";

  print "<tr><td>Duration of the update</td>";
  print "<td>".round($end_time - $start_time, 2)." sec</td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";

  print "<tr><td>This update was done in</td>";
  print "<td>".$sql_queries." SQL queries.</td>";
  print "<td><IMG style='border:0px;width:20px;height:20px;' SRC='".$_newstatpress_url."/images/ok.gif'></td></tr>";

  print "</tbody></table></div><br>\n";
  $wpdb->hide_errors();
}


function NewStatPress_Widget($w='') {

}

/**
 * Return the expanded vars into the give code. Wrapper for internal use
 */
function NewStatPress_Print($body='') {
  return iri_NewStatPress_Vars($body);
}


/**
 * Expand vars into the give code
 * 
 * @param boby the code where to look for variables to expand
 * @return the modified code
 */
function iri_NewStatPress_Vars($body) {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";

  # look for %visits%
  if(strpos(strtolower($body),"%visits%") !== FALSE) {
    $qry = $wpdb->get_results(
      "SELECT count(DISTINCT(ip)) AS pageview 
       FROM $table_name 
       WHERE 
        date = '".gmdate("Ymd",current_time('timestamp'))."' AND
        spider='' and feed='';
      ");
    $body = str_replace("%visits%", $qry[0]->pageview, $body);
  }

  # look for %totalvisits%
  if(strpos(strtolower($body),"%totalvisits%") !== FALSE) {
    $qry = $wpdb->get_results(
      "SELECT count(DISTINCT(ip)) AS pageview 
       FROM $table_name 
       WHERE 
         spider='' AND
         feed='';
      ");
    $body = str_replace("%totalvisits%", $qry[0]->pageview, $body);
  }

  # look for %totalpageviews%
  if(strpos(strtolower($body),"%totalpageviews%") !== FALSE) {
    $qry = $wpdb->get_results(
      "SELECT count(id) AS pageview 
       FROM $table_name 
       WHERE 
         spider='' AND 
         feed='';
      ");
    $body = str_replace("%totalpageviews%", $qry[0]->pageview, $body);
  }

  # look for %todaytotalpageviews%
  if(strpos(strtolower($body),"%todaytotalpageviews%") !== FALSE) {
    $qry = $wpdb->get_results(
      "SELECT count(id) AS pageview 
       FROM $table_name 
       WHERE 
         date = '".gmdate("Ymd",current_time('timestamp'))."' AND
         spider='' AND 
         feed='';
      ");
    $body = str_replace("%todaytotalpageviews%", $qry[0]->pageview, $body);
  }

  # look for %thistotalvisits%
  if(strpos(strtolower($body),"%thistotalvisits%") !== FALSE) {
    $qry = $wpdb->get_results(
      "SELECT count(DISTINCT(ip)) AS pageview 
       FROM $table_name 
       WHERE 
         spider='' AND
         feed='' AND 
         urlrequested='".iri_NewStatPress_URL()."';
      ");
    $body = str_replace("%thistotalvisits%", $qry[0]->pageview, $body);
  }

  # look for %alltotalvisits%
  if(strpos(strtolower($body),"%alltotalvisits%") !== FALSE) {
    $qry = $wpdb->get_results(
      "SELECT SUM(pageview) AS pageview 
       FROM (
         SELECT count(DISTINCT(ip)) AS pageview 
         FROM $table_name AS t1 
         WHERE 
           spider='' AND 
           feed='' AND 
           urlrequested!='' 
         GROUP BY urlrequested
       ) AS t2;
      ");
    $body = str_replace("%alltotalvisits%", $qry[0]->pageview, $body); 
  }

  # look for %since%
  if(strpos(strtolower($body),"%since%") !== FALSE) {
    $qry = $wpdb->get_results(
      "SELECT date 
       FROM $table_name 
       ORDER BY date 
       LIMIT 1;
      ");
    $body = str_replace("%since%", irihdate($qry[0]->date), $body);
  }

  # look for %os%
  if(strpos(strtolower($body),"%os%") !== FALSE) {
    $userAgent = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
    $os=iriGetOS($userAgent);
    $body = str_replace("%os%", $os, $body);
  }

  # look for %browser%
  if(strpos(strtolower($body),"%browser%") !== FALSE) {
    $browser=iriGetBrowser($userAgent);
    $body = str_replace("%browser%", $browser, $body);
  }

  # look for %ip%
  if(strpos(strtolower($body),"%ip%") !== FALSE) { 	
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $body = str_replace("%ip%", $ipAddress, $body);
  }

  # look for %visitorsonline%
  if(strpos(strtolower($body),"%visitorsonline%") !== FALSE) { 	
    $to_time =  current_time('timestamp');
    $from_time =  date('Y-m-d H:i:s', strtotime('-4 minutes', $to_time));
    $to_time = date('Y-m-d H:i:s', $to_time);
    $qry = $wpdb->get_results(
      "SELECT count(DISTINCT(ip)) AS visitors 
       FROM $table_name 
       WHERE 
         spider='' AND
         feed='' AND 
         timestamp BETWEEN '$from_time' AND '$to_time';
      ");
    $body = str_replace("%visitorsonline%", $qry[0]->visitors, $body);
  }

  # look for %usersonline%
  if(strpos(strtolower($body),"%usersonline%") !== FALSE) { 	
    $to_time =  current_time('timestamp');
    $from_time =  date('Y-m-d H:i:s', strtotime('-4 minutes', $to_time));
    $to_time = date('Y-m-d H:i:s', $to_time);
    $qry = $wpdb->get_results(
      "SELECT count(DISTINCT(ip)) AS users 
       FROM $table_name 
       WHERE 
         spider='' AND 
         feed='' AND 
         user<>'' AND 
         timestamp BETWEEN '$from_time' AND '$to_time';
      ");
    $body = str_replace("%usersonline%", $qry[0]->users, $body);
  }

  # look for %toppost%
  if(strpos(strtolower($body),"%toppost%") !== FALSE) {
    $qry = $wpdb->get_results(
      "SELECT urlrequested,count(*) AS totale 
       FROM $table_name 
       WHERE 
         spider='' AND 
         feed='' AND 
         urlrequested LIKE '%p=%' 
       GROUP BY urlrequested 
       ORDER BY totale DESC 
       LIMIT 1;
      ");
    $body = str_replace("%toppost%", iri_NewStatPress_Decode($qry[0]->urlrequested), $body);
  }

  # look for %topbrowser%
  if(strpos(strtolower($body),"%topbrowser%") !== FALSE) {
    $qry = $wpdb->get_results(
       "SELECT browser,count(*) AS totale 
        FROM $table_name 
        WHERE 
          spider='' AND 
          feed='' 
        GROUP BY browser 
        ORDER BY totale DESC 
        LIMIT 1;
       ");
    $body = str_replace("%topbrowser%", iri_NewStatPress_Decode($qry[0]->browser), $body);
  }

  # look for %topos%
  if(strpos(strtolower($body),"%topos%") !== FALSE) {
    $qry = $wpdb->get_results(
      "SELECT os,count(*) AS totale 
       FROM $table_name 
       WHERE 
         spider='' AND 
         feed='' 
       GROUP BY os 
       ORDER BY totale DESC
       LIMIT 1;
      ");
    $body = str_replace("%topos%", iri_NewStatPress_Decode($qry[0]->os), $body);
  }

  # look for %topsearch%
  if(strpos(strtolower($body),"%topsearch%") !== FALSE) {
    $qry = $wpdb->get_results(
      "SELECT search, count(*) AS csearch
       FROM $table_name 
       WHERE 
         search<>'' 
       GROUP BY search
       ORDER BY csearch DESC
       LIMIT 1;
      ");
    $body = str_replace("%topsearch%", iri_NewStatPress_Decode($qry[0]->search), $body);
  }
  return $body;
}

/**
 * Get top posts
 *
 * @param limit the number of post to show
 * @param showcounts if checked show totals
 * @return result of extraction
 */
function iri_NewStatPress_TopPosts($limit=5, $showcounts='checked') {
  global $wpdb;
  $res="\n<ul>\n";
  $table_name = $wpdb->prefix . "statpress";
  $qry = $wpdb->get_results(
    "SELECT urlrequested,count(*) as totale 
     FROM $table_name 
     WHERE 
       spider='' AND 
       feed='' AND 
       urlrequested LIKE '%p=%' 
     GROUP BY urlrequested 
     ORDER BY totale DESC LIMIT $limit;
    ");

  foreach ($qry as $rk) {
    $res.="<li><a href='?".$rk->urlrequested."' target='_blank'>".iri_NewStatPress_Decode($rk->urlrequested)."</a></li>\n";
    if(strtolower($showcounts) == 'checked') { $res.=" (".$rk->totale.")"; }
  }
  return "$res</ul>\n";
}


function widget_newstatpress_init($args) {
  if ( !function_exists('wp_register_sidebar_widget') || !function_exists('wp_register_widget_control') ) return;

  // Multifunctional StatPress pluging
  function widget_newstatpress_control() {
    $options = get_option('widget_newstatpress');
    if ( !is_array($options) ) $options = array('title'=>'NewStatPress', 'body'=>'Visits today: %visits%');
    if ( isset($_POST['newstatpress-submit']) && $_POST['newstatpress-submit'] ) {
      $options['title'] = strip_tags(stripslashes($_POST['newstatpress-title']));
      $options['body'] = stripslashes($_POST['newstatpress-body']);
      update_option('widget_newstatpress', $options);
    }
    $title = htmlspecialchars($options['title'], ENT_QUOTES);
    $body = htmlspecialchars($options['body'], ENT_QUOTES);
     // the form
    echo '<p style="text-align:right;"><label for="newstatpress-title">' . __('Title:') . ' <input style="width: 250px;" id="newstatpress-title" name="newstatpress-title" type="text" value="'.$title.'" /></label></p>';
    echo '<p style="text-align:right;"><label for="newstatpress-body"><div>' . __('Body:', 'widgets') . '</div><textarea style="width: 288px;height:100px;" id="newstatpress-body" name="newstatpress-body" type="textarea">'.$body.'</textarea></label></p>';
    echo '<input type="hidden" id="newstatpress-submit" name="newstatpress-submit" value="1" /><div style="font-size:7pt;">%totalvisits% %visits% %thistotalvisits% %os% %browser% %ip% %since% %visitorsonline% %usersonline% %toppost% %topbrowser% %topos%</div>';
  }
  function widget_newstatpress($args) {
    extract($args);
    $options = get_option('widget_newstatpress');
    $title = $options['title'];
    $body = $options['body'];
    echo $before_widget;
    print($before_title . $title . $after_title);
    print iri_NewStatPress_Vars($body);
    echo $after_widget;
  }
  wp_register_sidebar_widget('NewStatPress', 'NewStatPress', 'widget_newstatpress');
  wp_register_widget_control('NewStatPress', array('NewStatPress','widgets'), 'widget_newstatpress_control', 300, 210);

  // Top posts
  function widget_newstatpresstopposts_control() {
    $options = get_option('widget_newstatpresstopposts');
    if ( !is_array($options) ) {
      $options = array('title'=>'NewStatPress TopPosts', 'howmany'=>'5', 'showcounts'=>'checked');
    }
    if ( isset($_POST['newstatpresstopposts-submit']) && $_POST['newstatpresstopposts-submit'] ) {
      $options['title'] = strip_tags(stripslashes($_POST['newstatpresstopposts-title']));
      $options['howmany'] = stripslashes($_POST['newstatpresstopposts-howmany']);
      $options['showcounts'] = stripslashes($_POST['newstatpresstopposts-showcounts']);
      if($options['showcounts'] == "1") {
        $options['showcounts']='checked';  
      }
      update_option('widget_newstatpresstopposts', $options);
    }
    $title = htmlspecialchars($options['title'], ENT_QUOTES);
    $howmany = htmlspecialchars($options['howmany'], ENT_QUOTES);
    $showcounts = htmlspecialchars($options['showcounts'], ENT_QUOTES);
    // the form
    echo '<p style="text-align:right;"><label for="newstatpresstopposts-title">' . __('Title','newstatpress') . ' <input style="width: 250px;" id="newstatpress-title" name="newstatpresstopposts-title" type="text" value="'.$title.'" /></label></p>';
    echo '<p style="text-align:right;"><label for="newstatpresstopposts-howmany">' . __('Limit results to','newstatpress') . ' <input style="width: 100px;" id="newstatpresstopposts-howmany" name="newstatpresstopposts-howmany" type="text" value="'.$howmany.'" /></label></p>';
    echo '<p style="text-align:right;"><label for="newstatpresstopposts-showcounts">' . __('Visits','newstatpress') . ' <input id="newstatpresstopposts-showcounts" name="newstatpresstopposts-showcounts" type=checkbox value="checked" '.$showcounts.' /></label></p>';
    echo '<input type="hidden" id="newstatpress-submitTopPosts" name="newstatpresstopposts-submit" value="1" />';
  }
  function widget_newstatpresstopposts($args) {
    extract($args);
    $options = get_option('widget_newstatpresstopposts');
    $title = htmlspecialchars($options['title'], ENT_QUOTES);
    $howmany = htmlspecialchars($options['howmany'], ENT_QUOTES);
    $showcounts = htmlspecialchars($options['showcounts'], ENT_QUOTES);
    echo $before_widget;
    print($before_title . $title . $after_title);
    print iri_NewStatPress_TopPosts($howmany,$showcounts);
    echo $after_widget;
  }
  wp_register_sidebar_widget('NewStatPress TopPosts', 'NewStatPress TopPosts', 'widget_newstatpresstopposts');
  wp_register_widget_control('NewStatPress TopPosts', array('NewStatPress TopPosts','widgets'), 'widget_newstatpresstopposts_control', 300, 110);
}

/**
 * Replace a content in page with NewStatPress output
 * Used format is: [NewStatPress: type]
 * Type can be:
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
 * 
 * @param content the content of page
 */
function content_newstatpress($content = '') {
  ob_start();
  $TYPEs = array();
  $TYPE = preg_match_all('/\[NewStatPress: (.*)\]/Ui', $content, $TYPEs);

  foreach ($TYPEs[1] as $k => $TYPE) {
    switch ($TYPE) {
      case "Overview":
        $replacement=iriOverview(FALSE);
        break;
      case "Top days":
        $replacement=iriValueTable2("date","Top days", (get_option('newstatpress_el_top_days')=='') ? 5:get_option('newstatpress_el_top_days'), FALSE);
        break;
      case "O.S.":
        $replacement=iriValueTable2("os","O.S.",(get_option('newstatpress_el_os')=='') ? 10:get_option('newstatpress_el_os'),"","","AND feed='' AND spider='' AND os<>''", FALSE);
        break;
      case "Browser":
        $replacement=iriValueTable2("browser","Browser",(get_option('newstatpress_el_browser')=='') ? 10:get_option('newstatpress_el_browser'),"","","AND feed='' AND spider='' AND browser<>''", FALSE);
        break;
      case "Feeds":
        $replacement=iriValueTable2("feed","Feeds",(get_option('newstatpress_el_feed')=='') ? 5:get_option('newstatpress_el_feed'),"","","AND feed<>''", FALSE);
        break;
      case "Search Engine":
        $replacement=iriValueTable2("searchengine","Search engines",(get_option('newstatpress_el_searchengine')=='') ? 10:get_option('newstatpress_el_searchengine'),"","","AND searchengine<>''", FALSE);
        break;
      case "Search terms":
        $replacement=iriValueTable2("search","Top search terms",(get_option('newstatpress_el_search')=='') ? 20:get_option('newstatpress_el_search'),"","","AND search<>''", FALSE);
        break;
      case "Top referrer":
        $replacement= iriValueTable2("referrer","Top referrer",(get_option('newstatpress_el_referrer')=='') ? 10:get_option('newstatpress_el_referrer'),"","","AND referrer<>'' AND referrer NOT LIKE '%".get_bloginfo('url')."%'", FALSE);
        break;
      case "Languages":
        $replacement=iriValueTable2("nation","Countries/Languages",(get_option('newstatpress_el_languages')=='') ? 20:get_option('newstatpress_el_languages'),"","","AND nation<>'' AND spider=''", FALSE);
        break;
      case "Spider":
        $replacement=iriValueTable2("spider","Spiders",(get_option('newstatpress_el_spiders')=='') ? 10:get_option('newstatpress_el_spiders'),"","","AND spider<>''", FALSE);
        break;
      case "Top Pages":
        $replacement=iriValueTable2("urlrequested","Top pages",(get_option('newstatpress_el_pages')=='') ? 5:get_option('newstatpress_el_pages'),"","urlrequested","AND feed='' and spider=''", FALSE);
        break;
      case "Top Days - Unique visitors":
        $replacement=iriValueTable2("date","Top Days - Unique visitors",(get_option('newstatpress_el_visitors')=='') ? 5:get_option('newstatpress_el_visitors'),"distinct","ip","AND feed='' and spider=''", FALSE); 
        break;
      case "Top Days - Pageviews":
        $replacement=iriValueTable2("date","Top Days - Pageviews",(get_option('newstatpress_el_daypages')=='') ? 5:get_option('newstatpress_el_daypages'),"","urlrequested","AND feed='' and spider=''", FALSE); 
        break;
      case "Top IPs - Pageviews":
        $replacement=iriValueTable2("ip","Top IPs - Pageviews",(get_option('newstatpress_el_ippages')=='') ? 5:get_option('newstatpress_el_ippages'),"","urlrequested","AND feed='' and spider=''", FALSE); 
        break;
      default:
        $replacement="";
    }
    $content = str_replace($TYPEs[0][$k], $replacement, $content);
  }
  ob_get_clean();
  return $content;
}


/**
 * Show statistics in dashboard
 */
function iri_dashboard_widget_function() {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";

  # Tabella OVERVIEW
  $unique_color="#114477";
  $web_color="#3377B6";
  $rss_color="#f38f36";
  $spider_color="#83b4d8";
  $lastmonth = iri_NewStatPress_lastmonth();
  $thismonth = gmdate('Ym', current_time('timestamp'));
  $yesterday = gmdate('Ymd', current_time('timestamp')-86400);
  $today = gmdate('Ymd', current_time('timestamp'));
  $tlm[0]=substr($lastmonth,0,4); $tlm[1]=substr($lastmonth,4,2);

  //print "<div class='wrap'><h2>". __('Overview','NewStatPress'). "</h2>";
  print "<table class='widefat'><thead><tr>
  <th scope='col'></th>
  <th scope='col'>". __('Total since','newstatpress'). "<br /><font size=1>";
  print NewStatPress_Print('%since%');
  print "</font></th>
  <th scope='col'>". __('Last month','newstatpress'). "<br /><font size=1>" . gmdate('M, Y',gmmktime(0,0,0,$tlm[1],1,$tlm[0])) ."</font></th>
  <th scope='col'>". __('This month','newstatpress'). "<br /><font size=1>" . gmdate('M, Y', current_time('timestamp')) ."</font></th>
  <th scope='col'>Target ". __('This month','newstatpress'). "<br /><font size=1>" . gmdate('M, Y', current_time('timestamp')) ."</font></th>
  <th scope='col'>". __('Yesterday','newstatpress'). "<br /><font size=1>" . gmdate('d M, Y', current_time('timestamp')-86400) ."</font></th>
  <th scope='col'>". __('Today','newstatpress'). "<br /><font size=1>" . gmdate('d M, Y', current_time('timestamp')) ."</font></th>
  </tr></thead>
  <tbody id='the-list'>";
  ################################################################################################
  # VISITORS ROW
  print "<tr><td><div style='background:$unique_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>". __('Visitors','newstatpress'). "</td>";

  #TOTAL
  $qry_total = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE  
      feed='' AND 
      spider=''
  ");
  print "<td>" . $qry_total->visitors . "</td>\n";

  #LAST MONTH
  $qry_lmonth = $wpdb->get_row("
   SELECT count(DISTINCT ip) AS visitors
   FROM $table_name
   WHERE 
     feed='' AND 
     spider='' AND 
     date LIKE '" . $lastmonth . "%'
  ");
  print "<td>" . $qry_lmonth->visitors . "</td>\n";

  #THIS MONTH
  $qry_tmonth = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date LIKE '" . $thismonth . "%'
  ");
  $qry_tmonth->change = null;
  $qry_tmonth->added = null;
  if($qry_lmonth->visitors <> 0) {
    $pc = round( 100 * ($qry_tmonth->visitors / $qry_lmonth->visitors ) - 100,1);
    if($pc >= 0) $pc = "+" . $pc;
    $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
  }
  print "<td>" . $qry_tmonth->visitors . $qry_tmonth->change . "</td>\n";

  #TARGET
  $qry_tmonth->target = round($qry_tmonth->visitors / date("d", current_time('timestamp')) * 30);
  if($qry_lmonth->visitors <> 0) {
    $pt = round( 100 * ($qry_tmonth->target / $qry_lmonth->visitors ) - 100,1);
    if($pt >= 0) $pt = "+" . $pt;
    $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
  }
  print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";

  #YESTERDAY
  $qry_y = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date = '$yesterday'
  ");
  print "<td>" . $qry_y->visitors . "</td>\n";

  #TODAY
  $qry_t = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date = '$today'
  ");
  print "<td>" . $qry_t->visitors . "</td>\n";
  print "</tr>";

  ################################################################################################
  # PAGEVIEWS ROW
  print "<tr><td><div style='background:$web_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>". __('Pageviews','newstatpress'). "</td>";

  #TOTAL
  $qry_total = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE 
      feed='' AND 
      spider=''
  ");
  print "<td>" . $qry_total->pageview . "</td>\n";

  #LAST MONTH
  $prec=0;
  $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date LIKE '" . $lastmonth . "%'
  ");
  print "<td>".$qry_lmonth->pageview."</td>\n";

  #THIS MONTH
  $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date LIKE '" . $thismonth . "%'
  ");
  $qry_tmonth->change = null;
  $qry_tmonth->added = null;
  if($qry_lmonth->pageview <> 0) {
    $pc = round( 100 * ($qry_tmonth->pageview / $qry_lmonth->pageview ) - 100,1);
    if($pc >= 0) $pc = "+" . $pc;
    $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
  }
  print "<td>" . $qry_tmonth->pageview . $qry_tmonth->change . "</td>\n";

  #TARGET
  $qry_tmonth->target = round($qry_tmonth->pageview / date("d", current_time('timestamp')) * 30);
  if($qry_lmonth->pageview <> 0) {
    $pt = round( 100 * ($qry_tmonth->target / $qry_lmonth->pageview ) - 100,1);
    if($pt >= 0) $pt = "+" . $pt;
      $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
  }
  print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";

  #YESTERDAY
  $qry_y = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date = '$yesterday'
  ");
  print "<td>" . $qry_y->pageview . "</td>\n";

  #TODAY
  $qry_t = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date = '$today'
  ");
  print "<td>" . $qry_t->pageview . "</td>\n";
  print "</tr>";

  ################################################################################################
  # SPIDERS ROW
  print "<tr><td><div style='background:$spider_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>Spiders</td>";
  #TOTAL
  $qry_total = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE 
      feed='' AND 
      spider<>''
  ");
  print "<td>" . $qry_total->spiders . "</td>\n";

  #LAST MONTH
  $prec=0;
  $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE 
      feed='' AND 
      spider<>'' AND 
      date LIKE '" . $lastmonth . "%'
  ");
  print "<td>" . $qry_lmonth->spiders. "</td>\n";

  #THIS MONTH
  $prec=$qry_lmonth->spiders;
  $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE 
      feed='' AND 
      spider<>'' AND 
      date LIKE '" . $thismonth . "%'
  ");
  $qry_tmonth->change = null;
  $qry_tmonth->added = null;
  if($qry_lmonth->spiders <> 0) {
    $pc = round( 100 * ($qry_tmonth->spiders / $qry_lmonth->spiders ) - 100,1);
    if($pc >= 0) $pc = "+" . $pc;
    $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
  }
  print "<td>" . $qry_tmonth->spiders . $qry_tmonth->change . "</td>\n";

  #TARGET
  $qry_tmonth->target = round($qry_tmonth->spiders / date("d", current_time('timestamp')) * 30);
  if($qry_lmonth->spiders <> 0) {
    $pt = round( 100 * ($qry_tmonth->target / $qry_lmonth->spiders ) - 100,1);
    if($pt >= 0) $pt = "+" . $pt;
    $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
  }
  print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";

  #YESTERDAY
  $qry_y = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE 
      feed='' AND 
      spider<>'' AND 
      date = '$yesterday'
  ");
  print "<td>" . $qry_y->spiders . "</td>\n";

  #TODAY
  $qry_t = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE 
      feed='' AND 
      spider<>'' AND 
      date = '$today'
  ");
  print "<td>" . $qry_t->spiders . "</td>\n";
  print "</tr>";

  ################################################################################################
  # FEEDS ROW
  print "<tr><td><div style='background:$rss_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>Feeds</td>";
  #TOTAL
  $qry_total = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE 
      feed<>'' AND 
      spider='' 
  ");
  print "<td>".$qry_total->feeds."</td>\n";

  #LAST MONTH
  $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE 
      feed<>'' AND 
      spider='' AND 
      date LIKE '" . $lastmonth . "%'
  ");
  print "<td>".$qry_lmonth->feeds."</td>\n";

  #THIS MONTH
  $qry_tmonth = $wpdb->get_row("
   SELECT count(date) as feeds
   FROM $table_name
   WHERE 
     feed<>'' AND 
     spider='' AND 
     date LIKE '" . $thismonth . "%'
  ");
  $qry_tmonth->change = null;
  $qry_tmonth->added = null;
  if($qry_lmonth->feeds <> 0) {
    $pc = round( 100 * ($qry_tmonth->feeds / $qry_lmonth->feeds ) - 100,1);
    if($pc >= 0) $pc = "+" . $pc;
    $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
  }
  print "<td>" . $qry_tmonth->feeds . $qry_tmonth->change . "</td>\n";

  #TARGET
  $qry_tmonth->target = round($qry_tmonth->feeds / date("d", current_time('timestamp')) * 30);
  if($qry_lmonth->feeds <> 0) {
    $pt = round( 100 * ($qry_tmonth->target / $qry_lmonth->feeds ) - 100,1);
    if($pt >= 0) $pt = "+" . $pt;
    $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
  }
  print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";

  $qry_y = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE 
      feed<>'' AND 
      spider='' AND 
      date = '".$yesterday."'
  ");
  print "<td>".$qry_y->feeds."</td>\n";

  $qry_t = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE 
      feed<>'' AND 
      spider='' AND 
      date = '$today'
  ");
  print "<td>".$qry_t->feeds."</td>\n";
  print "</tr></table><br />\n\n";
  print '</tr></table>';
  print '</div>';
  # END OF OVERVIEW
  ####################################################################################################

  print "<div class='wrap'><h4><a href='admin.php?page=newstatpress/newstatpress.php'>". __('More details','newstatpress'). " &raquo;</a></h4>";
}

/**
 * Make the overwiew
 *
 * @param print true if to print
 * @return the printing represetnation if print is false
 */
function iriOverview($print = TRUE) {
  global $wpdb;
  $table_name = $wpdb->prefix . "statpress";

  $result="";

  # Tabella OVERVIEW
  $unique_color="#114477";
  $web_color="#3377B6";
  $rss_color="#f38f36";
  $spider_color="#83b4d8";
  $lastmonth = iri_NewStatPress_lastmonth();
  $thismonth = gmdate('Ym', current_time('timestamp'));
  $yesterday = gmdate('Ymd', current_time('timestamp')-86400);
  $today = gmdate('Ymd', current_time('timestamp'));
  $tlm[0]=substr($lastmonth,0,4); $tlm[1]=substr($lastmonth,4,2);

  $result = $result. "<div class='wrap'><h2>". __('Overview','newstatpress'). "</h2>";
  $result = $result. "<table class='widefat'><thead><tr>
         <th scope='col'></th>
         <th scope='col'>". __('Total since','newstatpress'). "<br /><font size=1>";
  $result = $result. NewStatPress_Print('%since%');
  $result = $result. "</font></th>
         <th scope='col'>". __('Last month','newstatpress'). "<br /><font size=1>" . gmdate('M, Y',gmmktime(0,0,0,$tlm[1],1,$tlm[0])) ."</font></th>
         <th scope='col'>". __('This month','newstatpress'). "<br /><font size=1>" . gmdate('M, Y', current_time('timestamp')) ."</font></th>
         <th scope='col'>Target ". __('This month','newstatpress'). "<br /><font size=1>" . gmdate('M, Y', current_time('timestamp')) ."</font></th>
         <th scope='col'>". __('Yesterday','newstatpress'). "<br /><font size=1>" . gmdate('d M, Y', current_time('timestamp')-86400) ."</font></th>
         <th scope='col'>". __('Today','newstatpress'). "<br /><font size=1>" . gmdate('d M, Y', current_time('timestamp')) ."</font></th>
         </tr></thead>
         <tbody id='the-list'>";

  ################################################################################################
  # VISITORS ROW
  $result = $result. "<tr><td><div style='background:$unique_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>". __('Visitors','newstatpress'). "</td>";

  #TOTAL
  $qry_total = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE 
      feed='' AND 
      spider=''
  ");
  $result = $result. "<td>" . $qry_total->visitors . "</td>\n";

  #LAST MONTH
  $qry_lmonth = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE  
      feed='' AND 
      spider='' AND 
      date LIKE '" . $lastmonth . "%'
  ");
  $result = $result. "<td>" . $qry_lmonth->visitors . "</td>\n";

  #THIS MONTH
  $qry_tmonth = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date LIKE '" . $thismonth . "%'
  ");
  $qry_tmonth->change = null;
  $qry_tmonth->added = null;
  if($qry_lmonth->visitors <> 0) {
    $pc = round( 100 * ($qry_tmonth->visitors / $qry_lmonth->visitors ) - 100,1);
    if($pc >= 0) $pc = "+" . $pc;
    $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
  }
  $result = $result. "<td>" . $qry_tmonth->visitors . $qry_tmonth->change . "</td>\n";

  #TARGET
  $qry_tmonth->target = round($qry_tmonth->visitors / date("d", current_time('timestamp')) * 30);
  if($qry_lmonth->visitors <> 0) {
    $pt = round( 100 * ($qry_tmonth->target / $qry_lmonth->visitors ) - 100,1);
    if($pt >= 0) $pt = "+" . $pt;
      $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
    }
  $result = $result. "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";

  #YESTERDAY
  $qry_y = $wpdb->get_row("
     SELECT count(DISTINCT ip) AS visitors
     FROM $table_name
     WHERE 
       feed='' AND 
       spider='' AND 
       date = '$yesterday'
  ");
  $result = $result. "<td>" . $qry_y->visitors . "</td>\n";

  #TODAY
  $qry_t = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date = '$today'
  ");
  $result = $result. "<td>" . $qry_t->visitors . "</td>\n";
  $result = $result. "</tr>";

  ################################################################################################
  # PAGEVIEWS ROW
  $result = $result. "<tr><td><div style='background:$web_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>". __('Pageviews','newstatpress'). "</td>";

  #TOTAL
  $qry_total = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE 
      feed='' AND 
      spider=''
  ");
  $result = $result. "<td>" . $qry_total->pageview . "</td>\n";

  #LAST MONTH
  $prec=0;
  $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date LIKE '" . $lastmonth . "%'
    ");
  $result = $result. "<td>".$qry_lmonth->pageview."</td>\n";

  #THIS MONTH
  $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date LIKE '" . $thismonth . "%'
  ");
  $qry_tmonth->change = null;
  $qry_tmonth->added = null;
  if($qry_lmonth->pageview <> 0) {
    $pc = round( 100 * ($qry_tmonth->pageview / $qry_lmonth->pageview ) - 100,1);
    if($pc >= 0) $pc = "+" . $pc;
    $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
  }
  $result = $result. "<td>" . $qry_tmonth->pageview . $qry_tmonth->change . "</td>\n";

  #TARGET
  $qry_tmonth->target = round($qry_tmonth->pageview / date("d", current_time('timestamp')) * 30);
  if($qry_lmonth->pageview <> 0) {
    $pt = round( 100 * ($qry_tmonth->target / $qry_lmonth->pageview ) - 100,1);
    if($pt >= 0) $pt = "+" . $pt;
    $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
  }
  $result = $result. "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";

  #YESTERDAY
  $qry_y = $wpdb->get_row("
   SELECT count(date) as pageview
   FROM $table_name
   WHERE 
     feed='' AND 
     spider='' AND 
     date = '$yesterday'
   ");
  $result = $result."<td>" . $qry_y->pageview . "</td>\n";

  #TODAY
  $qry_t = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE 
      feed='' AND 
      spider='' AND 
      date = '$today'
    ");
  $result = $result."<td>" . $qry_t->pageview . "</td>\n";
  $result = $result."</tr>";

  ################################################################################################
  # SPIDERS ROW
  $result = $result."<tr><td><div style='background:$spider_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>Spiders</td>";

  #TOTAL
  $qry_total = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE 
      feed='' AND 
      spider<>''
  ");
  $result = $result."<td>" . $qry_total->spiders . "</td>\n";

  #LAST MONTH
  $prec=0;
  $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE 
      feed='' AND 
      spider<>'' AND 
      date LIKE '" . $lastmonth . "%'
  ");
  $result = $result. "<td>" . $qry_lmonth->spiders. "</td>\n";

  #THIS MONTH
  $prec=$qry_lmonth->spiders;
  $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE 
      feed='' AND 
      spider<>'' AND 
      date LIKE '" . $thismonth . "%'
  ");
  $qry_tmonth->change = null;
  $qry_tmonth->added = null;
  if($qry_lmonth->spiders <> 0) {
    $pc = round( 100 * ($qry_tmonth->spiders / $qry_lmonth->spiders ) - 100,1);
    if($pc >= 0) $pc = "+" . $pc;
    $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
  }
  $result = $result. "<td>" . $qry_tmonth->spiders . $qry_tmonth->change . "</td>\n";

  #TARGET
  $qry_tmonth->target = round($qry_tmonth->spiders / date("d", current_time('timestamp')) * 30);
  if($qry_lmonth->spiders <> 0) {
    $pt = round( 100 * ($qry_tmonth->target / $qry_lmonth->spiders ) - 100,1);
    if($pt >= 0) $pt = "+" . $pt;
    $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
  }
  $result = $result. "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";

  #YESTERDAY
  $qry_y = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE 
      feed='' AND 
      spider<>'' AND 
      date = '$yesterday'
  ");
  $result = $result. "<td>" . $qry_y->spiders . "</td>\n";

  #TODAY
  $qry_t = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE 
     feed='' AND 
     spider<>'' AND 
     date = '$today'
  ");
  $result = $result. "<td>" . $qry_t->spiders . "</td>\n";
  $result = $result. "</tr>";

  ################################################################################################
  # FEEDS ROW
  $result = $result. "<tr><td><div style='background:$rss_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>Feeds</td>";
  #TOTAL
  $qry_total = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE 
      feed<>'' AND spider=''
    ");
  $result = $result. "<td>".$qry_total->feeds."</td>\n";

  #LAST MONTH
  $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE 
      feed<>'' AND 
      spider='' AND 
      date LIKE '" . $lastmonth . "%'
  ");
  $result = $result. "<td>".$qry_lmonth->feeds."</td>\n";

  #THIS MONTH
  $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE 
      feed<>'' AND 
      spider='' AND 
      date LIKE '" . $thismonth . "%'
  ");
  $qry_tmonth->change = null;
  $qry_tmonth->added = null;
  if($qry_lmonth->feeds <> 0) {
    $pc = round( 100 * ($qry_tmonth->feeds / $qry_lmonth->feeds ) - 100,1);
    if($pc >= 0) $pc = "+" . $pc;
    $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
  }
  $result = $result. "<td>" . $qry_tmonth->feeds . $qry_tmonth->change . "</td>\n";

  #TARGET
  $qry_tmonth->target = round($qry_tmonth->feeds / date("d", current_time('timestamp')) * 30);
  if($qry_lmonth->feeds <> 0) {
    $pt = round( 100 * ($qry_tmonth->target / $qry_lmonth->feeds ) - 100,1);
    if($pt >= 0) $pt = "+" . $pt;
    $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
  }
  $result = $result. "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";

  $qry_y = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE 
      feed<>'' AND 
      spider='' AND 
      date = '".$yesterday."'
  ");
  $result = $result. "<td>".$qry_y->feeds."</td>\n";

  $qry_t = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE 
      feed<>'' AND 
      spider='' AND 
      date = '$today'
  ");
  $result = $result. "<td>".$qry_t->feeds."</td>\n";

  $result = $result. "</tr></table><br />\n\n";
    
  ################################################################################################
  ################################################################################################
  # THE GRAPHS

  # last "N" days graph  NEW
  $gdays=get_option('newstatpress_daysinoverviewgraph'); if($gdays == 0) { $gdays=20; }
  $start_of_week = get_option('start_of_week');
  $result = $result. '<table width="100%" border="0"><tr>';
  $qry = $wpdb->get_row("
    SELECT count(date) as pageview, date
    FROM $table_name
    GROUP BY date HAVING date >= '".gmdate('Ymd', current_time('timestamp')-86400*$gdays)."'
    ORDER BY pageview DESC
    LIMIT 1
  ");
  if ($qry != null) $maxxday=$qry->pageview;
  if($maxxday == 0) { $maxxday = 1; }
  # Y
  $gd=(90/$gdays).'%';
  for($gg=$gdays-1;$gg>=0;$gg--) {
    #TOTAL VISITORS
    $qry_visitors = $wpdb->get_row("
      SELECT count(DISTINCT ip) AS total
      FROM $table_name
      WHERE 
        feed='' AND 
        spider='' AND 
        date = '".gmdate('Ymd', current_time('timestamp')-86400*$gg)."'
    ");
    $px_visitors = round($qry_visitors->total*100/$maxxday);

    #TOTAL PAGEVIEWS (we do not delete the uniques, this is falsing the info.. uniques are not different visitors!)
    $qry_pageviews = $wpdb->get_row("
      SELECT count(date) as total
      FROM $table_name
      WHERE 
        feed='' AND 
        spider='' AND 
        date = '".gmdate('Ymd', current_time('timestamp')-86400*$gg)."'
    ");
    $px_pageviews = round($qry_pageviews->total*100/$maxxday);

    #TOTAL SPIDERS
    $qry_spiders = $wpdb->get_row("
      SELECT count(ip) AS total
      FROM $table_name
      WHERE 
        feed='' AND 
        spider<>'' AND 
        date = '".gmdate('Ymd', current_time('timestamp')-86400*$gg)."'
      ");
    $px_spiders = round($qry_spiders->total*100/$maxxday);

    #TOTAL FEEDS
    $qry_feeds = $wpdb->get_row("
      SELECT count(ip) AS total
      FROM $table_name
      WHERE 
        feed<>'' AND 
        spider='' AND 
        date = '".gmdate('Ymd', current_time('timestamp')-86400*$gg)."'
    ");
    $px_feeds = round($qry_feeds->total*100/$maxxday);

    $px_white = 100 - $px_feeds - $px_spiders - $px_pageviews - $px_visitors;

    $result = $result.'<td width="'.$gd.'" valign="bottom"';
    if($start_of_week == gmdate('w',current_time('timestamp')-86400*$gg)) { 
      $result = $result.' style="border-left:2px dotted gray;"'; 
    }  # week-cut
    $result = $result. "><div style='float:left;height: 100%;width:100%;font-family:Helvetica;font-size:7pt;text-align:center;border-right:1px solid white;color:black;'>
       <div style='background:#ffffff;width:100%;height:".$px_white."px;'></div>
       <div style='background:$unique_color;width:100%;height:".$px_visitors."px;' title='".$qry_visitors->total." visitors'></div>
       <div style='background:$web_color;width:100%;height:".$px_pageviews."px;' title='".$qry_pageviews->total." pageviews'></div>
       <div style='background:$spider_color;width:100%;height:".$px_spiders."px;' title='".$qry_spiders->total." spiders'></div>
       <div style='background:$rss_color;width:100%;height:".$px_feeds."px;' title='".$qry_feeds->total." feeds'></div>
       <div style='background:gray;width:100%;height:1px;'></div>
       <br />".gmdate('d', current_time('timestamp')-86400*$gg) . ' ' . gmdate('M', current_time('timestamp')-86400*$gg) . "</div></td>\n";
  }
  $result = $result.'</tr></table>';

  $result = $result.'</div>';
  # END OF OVERVIEW
  ####################################################################################################

  if ($print) print $result;
  else return $result;
}

// Create the function use in the action hook

/**
 * Add the dashboard widget if option for that is on
 */
function iri_add_dashboard_widgets() {
  global $wp_meta_boxes;

  if (get_option('newstatpress_dashboard')=='checked') {
    wp_add_dashboard_widget('iri_dashboard_widget', 'NewStatPress Overview', 'iri_dashboard_widget_function');	
  } else unset($wp_meta_boxes['dashboard']['side']['core']['wp_dashboard_setup']);
} 

/**
 * Set the header for the page.
 * It loads google api
 */
function iri_page_header() {
  ##  echo "<style id='NewStatPress' type='text/css'>\n";
  ##  echo stripslashes(file_get_contents(ABSPATH.'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/css/newstatpress.css'));
  ##  echo "</style>\n";
  #echo "<script type=\'text/javascript\' src=\'https://www.google.com/jsapi\'></script>"
  echo '<script type="text/javascript" src="http://www.google.com/jsapi"></script>';
  echo '<script type="text/javascript">';
  echo 'google.load(\'visualization\', \'1\', {packages: [\'geochart\']});';
  echo '</script>';
}

load_plugin_textdomain('newstatpress', 'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/locale', '/'.dirname(plugin_basename(__FILE__)).'/locale');

add_action('admin_menu', 'iri_add_pages');
add_action('plugins_loaded', 'widget_newstatpress_init');
add_action('send_headers', 'iriStatAppend');  //add_action('wp_head', 'iriStatAppend');
add_action('init','iri_checkExport');
###add_action('wp_head', 'iri_page_header');

// Hoook into the 'wp_dashboard_setup' action to register our other functions
add_action('wp_dashboard_setup', 'iri_add_dashboard_widgets' );

add_filter('the_content', 'content_newstatpress');

register_activation_hook(__FILE__,'iri_NewStatPress_CreateTable');

?>