<?php
// The form

// Get options
$ef_settings = new EF_Settings;
$opt = $ef_settings->options;

$link = (empty( $opt['ef_form_link'])) ? 
$ef_settings::LINK : $opt['ef_form_link'];

$headline = ( empty($opt['ef_form_headline']) ) ? 
$ef_settings::HEADLINE : $opt['ef_form_headline'];

$desc = ( empty($opt['ef_form_description']) ) ? 
$ef_settings::DESC : $opt['ef_form_description'];
?>

<a href="" id="ef-toggle" class="ef-toggle" onclick="efToggle()"><?php echo $link; ?></a>
<div id="ef-form" style="display:none;">
	
	<form id="ef-form-proper" name="ef-form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
		<?php wp_nonce_field( 'ef_send_form', 'ef_send_nonce' ); ?>
		<input type="hidden" name="action" value="ef_send" >
		<input type="hidden" name="ef-page" value="<?php the_permalink(); ?>" >
		<h3><?php echo $headline; ?></h3>
		<p><?php echo $desc; ?></p>
		
		<div class="ef-input">
			<label for="ef-what-doing">What you were doing</label>
			<input type="text" name="ef-what-doing" />
		</div>

		<div class="ef-input">
			<label for="ef-what-wrong">What went wrong</label>
			<input type="text" name="ef-what-wrong" />
		</div>

		<div class="ef-input">
			<label for="ef-email">Your email address</label>
			<input type="text" name="ef-email" />
			<p id="ef-optional">Your email address is optional.</p>
		</div>
		
		<div class="ef-input">
			<button id="ef-form-submit" type="submit">Send</button>
		</div>
		
		<div class="ef-close">
			<a href="" onclick="efToggle()">Close</a>
		</div>
	</form>

	<div id="ef-result">
	</div>
	
</div>