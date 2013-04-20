<?php
global $current_user;
$default_username = ($current_user->user_login != 'admin') ? $current_user->user_login : str_replace(' ', '_', sanitize_user($current_user->display_name, true));
if ($default_username == 'admin')
	$default_username == '';
?>
	<section id="profile" class="api-key-form step1">
		<form id="api-signup" class="clearfix" action="<?php echo $this->server_url . '?action=register'; ?>" method="post"<?php echo (isset($_GET['api_error'])  || isset($_GET['clear_key']) || (isset($key_valid) && !$key_valid)) ? ' style="display:none;"' : ''; ?>>
			<fieldset>
				<legend>
					<?php _e('Get your <b>free</b> API key', 'wpmudev') ?><br />
					<small><?php _e('to experience the power of WPMU DEV', 'wpmudev') ?></small>
				</legend>
				<?php if (isset($_GET['register_error'])) {
					//build error message
					$err_message = array();
					foreach($_GET['register_error'] as $error) {
						if ($error == 'username') $err_message[] = __('This username is invalid.  Please enter a valid username.', 'wpmudev');
						if ($error == 'email') $err_message[] = __('This email is invalid or in use already.  Please enter a valid email.', 'wpmudev');
						if ($error == 'password') $err_message[] = __('Please enter a valid password with a minimum of 5 characters.', 'wpmudev');
						if ($error == 'fail') $err_message[] = __('There was an unknown error. Couldn&#8217;t register you... Sorry!', 'wpmudev');
					}
					?><div class="registered_error"><p><i class="icon-warning-sign icon-large"></i>&nbsp;<?php echo implode('<br>', $err_message); ?></p></div><?php
				} ?>
				<ol>
					<li>
						<div><label for="user_name"><?php _e('Your desired username', 'wpmudev') ?></label></div>
						<input type="text" name="username" id="user_name" value="<?php echo $default_username; ?>" />
						<!-- output line below if error validating -->
						<section class="validation error"><span class="icon-remove-sign"></span><?php _e('This username is already taken, please try a different one', 'wpmudev') ?></section>
					</li>
					<li>
						<div><label for="email_addr"><?php _e('Your email', 'wpmudev') ?></label></div>
						<input type="email" name="email" id="email_addr" value="<?php echo $current_user->user_email; ?>" />
						<!-- output line below if validation passed -->
						<section class="validation"><span class="icon-ok"></span></section>
					</li>
					<li>
						<div><label for="password"><?php _e('Choose your password', 'wpmudev') ?></label></div>
						<input type="password" name="password" id="password" />
						<input type="hidden" name="dashboard_url" value="<?php echo $this->dashboard_url; ?>" />
						<input type="hidden" name="referrer" value="<?php echo apply_filters('wpmudev_registration_referrer', ''); ?>" />
						<!-- output line below if validation passed -->
						<section class="validation"><span class="icon-ok"></span></section>
					</li>
					<li class="submit-data">
						<div class="cta-wrap">
							<button type="submit" class="wpmu-button full-width"><?php _e('Get your API key &raquo;', 'wpmudev') ?></button>
							<p><?php _e('Already a member?', 'wpmudev') ?> <a href="#" id="already-member"><?php _e('Click here', 'wpmudev') ?></a>.</p>
						</div>
					</li>
				</ol>
				
			</fieldset>
		</form>
		<form action="<?php echo $this->server_url . '?action=get_apikey'; ?>" method="post" id="api-login" class="clearfix"<?php echo (isset($_GET['api_error']) || isset($_GET['clear_key']) || (isset($key_valid) && !$key_valid)) ? '' : ' style="display:none;"'; ?>>
			
			<fieldset>
				<legend>
					<?php _e('Login to WPMU DEV', 'wpmudev') ?><br />
					<small><?php _e('to enable the power of the Dashboard', 'wpmudev') ?></small>
				</legend>
			<?php if (isset($_GET['api_error'])) {
				?><div class="registered_error"><p><i class="icon-warning-sign icon-large"></i>&nbsp;&nbsp;&nbsp;<?php _e('Invalid Username or Password. Please try again.', 'wpmudev'); ?><br /><a href="http://premium.wpmudev.org/wp-login.php?action=lostpassword" target="_blank"><?php _e('Forgot your password?', 'wpmudev'); ?></a></p></div><?php
			} ?>
			<?php if (isset($connection_error) && $connection_error) { ?>
				<div class="registered_error"><p><i class="icon-warning-sign icon-large"></i> <?php _e('Your server had a problem connecting to WPMU DEV. Please try again.', 'wpmudev'); ?><br><?php _e('If this problem continues, please contact your host and ask:', 'wpmudev'); ?><br><em><?php _e('"Is php on my server properly configured to be able to contact http://premium.wpmudev.org/wdp-un.php with a GET HTTP request via fsockopen or CURL?"', 'wpmudev'); ?></em></p></div>
			<?php } else if (isset($key_valid) && !$key_valid) { ?>
				<div class="registered_error"><p><i class="icon-warning-sign icon-large"></i> <?php _e('Your API Key was invalid. Please try again.', 'wpmudev'); ?></p></div>
			<?php } ?>
				<ol>
					<li>
						<div><label for="user_name1"><?php _e('Your username', 'wpmudev') ?></label></div>
						<input type="text" name="username" id="user_name1" autocomplete="off" />
						<!-- output line below if validation passed -->
						<section class="validation"><span class="icon-ok"></span></section>
					</li>
					<li>
						<div><label for="password1"><?php _e('Your password', 'wpmudev') ?></label></div>
						<input type="password" name="password" id="password1" autocomplete="off" />
						<input type="hidden" name="dashboard_url" value="<?php echo $this->dashboard_url; ?>" />
						<!-- output line below if validation passed -->
						<section class="validation"><span class="icon-ok"></span></section>
					</li>
					<li class="submit-data">
						<div class="cta-wrap">
							<button type="submit" class="wpmu-button full-width"><?php _e('Login &raquo;', 'wpmudev') ?></button>
							<p><?php _e('Not a member yet?', 'wpmudev') ?> <a href="#" id="not-member"><?php _e('Click here', 'wpmudev') ?></a>.</p>
						</div>
					</li>
				</ol>
			</fieldset>
		</form>
	</section>
	<section class="promotional">
		<h3><span class="wpmudev-logo-small"></span>&nbsp; <?php _e('members get:', 'wpmudev') ?></h3>
		<ul>
			<li class="plugins"><span class="promo-icn"></span><?php _e('Hundreds of amazing WordPress plugins to choose from', 'wpmudev') ?></li>
			<li class="themes"><span class="promo-icn"></span><?php _e('Quality WordPress, Multisite &amp; BuddyPress themes and theme packs', 'wpmudev') ?></li>
			<li class="support"><span class="promo-icn"></span><?php _e('Spectacular WordPress support, fast response times, white label videos &amp; more', 'wpmudev') ?></li>
			<li class="community"><span class="promo-icn"></span><?php _e('An amazing community of WordPress professionals to interact with', 'wpmudev') ?></li>
		</ul>
	</section>