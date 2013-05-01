<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Past missions template
 *
   Template Name:  Trajectory Designer
 *
 * @file           trajectories.php
 * @author         Iban Eguia
 * @copyright      2013 - NASA
 */

function calculate_mag($min_diam)
{
	return log10($min_diam*sqrt(0.05)/1329000)/(-0.2);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$neos		= ($_POST['asteroids'] === 'on' OR $_POST['comets'] === 'on') ? 'NEOs=on&' : '';
	$neas		= $_POST['asteroids'] === 'on' ? 'NEAs=on&' : '';
	$necs		= $_POST['comets'] === 'on' ? 'NECs=on&' : '';
	$maxMag		= 'maxMag='.calculate_mag((int) $_POST['min-diam']).'&';
	$maxOCC		= 'maxOCC='.$_POST['orbit-uncert'].'&';
	$tgtList	= ! empty($_POST['extra-list']) ? 'target_list='.$_POST['extra-list'].'&' : '';
	$mClass		= 'mission_class='.$_POST['mis-type-way'].'&';
	$mEncount	= 'mission_type='.$_POST['mis-type-enc'].'&';
	$ld1		= 'LD1='.$_POST['launch-year-fr'].'&';
	$ld2		= 'LD2='.$_POST['launch-year-to'].'&';
	$maxDT		= 'maxDT='.$_POST['max-duration'].'&';
	$timeU		= 'DTunit='.$_POST['DTunit'].'&';
	$deltaV		= 'maxDV='.$_POST['dv'].'&';
	$minimize	= 'min='.$_POST['minimize'].'&';
	$window		= $_POST['all-traj'] === 'on' ? 'wdw_width=-1' : 'wdw_width=0';

	$url		= 'http://trajbrowser.arc.nasa.gov/traj_browser.php?'.
					$neos.$neas.$necs.'chk_maxMag=on&'.$maxMag.'chk_maxOCC=on&'.$maxOCC.'chk_target_list=on&'.$tgtList.
					$mClass.$mEncount.$ld1.$ld2.$maxDT.$timeU.$deltaV.$minimize.$window.'&submit=Search';

	require('includes/simple_html_dom.php');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	$scrap = str_get_html(curl_exec($ch));
	curl_close($ch);

	$script = $scrap->find('body', 0)->find('script', 0);
}

get_header(); ?>

<?php if($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
<script src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/js/traj_lib.js"></script>
<script src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/js/traj_browser.js"></script>
<script src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/js/traj_viewer.js"></script>
<script src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/js/sorttable.js"></script>
<?php endif; ?>

<div id="content-full" class="grid col-940">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

		<?php get_template_part( 'loop-header' ); ?>

			<?php responsive_entry_before(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php responsive_entry_top(); ?>

				<?php get_template_part( 'post-meta-page' ); ?>

				<div class="post-entry">
					<a href="#" id="toggle-form"><?php echo (($_SERVER['REQUEST_METHOD'] === 'POST') ? 'Show from ▼' : 'Hide form ▲'); ?></a>
					<section id="traj-form"<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') echo 'style="display:none"'; ?>>
						<form id="trajectory-form" action="<?php echo site_url('trajectory-designer'); ?>" accept-encoding="UTF-8" method="POST">
						<div class="form-left">
							<label>Objects to include:</label>
							<label for="asteroids" class="inline">Asteroids:</label>
							<input type="checkbox" name="asteroids" id="asteroids" checked>

							<label for="comets" class="inline">Comets:</label>
							<input type="checkbox" name="comets" id="comets">

							<label for="extra-list">Insert extra objects you would like to browse. One per line:</label>
							<textarea name="extra-list" id="extra-list"></textarea>

							<label for="min-diam">Minimum asteroid diameter (in meters):</label>
							<input type="number" name="min-diam" id="min-diam" min="0" value="50" required>

							<label for="orbit-uncert">Orbit uncertainty (0-9):</label>
							<input type="number" name="orbit-uncert" id="orbit-uncert" min="0" max="9" value="4" required>

							<label>Mission type:</label>
							<span id="mis-type-way">
								<input type="radio" name="mis-type-way" id="mis-type-way-one" value="oneway" checked><label for="mis-type-way-one" class="inline">One-way</label>
								<input type="radio" name="mis-type-way" id="mis-type-way-round" value="roundtrip"><label for="mis-type-way-round" class="inline">Round-trip</label>
							</span><span id="mis-type-enc">
								<input type="radio" name="mis-type-enc" id="mis-type-enc-fly" value="flyby" checked><label for="mis-type-enc-fly" class="inline">Flyby</label>
								<input type="radio" name="mis-type-enc" id="mis-type-enc-rend" value="rendezvous"><label for="mis-type-enc-rend" class="inline">Rendezvous</label>
							</span>
						</div>
						<div class="form-right">
							<label for="launch-year-fr">Launch year:</label>
							<input name="launch-year-fr" id="launch-year-fr" type="number" min="<?php echo date("Y"); ?>" max="2040" value="<?php echo date("Y")+2; ?>" required>
							to<input name="launch-year-to" type="number" min="<?php echo date("Y"); ?>" max="2040" value="<?php echo date("Y")+5; ?>" required>

							<label for="max-duration">Maximum duration:</label>
							<input name="max-duration" id="max-duration" type="number" min="0.04" max="21" step="0.01" value="2.0" required>
							<select name="DTunit" required>
								<option value="yrs" selected>Years</option>
								<option value="days">Days</option>
							</select>

							<label for="dv">Maximum ΔV:</label>
							<input name="dv" id="dv" type="number" min="3.15" max="20" step="0.01" value="5.0" required> km/s

							<label>Minimize:</label>
							<input type="radio" name="minimize" id="minimize-dv" value="DV" checked><label for="minimize-dv" class="inline">ΔV</label>
							<input type="radio" name="minimize" id="minimize-dur" value="DT"><label for="minimize-dur" class="inline">Duration</label>

							<br>
							<label for="all-traj" class="inline">Return all trajectories:</label>
							<input type="checkbox" name="all-traj" id="all-traj">
						</div>
							<input id="submit" type="submit" value="Search">
					</form></section>
					<script type="text/javascript" charset="UTF-8" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/js/trajectory-sim.js"></script>
					<?php if (($_SERVER['REQUEST_METHOD'] === 'POST')): ?>
					<section id="traj-table">
						<label for="link-br">NASA's Trajectory Browser's link:</label><input type="text" value="<?php echo $url; ?>" disabled>

						<canvas id="results_graph" width="800" height="400">Your browser or your browser's settings are not supported. HTML5/Canvas is required, please download the latest version of your browser: IE9, Firefox 3.6+, Safari 3.2+, Chrome 11+, Opera 10.6+</canvas>

						<b>Tabulated Results</b><button type="button" onclick="Table_exportCSV()">Export to CSV</button>
						<br>
						<a href="javascript:Table_set_checkboxes_visibility(1)">
							<img src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_closed.png" style="border: none; display: none;" onclick="" id="img_cols_closed">
						</a>
						<a href="javascript:Table_set_checkboxes_visibility(0)">
							<img src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_opened.png" style="border: none;" id="img_cols_opened">
						</a>

						<b>Column heading descriptions</b><br>
						<table id="tbl_cols" style="">
						<tbody>
						<tr><td colspan="2" style="text-align:center;">
						<button type="button" onclick="Table_check_all_cols(1)">Check all</button><button type="button" onclick="Table_check_all_cols(0)">Uncheck all</button></td></tr>

						<tr id="tr_SPK_ID"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;SPK_ID&quot;)" id="chk_SPK_ID" checked><label for="chk_SPK_ID">SPK-ID</label></td><td>Unique object identifier</td></tr>
						<tr id="tr_Name"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;Name&quot;)" id="chk_Name" checked><label for="chk_Name">Name</label></td><td>Full name of object</td></tr>
						<tr id="tr_Mag"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;Mag&quot;)" id="chk_Mag" checked><label for="chk_Mag">Absolute Mag</label></td><td>H magnitude for asteroids, M2 nuclear magnitude for comets <a target="_blank" href="<?php echo home_url('user-guide/#mag'); ?>">[more]</a></td></tr>
						<tr id="tr_Size"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;Size&quot;)" id="chk_Size" checked><label for="chk_Size">Size</label></td><td>Known or estimated range of diameters <a target="_blank" href="<?php echo home_url('user-guide/#mag'); ?>">[more]</a></td></tr>
						<tr id="tr_OCC"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;OCC&quot;)" id="chk_OCC" checked><label for="chk_OCC">Orbit Condition Code</label></td><td>Orbit knowledge uncertainty (0-9) <a target="_blank" href="<?php echo home_url('user-guide/#orbitcode'); ?>">[more]</a></td></tr>
						<tr id="tr_EarthDep"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;EarthDep&quot;)" id="chk_EarthDep" checked><label for="chk_EarthDep">Earth Departure</label></td><td>Departure date from Earth</td></tr>
						<tr id="tr_DestArr"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;DestArr&quot;)" id="chk_DestArr" checked><label for="chk_DestArr">Destination Arrival</label></td><td>Arrival date at destination</td></tr>
						<tr id="tr_DestFlyby" style="display: none;"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;DestFlyby&quot;)" id="chk_DestFlyby" checked><label for="chk_DestFlyby">Destination Flyby</label></td><td>Flyby date of object</td></tr>
						<tr id="tr_DestDep" style="display: none;"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;DestDep&quot;)" id="chk_DestDep" checked><label for="chk_DestDep">Destination Depature</label></td><td>Departure date from destination</td></tr>
						<tr id="tr_EarthArr" style="display: none;"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;EarthArr&quot;)" id="chk_EarthArr" checked><label for="chk_EarthArr">Earth Arrival</label></td><td>Return date to Earth</td></tr>
						<tr id="tr_DTdest" style="display: none;"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;DTdest&quot;)" id="chk_DTdest" checked><label for="chk_DTdest">Stay time</label></td><td>Time spent at destination</td></tr>
						<tr id="tr_DT"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;DT&quot;)" id="chk_DT" checked><label for="chk_DT">Duration</label></td><td>Total mission duration</td></tr>
						<tr id="tr_C3"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;C3&quot;)" id="chk_C3" checked><label for="chk_C3">Injection C3</label></td><td>Energy required for Earth departure <a target="_blank" href="<?php echo home_url('user-guide/#patched_launching'); ?>">[more]</a></td></tr>
						<tr id="tr_DLA"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;DLA&quot;)" id="chk_DLA" checked><label for="chk_DLA">DLA</label></td><td>Declination of the launching asymptote <a target="_blank" href="<?php echo home_url('user-guide/#patched_launching'); ?>">[more]</a></td></tr>
						<tr id="tr_DVi"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;DVi&quot;)" id="chk_DVi" checked><label for="chk_DVi">Injection ΔV</label></td><td>Change in velocity required for Earth departure from 200 km LEO <a target="_blank" href="<?php echo home_url('user-guide/#deltav'); ?>">[more]</a></td></tr>
						<tr id="tr_DVpi"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;DVpi&quot;)" id="chk_DVpi" checked><label for="chk_DVpi">Post-injection ΔV</label></td><td>Change in velocity required for all maneuvers after Earth departure <a target="_blank" href="<?php echo home_url('user-guide/#deltav'); ?>">[more]</a></td></tr>
						<tr id="tr_DV"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;DV&quot;)" id="chk_DV" checked><label for="chk_DV">Total ΔV</label></td><td>Total change in velocity required <a target="_blank" href="<?php echo home_url('user-guide/#deltav'); ?>">[more]</a></td></tr>
						<tr id="tr_Vflyby" style="display: none;"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;Vflyby&quot;)" id="chk_Vflyby" checked><label for="chk_Vflyby">Flyby speed</label></td><td>Relative speed of spacecraft and object at closest approach</td></tr>
						<tr id="tr_Vreentry" style="display: none;"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;Vreentry&quot;)" id="chk_Vreentry" checked><label for="chk_Vreentry">Earth reentry speed</label></td><td>Relative speed of spacecraft to Earth at reentry</td></tr>
						<tr id="tr_Route"><td style="padding-right:15px;"><input type="checkbox" onclick="Table_checkbox_toggle(&quot;Route&quot;)" id="chk_Route" checked><label for="chk_Route">Route</label></td><td>Trajectory itinerary <a target="_blank" href="<?php echo home_url('user-guide/#itineraries'); ?>">[more]</a></td></tr>

						<tr><td colspan="2">
						<br>See the <a target="_blank" href="<?php echo home_url('user-guide'); ?>">user guide</a> for more documentation.
						<br></td></tr>
						</tbody></table>

						<table id="results_table">
							<tr>
								<th scope="col" id='th_Viewer'></th>
								<th scope="col" id='th_SPK_ID'>SPK ID</th>
								<th scope="col" id='th_Name'>Name</th>
								<th scope="col" id='th_Mag'>Abs<br>Mag</th>
								<th scope="col" id='th_Size'>Size</th>
								<th scope="col" id='th_OCC'>Orbit<br>condition<br>code</th>
								<th scope="col" id='th_EarthDep'>Earth<br>Departure</th>
								<th scope="col" id='th_DestArr'>Destination<br>Arrival</th>
								<th scope="col" id='th_DestFlyby'>Destination<br>Flyby</th>
								<th scope="col" id='th_DestDep'>Destination<br>Departure</th>
								<th scope="col" id='th_EarthArr'>Earth<br>Return</th>
								<th scope="col" id='th_DTdest'>Stay time</th>
								<th scope="col" id='th_DT'>Duration</th>
								<th scope="col" id='th_C3'>Injection<br>C3<br>(km<sup>2</sup>/s<sup>2</sup>)</th>
								<th scope="col" id='th_DLA'>Abs<br>DLA</th>
								<th scope="col" id='th_DVi'>Injection<br>&Delta;V<br>(km/s)</th>
								<th scope="col" id='th_DVpi'>Post-<br>Injection<br>&Delta;V (km/s)</th>
								<th scope="col" id='th_DV'>Total<br>&Delta;V<br>(km/s)</th>
								<th scope="col" id='th_Vflyby'>Flyby<br>speed<br>(km/s)</th>
								<th scope="col" id='th_Vreentry'>Reentry<br>speed<br>(km/s)</th>
								<th scope="col" id='th_Route'>Route</th>
							</tr>
						</table>

						<?php echo str_replace('window.location.href = \'#a_load_results\';', 'window.location.href = \'#results_graph\';', $script); ?>
						</div>

						<div id="trajdiv">
							<table id="trajtable">
							<tr>
								<td style="text-align:center;" id="tv_title">Trajectory Viewer</td>
								<td style="text-align:right;"><a href="javascript:TrajViewer_close()"><img style="border:none;" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_close.png" title="Close"></a></td>
							</tr>
							<tr>
								<td style="text-align:center;" width="500">

									<canvas id="tv_canvas" width="500" height="450" style="cursor:move;">
									Your browser or your browser's settings are not supported. HTML5/Canvas is required, please download a compatible browser: IE9, Firefox 3.6+, Safari 3.2+, Chrome 11+, Opera 10.6+
									</canvas>

									<div>
										<a href="javascript:animate_start()"><img class="icon_animate" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_animate_start.png" title="Rewind"></a>
										<a href="javascript:animate_step(-1)"><img class="icon_animate" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_animate_stepback.png" title="Step back"></a>
										<a href="javascript:animate_play()" id="btn_play" ><img class="icon_animate" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_animate_play.png" title="Play"></a>
										<a href="javascript:animate_pause()" id="btn_pause"><img class="icon_animate" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_animate_pause.png" title="Pause"></a>
										<a href="javascript:animate_step(+1)"><img class="icon_animate" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_animate_stepforward.png" title="Step forward"></a>
										<a href="javascript:animate_end()"><img class="icon_animate" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_animate_end.png" title="Fast forward"></a>
										<a href="javascript:animate_stepsize(-1)"><img class="icon_animate" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_animate_smallertimestep.png" title="Decrease time step"></a>
										<a href="javascript:animate_stepsize(+1)"><img class="icon_animate" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_animate_largertimestep.png" title="Increase time step"></a>
									</div>

								</td>

								<td style="text-align:left; padding:5px; width:100%; vertical-align:top;">

									<div style="float:right;">
										<a href="javascript:TrajViewer_next(-1)" id="tv_prevtraj"><img style="border:none;" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_up.png" title="Next trajectory"></a><br>
										<a href="javascript:TrajViewer_next(+1)" id="tv_nexttraj"><img style="border:none;" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/icon_down.png" title="Previous trajectory"></a>
									</div>

									<b id="tv_dest_name">Destination</b>
									&nbsp;&nbsp;&nbsp;&nbsp;<a id="tv_sbdb_link" style="font-size:11px;" href="" target='_blank'></a>
									<table class="tv_stats_table" id="tv_dest_tbl">
									</table>

									<b>Trajectory Itinerary</b>
									<table class="tv_stats_table" id="tv_itin">
										<tr> <th></th> <th>Date</th> <th>&Delta;V</th> </tr>
									</table>

									<table class="tv_stats_table" id="tv_traj_tbl">
									</table>
								</td>
							</tr>
							</table>

							<script type="text/javascript">
								TrajViewer_close();
								Table_set_checkboxes_visibility(0);

								if (jQuery(document).width() < 975)
								{
									jQuery('#results_graph').hide();
									jQuery('#chk_Mag').removeAttr('checked');
									Table_checkbox_toggle('Mag');
									jQuery('#chk_OCC').removeAttr('checked');
									Table_checkbox_toggle('OCC');
									jQuery('#chk_C3').removeAttr('checked');
									Table_checkbox_toggle('C3');
									jQuery('#chk_DLA').removeAttr('checked');
									Table_checkbox_toggle('DLA');
									jQuery('#chk_DVi').removeAttr('checked');
									Table_checkbox_toggle('DVi');
									jQuery('#chk_DVpi').removeAttr('checked');
									Table_checkbox_toggle('DVpi');
								}

								jQuery(window).resize(function()
								{
									if (jQuery(document).width() < 975)
									{
										jQuery('#results_graph').hide();
										jQuery('#chk_Mag').removeAttr('checked');
										Table_checkbox_toggle('Mag');
										jQuery('#chk_OCC').removeAttr('checked');
										Table_checkbox_toggle('OCC');
										jQuery('#chk_C3').removeAttr('checked');
										Table_checkbox_toggle('C3');
										jQuery('#chk_DLA').removeAttr('checked');
										Table_checkbox_toggle('DLA');
										jQuery('#chk_DVi').removeAttr('checked');
										Table_checkbox_toggle('DVi');
										jQuery('#chk_DVpi').removeAttr('checked');
										Table_checkbox_toggle('DVpi');
									}
									else
									{
										jQuery('#results_graph').show();
										jQuery('#chk_Mag').attr('checked', 'checked');
										Table_checkbox_toggle('Mag');
										jQuery('#chk_OCC').attr('checked', 'checked');
										Table_checkbox_toggle('OCC');
										jQuery('#chk_C3').attr('checked', 'checked');
										Table_checkbox_toggle('C3');
										jQuery('#chk_DLA').attr('checked', 'checked');
										Table_checkbox_toggle('DLA');
										jQuery('#chk_DVi').attr('checked', 'checked');
										Table_checkbox_toggle('DVi');
										jQuery('#chk_DVpi').attr('checked', 'checked');
										Table_checkbox_toggle('DVpi');
									}
								});
							</script>
					</section>
					<?php endif; ?>
				</div><!-- end of .post-entry -->

				<?php get_template_part( 'post-data' ); ?>

				<?php responsive_entry_bottom(); ?>
			</div><!-- end of #post-<?php the_ID(); ?> -->
			<?php responsive_entry_after(); ?>

			<?php responsive_comments_before(); ?>
			<?php comments_template( '', true ); ?>
			<?php responsive_comments_after(); ?>

		<?php
		endwhile;

		get_template_part( 'loop-nav' );

	else :

		get_template_part( 'loop-no-posts' );

	endif;
	?>

</div><!-- end of #content-full -->

<?php get_footer(); ?>