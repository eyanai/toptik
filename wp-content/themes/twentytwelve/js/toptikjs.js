jQuery(document).ready(function(e) {
	
	jQuery('.gotop').on('click',this,function(){
		
		jQuery('html, body').animate({
				scrollTop: jQuery('body').offset().top
			 }, 1000);
	});
	
	
	jQuery('.cart-contents').on('click',this,function(e){
		jQuery('#minicart').slideToggle(300);
		e.preventDefault();
	});
	
});