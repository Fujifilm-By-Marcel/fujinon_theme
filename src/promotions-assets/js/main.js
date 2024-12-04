jQuery(document).ready(function() {


	// Set up swiper JS for the product images for each product card
	const allProductImageSliders = document.querySelectorAll('.product-images');

	allProductImageSliders.forEach((slider, i) => {


		// Add a number class I can hook into below for each card
		slider.classList.add( `product-images-${i + 1}` )


		// Init new Swiper instance for each card with an onChange
		// event listener to update the colour name and custom pagination
		// with the colours to view...
		var swiper = new Swiper(`.product-images-${i + 1}` , {
			slidesPerView: 1,
			on: {
				slideChange: function (event) {
					const currentSwatchTitle = event.el.querySelector( '.current-swatch-title' );

					currentSwatchTitle.innerText = event.el.querySelector( `.product-image-${event.activeIndex + 1}` ).getAttribute( "data-label" )
				}
			},
			pagination: {
				el: ".swatches",
				clickable: true,
				renderBullet: function (index, className) {
					const dataHex = document.querySelector( `.product-images-${i + 1} .product-image-${index + 1}` ).getAttribute( "data-hex" )

					if ( ! dataHex ) {
						return `<span class="image-swatch disabled ${className}"></span>`;

					}

					return `<span class="image-swatch ${className}" style="background-color: ${ document.querySelector( `.product-images-${i + 1} .product-image-${index + 1}` ).getAttribute( "data-hex" )};"></span>`;
				},
			},
		});



		// Set the default (first) colour title for each card...
		const swatchTitle = slider.querySelector('.current-swatch-title');
		const currentSlide = slider.querySelector('.swiper-slide-active');

		swatchTitle.innerText = currentSlide.getAttribute( 'data-label' );
	});



	// Same height boxes
	jQuery.fn.sameheight = function() {

		var maxHeight = 0;
		var $this = jQuery(this);

		maxHeight = 0;
		$this.find('.same-height').each( function() {
			// reset the height
			jQuery(this).css({'height':'auto'});
			var thisHeight = jQuery(this).height();
			if ( thisHeight > maxHeight ) {
				maxHeight = thisHeight;
			}
		});
		$this.find('.same-height').height(maxHeight);


		maxHeight = 0;
		$this.find('.same-height2').each( function() {

			// reset the height
			jQuery(this).css({'height':'auto'});
			var thisHeight = jQuery(this).height();
			if ( thisHeight > maxHeight ) {
				maxHeight = thisHeight;
			}
		});
		$this.find('.same-height2').height(maxHeight);


		maxHeight = 0;
		$this.find('.same-height3').each( function() {

			// reset the height
			jQuery(this).css({'height':'auto'});
			var thisHeight = jQuery(this).height();
			if ( thisHeight > maxHeight ) {
				maxHeight = thisHeight;
			}
		});
		$this.find('.same-height3').height(maxHeight);


		maxHeight = 0;
		$this.find('.same-height4').each( function() {

			// reset the height
			jQuery(this).css({'height':'auto'});
			var thisHeight = jQuery(this).height();
			if ( thisHeight > maxHeight ) {
				maxHeight = thisHeight;
			}
		});
		$this.find('.same-height4').height(maxHeight);



		$this.find('.same-min-height').each( function() {
			// reset the height
			jQuery(this).css({'height':'auto'});
			var thisHeight = jQuery(this).height();
			if ( thisHeight > maxHeight ) {
				maxHeight = thisHeight;
			}
		});
		$this.find('.same-min-height').css('min-height', maxHeight+'px');

	};

	var width = jQuery(window).width();



	jQuery('.same-height-all-group').each( function() {
		jQuery(this).sameheight();
	});



	if ( width > 688 ) {

		jQuery('.same-height-group').each( function() {
			jQuery(this).sameheight();
		});

	}



	jQuery( window ).on( 'resize', function() {

		var width = jQuery(window).width();

		setTimeout(function() {
			jQuery( '.same-height-all-group' ).each( function () {
				jQuery( this ).sameheight();
			} );
		}, 200 )

		//
		// if ( width > 688 ) {
		//
		// 	setTimeout(function() {
		// 		jQuery('.same-height-group').each( function() {
		// 			jQuery(this).sameheight();
		// 		});
		// 	}, 200);
		// }





	}).trigger('resize');



});


jQuery(window).on("load", function() {
	const productGrids = document.querySelectorAll( '.product-grid' );


	productGrids.forEach((grid, i) => {
		console.log( {i})
		const selector = `.product-grid-${i + 1}`;


		console.log({selector})

		const options = {

			animationDuration: 0.3, // in seconds
			callbacks: {
				onFilteringStart: function() { },
				onFilteringEnd: function() { },
				onShufflingStart: function() { },
				onShufflingEnd: function() { },
				onSortingStart: function() { },
				onSortingEnd: function() { }
			},
			controlsSelector: `.secondary-tabs-${i + 1} li`, // Selector for custom controls
			delay: 0, // Transition delay in ms
			delayMode: 'progressive', // 'progressive' or 'alternate'
			easing: 'ease-in-out',
			filter: 'all', // Initial filter
			filterOutCss: {
				opacity: 0,
				transform: 'scale(0.5)'
			},
			filterInCss: {
				opacity: 1,
				transform: 'scale(1)'
			},
			gridItemsSelector: `.filtr-item-${i + 1}`,
			gutterPixels: 0, // Items spacing in pixels
			layout: 'sameWidth', // See layouts
			multifilterLogicalOperator: 'or',
			searchTerm: '',
			setupControls: true, // Should be false if controlsSelector is set
			spinner: { // Configuration for built-in spinner
				enabled: true,
				fillColor: '#01916D',
				styles: {
					height: '75px',
					margin: '0 auto',
					width: '75px',
					'z-index': 2,
				},
			},
		}





		const filterizr = new Filterizr( selector, options );
	});




});