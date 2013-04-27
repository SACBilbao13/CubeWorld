<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Past missions template
 *
   Template Name:  Past missions
 *
 * @file           pastmissions.php
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
					Here you can get more information about past launches of the Cubesat project, the information
					is updated in real-time.
					<?php
						include('simple_html_dom.php');
						$html = file_get_html('http://www.cubesat.org/index.php/missions/past-launches');

						echo '<ul id="past-missions">';
						$i = 1;

						foreach($html->find('tr[class=sectiontableentry1] a, tr[class=sectiontableentry2] a') as $e)
						{
							echo '<li>';

							echo '<a href="'.str_replace('/index.php', 'http://www.cubesat.org/index.php', $e->href).'" title="'.trim($e->innertext).'">'.trim($e->innertext).'</a>';

							echo '</li>';
						}
						echo '</ul>';
						echo '<a href="http://www.cubesat.org/index.php/missions/past-launches" target="_blank" title="More">More</a>';
					?>
					<script type="text/javascript" charset="UTF-8" src="<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/js/past-missions.js"></script>
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