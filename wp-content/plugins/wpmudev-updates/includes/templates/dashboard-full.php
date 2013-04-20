	<section id="profile" class="grid_container wpmudev-dash">
		<section class="overlay"></section>
		<section class="profile-left col-03">
			<section class="profile-user">
				<section class="profile-img">
					 <figure>
							<?php echo $profile['profile']['gravatar']; ?>
							<figcaption><?php echo $profile['profile']['title']; ?></figcaption>
					 </figure>
					<?php if (!$this->current_user_has_dev_gravatar()) { // Ooooh this is a mystery man! ?>
						<b><a href="https://en.gravatar.com/site/signup/" class="grav-link" target="_blank"><i class="icon-user"></i>&nbsp;&nbsp;<?php _e('Get a gravatar now!', 'wpmudev'); ?></a></b>
					<?php } else { // Regular gravatar (some sort of actual image) carry on ?>
						<a href="https://en.gravatar.com/site/login/" class="grav-link" target="_blank"><i class="icon-user"></i>&nbsp;&nbsp;<?php _e('Change Gravatar', 'wpmudev'); ?></a>
					<?php } ?>
					</section>
					<section class="profile-reputation">
						<h1><?php _e('Welcome', 'wpmudev'); ?> <strong><em><?php echo $profile['profile']['name']; ?></em></strong></h1>
						<small><?php printf(__('member since %s', 'wpmudev'), date_i18n(get_option('date_format'), $profile['profile']['member_since'])); ?></small>
						<h1 class="rep">
							<strong><?php _e('Your reputation', 'wpmudev'); ?></strong> 
							<span class="tooltip"><i class="icon-question-sign"></i>
							<section>
								<?php _e('Participate in our community discussions and earn reputation points. 1000 points gets you a free lifetime WPMU DEV membership!', 'wpmudev'); ?>
							</section>
							</span>
						</h1>
						
					<?php  if (isset($profile['reputation']['overall']) && isset($profile['reputation']['unique_users'])) { ?>
						<span class="rep-points"><i><?php _e('You currently have ', 'wpmudev'); ?><span class="number-of-points"><?php echo number_format_i18n($profile['reputation']['overall']); ?></span><?php _e(' reputation points', 'wpmudev'); ?></i></span>
						<br />
						<!-- user rep level -->
						<?php  if (0 == $profile['reputation']['overall']) { ?>
						<span class="profile-reputation-badge"><section><?php _e('Brand new here', 'wpmudev'); ?></section></span>
						<?php } else if ($profile['reputation']['overall'] > 0 && $profile['reputation']['overall'] <= 10) { ?>
						<span class="profile-reputation-badge ul-10"><section><?php _e('Getting my WPMU DEV Wings', 'wpmudev'); ?></section></span>
						<?php } else if ($profile['reputation']['overall'] > 10 && $profile['reputation']['overall'] <= 50) { ?>
						<span class="profile-reputation-badge ul-50"><section><?php _e('Starting to get into this DEV thing', 'wpmudev'); ?></section></span>
						<?php } else if ($profile['reputation']['overall'] > 50 && $profile['reputation']['overall'] <= 200) { ?>
						<span class="profile-reputation-badge ul-200"><section><?php _e('Serious WPMU DEV-ster', 'wpmudev'); ?></section></span>
						<?php } else if ($profile['reputation']['overall'] > 200 && $profile['reputation']['overall'] <= 500) { ?>
						<span class="profile-reputation-badge ul-500"><section><?php _e('WPMU DEV Expert', 'wpmudev'); ?>"</section></span>
						<?php } else if ($profile['reputation']['overall'] > 500) { ?>
						<span class="profile-reputation-badge ul-500plus"><section><?php _e('Like some sort of WPMU DEV God', 'wpmudev'); ?></section></span>
						<?php } ?>
					
						<!-- user help support level -->
						<?php if ($profile['reputation']['unique_users'] > 0 && $profile['reputation']['unique_users'] < 5) { ?>
						<span class="profile-reputation-badge us-5"><section><?php _e("I'm helpful", 'wpmudev'); ?>"</section></span>
						<?php } else if ($profile['reputation']['unique_users'] > 5 && $profile['reputation']['unique_users'] < 10) { ?>
						<span class="profile-reputation-badge us-10"><section><?php _e('Seriously helpful member', 'wpmudev'); ?></section></span>
						<?php } else if ($profile['reputation']['unique_users'] > 10 && $profile['reputation']['unique_users'] < 20) { ?>
						<span class="profile-reputation-badge us-20"><section><?php _e('Exceptionally helpful', 'wpmudev'); ?></section></span>
						<?php } else if ($profile['reputation']['unique_users'] > 20) { ?>
						<span class="profile-reputation-badge us-20plus"><section><?php _e('Mindblowingly helpful member', 'wpmudev'); ?></section></span>
						<?php } ?>
						<!-- end -->
						
						<?php if ($profile['reputation']['overall'] > 1000 && $profile['reputation']['unique_users'] >= 10) { ?>
						<span class="profile-reputation-badge lifetime"><section><?php _e('Lifetime WPMU Dev member', 'wpmudev'); ?></section></span>
						<?php } ?>
						
					<?php } ?>
					</section>
				</section>
			<section class="profile-rep-table">
				<table class="hoverExpand" cellpadding="0" cellspacing="0" border="0">
				<thead>
					<td width="70" align="center"><?php _e('Points', 'wpmudev'); ?></td>
					<td width="115" align="left"><?php _e('When', 'wpmudev'); ?></td>
					<td width="174" align="left"><?php _e('By', 'wpmudev'); ?></td>
				</thead>
				<tbody>
					<?php $count = 1; if (isset($profile['reputation']['history'])) foreach ($profile['reputation']['history'] as $rep) { ?>
					<tr> 
						<td align="center"><?php echo $rep['points'];?></td>
						<td class="date"><?php echo date_i18n(get_option('date_format'), $rep['time']);?></td>
						<td><a href="<?php echo $rep['from_user_url'];?>" target="_blank"><?php echo $rep['from_user'];?></a></td>
					</tr>
					<tr class="hiddenrow"><td colspan="3">
						<div class="reason">
							<strong><?php _e('On:', 'wpmudev'); ?></strong> <a href="<?php echo esc_url($rep['topic_link']); ?>" target="_blank"><?php echo $rep['topic_title'];?></a>
						</div>
					</td></tr>
					<?php if ($count >= 4) break; $count++ ?>
					<?php } ?>
				</tbody>
			</table>
			</section>
		</section>
		<section class="profile-activity col-03">
			<h1><strong><?php _e('Your recent activity', 'wpmudev'); ?></strong></h1>
			<table cellpadding="0" cellspacing="0" border="0">
				<?php $count = 1; if (isset($profile['activity'])) foreach($profile['activity'] as $activity) { ?>
				<tr>
					<td class="date" width="31.8%" align="center"><?php echo date_i18n(get_option('date_format'), $activity['timestamp']);?></td>
					<td width="4%"></td>
					<td align="left" width="64%"><?php echo $activity['memo'];?></td>
				</tr>
				<?php if ($count >= 5) break; $count++ ?>
				<?php } ?>
			</table>
			<a href="http://premium.wpmudev.org/profile/private/" target="_blank" class="wpmu-button icon"><i class="icon-list icon-large"></i> <?php _e('VIEW ALL ACTIVITY', 'wpmudev'); ?></a>
		</section>
	</section>

	<!-- Bottom part of the dashboard -->
	
	<!-- 3 column section -->
	<section id="main" role="main" class="wpmudev-dash">
		<!-- VISUAL BACKDROP -->
		<div id="left-side-bg">
			<div class="spacer"></div>
		</div>
		<div id="right-side-bg">
			<div id="right-section-gradient"></div>
		</div>
		<!-- VISUAL BACKDROP -->
		<section id="dash-main-content" class="grid_container">
			<section class="support-column col-02">
				<h1><i class="icon-question-sign"></i><?php _e('Support', 'wpmudev'); ?></h1>
				<hr />
				<input type="text" class="dash-search" id="forum-search-q" placeholder="<?php _e('Search support', 'wpmudev'); ?>" /><a id="forum-search-go" href="#" class="column-search"><i class="icon-search"></i></a>
				<a href="admin.php?page=wpmudev-support" class="wpmu-button icon big"><span class="btn-txt"><strong><?php _e('Q&A', 'wpmudev'); ?><i class="icon-play-circle icon-larger"></i></strong><small><?php _e('Ask a question', 'wpmudev'); ?></small></span></a>
				<section class="recent-activity-widget">
					<ul>
						<li class="accordion-title">
							<p><?php _e('YOUR LATEST Q&A ACTIVITY:', 'wpmudev'); ?><a href="#" class="ui-hide-link"><span><?php _e('HIDE', 'wpmudev'); ?></span><span class="ui-hide-triangle ui-show-triangle"></span></a></p>
							<ul>
							<?php $count = 1; if (isset($profile['forum']['support_threads'])) foreach ($profile['forum']['support_threads'] as $thread) { ?>
								<li>
									<?php if ($thread['status'] == 'resolved') { ?>
									<i class="icon-ok-sign icon-large resolved" title="<?php _e('Resolved', 'wpmudev'); ?>"></i>
									<?php } else { ?>
									
									<?php } ?>
									<a href="<?php echo $thread['link'];?>" target="_blank"><?php echo $thread['title'];?></a>
								</li>
							<?php if ($count >= 5) break; $count++ ?>
							<?php } else if (!$this->get_apikey()) { ?>
								<li><a href="#profile"><?php _e('Enter your API key to show activity', 'wpmudev'); ?></a></li>
							<?php } else { ?>
								<li><?php _e('No support activity yet.', 'wpmudev'); ?> <a href="admin.php?page=wpmudev-support" style="float:right;"><?php _e('Ask a question &raquo;', 'wpmudev'); ?></a></li>
							<?php } ?>
							</ul>
						</li>
					</ul>
				</section>
			</section> <!-- /SUPPORT COLUMN -->

			<section class="products-column col-02" >
				<h1><i class="icon-th"></i><?php _e('Products', 'wpmudev'); ?></h1>
				<hr />
				<input type="text" id="suggestive-dash-search" class="dash-search" placeholder="<?php _e('Search products', 'wpmudev'); ?>" /><a href="#" id="project-search-go" class="column-search"><i class="icon-search"></i></a>
				<!-- plugin / theme foldout on main dash -->
				<section class="product-foldout">
					<ul>
						<li class="accordion-title">
							<p><?php _e('LATEST PLUGIN RELEASES:', 'wpmudev'); ?></p>
							<ul class="hover-to-expand">
								<?php
								$data = $this->get_updates();
								if ( is_array( $data ) ) {
									$list = $data['latest_plugins'];
									$projects = $data['projects'];
									$i = 1;
									if (count($list) > 0) {
										foreach ($list as $item) {
											if ( isset($projects[$item]) ) {
												//skip if not given type
												if ($projects[$item]['type'] != 'plugin')
													continue;
												?>
												<!-- start project block -->
												<li>
													<div>
														<h5><?php echo trim(stripslashes($projects[$item]['name'])); ?></h5>
														<a href="#" class="hover-to-expand"><i class="icon-play-circle icon-larger"></i>&nbsp;&nbsp;<?php _e('hover for more', 'wpmudev'); ?></a>
													</div>
													<div class="expanded-content">
														<span>
															<h5><a href="<?php echo self_admin_url('admin.php?page=wpmudev-plugins#pid=' . (int)$projects[$item]['id']);?>"><?php echo trim(stripslashes($projects[$item]['name'])); ?></a></h5>
														</span>
														<ul>
															<li><?php echo stripslashes($projects[$item]['short_description']); ?></li>
															<a href="<?php echo self_admin_url('admin.php?page=wpmudev-plugins#pid=' . (int)$projects[$item]['id']);?>"><span class="ui-hide-triangle"></span><?php _e('Learn more', 'wpmudev'); ?></a>
														</ul>
													</div>
												</li>
												<!-- end project block -->
												<?php
												if ($i >= 3) break;
												$i++;
											}
										}
									}
								}
								?>
							</ul>
						</li>
					</ul>
				</section>
				<!-- plugin / theme foldout on main dash -->
				<a href="admin.php?page=wpmudev-plugins" class="wpmu-button icon big"><i class="icon-play-circle icon-larger"></i><strong><?php _e('All plugins', 'wpmudev'); ?></strong></a>

				<!-- plugin / theme foldout on main dash -->
				<section class="product-foldout">
					<ul>
						<li class="accordion-title">
							<p><?php _e('LATEST THEME RELEASES:', 'wpmudev'); ?></p>
							<ul class="hover-to-expand">
								<?php
								$data = $this->get_updates();
								$local_projects = get_site_option('wdp_un_local_projects');
								if ( is_array( $data ) ) {
									$list = $data['latest_themes'];
									$projects = $data['projects'];
									$i = 1;
									if (count($list) > 0) {
										foreach ($list as $item) {
											if ( isset($projects[$item]) ) {
												//skip if not given type
												if ($projects[$item]['type'] != 'theme')
													continue;
											
												?>
												<!-- start project block -->
												<li>
													<div>
														<h5><?php echo trim(stripslashes($projects[$item]['name'])); ?></h5>
														<a href="" class="hover-to-expand"><i class="icon-play-circle icon-larger"></i>&nbsp;&nbsp;<?php _e('hover for more', 'wpmudev'); ?></a>
													</div>
													<div class="expanded-content">
														<span>
															<h5><a href="<?php echo self_admin_url('admin.php?page=wpmudev-themes#pid=' . (int)$projects[$item]['id']);?>"><?php echo trim(stripslashes($projects[$item]['name'])); ?></a></h5>
														</span>
														<ul>
															<li><?php echo stripslashes($projects[$item]['short_description']); ?></li>
															<a href="<?php echo self_admin_url('admin.php?page=wpmudev-themes#pid=' . (int)$projects[$item]['id']);?>"><span class="ui-hide-triangle"></span><?php _e('Learn more', 'wpmudev'); ?></a>
														</ul>
													</div>
												</li>
												<!-- end project block -->
												<?php
												if ($i >= 3) break;
												$i++;
											}
										}
									}
								}
								?>
							</ul>
						</li>
					</ul>
				</section>
				<!-- plugin / theme foldout on main dash -->
				<a href="admin.php?page=wpmudev-themes" class="wpmu-button icon big"><i class="icon-play-circle icon-larger"></i><strong><?php _e('All themes', 'wpmudev'); ?></strong></a>
			</section> <!-- /PRODUCTS COLUMN -->

			<section class="community-column col-02" >
				<h1><i class="icon-comments"></i><?php _e('Community', 'wpmudev'); ?></h1>
				<hr />
				<i class="community-quote"><?php _e('Help other members and earn reputation points.', 'wpmudev'); ?></i>
				<h3><?php _e('Latest community topics:', 'wpmudev'); ?></h3>
				<!-- Accordion -->
				<section class="main-community-topics">
					<ul>
						<?php $count=1; if (@$profile['forum']['recent_threads']) foreach ($profile['forum']['recent_threads'] as $forum) { ?>
						<li class="accordion-title">
							<p><?php echo $forum['title']; ?><a href="#" class="ui-hide-link"><span><?php echo (1==$count) ? __('HIDE', 'wpmudev') : __('SHOW', 'wpmudev'); ?></span><span class="ui-hide-triangle"></span></a></p>
							<ul>
							<?php $count = 1; unset($forum['title']); unset($forum['link']); foreach ($forum as $thread) { ?>
								<li><a href="<?php echo $thread['link'];?>" target="_blank"><?php echo $thread['title'];?></a></li>
							<?php if ($count >= 3) break; $count++; ?>
							<?php } ?>
							</ul>
						</li>
						<?php } ?>
					</ul>
				</section><!-- /Accordion -->
			</section> <!-- /COMMUNITY COLUMN -->
		</section>
	</section>