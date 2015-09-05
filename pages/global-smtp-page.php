<?php

// Admin page
function global_smtp_page() {
?>
	<div class="wrap">
		<h1><?php _e( 'Global SMTP', 'global-smtp' ); ?></h1>

		<p>This plugin allows you to configure SMTP mail on WordPress <strong>(including multisite)</strong> with nothing more than wp-config.php constants.</p>

		<p>It's easy to automate this configuration for new site installs with the use of WP-CLI: <a href="https://github.com/wp-cli/wp-cli" target="_blank">https://github.com/wp-cli/wp-cli</a></p>

		<h2>Getting started</h2>

		<p>First install as a WordPress pluing. Optionally, you could install as a "must use" plugin by placing <code>smtp.php</code> in <code>wp-content/mu-plugins</code>.</p>

		<p>Once the plugin is installing installed, add the constants to <code>wp-config.php</code></p>

		<p>The minimum requirement is setting the host, username, and password. Everything else will be assumed from some defaults.</p>

		<pre>
	define('GLOBAL_SMTP_HOST','mail.example.com');
	define('GLOBAL_SMTP_USER','admin@example.com');
	define('GLOBAL_SMTP_PASSWORD','password');
		</pre>

		<p>Assumed defaults:
		From -&gt; Network admin email address
		FromName -&gt; Network Name
		Port -&gt; 465
		Secure -&gt; ssl</p>

		<p>You can specify your own with these statements:</p>

		<pre>
	define('GLOBAL_SMTP_FROM','you@example.com');
	define('GLOBAL_SMTP_FROM_NAME','Your Name');
	define('GLOBAL_SMTP_PORT',587);
	define('GLOBAL_SMTP_SECURE;','tls');
		</pre>

		<p>You can also specify some other values for the SMTP mailer:</p>

		<p>
			<code>GLOBAL_SMTP_RETURN_PATH</code> - Bounce address
			<code>GLOBAL_SMTP_REPLYTO_FROM</code> - Email address for client side replies
			<code>GLOBAL_SMTP_REPLYTO_FROM_NAME</code> - Name for client side replies
		</p>

		<p><code>GLOBAL_SMTP_AUTH_TYPE</code> - Can be <code>'LOGIN'</code>, <code>'PLAIN'</code>, <code>'NTLM'</code> (defaults to <code>'LOGIN'</code>)</p>

		<h2>Environment Specific Settings</h2>

		<p>Because all the configuration happens via PHP constants, you can have different configurations depending on what kind of server environment is being used. For example, you could set up staging email addresses, and have your staging environment isolated from the production environment.</p>

		<pre>
	define('GLOBAL_SMTP_DISABLE',true);
		</pre>

		<p>That will prevent Global SMTP from initializing at all; quite useful for development environments.</p>

		<h2>Debugging</h2>

		<p>This plugin will trigger warnings if you've done something wrong. Just be sure to have <a href="http://codex.wordpress.org/Debugging_in_WordPress#WP_DEBUG" target="_blank">WP_DEBUG</a> enabled.</p>

		<p>You can turn on SMTP debug by including this statement:</p>

		<pre>
	define('GLOBAL_SMTP_DEBUG',true);
		</pre>

		<p>This will display debug output from the PHP Mailer class when combined with the <a href="http://wordpress.org/plugins/check-email/" target="_blank">http://wordpress.org/plugins/check-email/</a> plugin. This allows you to send a test message and troubleshoot any connectivity problems.</p>

		<p>Have any ideas for improvement? Pull requests are welcome! :)</p>

	</div>
<?php
}
