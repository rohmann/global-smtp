<?php 
/*
Plugin Name: Multisite SMTP
Plugin URI: https://github.com/webtekk/wp-multisite-smtp
Description: Allows for the setup of SMTP mail via constants in wp-config.php
Author: Alexander Rohmann
Author URI: https://github.com/webtekk/
Version: 1.0
*/

//Validate settings before hooking phpmailer
if(global_setup_network_smtp_validate() ) {
    add_action('phpmailer_init','global_setup_network_smtp');
}

//Configure phpmailer using our constants
function global_setup_network_smtp($phpmailer) {

    //debug?
    if(defined('GLOBAL_SMTP_DEBUG') && GLOBAL_SMTP_DEBUG ) $phpmailer->SMTPDebug = true;

    //preset
    $phpmailer->Mailer = "smtp";
    $phpmailer->SMTPAuth = true;
    
    //required
    $phpmailer->Host = GLOBAL_SMTP_HOST;
    $phpmailer->Username = GLOBAL_SMTP_USER;
    $phpmailer->Password = GLOBAL_SMTP_PASSWORD;
    
    //assumed
    $phpmailer->From = GLOBAL_SMTP_FROM;
    $phpmailer->FromName = GLOBAL_SMTP_FROM_NAME;
    $phpmailer->Port = GLOBAL_SMTP_PORT;
    $phpmailer->SMTPSecure = GLOBAL_SMTP_SECURE; 
    $phpmailer->AuthType = GLOBAL_SMTP_AUTH_TYPE;

    //Optional
    $phpmailer->Sender = defined('GLOBAL_SMTP_RETURN_PATH') ? GLOBAL_SMTP_RETURN_PATH : GLOBAL_SMTP_FROM;

    if(defined('GLOBAL_SMTP_REPLYTO_FROM')) {
        $phpmailer->AddReplyTo(GLOBAL_SMTP_REPLYTO_FROM, defined('GLOBAL_SMTP_REPLYTO_FROM_NAME') ? GLOBAL_SMTP_REPLYTO_FROM_NAME : GLOBAL_SMTP_FROM_NAME);    
    }
    
}

//If WP_DEBUG is enabled, this will perform strict validation and output debug messages otherwise this always returns true after assuming defaults
function global_setup_network_smtp_validate() {

    //Assume any undefined settings
    $assume = array('GLOBAL_SMTP_PORT'=> 465,
                        'GLOBAL_SMTP_SECURE' => 'ssl',
                        'GLOBAL_SMTP_TIMEOUT' => 10,
                        'GLOBAL_SMTP_FROM' => get_site_option('admin_email','',true),
                        'GLOBAL_SMTP_FROM_NAME' => get_site_option('site_name','',true),
                        'GLOBAL_SMTP_AUTH_TYPE' => 'LOGIN');

    foreach ($assume as $setting => $default) {
        if(!defined($setting)) {
            define($setting, $default);
        }
    }
    
    if(defined('WP_DEBUG') && WP_DEBUG ) {
    
        $error = false;
        
        //classify validations
        $required = array('GLOBAL_SMTP_HOST','GLOBAL_SMTP_USER','GLOBAL_SMTP_PASSWORD');
        $is_email = array('GLOBAL_SMTP_RETURN_PATH','GLOBAL_SMTP_FROM','GLOBAL_SMTP_REPLYTO_FROM');
        $not_empty = array('GLOBAL_SMTP_FROM','GLOBAL_SMTP_FROM_NAME');
        $is_int = array('GLOBAL_SMTP_PORT','GLOBAL_SMTP_TIMEOUT');
        $should_be = array('GLOBAL_SMTP_SECURE' => array('ssl','tls','none'),
            'GLOBAL_SMTP_AUTH_TYPE' => array('LOGIN','PLAIN','NTLM') );

        //Run Validations
        foreach ($required as $setting) {
            if(!defined($setting)) {
                trigger_error($setting." is required for Multisite SMTP. Please define this in wp-config.php", E_USER_WARNING);
                $error=true;
            }
        }

        foreach ($is_email as $email) {
            if (defined($email) && !is_email(constant($email))) {
                trigger_error("Value of ".$email." is not a valid email address. Check wp-config.php, or ensure a valid fallback is available.", E_USER_WARNING);
                $error=true;
            }
        }

        foreach ($not_empty as $key) {
            if(defined($key) && constant($key)=="") {
                trigger_error($key." is empty. Check wp-config.php, or ensure a valid fallback is available.", E_USER_WARNING);
                $error=true;
            }
        }

        foreach ($is_int as $key) {
            if(defined($key) && !is_int(constant($key)) ) {
                trigger_error($key." should be an integer. The force is not so strong with this one...", E_USER_WARNING);
                $error=true;
            }
        }

        foreach ($should_be as $key => $allowed) {
            if(defined($key) && !in_array(constant($key), $allowed)) {
                trigger_error($key." is invalid. It should be one of these values: '".implode("' , '",$allowed)."'", E_USER_WARNING);
                $error=true;
            }
        }

        if($error) return false;
    }

    return true;
}
