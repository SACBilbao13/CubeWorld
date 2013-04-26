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

get_header(); ?>

<div id="content-full" class="grid col-940">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

		<?php get_template_part( 'loop-header' ); ?>

			<?php responsive_entry_before(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php responsive_entry_top(); ?>

				<?php get_template_part( 'post-meta-page' ); ?>

				<div class="post-entry">
					<a href="#" id="toggle-form">Hide form</a>
					<section id="traj-form"><form id="trajectory-form" action="<?php bloginfo('siteurl'); ?>/wp-admin/trajectory-sim.php" accept-encoding="UTF-8" method="POST">
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
								<input type="radio" name="mis-type-way" id="mis-type-way-one" value="0" checked><label for="mis-type-way-one" class="inline">One-way</label>
								<input type="radio" name="mis-type-way" id="mis-type-way-round" value="1"><label for="mis-type-way-round" class="inline">Round-trip</label>
							</span><span id="mis-type-enc">
								<input type="radio" name="mis-type-enc" id="mis-type-enc-fly" value="0" checked><label for="mis-type-enc-fly" class="inline">Flyby</label>
								<input type="radio" name="mis-type-enc" id="mis-type-enc-rend" value="1"><label for="mis-type-enc-rend" class="inline">Rendezvous</label>
							</span>
						</div>
						<div class="form-right">
							<label for="launch-year-fr">Launch year:</label>
							<input name="launch-year-fr" id="launch-year-fr" type="number" min="<?php echo date("Y"); ?>" max="2040" value="<?php echo date("Y")+2; ?>" required>
							to<input name="launch-year-to" type="number" min="<?php echo date("Y"); ?>" max="2040" value="<?php echo date("Y")+5; ?>" required>

							<label for="max-duration">Max duration:</label>
							<input name="max-duration" id="max-duration" type="number" min="0.04" max="21" step="0.01" value="2.0" required>

							<label for="dv">Max ΔV:</label>
							<input name="dv" id="dv" type="number" min="3.15" max="20" step="0.01" value="5.0" required> km/s

							<label>Minimize:</label>
							<input type="radio" name="minimize" id="minimize-dv" value="0" checked><label for="minimize-dv" class="inline">ΔV</label>
							<input type="radio" name="minimize" id="minimize-dur" value="1"><label for="minimize-dur" class="inline">Duration</label>

							<br>
							<label for="all-traj" class="inline">Return all trajectories:</label>
							<input type="checkbox" name="all-traj" id="all-traj">
						</div>
							<input type="submit" value="Search">
					</form></section>

					<?php
						include(ABSPATH.'wp-includes/simple_html_dom.php');
						$html = file_get_html('http://www.cubesat.org/index.php/missions/past-launches');

					//	echo '<ul id="past-missions">';
						$i = 1;

						foreach($html->find('tr[class=sectiontableentry1] a, tr[class=sectiontableentry2] a') as $e)
						{
						//	echo '<li>';

						//	echo '<a href="'.str_replace('/index.php', 'http://www.cubesat.org/index.php', $e->href).'" title="'.trim($e->innertext).'">'.trim($e->innertext).'</a>';

						//	echo '</li>';
						}
					//	echo '</ul>';
					//	echo '<a href="http://www.cubesat.org/index.php/missions/past-launches" target="_blank" title="More">More</a>';
					?>
					<script type="text/javascript" charset="UTF-8" src="<?php bloginfo('siteurl'); ?>/wp-includes/js/past-missions.js"></script>
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