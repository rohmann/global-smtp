Global SMTP
=================

Global SMTP is a utility plugin designed to easily configure SMTP for WordPress by adding a set of constants to your `wp-config.php` file.

When used with multisite, the configuration is applied network wide. This is often handy to install as a "must use" plugin.

Getting started
---------------
First install as a WordPress pluing. Optionally, you could install as a "must use" plugin by placing `smtp.php` in `wp-content/mu-plugins`.

Once the plugin is installing installed, add the constants to `wp-config.php`

The minimum requirement is setting the host, username, and password. Everything else will be assumed from some defaults.
```
define('GLOBAL_SMTP_HOST','mail.example.com');
define('GLOBAL_SMTP_USER','admin@example.com');
define('GLOBAL_SMTP_PASSWORD','password');
```

Assumed defaults:
* From and From Name: WordPress defaults (or possibly overriden by your mail server)
* Port -> 587
* Secure -> tls

You can specify your own with these statements:
```
define('GLOBAL_SMTP_FROM','you@example.com');
define('GLOBAL_SMTP_FROM_NAME','Your Name');
define('GLOBAL_SMTP_PORT',465); // use SSL
define('GLOBAL_SMTP_SECURE;','ssl');
```

You can also specify some other values for the SMTP mailer:

`GLOBAL_SMTP_RETURN_PATH` - Bounce address
`GLOBAL_SMTP_REPLYTO_FROM` - Email address for client side replies
`GLOBAL_SMTP_REPLYTO_FROM_NAME` - Name for client side replies

`GLOBAL_SMTP_AUTH_TYPE` - Can be `'LOGIN'`, `'PLAIN'`, `'NTLM'` (defaults to `'LOGIN'`)

Environment Specific Settings
-----------------------------
Because all the configuration happens via PHP constants, you can have different configurations depending on what kind of server environment is being used. For example, you could set up staging email addresses, and have your staging environment isolated from the production environment.

`define('GLOBAL_SMTP_DISABLE',true);`

That will prevent Global SMTP from initializing at all; quite useful for development environments.

Debugging
---------

This plugin will trigger warnings if you've done something wrong. Just be sure to have [WP_DEBUG](http://codex.wordpress.org/Debugging_in_WordPress#WP_DEBUG "Title") enabled.

You can turn on SMTP debug by including this statement:

`define('GLOBAL_SMTP_DEBUG',true);`

This will display debug output from the PHP Mailer class when combined with the [http://wordpress.org/plugins/check-email/](http://wordpress.org/plugins/check-email/ "Check Email") plugin. This allows you to send a test message and troubleshoot any connectivity problems.

Have any ideas for improvement? Pull requests are welcome! :)
