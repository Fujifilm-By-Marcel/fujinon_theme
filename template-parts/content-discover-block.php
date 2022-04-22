<?php if( get_field("discover_block", "option")['content'] != "" ){ 
	if(get_field("discover_block", "option")['background']){
		$background = "background:url(".get_field("discover_block", "option")['background'].");";
	} else {
		$background = "";
	}
	?>
<section class="discover-block standard-spacing-margin" >
	<div class='container' style="background:#acacac;<?php echo $background ?>;" style="max-width:90em;">
		<div style="max-width:<?php echo get_field('discover_block', "option")['max_width']; ?>;margin:auto;">
			<?php echo get_field("discover_block", "option")['content'] ?>
		</div>
	</div>
</section>
<?php }