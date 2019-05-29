<?php

class EF_Settings {

	public $options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ef_add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'ef_settings_init' ) );
		$this->options = get_option( 'ef_settings' );
	}

	// Form defaults
	const LINK = 'Something wrong with this page?';
	const HEADLINE = 'Help us improve';
	const DESC = 'Thanks for helping us improve the site. Is there a typo? Something broken? Didn\'t find something where you expected to? Let us know. We\'ll use your feedback to improve this page, and the site overall.';

	function ef_add_admin_menu(  ) { 
		add_submenu_page( 'options-general.php', 'Easy Feedback', 'Easy Feedback', 'manage_options', 'easy_feedback', array( $this, 'ef_options_page' ) );
	}

	function ef_settings_init(  ) { 

		register_setting( 'ef_Feedback', 'ef_settings' );
	
		add_settings_section(
			'ef_pluginPage_section', 
			'', // No title for this section
			array( $this, 'ef_settings_section_callback' ), 
			'ef_Feedback'
		);
	
		add_settings_field( 
			'ef_email_notification', 
			__( 'Notification email', 'wordpress' ), 
			array( $this, 'ef_email_notification_render' ), 
			'ef_Feedback', 
			'ef_pluginPage_section' 
		);
	
		add_settings_field( 
			'ef_form_link', 
			__( 'Link text', 'wordpress' ), 
			array( $this, 'ef_form_link_render' ), 
			'ef_Feedback', 
			'ef_pluginPage_section' 
		);
	
		add_settings_field( 
			'ef_form_headline', 
			__( 'Form headline', 'wordpress' ), 
			array( $this, 'ef_form_headline_render' ), 
			'ef_Feedback', 
			'ef_pluginPage_section' 
		);
	
		add_settings_field( 
			'ef_form_description', 
			__( 'Form description', 'wordpress' ), 
			array( $this, 'ef_form_description_render' ), 
			'ef_Feedback', 
			'ef_pluginPage_section' 
		);
	}

	function ef_email_notification_render() { 
		?>
		<input type='text' name='ef_settings[ef_email_notification]' value='<?php echo $this->options['ef_email_notification']; ?>'>
		<p class="description">Email addressed used for sending submissions (<strong>Default:</strong> admin email).</p>
		<?php
	
	}

	function ef_form_link_render() { 
		?>
		<input type='text' name='ef_settings[ef_form_link]' value='<?php echo $this->options['ef_form_link']; ?>'>
		<p class="description">Customize the link to the form (<strong>Default:</strong> 'Something wrong with this page?'). </p>
		<?php
	
	}
	
	
	function ef_form_headline_render() { 
		?>
		<input type='text' name='ef_settings[ef_form_headline]' value='<?php echo $this->options['ef_form_headline']; ?>'>
		<p class="description">Customize the form's headline (<strong>Default:</strong> 'Help us improve').</p>
		<?php
	}
	
	
	function ef_form_description_render() { 
		?>
		<textarea cols='40' rows='5' name='ef_settings[ef_form_description]'><?php echo $this->options['ef_form_description']; ?></textarea>
		<p class="description">Customize the form's description (<strong>Default:</strong> 'Thanks for helping us improve the site. Is there a typo? Something broken? Didn't find something where you expected to? Let us know. We'll use your feedback to improve this page, and the site overall.').</p>
		<?php
	}
	
	
	function ef_settings_section_callback() { ?>
		<div class='ef_settings_desc'>
		<?php
		echo __( "<p>The <strong>Easy Feedback</strong> form will display on every <em><strong>page</strong></em> of your site (not posts, archive pages, or other custom post-types).</p><p>Use the form below to customize the content of the form and the email used for receiving submissions.</p>", 'wordpress' ); ?>
		</div><?php
	}
	
	
	function ef_options_page() { 
		?>
		<div class="wrap">
			<h1>Easy Feedback</h1>
	
			<div id="ef_form_content">
				<form action='options.php' method='post'>

					<?php
					settings_fields( 'ef_Feedback' );
					do_settings_sections( 'ef_Feedback' );
					submit_button();
					?>
		
				</form>
			</div>

		</div>
		<?php
	
	}
	
}
new EF_Settings();



