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

	//Load more posts button
	$( '#more-posts-button' ).click( function( e ) {

		e.preventDefault(); 
		
		if( ! $("#more-posts-button").hasClass("lock") ) {
			ajax_next_posts(); 
		}

	});

    function ajax_next_posts() {

        $("#more-posts-button").addClass("lock",true);

        //get new page number
        var pageNumber = parseInt($("#more-posts-button").data("page"))+1;

        //get max page number
        var maxPageNumber = parseInt($("#more-posts-button").data("maxpage"));

        //get category
        var cat = $("#more-posts-button").data("cat");
        
        //Change that to your right site url unless you've already set global ajaxURL
        var ajaxURL = '//' + window.location.hostname + '/wp-admin/admin-ajax.php';

        //Parameters you want to pass to query
        var ajaxData = '&page=' + pageNumber + '&cat=' + cat + '&action=ajax_next_posts';

        //Ajax call itself
        $.ajax({

            type: 'get',
            url:  ajaxURL,
            data: ajaxData,
            dataType: 'json',

            //Ajax call is successful
            success: function ( response ) {
				console.log({response})


                //Add new posts
                $( '.posts-container' ).append( response[0] );

                //Update the page
                $("#more-posts-button").data("page", response[1] );



		        //Hide button if all posts are loaded		        
		        if( response[1] >= maxPageNumber ) {
		            $( '#more-posts-button' ).hide();
		        }

                $("#more-posts-button").removeClass("lock",false);
            },

            //Ajax call is not successful, still remove lock in order to try again
            error: function () {

                $("#more-posts-button").removeClass("lock",false);
            }
        });

    }

    function init_next_posts_button() {
	
		//init show more posts button
		//get new page number
		var pageNumber = parseInt($("#more-posts-button").data("page"));

		//get max page number
		var maxPageNumber = parseInt($("#more-posts-button").data("maxpage"));

		if( pageNumber < maxPageNumber ) {
			$("#more-posts-button").show();
		}
	}

	init_next_posts_button();

})( jQuery );