<?php 
get_header();
set_query_var( 'hero-classes', 'standard-spacing-margin' );
get_template_part( 'template-parts/content', 'hero-mini' ); 
?>

<section class="faq-header-block">
	<div class="container">
		<div class="header-block">
			<?php the_field('header_block') ?>
		</div>
	</div>
</section>

<section class="faq-search reduced-spacing-margin">
	<div class="container">
		<div class="input-container"><input type="text" id="search" placeholder="what can we help you with?"><i style="display:none;" class="fa fa-times clear-button" aria-hidden="true"></i></div>
	</div>
</section>

<?php 
$faq = get_field('faq');
?>
<section class="faq reduced-spacing-margin">
	<div class="container">
		<div class="faq-navigation">
			<div class="split">
				<?php $i=0;foreach($faq as  $value) {  ?>
				<div class="underline nav-item <?php echo !$i?"active":""; ?>">				
					<a class="category-switcher" href="#" data-index="<?php echo $i; ?>"><h3><span><?php echo $value['title'] ?></span></h3></a>				
				</div>	
				<?php $i++;} ?>
			</div>
		</div>
		<?php $i=0;foreach($faq as  $value) {  ?>
		<div class="faq-category" data-index="<?php echo $i; ?>" style="<?php echo $i?"display:none":""; ?>">
			<?php $j=0;foreach($value['section'] as  $value) {  ?>
				<div class="faq-section result <?php echo !$j?"active":""; ?>" style="<?php echo $j>=5?"display:none;":""; ?>">
					<div>						
						<h4 class="section-title underline"><?php echo $value['title']; ?></h4>
						<div class="qa-section " style="<?php echo $j?"display:none":""; ?>">
						<?php $k=0;foreach($value['qa'] as  $value) {  ?>
							<div class="qa" >
								<p class="q"><?php echo $value['q']; ?><i class='fas fa-caret-right' style="<?php echo !$k?"display:none":""; ?>"></i><i class='fas fa-caret-down' style="<?php echo $k?"display:none":""; ?>"></i></p>
								<p class="a" style="<?php echo $k?"display:none":""; ?>"><span><?php echo $value['a']; ?></span></p>
							</div>
						<?php $k++;} ?>
						</div>
					</div>
				</div>
			<?php $j++;} ?>	
			<div class="load-more-button" style="<?php echo $j<=5?"display:none;":""; ?>"><a href="#" class="button">Load More</a></div>			
			<p class="no-results" style="text-align: center;display:none;margin:3rem 0;">No results.</p>
		</div>
		<?php $i++;} ?>
	</div>

</section>



<script>
(function($) {
	$(".faq .category-switcher").click(function (){
		var index = $(this).data('index');
		$(".category-switcher").parent().removeClass('active');
		$(this).parent().addClass('active');
		$(".faq-category").hide();
		$(".faq-category[data-index="+index+"]").show();		
		return false;
	});
	$(".faq .section-title").click(function (){
		var faqSection = $(this).closest('.faq-section');
		var qaSection = $(this).siblings('.qa-section');
		if (faqSection.is('.active')){
			faqSection.removeClass('active');	
			qaSection.hide();
		} else {
			faqSection.addClass('active');			
			qaSection.show();
		}		
		return false;
	});
	$(".faq .q").click(function (){
		var rightcaret = $(this).children('.fa-caret-right');
		var downcaret = $(this).children('.fa-caret-down');
		var a = $(this).siblings('.a');
		if (downcaret.is(':visible')){
			downcaret.hide();	
			rightcaret.show();
			a.hide();
		} else {
			downcaret.show();	
			rightcaret.hide();
			a.show();
		}		
		return false;
	});	
	$(".faq .load-more-button .button").click(function (){
		var faqsections = $(this).closest(".faq-category").find(".faq-section.result");
		var visiblesections = faqsections.not(":not(:visible)").length;		
		var totalsections = faqsections.length;
		for(i=0;i<faqsections.length;i++){
			if(visiblesections <= visiblesections+5 && i<visiblesections+5){
				$(faqsections[i]).show();				
				iterated = true;
			}			
		}		
		visiblesections+=5;		
		if(visiblesections >= totalsections){
			$(this).closest('.load-more-button').hide();
		}
		return false;
	});
	function resetVisibleSections(){
		var categories = $(".faq .faq-category");		
		for (i=0;i<categories.length;i++){
			var loadmorebutton = $(categories[i]).children('.load-more-button');
			var results = $(categories[i]).children('.faq-section.result');						
			if(results.length>5){
				loadmorebutton.show();				
			} else {
				loadmorebutton.hide();
			}

			for (j=0;j<results.length;j++){
				if(j>=5){
					$(results[j]).hide();
				}
			}

			console.log(results.length);
			if(results.length == 0){
				$(categories[i]).children('.no-results').show();
			} else {
				$(categories[i]).children('.no-results').hide();
			}
			
		}

	}

	$('#search').on('input', function() {
		var filter = $( this ).val().toUpperCase();
		var faqsections = $(".faq .faq-section");		
		var clearbutton = $(".faq-search .clear-button")
		if(filter.length){
			clearbutton.show();
		} else {
			clearbutton.hide();
		}
		for(i=0;i<faqsections.length;i++){
			var q = $(faqsections[i]).find('.q').text().toUpperCase();
			var a = $(faqsections[i]).find('.a').text().toUpperCase();
			var title = $(faqsections[i]).find('.section-title').text().toUpperCase();
			if( q.includes(filter) || a.includes(filter) || title.includes(filter) ){
				$(faqsections[i]).show();
				$(faqsections[i]).addClass('result');
			} else {
				$(faqsections[i]).hide();
				$(faqsections[i]).removeClass('result');
			}			
		}
		resetVisibleSections();
	});
	$(".faq-search").on('click', '.clear-button', function () {
		console.log("exec");
		$(this).siblings("#search").val('').trigger("input");
		$(this).hide();
		return false;
	});
})( jQuery );
</script>
<?php
get_template_part( 'template-parts/content', 'discover-block' );
get_sidebar();
get_footer();