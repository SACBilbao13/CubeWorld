
	<div id="comment-<?php echo $comment->comment_ID; ?>">
		<div class="comment-author vcard">
			<?php echo $avatar; ?>
		</div>
		<div class="comment-body"><?php echo $comment->comment_content; ?>
			<div class="comment-meta commentmetadata">
				<a href="<?php echo $comment->comment_author_url;?>" rel="nofollow">
					<?php printf( '<cite class="fn">%s</cite>', $comment->comment_author ); ?>
					&nbsp;&nbsp;	    		 	 			 		  
					<?php printf(
						__('%1$s at %2$s'),
						mysql2date(get_option('date_format'), $comment->comment_date),
						mysql2date(get_option('time_format'), $comment->comment_date)
					); ?>
				</a>
			</div>
		</div>
		<div class="clear"></div>
	</div>