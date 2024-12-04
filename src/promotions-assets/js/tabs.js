jQuery( document ).ready( function () {

	jQuery( ".defaultOpen" ).each( function () {
		jQuery( this ).trigger( 'click' );
	} );

});



function openTab( tab, tabName ) {
	// Declare all variables
	var i, tabcontent, tablinks;

	// Get all elements with class="tabcontent" and hide them
	tabcontent = document.getElementsByClassName( "tabcontent" );
	for ( i = 0; i < tabcontent.length; i++ ) {
		tabcontent[i].style.display = "none";
	}

	// Get all elements with class="tablinks" and remove the class "active"
	tablinks = document.getElementsByClassName( "tablinks" );
	tabs = document.getElementsByClassName( "tabcontent" );
	for ( i = 0; i < tablinks.length; i++ ) {
		tablinks[i].className = tablinks[i].className.replace( " active", "" );
	}

	// Show the current tab, and add an "active" class to the button that opened the tab

	jQuery( tab ).addClass( "active" );
	if ( tabName == "show-all" ) {
		for ( i = 0; i < tabs.length; i++ ) {
			tabs[i].style.display = "block";
		}
	} else {
		document.getElementById( tabName ).style.display = "block";
	}


}

function filterTab( filter, filterName, tabName ) {

	jQuery( filter ).parent().find( ".tabfilters" ).removeClass( "active" );
	jQuery( filter ).addClass( "active" );


	var tab = jQuery( "#" + tabName );

	if ( filterName == "show-all" ) {
		tab.find( ".deal" ).parent().show();
	} else {
		tab.find( ".deal" ).parent().hide();
		tab.find( "." + filterName ).parent().show();
	}

}

function toggleActiveClass( filter ) {

	jQuery( filter ).parent().find( ".tabfilters" ).removeClass( "active" );
	jQuery( filter ).addClass( "active" );

}