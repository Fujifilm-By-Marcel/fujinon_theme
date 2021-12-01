(function($) {
	$('.modal .close').click(function(){
		$(this).closest('.modal').hide();
	});
	$('.modal').click(function(event){
		if ( $(event.target).hasClass('modal') ) {
			$(this).hide();
		}		
	});
	$('.open-newsletter-modal').click(function(){
		$('.newsletter-modal').show();
		return false;
	});
})( jQuery );