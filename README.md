Multisite / Global SMTP
=================
**Note: Everything here will work on single site installs as well. It was just orginally purposed for quickly setting up Multisite installs**

This plugin allows you to configure SMTP mail on Wordpress with nothing more than wp-config.php constants.

It's easy to automate this configuration for new site installs with the use of WP-CLI: [https://github.com/wp-cli/wp-cli](https://github.com/wp-cli/wp-cli)

Getting started
---------------
First install the plugin. This can be done by uploading the `wp-multisite-smtp` folder to `wp-content/plugins` and network activating it. You could place smtp.php in wp-content/mu-plugins (my preference)

Once installed, add the constants to wp-config.php

The minimum requirement is setting the host, username, and password. Everything else will be assumed from some defaults.
```
define('GLOBAL_SMTP_HOST','mail.example.com');
define('GLOBAL_SMTP_USER','admin@example.com');
define('GLOBAL_SMTP_PASSWORD','password');
```

Assumed defaults:
From -> Network admin email address
FromName -> Network Name
Port -> 465
Secure -> ssl

You can specifu your own with these statements:
```
define('GLOBAL_SMTP_FROM','you@example.com');
define('GLOBAL_SMTP_FROM_NAME','Your Name');
define('GLOBAL_SMTP_PORT',587);
define('GLOBAL_SMTP_SECURE;','tls');
```

You can also specify some other values for the SMTP mailer:

GLOBAL_SMTP_RETURN_PATH - Bounce address
GLOBAL_SMTP_REPLYTO_FROM - Email address for client side replies
GLOBAL_SMTP_REPLYTO_FROM_NAME - Name for client side replies

GLOBAL_SMTP_AUTH_TYPE - Can be 'LOGIN', 'PLAIN', 'NTLM' (defaults to LOGIN)

Environment Specific Settings
-----------------------------
Because all the configuration happens via PHP constants, you can have different configurations depending on what kind of server environment is being used. For example, you could set up staging email addresses, and have your staging environment isolated from the production environment.

### MailTrap

Multisite SMTP is also useful for quickly configuring a service like **[MailTrap](https://mailtrap.io/ "MailTrap")** Just set things up as usual in your staging and development environments. Then you can disable the plugin entirely in production by adding this.

`define('GLOBAL_SMTP_DISABLE',true);`

That will prevent Multisite SMTP from initializing, allowing your prefered service to be used in Production

Here's a sample configuration for using MailTrap in development or staging environments.

```
define('GLOBAL_SMTP_HOST','mailtrap.io');
define('GLOBAL_SMTP_USER','yourusername');
define('GLOBAL_SMTP_PASSWORD','imasecret');
define('GLOBAL_SMTP_SECURE', 'tls');
```

Debugging
---------

This plugin will trigger warnings if you've done something wrong. Just be sure to have [WP_DEBUG](http://codex.wordpress.org/Debugging_in_WordPress#WP_DEBUG "Title") enabled. 

You can turn on SMTP debug by including this statement:

`define('GLOBAL_SMTP_DEBUG',true);`

Here's a nice plugin that will send a quick test email from your dashboard if you end up needing to to extensive testing.
[http://wordpress.org/plugins/check-email/](http://wordpress.org/plugins/check-email/ "Check Email")

Have any ideas for improvement? Pull requests are welcome! :)
