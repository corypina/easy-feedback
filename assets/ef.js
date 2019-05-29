/* Toggles the front-end form */

function efToggle() {
	event.preventDefault();
	if (jQuery("#ef-form").is(':hidden')) {
		jQuery("#ef-form").slideDown( 100 );
		jQuery("html, body").animate({scrollTop: jQuery("#ef-form").offset().top - 85 });
	} else {
		jQuery("#ef-form").slideUp( 300 );
		jQuery("html, body").animate({scrollTop: jQuery("#ef-toggle").offset().top - 85 });
	 }
}

/* Process the front-end form */

jQuery(document).ready(function($) {
	
	$('#ef-form-proper').on('submit', function(e) {
		e.preventDefault();

		$('#ef-form-submit').text('...').prop('disabled', true);

		var $form = $(this);

		$.post($form.attr('action'), $form.serialize(), function(response) {
			$('.ef-toggle').hide();
			$("html, body").animate({scrollTop: $("#ef-form").offset().top - 85 });
			$('#ef-form-proper').slideUp( 200 );
			$('#ef-result').html( response.data ).delay( 300 ).slideDown( 200 );
			
		}, 'json');
		
	});
 
});
