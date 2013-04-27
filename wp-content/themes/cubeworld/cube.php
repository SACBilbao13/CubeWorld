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

				<div class="post-entry" style="text-align:center">
					<div align="left">
					<h3>Instructions</h3>
					In this game you can take the control of one Cubesat in landing operation. You should not let it fall very
					quickly or the Cubesat will break by the gravity acceleration.<br>
					<br>
					To keep the control of the Cubesat, you need the following keyboard controls:<br>
					<br>
					<b>W</b> To retain the CubeSat.<br>
					<b>A</b> To move left.<br>
					<b>D</b> To move right.<br>
					<br>
					Be careful! Because you have a limited amount of fuel, and it consumes each you push the retain button, if you
					waste all the fuel, your Cubesat probably will crash to the ground.<br>
					<br>
					Good luck!
				</div>
					<script type="text/javascript" charset="UTF-8" src="<?php bloginfo('siteurl'); ?>/wp-includes/js/games/library.js"></script>
					<script type="application/processing">

// images
PImage cubeSat;
PImage fireCube;
PImage startBg;
PImage gameBg;
PImage loseBg;
PImage plat;
PImage win;
PImage lose;
PImage ignition1;
PImage ingnition2;
PImage playAgain1;
PImage playAgain2;
// booleans
boolean inicioPartida;
boolean finPartida;
boolean resultado;
boolean enPosicionIgnition;
boolean enPosicionPlay;
boolean preparado;
boolean destino;
boolean fire;
// floats
float posX;
float posY;
float vx;
float vy;
float fuel;
float botonXignition;
float botonYignition;
float botonXplay;
float botonYplay;


void setup(){
	size(640,480);
	startBg = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/intro.jpg");
	gameBg = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/background_win.jpg");
	loseBg = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/background_fail.jpg");
	win = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/congratulations.png");
	lose = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/fail.png");
	ignition1 = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/ignition_normal.png");
	ignition2 = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/ignition_high.png");
	playAgain1 = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/play_again_normal.png");
	playAgain2 = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/play_again_high.png");
	plat = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/base.png");
	cubeSat = loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/cube.png");
	fireCube= loadImage("<?php echo dirname(get_bloginfo('stylesheet_url')); ?>/images/cube/fire_cube.png");

	inicioPartida = false;
	finPartida = false;
	resultado = false;
	enPosicionIgnition = false;
	enPosicionPlay = false;
	destino = false;
	preparado = false;
	fire = true;

	botonXignition = width/5;
	botonYignition = height/1.75;
	botonXplay = width/6;
	botonYplay = height/2;
	vx = 3;
	vy = 1;
	fuel = 100;
	posX = random(10,600);
	posY = 0;
}

void mouseMoved(){

	if(!finPartida&&(mouseX>botonXignition && mouseX<botonXignition+ignition1.width)&&(mouseY>botonYignition&&mouseY<botonYignition+ignition1.height)){
		enPosicionIgnition=true;
	}else if(!(finPartida&& (mouseX>botonXignition && mouseX<botonXignition+ignition1.width)&&(mouseY>botonYignition&&mouseY<botonYignition+ignition1.height))){
		enPosicionIgnition=false;
	}else if(finPartida&&(mouseX>botonXplay&&mouseX<playAgain1.width+botonXplay)&&(mouseY>botonYplay&&mouseY<botonYplay+playAgain1.height)){
		preparado= true;

	}
}
void mousePressed(){
	if(enPosicionIgnition&&mouseButton==LEFT)
		inicioPartida = true;
	else if(preparado&&mouseButton==LEFT){
		setup();
		loop();
	}
}
void drawFuelLeft(){
    text("Fuel:  ", 15.0F, 30.0F);
    stroke(123);
    noFill();
    rect(50.0F, 15.0F, 100.0F, 15.0F);
    if(fuel>=0){
		fill(103,64,58);
		rect(50.0F, 15.0F, fuel, 15.0F);
	}
  }

void draw(){

	image(startBg,0,0);

	if(!enPosicionIgnition){
		image(ignition1,botonXignition,botonYignition);
	}else if(enPosicionIgnition){
		image(ignition2,botonXignition,botonYignition);
	}
	if(inicioPartida){
		if(((key!='w'&&!finPartida&&key!='a'&&key!='d')||fuel<=0||!keyPressed)&&!destino){
			vy=vy+0.01;
			posY=posY+vy;
			fire = false;
		}
		else if(key=='w'&&!destino){
			fire = true;
			image(fireCube,posX,posY);
			fuel--;
			if(fuel>=0){
				posY=posY+vy;
				vy=vy-0.08;
			}
		}else if(key=='a'&&!destino){
			fire = true;
			image(fireCube,posX,posY);
			fuel--;
			if(fuel>=0){
				posX=posX-vx;
			}
		}else if(key=='d'&&!destino){
			fire = true;
			image(fireCube,posX,posY);
			fuel--;
			if(fuel>=0){
				posX=posX+vx;
			}
		}
		image(gameBg,0,0);
		image(plat,width/2-plat.width,height-plat.height);
		drawFuelLeft();
		textSize(15);
		text("vy: "+vy, 10, 50);
		if(fire)
		image(fireCube,posX,posY);
		else if(!fire)
		image(cubeSat,posX,posY);

		if((posY+cubeSat.height)>=height-plat.height-4 && vy<1 && !resultado){

			float actualX = posX;
			float actualY = posY;
			destino = true;
			image(gameBg,0,0);
			image(cubeSat,actualX,actualY);
			image(plat,width/2-plat.width,height-plat.height);
			finPartida=true;

			image(win, botonXplay, botonYplay-win.height);
			vy=0;
			finPartida=true;
		}else if(((posY+cubeSat.height)>=height-plat.height-4&& vy>1) ||((posY+cubeSat.height)>=height-plat.height-2 && vy<1 && (posX < (width/2-plat.width)||posX > width/2))){
			float actualX = posX;
			float actualY = posY;
			destino = true;
			image(loseBg,0,0);
			image(cubeSat,actualX,actualY);
			image(plat,width/2-plat.width,height-plat.height);
			resultado=true;

			image(lose, botonXplay, botonYplay-lose.height);
			finPartida = true;

		}
	}
	if(finPartida){
		image(playAgain1,botonXplay,botonYplay);
	}

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