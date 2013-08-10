Multisite SMTP
=================

This plugin allows you to configure SMTP mail on Wordpress Multisite with nothing more than wp-config.php constants. 

It's easy to automate this configuration for new site installs with the use of WP-CLI: https://github.com/wp-cli/wp-cli

I plan to include this as part of the initial setup for my vps scripts: https://github.com/webtekk/vps-setup

Getting started
---------------
First install the plugin. This can be done by placing smtp.php in it's own plugin folder and network activating it - or you could place smtp.php in wp-content/mu-plugins (my preference)

Next, setup add the constants to wp-config.php

The minimum requirement is setting the host, username, and password. Everything else will be assumed from some defaults.
`define('GLOBAL_SMTP_HOST','mail.example.com');
define('GLOBAL_SMTP_USER','admin@example.com');
define('GLOBAL_SMTP_PASSWORD','password');`

Assumed defaults:
From -> Network admin email address
FromName -> Network Name
Port -> 465
Secure -> ssl

You can specifu your own with these statements:
`define('GLOBAL_SMTP_FROM','you@example.com');
define('GLOBAL_SMTP_FROM_NAME','Your Name');
define('GLOBAL_SMTP_PORT',587);
define('GLOBAL_SMTP_SECURE;','tls');`

You can also specify some other values for the SMTP mailer:

GLOBAL_SMTP_RETURN_PATH - Bounce address
GLOBAL_SMTP_REPLYTO_FROM - Email address for client side replies
GLOBAL_SMTP_REPLYTO_FROM_NAME - Name for client side replies

GLOBAL_SMTP_AUTH_TYPE - Can be 'LOGIN', 'PLAIN', 'NTLM' (defaults to LOGIN)

Debugging
---------

When WP_DEBUG is enabled, this plugin will perform a strict validation check on all the settings. It will provide error warnings if you've done something wrong.

You can turn on SMTP debug by including this statement:

`define('GLOBAL_SMTP_DEBUG',true);`

Here's a nice plugin that will send a quick test email from your dashboard if you end up needing to to extensive testing.
http://wordpress.org/plugins/check-email/

Have any ideas for improvement? Pull requests are welcome! :)

-Alexander Rohmann