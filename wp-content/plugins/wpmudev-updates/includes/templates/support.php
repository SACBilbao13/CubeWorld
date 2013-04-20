<?php if (!$disabled) { ?>
	<section class="lightbox overlay">
		<section class="before-you-post lightbox" >
			<h3><?php _e("Here are some ways you can solve your problem right now!", 'wpmudev'); ?></h3>
			<ol>
				<li><?php _e("Make sure you're running the latest version of WordPress.", 'wpmudev'); ?></li>
				<li><?php _e("<a href='admin.php?page=wpmudev-updates'>Make sure</a> you are using the latest version of our product.", 'wpmudev'); ?></li>
				<li><?php _e("Have you read the <a href='admin.php?page=wpmudev-updates&tab=installed'>'Usage' instructions</a>? It's like a mini manual.", 'wpmudev'); ?></li>
				<li><?php _e("Have you tried searching? Use the field in the top right to search our support.", 'wpmudev'); ?></li>
			</ol>
			<h3><?php _e("And if you're feeling a bit more technical:", 'wpmudev'); ?></h3>
			<ol>
				<li><?php _e("Disable and re-activate the plugin or theme.", 'wpmudev'); ?></li>
				<li><?php _e("Check for a plugin conflict - try disabling other plugins and see if that fixes it... if it does, notify us &amp; we'll find a fix.", 'wpmudev'); ?></li>
				<li><?php _e("Check for a theme conflict - try another theme (like Twenty Eleven) and see if it fixes it... if it does, notify us &amp; we'll find a fix.", 'wpmudev'); ?></li>
			</ol>
		</section>
	</section>
	
<?php } ?>

<?php if ($disabled) { ?> 

	<section id="support-disabled">
		<section class="contents clearfix">
			<section class="layer" id="support-layer">
				<section class="promotional">
					<span class="tag-upgrade"></span>
					<h3 class="support-msg"><span class="wpmudev-logo-small"></span>&nbsp; <?php _e('members get unlimited, comprehensive support for <br/>all our products and any <br />WordPress related Queries', 'wpmudev') ?></h3>
					<a class="btn" href="<?php echo apply_filters('wpmudev_join_url', 'http://premium.wpmudev.org/join/'); ?>">
						<button class="wpmu-button"><?php _e('Find out more &raquo;', 'wpmudev') ?></button>
					</a>
					<?php if (!$this->get_apikey()) { ?> 
					<p class="support-already-member"><a href="admin.php?page=wpmudev&clear_key=1"><?php _e('Already a member?', 'wpmudev') ?></a></p>
					<?php } ?> 
				</section>
			</section>
		</section>
	</section>

<?php } ?>

<hr class="section-head-divider" />
<section class="support-wrap wpmudev-dash">
	<div class="wrap grid_container">
		<h1 class="section-header">
			<i class="icon-question-sign"></i><?php _e('Support', 'wpmudev') ?>
		</h1>
		<div class="listing-form-elements">
			<table cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<tr>
						<td width="48%" align="center">&nbsp;</td>
						<td width="4.8%">&nbsp;</td>
						<td width="47%"><input type="text" id="search_projects" placeholder="<?php _e('Search support', 'wpmudev') ?>" /><a id="forum-search-go" href="#" class="search-btn"><i class="icon-search"></i></a></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="support-container grid_container">
		<div class="ask-question-container">

			<div id="success_ajax" style="display:none;">
				<h1><i class="icon-ok"></i> <?php _e("Success!", 'wpmudev') ?></h1>
				<p><?php _e("Thanks for contacting Support, we'll get back to you as soon as possible.", 'wpmudev'); ?></p>
				<p><a href="#" target="_blank"><?php _e('You can view or add to your support request here &raquo;', 'wpmudev'); ?></a></p>
			</div>
			
			<form id="qa-form" method="post" enctype="multipart/form-data" action="">
				<fieldset>
					<legend>
						<?php _e("Question? Bug? Feature request? <br />Let's see how we can help.", 'wpmudev') ?><br />
						<small>Before you post, please read <a href="#" id="tips">these tips</a>.</small>
					</legend>

					<ol>
						<?php if ( $this->get_apikey() && ($data['membership'] == 'full' || is_numeric($data['membership'])) && isset($data['downloads']) && $data['downloads'] != 'enabled' ) { ?>
							<div class="error fade"><p><?php _e('You have reached your maximum enabled sites for direct dashboard support. You may <a href="http://premium.wpmudev.org/wp-admin/profile.php?page=wdpun">change which sites are enabled or upgrade to a higher membership level here &raquo;</a>', 'wpmudev'); ?></p></div>
						<?php } else if (!$this->allowed_user()) {
							$user_info = get_userdata( get_site_option('wdp_un_limit_to_user') );
						?>
							<div class="error fade"><p><?php printf(__('Only the admin user "%s" has access to WPMU DEV support.', 'wpmudev'), $user_info->display_name); ?></p></div>
						<?php } ?>
					
						<div id="error_topic" style="1display:none;" class="error fade">
							<p><i class="icon-warning-sign icon-large"></i> <?php _e('Please enter your question title.', 'wpmudev'); ?></p>
						</div>
						<div id="error_ajax" style="display:none;" class="error fade">
							<p><i class="icon-warning-sign icon-large"></i> <?php _e('There was a problem posting your support question:', 'wpmudev'); ?></p>
						</div>
						<li>
							<div class="wrap"><label for="topic"><?php _e('What\'s your question or topic?<br /> Be specific please :)', 'wpmudev') ?></label></div>
							<input type="text" name="topic" id="topic" />
						</li>
						<div id="error_project" style="display:none;" class="error fade"><p><i class="icon-warning-sign icon-large"></i> <?php _e('Please select what you need support for.', 'wpmudev'); ?></p></div>
						<li class="select">
							<select id="q-and-a" name="project_id">
								<option value=""><?php _e('Select an Installed Product:', 'wpmudev') ?></option>
								<?php
								$projects = $this->get_local_projects();
								$data = $this->get_updates();
								$forum = isset( $_GET['forum'] ) ? (int)$_GET['forum'] : false;
								$plugins = '';
								$themes = '';
								foreach ($projects as $pid => $project) {
									if (isset($data['projects'][$pid])) {
										if ($data['projects'][$pid]['type'] == 'plugin')
											$plugins .= '<option value="'.$pid.'"'.$disabled.'>'.esc_attr($data['projects'][$pid]['name'])."</option>\n";
										else if ($data['projects'][$pid]['type'] == 'theme')
											$themes .= '<option value="'.$pid.'"'.$disabled.'>'.esc_attr($data['projects'][$pid]['name'])."</option>\n";
									}
								}
								if ($plugins) {
									echo '<optgroup forum_id="1" label="'.__('Plugins:', 'wpmudev').'">' . $plugins . '</optgroup>';
								}
								if ($themes) {
									echo '<optgroup forum_id="2" label="'.__('Themes:', 'wpmudev').'">' . $themes . '</optgroup>';
								}
								?>
								<optgroup label="<?php _e('General Topic:', 'wpmudev'); ?>">
									<option forum_id="11" value=""<?php echo $disabled; selected($forum, 11); ?>><?php _e('General', 'wpmudev'); ?></option>
									<option forum_id="10" value=""<?php echo $disabled; selected($forum, 10); ?>><?php _e('BuddyPress', 'wpmudev'); ?></option>
									<option forum_id="8" value=""<?php echo $disabled; selected($forum, 8); ?>><?php _e('Beginners WordPress Discussion', 'wpmudev'); ?></option>
									<option forum_id="7" value=""<?php echo $disabled; selected($forum, 7); ?>><?php _e('Advanced WordPress Discussion', 'wpmudev'); ?></option>
									<option forum_id="5" value=""<?php echo $disabled; selected($forum, 5); ?>><?php _e('Feature Suggestions &amp; Feedback', 'wpmudev'); ?></option>
								</optgroup>
							</select>
						</li>
						
						<div id="error_content" style="display:none;" class="error fade"><p><i class="icon-warning-sign icon-large"></i> <?php _e('Please enter your support question.', 'wpmudev'); ?></p></div>
						<li>
							<div class="wrap"><label for="post_content"><?php _e('Ok, go for it...', 'wpmudev') ?></label></div>
							<textarea rows="20" id="post_content" name="post_content"></textarea>
						</li>
						<li>
							<p class="caution-note"><i class="icon-info-sign"></i> <?php _e("Please don't share any private information (passwords, API keys, etc.) here, support staff will ask for these via email if they are required.", 'wpmudev') ?></p>
						</li>
						<li>
							<div class="wrap"><label for="notify-me"><?php _e("Notify me of responses via email", 'wpmudev') ?></label></div>
							<input type="checkbox" id="notify-me" checked="checked" value="1" name="stt_checkbox"<?php echo $disabled; ?> />
							
							<?php if ($disabled) { ?>
								<a class="wpmu-button icon"><i class="icon-play-circle icon-large"></i><?php _e("Post your question", 'wpmudev') ?></a>
							<?php } else { ?>
								<a id="qa-submit" class="wpmu-button icon"><i class="icon-play-circle icon-large"></i><?php _e("Post your question", 'wpmudev') ?></a>
							<?php } ?>
								<span id="qa-posting" class="wpmu-button icon" style="display:none;"><img src="<?php echo $spinner; ?>" /> <?php _e("Posting question...", 'wpmudev') ?></span>
						</li>
					</ol>
				</fieldset>
				
			<input type="hidden" value="1" id="forum_id" name="forum_id">
			</form>
			<img src="<?php echo $spinner; ?>" width="1" height="1" /><!-- preload -->
		</div>
		<?php if (!$disabled) { ?>
		<div class="your-latest-q-and-a" >
			<section class="recent-activity-widget" id="recent-qa-activity">
				<ul>
					<li class="accordion-title">
						<p><?php _e('YOUR LATEST Q&A ACTIVITY:', 'wpmudev'); ?> <a href="#" class="ui-hide-link"><span><?php _e('HIDE', 'wpmudev'); ?></span><span class="ui-hide-triangle"></span></a></p>
						<ul>
						<?php if (isset($profile['forum']['support_threads'])) foreach ($profile['forum']['support_threads'] as $thread) { ?>
							<li>
								<?php if ($thread['status'] == 'resolved') { ?>
								<i class="icon-ok-sign icon-large resolved" title="<?php _e('Resolved', 'wpmudev'); ?>"></i>
								<?php } else { ?>
								
								<?php } ?> 
								<a href="<?php echo $thread['link'];?>" target="_blank"><?php echo $thread['title'];?></a>
							</li>
						<?php } else { ?>
							<li class="no-activity"><?php _e('No support activity yet.', 'wpmudev'); ?></li>
						<?php } ?>
						</ul>
					</li>
				</ul>
			</section>
		</div>
		<?php } ?>
	</div>
</section>