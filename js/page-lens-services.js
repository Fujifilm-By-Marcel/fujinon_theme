(function($) {
	$('.map-button .button').click(function(){
		console.log("A");
		$(this).closest(".content-wrap").find('.map-modal').show();
		return false;
	});
	$('.map-modal .close').click(function(){
		console.log("B");
		$(this).closest('.map-modal').hide();
	});
})( jQuery );