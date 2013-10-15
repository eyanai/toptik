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
		
		//new -for regular selection
		var imgw=jQuery('.imgcolor').length;
		jQuery('.varslide').css('width',imgw+'px');
		
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
	//slide for wide icons footer
	
	jQuery('.lIco').on('mousedown',this,function(){
		ulPos=parseInt(jQuery('.brandsMenu_wide ul').css('right'));
		widAll=jQuery('.brandsMenu_wide ul').width();
		conW=jQuery(".brandsMenu_wide").width();
		cPos=parseInt(jQuery('.brandsMenu_wide ul').css('right'));
		nobe=-(widAll);
		//alert(cPos+'<-->'+nobe);
			if(cPos-conW>nobe){
				npos=cPos-187.4;
				jQuery('.brandsMenu_wide ul').css('right',npos+'px');
			}else{
				return false
			}
			
	});
	
	jQuery('.rIco').on('mousedown',this,function(){
		ulPos=parseInt(jQuery('.brandsMenu_wide ul').css('right'));
		widAll=jQuery('.brandsMenu_wide ul').width();
		conW=jQuery(".brandsMenu_wide").width();
		cPos=parseInt(jQuery('.brandsMenu_wide ul').css('right'));
		nobe=-(widAll);
			if(cPos<=0){
				npos=cPos+187.4;
				jQuery('.brandsMenu_wide ul').css('right',npos+'px');
			}else{
				return false;
			}
			
	});
	
	//home gallery slide
	jQuery('.pointer').on('click',this,function(){
		jQuery('.pointer').removeClass('act');
		id=jQuery(this).data('idpoint');
		jQuery(this).addClass('act');
		
	//alert(jQuery('#'+id+'.wp-post-image').attr('alt'));
	jQuery('.wp-post-image').removeClass('actgall');
	jQuery('#'+id+'.wp-post-image').addClass('actgall');
	});
	
	
	//shiping and more
	jQuery('body').on('updated_shipping_method',function(){
		//alert('poo');
		sval=jQuery('#shipping_method option:selected').data('sval');
		if(sval!=0){
			jQuery('#shipnam').text(sval + 'ש"ח');
			jQuery('.shipVal').show();	
			}else{
			jQuery('.shipVal').hide();
		}
		saveingAndMore();
	});
	
	
	jQuery('.lock').on('click',this,function(){
		popUp('.premitionPop','.cPop');	
		return  false;
	});	
	jQuery('.members').on('click',this,function(){
		popUp('.loginpop','.cPop');	
		return  false;
	});
	//shipVal
   cookieCach();
	saveingAndMore();
	setWW();

	jQuery('#shipping_company,#billing_company').attr('type','date');
	
	
	jQuery('.submit_btb_paypal').on('click',function(){
		//what to do... when paypal btn clicked
		jQuery.post('cahck.php', function(data) {
				  $('.result').html(data);
				});
	});
	
	
	jQuery('.addFav').on('click',this,function(){
		add_favorite();
	});
	
	jQuery('.printF').on('click',this,function(){
		window.print();
	});


	ecolorSelection();
	//jQuery('#shipping_company').datepicker();
	
	
	personalNav();
	
	jQuery('.toptikviwebtm').on('click',function(){
			order=jQuery(this).data('singel');
			//alert(order);
			if(jQuery(this).hasClass('print')){
				window.print() ;
			}else{
				mysingelorder(order);
				jQuery(this).addClass('toprint').text('הדפס הזמנה').addClass('print');
				return false;
			}
	});
	
	jQuery('.resetPersonalOrder').on('click',this,function(){
	
	jQuery('.toshoworder').hide().removeClass('toshoworder');
	//jQuery('.toshoworder').hide().removeClass('toshoworder');
	jQuery('.singel_order').show();
	jQuery('.toptikviwebtm').removeClass('toprint print').text('פרטי הזמנה');
	});
		
		
	tagFillter();	
});//dom redy









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

function saveingAndMore(){
	save=jQuery('.discount .amount').html();
	allsum=jQuery('.total .subGeray .amount').html();
//	alert(allsum);
	if(save){
		jQuery('.saveing').html(save);
		jQuery('.sammSum').show();
	}else{
		jQuery('.sammSum').hide();
	}
	
	jQuery('.allSum').html(allsum);
}

function popUp(elm,elmClos){
	if(elm=='.loginpop'){
		if(jQuery('.loginPop').children('form').size() > 0){
			loc=jQuery(document).scrollTop();;
			jQuery(elm).css('top',loc+'px').fadeToggle();
			jQuery(elmClos).on('click',this,function(){
				jQuery(elm).hide();
			});
		}else{
			alert('אתה כבר מחובר');
		}
	}else{
		loc=jQuery(document).scrollTop();;
		jQuery(elm).css('top',loc+'px').fadeToggle();
		jQuery(elmClos).on('click',this,function(){
			jQuery(elm).hide();
		});
	}	
}

function setWW(){
	var liw=0;	
	conW=jQuery('.brandsCon').width();
	jQuery('.brandsMenu_wide ul li').each(function(index, element) {
			liw=liw+jQuery(this).width()+15;
	});
	jQuery('.brandsMenu_wide ul').css('width',liw+'px');
	
	if(liw>conW){
		jQuery('.lIco,.rIco').show();
	}
}


function add_favorite() { 
  title = document.title; 
  url = document.location; 
  try { 
    // Internet Explorer 
    window.external.AddFavorite( url, title ); 
  } 
  catch (e) { 
    try { 
      // Mozilla 
      window.sidebar.addPanel( title, url, "" ); 
    } 
    catch (e) { 
      // Opera 
      if( typeof( opera ) == "object" ) { 
        a.rel = "sidebar"; 
        a.title = title; 
        a.url = url; 
        return true; 
      } 
      else { 
        // Unknown 
        alert( 'Press Ctrl-D to add page to your bookmarks' ); 
      } 
    } 
  } 
  return false; 
}
//////////////////////////new color selection
function ecolorSelection(){
	var imgw=jQuery('.imgcolor').length*50;
		jQuery('.varslide').css('width',imgw+'px');
		
		//slide of the image variatin
		
	jQuery('.lver').on('click',this,function(){
			nobe=(imgw-45);
			cPos=parseInt(jQuery('.varslide').css('right'));
			if(cPos<=0 && imgw>315){
				npos=cPos+25;
				jQuery('.varslide').css('right',npos+'px');
			}
		});
		
		
		
	jQuery('.rver').on('click',this,function(){
			nobe=-(imgw-90);
			cPos=parseInt(jQuery('.varslide').css('right'));
			now=imgw+cPos;
			console.log(now);
			if(cPos>nobe && imgw>315 && now>315){
				npos=cPos-25;
				jQuery('.varslide').css('right',npos+'px');
			}
		});
}
/////////////////personal eara

function personalNav(){
	personalreset();
	jQuery('.myinfo').on('click',this,function(){
		jQuery('.personalorder,.passwordC').hide();
		jQuery('.myinfo_div').show();
		jQuery('.infonev').removeClass('act');
		jQuery(this).addClass('act');
		//return false;
	});

jQuery('.myorder').on('click',this,function(){
		jQuery('.myinfo_div,.passwordC').hide();
		jQuery('.personalorder').show();
		jQuery('.infonev').removeClass('act');
		jQuery(this).addClass('act');
		//return false;
	});
jQuery('.passChang').on('click',this,function(){
		jQuery('.myinfo_div,.personalorder').hide();
		jQuery('.passwordC').show();
		jQuery('.infonev').removeClass('act');
		jQuery(this).addClass('act');
	//return false;
});
}
	
function personalreset(){
	jQuery('.personalorder,.passwordC').hide();
	jQuery('.myinfo_div').show();
}

function mysingelorder(order_id){
	jQuery('.singel_order').hide();
	jQuery('#'+order_id+'_orderTop').show().addClass('toshoworder');
	jQuery('#'+order_id+'_singelDetail').show().addClass('toshoworder');
	//alert(order_id);
	/*jQuery.post(ajax_object.ajaxurl, {
				post_id:order_id,
				action: 'singel_order',
			}
			, function(data) {
				alert(data);
					
			});*/
}
////////////filtering by tag
function tagFillter(){
	jQuery('.tagcloud a').on('click',this,function(){
		
		
		ccat=jQuery('.page-title.toptik').text();
		ctag=jQuery(this).text();
		
	/*	jQuery.post(ajax_object.ajaxurl, {
				ctag:ctag,
				ccat:ccat,	
				action: 'singel_order',
			}
			, function(data) {
				jQuery('.products').html(data);
					
			});
			
		return false;*/
	});
}

/////////////////cart
jQuery(document).ready(function(e) {
	

	jQuery('input.plus').on('click',this,function(){
		jQuery('input.plus').each(function(index, element) {
			
			idp=jQuery(this).parent('div').parent('td').parent('tr').data('item');
			pqunt=jQuery(this).parent('div').children('input[type="number"]').val();	
			
			alert(idp+'---'+pqunt);
		});
		
		
	});

});