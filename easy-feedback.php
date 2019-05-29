<?php
/*
Plugin Name: Easy Feedback
Description: Adds a feeback form to the bottom of every page on your site.
Version: 1.0.1
Author: Cory Piña
*/

// Include Settings
include( plugin_dir_path( __FILE__ ) . 'ef-settings.php');

class EF_EasyFeedback {

	public function __construct() {
		add_filter( 'the_content', array( $this, 'render_frontend_form' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_ajax_ef_send', array( $this, 'send_feedback') );
		add_action( 'wp_ajax_nopriv_ef_send',array( $this, 'send_feedback') );
	}

	// Render the frontend form
	public function render_frontend_form( $content ) {
		if ( is_page() && !is_archive() ) :
			ob_start();
			require_once('ef-front-end-form.php'); // form content
			$form = ob_get_clean();
			$content = $content . $form;
		endif;
		return $content;
	}

	// Enqueue the scripts
	public function scripts() {
		wp_enqueue_script( 'easy-feedback', plugin_dir_url( __FILE__ ) . '/assets/ef.js', array('jquery'), '1.1', false );
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_style( 'easy-feedback', plugin_dir_url( __FILE__ ) . 'assets/ef-style.css', '', '1.0.2' );
	}

	// Enqueue the admin scripts
	public function admin_scripts() {
		$screen = get_current_screen();
		if ( $screen->id == 'settings_page_easy_feedback' ) :
			wp_enqueue_style( 'easy-feedback-admin', plugin_dir_url( __FILE__ ) . 'assets/ef-admin.css', '', '1.2' );
		endif;

	}

	// Process form submissions
	function send_feedback() {

		// Check nonce
		if ( ! wp_verify_nonce( $_POST['ef_send_nonce'], 'ef_send_form' ) ) die();

		// variables from the form
		$doing = sanitize_text_field($_POST['ef-what-doing']);
		$wrong = sanitize_text_field($_POST['ef-what-wrong']);
		$email = sanitize_text_field($_POST['ef-email']);
		$link = $_POST['ef-page'];
		
		// ** For future addition **
		// establish timezone to include relevant
		// date/time in the notification email
		// date_default_timezone_set('America/New_York');

		// Get Options
		$ef_settings = new EF_Settings;
		$opt = $ef_settings->options;
		
		// Setup the email
		$headers[] = 'Content-Type: text/html; charset=UTF-8';

		$notify_email = $opt['ef_email_notification'] !== '' ? 
		$opt['ef_email_notification'] : get_bloginfo('admin_email');

		$subject = '[User Report] ' . $link;
		$msg = "<strong>Date:</strong>  " . date('m-d-Y') . "<br/>"
			. "<strong>User was on:</strong> " . $link . "<br/><br/>"
			. "<strong>What were you doing?</strong>" . "<br/>"
			. $doing . "<br/><br/>"
			. "<strong>What went wrong?</strong>" . "<br/>"
			. $wrong . "<br/><br/>"
			. "<strong>Email:</strong> ";
		if ( $email !== '' ) :
			$msg .= $email;
		else:
			$msg .= 'Not provided';
		endif;
		
		// Send email
		$mail_success = wp_mail( $notify_email, $subject, $msg, $headers );

		if ( ! $mail_success ) :
			wp_send_json_error('Sorry. Something went wrong with the form submission. You can send feedback to ' . $notify_email . '.');
		endif;
		
		wp_send_json_success('Thank you!');
	}

}
new EF_EasyFeedback();