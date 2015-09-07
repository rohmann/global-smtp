=== Plugin Name ===
Contributors: alexanderrohmann
Tags: email, smtp, multisite
Requires at least: 4.1
Tested up to: 4.3
Stable tag: 1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Setup SMTP via wp-config.php.

== Description ==

Global SMTP is a utility plugin designed to easily configure SMTP for WordPress by adding a set of constants to your `wp-config.php` file.

When used with multisite, the configuration is applied network wide. This is often handy to install as a "must use" plugin.

== Installation ==

= Quick Setup =

1. Install the plugin, optionall as an mu plugin.
2. Visit 'Settings > Global SMTP Setup' in your dashboard.
3. Copy and paste the sample configuraiton to `wp-config.php` and update with your SMTP credentials.
4. (optional) Add additional statements to complete the configuration for your smtp server.

= Available Constants =

**Required**
`GLOBAL_SMTP_HOST` - The FQDN of the mail server (supplied by your SMTP provider)
`GLOBAL_SMTP_USER` - Username for accessing the mail server (most often your email address).
`GLOBAL_SMTP_PASSWORD` - Password to authenticate your email account.

**Optional**
`GLOBAL_SMTP_FROM` - Email address to use for outgoing mail. Uses WordPress defaults when not set. Many hosts will force this to be the same as the username.
`GLOBAL_SMTP_FROM_NAME` - Name set in the From header for outgoing mail. Uses WordPress defaults when not set.
`GLOBAL_SMTP_PORT` - Port number for SMTP connection. Assumed as 587 (for tls).
`GLOBAL_SMTP_SECURE` - Encryption type. Assumed to be 'tls'. Can be 'ssl', 'tls', or 'none'.
`GLOBAL_SMTP_AUTH_TYPE` - Authentication type. Defaults to 'LOGIN', can also be 'PLAIN' or 'NTLM'.

`GLOBAL_SMTP_RETURN_PATH` - Address to send bounced emails.

`GLOBAL_SMTP_REPLYTO_FROM` - Email address shown in recipients mail client when clicking "Reply"

`GLOBAL_SMTP_DISABLE` - Set to true to prevent this plugin from hooking into `wp_mail`. This is useful for environment specific configurations.

= Troubleshooting =

For troubleshooting, we recommend that you install the check email plugin:
https://wordpress.org/plugins/check-email/

If you add this statement to `wp-config.php` you will see debug output when sending a test email via that plugin.

`define('GLOBAL_SMTP_DEBUG',true);`

== Frequently Asked Questions ==

= What does this plugin do? =

It allows you to configure WordPress to send email via an SMTP account. You can use gmail for example.

= How does this plugin work? =

Global SMTP hooks into the existing PHP Mailer class in WordPress and switches it to use SMTP mode. It then provides the mailer class with SMTP credentials using values that you place in `wp-config.php`


== Changelog ==

= 1.0 =
* Initial release.
* Absorb original wp-multsite-smtp plugin, and make available as a standard plugin in additon to an mu plugin.

== Upgrade Notice ==

= 1.0 =
Initial release.
