<?php
/*
Plugin Name: Global SMTP
Plugin URI: https://github.com/rohmann/wp-global-smtp
Description: Allows for the setup of SMTP mail via constants in wp-config.php
Author: Alexander Rohmann
Author URI: https://github.com/rohmann/
Version: 2.2.0
License: GPLv2
Text Domain: global-smtp
*/

/**
* Configures WordPress PHPMailer with values from wp-config.php
* Documentation: https://github.com/rohmann/wp-global-smtp
*/
class Global_SMTP_Mailer {

	/**
	* Singleton
	*/
	protected static $instance;

	/**
	* Store validation types
	*/
	protected $validations;

	/**
	* Checks settings, and hooks into phpmailer if everything is good.
	*/
	function __construct() {

		$this->prepare_settings();
		$errors = $this->validate();
		$this->cancel = array( 'from' => false, 'from_name' => false );

		if( !empty( $errors ) ) {
			trigger_error( $errors[0]->get_error_message(), E_USER_WARNING );
		} else {
			add_action( 'phpmailer_init', array( $this, 'mailer') );
		}


		// Filter the from name and address early, allowing them to be set in a constant but also overriden by plugins.

		if ( defined( 'GLOBAL_SMTP_FROM' ) ) {
			add_filter( 'wp_mail_from', array( $this, 'get_from' ), -999 );
		}

		if ( defined( 'GLOBAL_SMTP_FROM_NAME' ) ) {
			add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ), -999 );
		}


		add_filter( 'wp_mail', array( $this, 'check_headers') );

		unset( $this->validations );

	}

	/**
	* Self Instantiate
	* Note: Plugin can be disable internally by setting GLOBAL_SMTP_DISABLE to true.
	* This is useful if you have different needs between staging and production environments.
	*/
	public static function launch() {
		self::$instance = ( defined( 'GLOBAL_SMTP_DISABLE' ) && GLOBAL_SMTP_DISABLE ) ? null : new self();
	}

	/**
	* Get this plugin's main class instance
	* @return object
	*/
	public static function instance() {
		return self::$instance;
	}

	/**
	* Prepare PHP Mailer settings. Allows for minimum configuration by asumming common defualts.
	*/
	protected function prepare_settings() {

		$this->validations = new stdClass;
		$this->validations->required  = array( 'GLOBAL_SMTP_HOST', 'GLOBAL_SMTP_USER', 'GLOBAL_SMTP_PASSWORD' );
		$this->validations->is_email  = array( 'GLOBAL_SMTP_RETURN_PATH', 'GLOBAL_SMTP_REPLYTO_FROM' );
		$this->validations->is_int    = array( 'GLOBAL_SMTP_PORT', 'GLOBAL_SMTP_TIMEOUT' );
		$this->validations->should_be = array( 'GLOBAL_SMTP_SECURE' => array( 'ssl', 'tls', 'none' ), 'GLOBAL_SMTP_AUTH_TYPE' => array( 'LOGIN', 'PLAIN', 'NTLM' ) );

		//Assume any undefined settings
		$assume = array(
			'GLOBAL_SMTP_PORT'      => 465,
			'GLOBAL_SMTP_SECURE'    => 'ssl',
			'GLOBAL_SMTP_TIMEOUT'   => 10,
			'GLOBAL_SMTP_FROM'      => '',
			'GLOBAL_SMTP_FROM_NAME' => '',
			'GLOBAL_SMTP_AUTH_TYPE' => 'LOGIN',
		);

		foreach ( $assume as $setting => $default ) {
			if( !defined( $setting ) ) {
				define( $setting, $default );
			}
		}
	}

	/**
	* Callback for wp_mail_from filter.
	* Applies the from address constant if a
	* "From" header was not present.
	* @return string from email address
	*/
	public function get_from( $from ) {
		$value = ( $this->cancel['from'] ) ? $from : GLOBAL_SMTP_FROM;
		$this->cancel['from'] = false;
		return $value;
	}

	/**
	* Callback for wp_mail_from_name filter
	* Applies the from name constant if a
	* "From" header was not present.
	* @return string from email address
	*/
	public function get_from_name( $from_name ) {
		$value = ( $this->cancel['from_name'] ) ? $from_name : GLOBAL_SMTP_FROM_NAME;
		$this->cancel['from_name'] = false;
		return $value;
	}

	/**
	* Validate Configuration to ensure things are setup correctly
	* @return bool|WP_Error Returns true if successful, else WP_Error with message
	*/
	protected function validate() {

		$errors = array();

		foreach ( $this->validations->required as $setting ) {
			if( !defined( $setting ) ) {
				$errors[] = new WP_Error( 'global-smtp', sprintf( __( '%s is required for Multisite SMTP. Please define this in wp-config.php.', 'global-smtp' ), $setting ) );
			}
		}

		foreach ( $this->validations->is_email as $setting ) {
			if ( defined( $setting ) && !is_email( constant( $setting ) ) ) {
				$errors[] = new WP_Error( 'global-smtp', sprintf( __( 'Value of %s is not a valid email address. Check wp-config.php, or ensure a valid fallback is available.', 'global-smtp' ), $setting ) );
			}
		}

		foreach ( $this->validations->is_int as $setting ) {
			if( defined( $setting ) && !is_int( constant( $setting ) ) ) {
				$errors[] = new WP_Error( 'global-smtp', sprintf( __( '%s should be a number', 'global-smtp' ), $setting ) );
			}
		}

		foreach ( $this->validations->should_be as $setting => $allowed ) {
			if( defined( $setting ) && !in_array( constant( $setting ), $allowed ) ) {
				$errors[] = new WP_Error( 'global-smtp', sprintf( __( '%s is invalid. It should be one of these values: "%s"', 'global-smtp' ), $setting, implode('" , "', $allowed ) ) );
			}
		}

		return $errors;

	}

	/**
	* Filter for `wp_mail` used for introspection
	* @param  array $atts Arguments passed into wp_mail
	* @return array       Unmodified $atts
	*/
	public function check_headers( $atts ) {

		// Detect from headers in string based headers
		if ( is_string( $atts['headers'] ) && strpos( $atts['headers'], 'From: ' ) !== false ) {
			$this->cancel['from'] = true;
			$this->cancel['from_name'] = true;
		}

		// Detect from headers in array based headers
		if ( is_array( $atts['headers'] ) ) {
			foreach ( $atts['headers'] as $header) {
				if ( is_string( $header ) && strpos( $header, 'From: ' ) !== false ) {
					$this->cancel['from'] = true;
					$this->cancel['from_name'] = true;
				}
			}
		}

		// Passthough original values unchanged.
		return $atts;

	}

	/**
	* Hook PHP Mailer to use our SMTP settings
	*/
	public function mailer( $phpmailer ) {

		// Allow debug output to be displayed
		if ( defined('GLOBAL_SMTP_DEBUG') && GLOBAL_SMTP_DEBUG && is_admin() && ( !defined('DOING_AJAX') || !DOING_AJAX ) ) {

			$phpmailer->SMTPDebug = true;

			// There's no way to close this <pre> without plugging wp_mail, which this project aims to avoid
			// It will make the PHPMailer output more readable, but only when used with: https://wordpress.org/plugins/check-email/
			if ( isset( $_GET['page'] ) && 'checkemail' == $_GET['page'] )
			  echo '<pre>';

		}

		//preset
		$phpmailer->Mailer = "smtp";
		$phpmailer->SMTPAuth = ( 'none' != GLOBAL_SMTP_SECURE );

		//required
		$phpmailer->Host = GLOBAL_SMTP_HOST;
		$phpmailer->Username = GLOBAL_SMTP_USER;
		$phpmailer->Password = GLOBAL_SMTP_PASSWORD;

		//assumed
		$phpmailer->Port = (int) GLOBAL_SMTP_PORT;
		$phpmailer->SMTPSecure = GLOBAL_SMTP_SECURE;
		$phpmailer->AuthType = GLOBAL_SMTP_AUTH_TYPE;

		//Optional
		$phpmailer->Sender = defined('GLOBAL_SMTP_RETURN_PATH') ? GLOBAL_SMTP_RETURN_PATH : $phpmailer->From;

		if ( defined('GLOBAL_SMTP_REPLYTO_FROM') ) {
			$phpmailer->AddReplyTo(GLOBAL_SMTP_REPLYTO_FROM, defined('GLOBAL_SMTP_REPLYTO_FROM_NAME') ? GLOBAL_SMTP_REPLYTO_FROM_NAME : $phpmailer->FromName );
		}

	}

}

/**
* Fire it up.
*/
Global_SMTP_Mailer::launch();
