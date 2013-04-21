<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Past missions template
 *
   Template Name:  Cube
 *
 * @file           cube.php
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
					<script type="text/javascript" charset="UTF-8" src="<?php bloginfo('siteurl'); ?>/wp-includes/js/games/library.js"></script>
					<script type="application/processing">
						PImage bg;
						PImage cubeSat;
						PImage plat;
						float posX;
						float posY;
						float vy=1;
						float vx =3;
						float myVy=0.05;
						boolean finPartida=false;
						boolean tecla = true;
						boolean youLose= false;
						float fuel = 50;


						void setup(){
						cubeSat = loadImage("<?php bloginfo('siteurl'); ?>/wp-includes/images/games/cube/cube.png");
						bg = loadImage("<?php bloginfo('siteurl'); ?>/wp-includes/images/games/cube/space.jpg");
						plat = loadImage("<?php bloginfo('siteurl'); ?>/wp-includes/images/games/cube/mars.png");
						posX = 320;
						posY = 0;
						size(640,480);
						float imageH=43;
						float imageW=640;

						}
						/*
						void fuel(){

						rect rectangle = (80, 80, 40, 40);

							fill(12,13,14);
						}
						*/


						void draw(){

						if(keyPressed!='w')
						println("ahbsgo");

						if((key!='w'&&!finPartida&&tecla)||fuel<=0||!keyPressed){
							vy=vy+0.01;
							posY=posY+vy;
							tecla=true;
						}
						else if(key=='w'){
							fuel--;
						tecla=false;

							if(fuel!=0){
							posY=posY+vy;
								vy=vy-0.08;
							}
								//println(vy)

						tecla=true;
						}
						else if(key=='a'){
						tecla=false;
							//	println(posX);
								//posY = posY+vy;
								posX=posX-vx;
						tecla=true;
							}
							else if(key=='d'){
								tecla=false;
							//	posY = posY+vy;
								posX=posX+vx;
								telca=true;
							}
						image(bg,0,0);
						image(plat,0,437);
						text("fuel: "+fuel, 10, 10);
						text("vy: "+vy, 10, 40);
						if((posY+cubeSat.height)>437&&vy<1&&!youLose){
								finPartida=true;
								textSize(32);
								text("You have landed \nyour cubeSat gratefully", width/6, height/2);
								vy=0;
								noLoop();
						}else if((posY+cubeSat.height)>437&&vy>1){
								finPartida=true;
								youLose=true;
								textSize(32);
								text("Your cubeSat is broken!!! ;( ", width/4, height/2);
								vy = 0;
								vx = 0;
								noLoop();
						}
						image(cubeSat,posX,posY);
						// imagen de plataforma de aterrizaje para la nave
						// cuando entre en las coordenadas de la plataforma, y si tiene una velocidad X, ha aterrizado con exito, sino, la ¿investigación?
						// habría sido un fracaso
						}

					</script>
					<canvas style="outline: none;" width="600" height="600"></canvas>
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