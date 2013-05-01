<?php
/**
 * Custom Comments Template
 */

if (!defined('TEMPLATE_DOMAIN'))
	define('TEMPLATE_DOMAIN', 'wdcp');


/**
 * Individual comment item handler callback.
 */
function wdcp_comments_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>

	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.'); ?></em>
			<br />
		<?php endif; ?>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
				</div>
				<div class="reply">
					<?php
						//comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
						$reply = get_comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );	    		 	 			 		  
						if (!preg_match('~nofollow~', $reply)) $reply = preg_replace('~\sclass=~', ' rel="nofollow" class=', $reply);
						echo $reply;
					?>
				</div>
		<div class="comment-body"><?php comment_text(); ?>
			<div class="comment-meta commentmetadata">
				<?php printf( __( '%s '), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				&nbsp;&nbsp;
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php printf( __( '%1$s at %2$s' ), get_comment_date(),  get_comment_time() ); ?>
				</a>

			</div>
		</div>
			<div class="clear"></div>
	</div>

	<?php break;
		  case 'pingback'  :
		  case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:'); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)'), ' ' ); ?></p>
	<?php break; endswitch;
}

/* ----- Actual comments template ----- */
?>
<div id="comments">
	<a id="comments-top"></a>
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.'); ?></p>
	</div>
<?php
return;
endif;
?>

<?php do_action('wdcp-cct-comments_top', get_the_ID()); ?>

<?php if ( have_comments() ) : ?>
	<h3 id="comment-title"><?php
	printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number()),
	number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
	?></h3>
	<ol class="commentlist">
		<?php
			wp_list_comments( array( 'callback' => 'wdcp_comments_callback' ) );
		?>
	</ol>
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :  ?>
	<div class="comment-navigation">
		<div class="comment-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments') ); ?></div>
		<div class="comment-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>') ); ?></div>
	</div>
<?php endif;  ?>

<?php else :

if ( ! comments_open() ) :
?>
<p class="nocomments"><?php _e( 'Comments are closed.'); ?></p>
<?php endif;  ?>

<?php endif;  ?>
<?php comment_form(); ?>

<?php do_action('wdcp-cct-comments_bottom', get_the_ID()); ?>

</div>