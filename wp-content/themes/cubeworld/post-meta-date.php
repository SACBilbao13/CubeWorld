<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Post Meta-Data Template-Part File
 *
 * @file           post-meta-date.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.1.0
 * @filesource     wp-content/themes/responsive/post-meta.php
 * @link           http://codex.wordpress.org/Templates
 * @since          available since Release 1.0
 */
?>
<!-- Imagen del post -->
<?php if ( has_post_thumbnail()) : ?>
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
	<?php the_post_thumbnail(); ?>
    </a>
<?php endif; ?>
<!-- Fecha del post -->
<div class="post-date">
<div class="month"><?php the_time('M') ?></div>
<div class="day"><?php the_time('d') ?></div>
</div>
<!-- Titulo del post -->
<h3 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'responsive'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h3>

<div class="post-meta">
<?php responsive_post_meta_data(); ?>

	<?php if ( comments_open() ) : ?>
		<span class="comments-link">
		<span class="mdash">&mdash;</span>
	<?php comments_popup_link(__('No Comments &darr;', 'responsive'), __('1 Comment &darr;', 'responsive'), __('% Comments &darr;', 'responsive')); ?>
		</span>
	<?php endif; ?> 
</div><!-- end of .post-meta -->