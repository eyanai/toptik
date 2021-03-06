jQuery(document).ready(function(e) {
	
	jQuery('.gotop').on('click',this,function(){
		
		jQuery('html, body').animate({
				scrollTop: jQuery('body').offset().top
			 }, 1000);
	});
	
	jQuery('#menu-recommend a').on('click',this,function(e){
		e.preventDefault();
		return false;
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
				npos=cPos+60;
				jQuery('.varslide').css('right',npos+'px');
			}
		});
		
		jQuery('.rver').on('click',this,function(){
			nobe=-(imgw-90);
			cPos=parseInt(jQuery('.varslide').css('right'));
			if(cPos>nobe && imgw>240){
				npos=cPos-60;
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
				jQuery('.rIco,.lIco').show();
				npos=cPos-187.4;
				jQuery('.brandsMenu_wide ul').css('right',npos+'px');
			}else{
				jQuery('.rIco').show();
				jQuery('.lIco').hide();
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
				if(npos>0){
					jQuery('.brandsMenu_wide ul').css('right','0px');
					jQuery('.rIco').hide();
					jQuery('.lIco').show();
				}else{
					jQuery('.rIco,.lIco').show();
				jQuery('.brandsMenu_wide ul').css('right',npos+'px');
				}
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
		//popUp('.loginpop','.cPop');	
		//return  false;
	});
	//shipVal
   cookieCach();
	saveingAndMore();
	setWW();
	shippingAndCredit();
	//jQuery('#shipping_company,#billing_company').attr('type','date');
//		$(function() {
	//	$( "#datepicker" ).datepicker();
	  //});
	
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
				jQuery(this).addClass('toprint').text('').addClass('print');
				return false;
			}
	});
	
	jQuery('.printAfter').on('click',this,function(){
					window.print() ;
	});
	
	jQuery('.resetPersonalOrder').on('click',this,function(){
	
	jQuery('.toshoworder').hide().removeClass('toshoworder');
	//jQuery('.toshoworder').hide().removeClass('toshoworder');
	jQuery('.singel_order').show();
	jQuery('.toptikviwebtm').removeClass('toprint print').text('פרטי הזמנה');
	});
		
		
	tagFillter();
	addressEdit();	
	
	/*jQuery('#billing_company,#birth').datepicker({
		 dateFormat: 'dd-mm-yy',
		dayNamesMin: ['ראשון', 'שני', 'שלישי', 'רביעי', 'חמישי', 'שישי', 'שבת'],  
		 });
	
	Modernizr.load({
		  test: Modernizr.date,
		  yep : '',
		  nope: function() {
					//jQuery('#shipping_company,#billing_company').datepicker();
	  		jQuery('#billing_company,#birth').datepicker({
				 dateFormat: 'dd-mm-yy',
				dayNamesMin: ['ראשון', 'שני', 'שלישי', 'רביעי', 'חמישי', 'שישי', 'שבת'],  
				 });
			}
		});*/
		jQuery(function(){
                if(!Modernizr.inputtypes.date) {
                    console.log("The 'date' input type is not supported, so using JQueryUI datepicker instead.");
                    jQuery('#billing_company,#birth').datepicker({
						 dateFormat: 'dd-mm-yy',
						dayNamesMin: ['ראשון', 'שני', 'שלישי', 'רביעי', 'חמישי', 'שישי', 'שבת'],  
				 });
                }
            });
		
		
	/*jQuery('.wysija-submit.wysija-submit-field').on('click',this,function(){
			if(jQuery('.agree_nwes').attr('checked')==true){
				return true;
			}else{
				alert('אתה חייב להסכים לקבלת מיללים פירסומיים');
				return false;
			}
		
	});	*/
	

	jQuery('.minicartval').each(function(index, element) {
		 pval=jQuery(this).val();
		jQuery(this).data('count',pval);	
	});
	
	jQuery('.minicartval').on('change',this,function(e) {
	 //setTimeout(function(){
		  var json={};
		  
		  jQuery('.minicartval').each(function(index, element) {
			  json[jQuery(this).data('proid')] = jQuery(this).val();
			});
		console.log(json);	
		  e.preventDefault();
		  pid=jQuery(this).data('proid');
		  pval=jQuery(this).val();
		  datac=jQuery(this).data('count');	
			addToCart(json);
		  return false; //},1000);
    });  
	
	paginationFix();
	fixThumbnail();
	formNews();
	
	var maxp=jQuery('#max_price').val();
	var minp=jQuery('#min_price').val();
	
	jQuery('#price_filter-2').hover(function(){
		if(maxp!=jQuery('#max_price').val()|| minp!=jQuery('#min_price').val()){
		//	alert('act');
			filterPriceBilud();
		}
	});
	
	//if(jQuery('.registerForm').length>0);
	 formvalid();
	
	plusNote();
	jQuery('.yith_magnifier_zoom.woocommerce-main-image').append('<div class="mousOver"><span>+</span>עבור עם העכבר להגדלה</div>');
});//dom redy=============================================================================================================


function filterPriceBilud(){
	
	//jQuery('.toptikpricefilter').on('submit',this,function(){
				setTimeout(function(){
			
				   var sURL = window.document.URL.toString();  
					 var arrParams = sURL.split("?");         
					 var arrURLParams = arrParams[1];
					
					maxpNew=jQuery('#max_price').val();
					minpNew=jQuery('#min_price').val();
						//alert(arrParams[0]);
					window.location=(arrParams[0]+'?'+arrURLParams+'&min_price='+minpNew+'&max_price='+maxpNew);
					return false;},3000);
				return false;
	//});
	
	
}
	








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
	save=parseFloat(jQuery('#saveCupon').data('cupon'));
	allsum=jQuery('.total .subGeray .amount').html();
	creditsave=0;
	creditsave=parseFloat(jQuery('#credirdiscout').data('credit'));
	
	if(save>0 && creditsave>0){
		totalsave=save+creditsave;
	}else{
		if(save>0){
			totalsave=save;
		}else{
			totalsave=creditsave;
		}
	}
	if(save||creditsave){
		jQuery('.saveing').html(totalsave +' &#8362;');
		jQuery('.sammSum').show();
	}else{
		jQuery('.sammSum').hide();
	}
	
	
	sval=jQuery('#shipping_method option:selected').data('sval');
	if(sval!=0){
		jQuery('#shipnam').html(sval + ' &#8362;');
		jQuery('.shipVal').show();	
	}else{
		jQuery('.shipVal').hide();
	}
	subtotal=jQuery('#cartSubToal').data('subtotls'); 
	sumtotal=sval+subtotal;
	jQuery('#sumValCost').html(sumtotal+' &#8362;');
	
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
			liw=liw+jQuery(this).width()+10;
	});
	jQuery('.brandsMenu_wide ul').css('width',liw+'px');
	
	if(liw>conW){
		jQuery('.lIco').show();
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
	var imgw=jQuery('.imgcolor').length*75;
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

function shippingAndCredit(){
			sval=jQuery('#shipping_method option:selected').data('sval');
		if(sval!=0){
			jQuery('#shipnam').html(sval + ' &#8362;');
			jQuery('.shipVal').show();	
			}else{
			jQuery('.shipVal').hide();
		}
		subtotal=jQuery('#cartSubToal').data('subtotls'); 
		sumtotal=sval+subtotal;
		jQuery('#sumValCost').html(sumtotal+' &#8362;');
		jQuery('#beforDisc').show();
}
	

function addressEdit(){
	jQuery('.addreesType,.regLabel').on('click',this,function(){
		adshow=jQuery(this).data('val');
		
		if(adshow=='reg'){
		 	jQuery('#regAddrees').attr('checked', true);
			jQuery('#newAddrees').attr('checked', false);
			jQuery('.new_addrees').hide();
			jQuery('.reg_addrees').show();
			jQuery('#checoutUp').show();
			jQuery('#checoutDown').hide();
		}else{
			jQuery('#regAddrees').attr('checked', false);
			jQuery('#newAddrees').attr('checked', true);
			jQuery('.new_addrees').show();
			jQuery('.reg_addrees').hide();
			jQuery('#checoutUp').hide();
			jQuery('#checoutDown').show();

		}
	});
}





//////////////////////////form compier for tranzila////////


 function addToCart(json) {
	 	/*	url=jQuery('.shop_table.cart.mini').data('url');
	 		
            jQuery.ajax({
              type: 'POST',
              url: url+'/?post_type=product&add-to-cart='+p_id,
              data: { 'product_id':  p_id,
              'quantity': amount},
              success: function(response, textStatus, jqXHR){
                    console.log("Product added");
					jQuery('#minicart .blockUI.blockOverlay').hide();
                }/*,
              dataType: 'JSON'
            }); */
      
	  
	  	jQuery('#minicart .blockUI.blockOverlay').show();	
			
		jQuery.post(ajax_object.ajaxurl, {
				json:json,
			//	pval:amount,	
				action: 'singel_oddpro',
			}
			, function(data) {
				//jQuery('#minicart .blockUI.blockOverlay').hide();
				location.reload(true);
			});
			
		return false;
	  }

  function paginationFix(){
	 all1=jQuery(".wp-pagenavi span.pages").first().text();
	 if(jQuery('.wp-pagenavi .previouspostslink').length>0){
	 	jQuery(".wp-pagenavi span.pages").hide();
	 	jQuery('.wp-pagenavi .previouspostslink').first().after('<span class="pages" style="display:block">'+all1+'</span>');
		jQuery('.wp-pagenavi .previouspostslink').last().after('<span class="pages" style="display:block">'+all1+'</span>');
	 }else{
	 	jQuery(".wp-pagenavi span.pages").show();
	 }
}

function fixThumbnail(){
	if(jQuery('.yith_magnifier_thumbnail li').length<5){
		jQuery('#slider-prev,#slider-next').hide();	
	}
}
function formNews(){
	jQuery('.widget_wysija.php_wysija').on('submit',this,function(e){
		if(jQuery('.agree_nwes').is(':checked')){
			return true;
		}else{
			alert('אנא הסכם לקבלת מיליים פירסומיים');
			return false;
		}

		
	});
}

function formvalid(){
	jQuery('.registerForm').on('submit',this,function(){
		valid1=jQuery('#zipcode').val();
		if(isInt(valid)){
			return true;
		}else{
			alert('אנא הכנס מספרים בלבד');
			return false;
		}
		
		valid2=jQuery('#phone').val();
		if(isInt(valid)){
			return true;
		}else{
			alert('אנא הכנס מספרים בלבד');
			return false;
		}
				
	});
	
	jQuery('#zipcode,#phone').on('change',this,function(){
		valid=jQuery(this).val();
		if(isInt(valid)){
			return true;
		}else{
			alert('אנא הכנס מספרים בלבד');
			return false;
		}
		
	
	});

}

function isInt(value) {
   return !isNaN(value) && parseInt(value) == value;
}

function plusNote(){
	jQuery('.topPlus').on('click',this,function(){
	var text='טופ-תיק מעניקה לכם קרדיט מזומן לקניות הבאות שלכם באתר טופ-תיק\n קונים מוצר מאחד המוצרים של טופ-תיק ומקבלים כסף מזומן לקניות הבאות באתר.\n בכל קניה  נצבר לך סכום כסף לקנייה חוזרת באתר,על כל מוצר ישנו ערך הקרדיט שלו.\nלדוגמא במידה וקנית מוצר שערך הקרדיט שלו הוא 40 ש"ח תוכל להשתמש בקרדיט ולקבל זיכוי בקניה הבאה באתר.\nהסכום הנצבר מופיע בדף תיאור הפריט.\nסכום  הקרדיט הנצבר  ניתן למימוש ללא הגבלת זמן בקניה הבאה באתר בלבד \n\n בברכה צוות טופ תיק';

alert(text);


	});

}