<?php
get_header();
set_query_var( 'hero-classes', 'standard-spacing-margin' );
get_template_part( 'template-parts/content', 'hero-mini' ); 
?>

<section class="icon-descriptions">
	<div class="container">
		<div class="split two-column-grid">
			<?php
			if( have_rows('icon_descriptions') ):
			    while( have_rows('icon_descriptions') ) : the_row(); ?>			        
			    	<div>
				        <div>
							<?php 
							the_sub_field('icon'); 
							the_sub_field('content'); 
							$tab = get_sub_field('tab');
							if( $tab['text'] != "" ){
								echo "<p><a href='#' data-slug='$tab[slug]' class='tab-button'>$tab[text]<i class='fas fa-caret-right'></i><i class='fas fa-caret-down' style='display:none;'></i></a></p>";
							}
							?>
							<div class="triangle" style="display:none;"></div>
						</div>	
					</div>
			    <?php 
			    endwhile;
			endif;
			?>
		</div>
	</div>
</section>
<section class="icon-tabs standard-spacing-margin">
	<div class="close"><i class="fal fa-times"></i></div>
	<div class="container">
	<?php 
	$tabs = get_field('tabs');
	foreach($tabs as $value){
		?>
		<div class="tab" data-slug="<?php echo $value['tab']['slug'] ?>" style="display:none;">

			<h3 class="tab-title"><?php echo $value['tab']['text'] ?></h3>
			<div class="tab-list">
			<?php 
			foreach($value['table_cell'] as $cell){	
				echo "<div>";
				echo $cell['content'];
				echo "</div>";
			}
			?>
			</div>
		</div>
		<?php
	}
	?>
	</div>
</section>
<script>
	(function($) {
		$('.icon-descriptions .tab-button').click(function(){
			var thisobject = $(this);
			var slug = thisobject.data('slug');
			var tab = $('.tab[data-slug='+slug+']');
			var thistriangle = thisobject.parent().siblings('.triangle');
			var caretright = thisobject.children('.fa-caret-right'); 
			var caretdown = thisobject.children('.fa-caret-down');
			var allcaretright = $('.icon-descriptions .fa-caret-right').not(caretright);
			var allcaretdown = $('.icon-descriptions .fa-caret-down').not(caretdown);
			var alltriangles = $('.icon-descriptions .triangle').not(thistriangle);

			if(tab.is(":visible")){
				tab.hide();
				thistriangle.hide();
				caretdown.hide();
				caretright.show();
			}
			else{
				console.log("test");				
				alltriangles.hide();
				thistriangle.show();
				caretdown.show();
				caretright.hide();				
				tab.siblings().hide();
				tab.show();
				allcaretdown.hide();
				allcaretright.show();
				return false;
			}
		});
		$('.icon-tabs .close').click(function(){
			var allcaretright = $('.icon-descriptions .fa-caret-right');
			var allcaretdown = $('.icon-descriptions .fa-caret-down');			
			var alltriangles = $('.icon-descriptions .triangle');
			$(this).closest('.icon-tabs').find('.tab').hide();
			allcaretdown.hide();
			allcaretright.show();
			alltriangles.hide();
			return false;
		});
	})( jQuery );
</script>
<?php 
get_template_part( 'template-parts/content', 'discover-block' ); 
get_sidebar();
get_footer();