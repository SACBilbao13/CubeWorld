<section id="profile" class="grid_container free-user clearfix wpmudev-dash">
	<section class="contents clearfix">
		<section class="profile-left">
			<section class="profile-user">
				<section class="profile-img">
					 <figure>
							<?php echo $profile['profile']['gravatar']; ?>
							<figcaption><?php echo $profile['profile']['title']; ?></figcaption>
					 </figure>
				</section>
				<section class="profile-data">
					<h1><?php _e('Welcome', 'wpmudev'); ?> <strong><em><?php echo $profile['profile']['name']; ?></em></strong></h1>
					<small><?php printf(__('member since %s', 'wpmudev'), date_i18n(get_option('date_format'), $profile['profile']['member_since'])); ?></small><br />
					<?php if (!$this->current_user_has_dev_gravatar()) { // Ooooh this is a mystery man! ?>
						<b><a href="https://en.gravatar.com/site/signup/" class="grav-link" target="_blank"><i class="icon-user"></i>&nbsp;&nbsp;<?php _e('Get a gravatar now!', 'wpmudev'); ?></a></b>
					<?php } else { // Regular gravatar (some sort of actual image) carry on ?>
						<a href="https://en.gravatar.com/site/login/" class="grav-link" target="_blank"><i class="icon-user"></i>&nbsp;&nbsp;<?php _e('Change Gravatar', 'wpmudev'); ?></a>
					<?php } ?>
				</section>
			</section>
		</section>
		<section class="acc-info">
			<span class="dev-logo-small"></span>
			<?php _e('You are currently a free member.', 'wpmudev'); ?> <br /><a href="<?php echo apply_filters('wpmudev_join_url', 'http://premium.wpmudev.org/join/'); ?>"><?php _e('Upgrade your membership', 'wpmudev'); ?></a> <?php _e('to unleash the full<br/> power of WPMU DEV.', 'wpmudev'); ?>
		</section>
	</section>
	<section class="user-related-products clearfix">
		<section class="product-group">
			<h3><?php _e('free plugins', 'wpmudev'); ?></h3>
			<ul>
			<?php foreach ($free_projects['plugins'] as $project) { ?>
				<li>
					<a href="<?php echo $this->plugins_url . '#pid=' . $project['id']; ?>">
						<img src="<?php echo esc_url($project['thumbnail']);?>" />
						<?php echo wp_strip_all_tags($project['name']); ?>
					</a>
				</li>
			<?php } ?>
				<li><button data-href="<?php echo esc_url($this->plugins_url); ?>" class="wpmu-button full-width small"><?php _e('View More &raquo;', 'wpmudev'); ?></button></li>
			</ul>
		</section>
		<section class="product-group">
			<h3><?php _e('free themes', 'wpmudev'); ?></h3>
			<ul>
			<?php foreach ($free_projects['themes'] as $project) { ?>
				<li>
					<a href="<?php echo $this->themes_url . '#pid=' . $project['id']; ?>">
						<img src="<?php echo esc_url($project['thumbnail']);?>" />
						<?php echo wp_strip_all_tags($project['name']); ?>
					</a>
				</li>
			<?php } ?>
				<li><button data-href="<?php echo esc_url($this->themes_url); ?>" class="wpmu-button full-width small"><?php _e('View More &raquo;', 'wpmudev'); ?></button></li>
			</ul>
		</section>
	</section>
</section>
<section class="premium-content">
	<section class="layer">
		<section class="promotional">
			<span class="tag-upgrade"></span>
			<h3><span class="wpmudev-logo-small"></span>&nbsp;<?php _e(' members get:', 'wpmudev'); ?></h3>
			<ul>
				<li class="plugins"><span class="promo-icn"></span><?php _e('Access to over 140 WordPress Premium plugins', 'wpmudev'); ?></li>
				<li class="themes"><span class="promo-icn"></span><?php _e('Quality WordPress, Multisite &amp; BuddyPress themes', 'wpmudev'); ?></li>
				<li class="support"><span class="promo-icn"></span><?php _e('Spectacularly fast WordPress support service', 'wpmudev'); ?></li>
				<li class="community"><span class="promo-icn"></span><?php _e('Amazing community of WordPress professionals', 'wpmudev'); ?></li>
			</ul>
			<a class="wpmu-button" href="<?php echo apply_filters('wpmudev_join_url', 'http://premium.wpmudev.org/join/'); ?>"><?php _e('Find out more &raquo;', 'wpmudev'); ?>
			</a>
		</section>
	</section>

	<section class="premium-products clearfix">
		<h3><?php _e('premium plugins', 'wpmudev'); ?></h3>
			<ul>
			<?php foreach ($premium_projects['plugins'] as $project) { ?>
				<li>
					<a href="<?php echo esc_url($project['url']);?>">
						<img src="<?php echo esc_url($project['thumbnail']);?>" />
						<?php echo wp_strip_all_tags($project['name']); ?>
					</a>
				</li>
			<?php } ?>
			</ul>

		<h3><?php _e('premium themes', 'wpmudev'); ?></h3>
			<ul>
			<?php foreach ($premium_projects['themes'] as $project) { ?>
				<li>
					<a href="<?php echo esc_url($project['url']);?>">
						<img src="<?php echo esc_url($project['thumbnail']);?>" />
						<?php echo wp_strip_all_tags($project['name']); ?>
					</a>
				</li>
			<?php } ?>
			</ul>
	</section>
</section>
