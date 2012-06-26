(function ($) {
	var init = function () {
		$( "#pluginconfigure input[type='checkbox']" ).each( function() {
			var fs = $(this).parent().next();
			// First disable and hide the fieldset for any intergrations not enabled.
			if ( $(this).is(":checked" ) ) {
				fs.prop( "disabled", false );
				fs.removeClass( "hidden" );				
			} else {
				fs.prop( "disabled", true );
				fs.addClass( "hidden" );	
			}
			
			// Now toggle the displaying and hiding of the fieldsets based on the checkbox state
			$(this).click(function() {
				fs.prop( "disabled", !$(this).prop( "checked" ) );
				fs.toggleClass( "hidden" );
			});
		});
		
	}
	$(init);
})(jQuery);