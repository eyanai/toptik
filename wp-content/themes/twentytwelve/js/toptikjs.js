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
	
	if(jQuery('.variations_form.cart').data('product_variations')){
		var data=jQuery('.variations_form.cart').data('product_variations');
		var con='';
		var imgw=data.length*61;
		for(var i=0;i<data.length;i++){
			
			var icon=data[i].image_src;//data[i].attributes.attribute_pa_color;
			con=con+'<img src="'+icon+'" width="45px" height="45px" data-val="'+data[i].attributes.attribute_pa_color+'">';
			//'<input type="checkbox" value="'+data[i].attributes.attribute_pa_color+'">';
		}
			con="<div class='imageVer'><span class='rver'>&#8249;</span><div class='imagvarCon'><div class='varslide' style='width:"+imgw+"px'>"+con+"</div></div><span class='lver'>&#8250;</span></div>";
			jQuery('.value select').hide();
			jQuery('.value').prepend(con);
			
		jQuery('.imageVer').on('click','img',function(){
			
				valvar=jQuery(this).data('val');
				jQuery('#pa_color').val(valvar);
				jQuery('#pa_color').change();
		});	
		
		//slide of the image variatin
		
		jQuery('.lver').on('click',this,function(){
			nobe=(imgw-45);
			cPos=parseInt(jQuery('.varslide').css('right'));
			if(cPos<nobe && imgw>240){
				npos=cPos+25;
				jQuery('.varslide').css('right',npos+'px');
			}
		});
		
		jQuery('.rver').on('click',this,function(){
			nobe=-(imgw-90);
			cPos=parseInt(jQuery('.varslide').css('right'));
			if(cPos>nobe && imgw>240){
				npos=cPos-25;
				jQuery('.varslide').css('right',npos+'px');
			}
		});
	}
	
	//home gallery slide
	jQuery('.pointer').on('click',this,function(){
		jQuery('.pointer').removeClass('act');
		id=jQuery(this).data('idpoint');
		jQuery(this).addClass('act');
		
	//alert(jQuery('#'+id+'.wp-post-image').attr('alt'));
	jQuery('.wp-post-image').removeClass('actgall');
	jQuery('#'+id+'.wp-post-image').addClass('actgall');
	});
	
	
	
   cookieCach();

});

function cookieCach(){
	if(jQuery.cookie('shop_pageResults')){
		var man=jQuery.cookie('shop_pageResults');
		jQuery('#woocommerce-sort-by-columns option').each(function(index, element) {
			if(jQuery(this).val()==man){
				jQuery(this).attr('selected','selected');
			}
		});
	}
}