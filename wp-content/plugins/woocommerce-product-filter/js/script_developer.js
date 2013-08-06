/**
 * CodeNegar Woocommerce AJAX Product Filter 
 *
 * Frontend Script File
 *
 * @package	WooCommerce AJAX Product Filter
 * @author	Farhad Ahmadi
 * @link	http://codenegar.com/woocommerce-ajax-product-filter/
 * version	1.9
 */
 
var codenegar_page_title = "";
function codenegar_addParameter(url, parameterName, parameterValue, atStart){
    replaceDuplicates = true;
    if(url.indexOf('#') > 0){
        var cl = url.indexOf('#');
        urlhash = url.substring(url.indexOf('#'),url.length);
    } else {
        urlhash = '';
        cl = url.length;
    }
    sourceUrl = url.substring(0,cl);

    var urlParts = sourceUrl.split("?");
    var newQueryString = "";

    if (urlParts.length > 1)
    {
        var parameters = urlParts[1].split("&");
        for (var i=0; (i < parameters.length); i++)
        {
            var parameterParts = parameters[i].split("=");
            if (!(replaceDuplicates && parameterParts[0] == parameterName))
            {
                if (newQueryString == "")
                    newQueryString = "?";
                else
                    newQueryString += "&";
                newQueryString += parameterParts[0] + "=" + (parameterParts[1]?parameterParts[1]:'');
            }
        }
    }
    if (newQueryString == "")
        newQueryString = "?";

    if(atStart){
        newQueryString = '?'+ parameterName + "=" + parameterValue + (newQueryString.length>1?'&'+newQueryString.substring(1):'');
    } else {
        if (newQueryString !== "" && newQueryString != '?')
            newQueryString += "&";
        newQueryString += parameterName + "=" + (parameterValue?parameterValue:'');
    }
    return urlParts[0] + newQueryString + urlhash;
}


function codenegar_format_range(template, min, max){
    var ret = template;
    ret = ret.replace("%s", min);
    ret = ret.replace("%e", max);
	return ret;
}

function codenegar_get_parameter( name ){
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var state = History.getState();
	var current_url = state.cleanUrl;
	var results = regex.exec( current_url );
	if( results == null )
		return "";
	else
		return results[1];
}

function codenegar_wcpf_add_parameter(widget, type, key, value){
	var state = History.getState();
	var current_url = state.cleanUrl;
	var new_url = "";
	var new_key = widget + "_" + key;
	if(new_key=='orderby_orderby') new_key = 'orderby'; // use native WooCommerce ordering
	new_url = codenegar_addParameter(current_url, "cnpf", "1", true); // at first
	new_url = codenegar_addParameter(new_url, "cnep", "0"); // disable paging: shows first page
	new_url = codenegar_addParameter(new_url, new_key, value);
	// some themes bring shop at home, so make it possible to filter products at home
	if((document.URL==codenegar_wcpf_config.home_url) || (document.URL==codenegar_wcpf_config.home_url+"/")){
		new_url = codenegar_addParameter(new_url, "post_type", "product");
	}
	if(codenegar_page_title.length==0){
		codenegar_page_title = jQuery("title").text();
	}
	History.pushState({state:1}, codenegar_page_title, new_url);
}

function codenegar_make_slider($this){
	var min = parseInt($this.closest(".codenegar_slider_wrapper").next().attr("data-min"));
	var max = parseInt($this.closest(".codenegar_slider_wrapper").next().attr("data-max"));
	var template = $this.closest(".codenegar_slider_wrapper").next().attr("data-template");
	var widget = $this.closest(".codenegar_slider_wrapper").next().attr("data-widget");
	var type = $this.closest(".codenegar_slider_wrapper").next().attr("data-type");
	var key = $this.closest(".codenegar_slider_wrapper").next().attr("data-key");
	var step = parseInt($this.closest(".codenegar_slider_wrapper").next().attr("data-step"));
	current_values = codenegar_get_parameter(widget + "_" + key);
    var current_min = 0;
    var current_max = 0;
	if(parseInt(codenegar_get_parameter("cnpf"))==1 && current_values.length>0){ // If CodeNegar Product Filter is active and has url parameters
        current_values = current_values.split(",");
        if(current_values.length==2){
            current_min = parseInt(current_values[0]);
            current_max = parseInt(current_values[1]);
        }
    }
	if(!((parseInt(current_min)>0))){
		current_min = min;
	}
	if(!((parseInt(current_max)>0))){
		current_max = max;
	}
	$this.slider({
	  range: true,
	  min: min,
	  max: max,
	  step: step,
	  values: [ current_min, current_max ],
	  create : function( event, ui ) {
			$this.closest(".codenegar_slider_wrapper").next().children(".min_value").val(current_min);
			$this.closest(".codenegar_slider_wrapper").next().children(".max_value").val(current_max);
	  },
	  slide: function( event, ui ) {
		$this.closest(".codenegar_slider_wrapper").next().children(".min_value").val(ui.values[ 0 ]);
		$this.closest(".codenegar_slider_wrapper").next().children(".max_value").val(ui.values[ 1 ]);
		$this.closest(".codenegar_slider_wrapper").next().children(".amount").html( codenegar_format_range(template, ui.values[ 0 ], ui.values[ 1 ]) );
	  },
	  change: function( event, ui ){
			if (event.originalEvent) { // if user changegs it not by code
				var value = ui.values[ 0 ] + "," + ui.values[ 1 ];
				codenegar_wcpf_add_parameter(widget, type, key, value);
			}
	  }
	});
	
	$this.closest(".codenegar_slider_wrapper").next().children(".amount").html(codenegar_format_range(template, current_min, current_max));
}

jQuery(function() {
jQuery(".range_meta_slider").each(function(){
	codenegar_make_slider(jQuery(this));
});

codenegar_page_title = jQuery("title").text();

jQuery("a.codenegar_product_filter_reset_button").live("click", function(e){ // Reset filters
	e.preventDefault();
	$this = jQuery(this);
	var new_url = $this.attr("data-url");
	new_url = codenegar_addParameter(new_url, "cnpf", "1");
	new_url = codenegar_addParameter(new_url, "cnep", "0");
	if(codenegar_page_title.length==0){
		codenegar_page_title = jQuery("title").text();
	}
	// reset category and check list
	if(parseInt(codenegar_get_parameter("cat_cat"))>0 || parseInt(codenegar_get_parameter("cnpf"))=="0"){
		var applied_id = codenegar_get_parameter("cat_cat");
		var cat_parameter = jQuery('.codenegar_product_filter_wrap ul li.codenegar_applied_filter_cat a[data-old-value="'+applied_id+'"]').attr("data-value");
		new_url = codenegar_addParameter(new_url, "cat_cat", cat_parameter);
	}
	
	// reset slider values
	jQuery(".range_meta_slider").each(function(){
		$this = jQuery(this);
		var min = parseInt($this.closest(".codenegar_slider_wrapper").next().attr("data-min"));
		var max = parseInt($this.closest(".codenegar_slider_wrapper").next().attr("data-max"));
		var template = $this.closest(".codenegar_slider_wrapper").next().attr("data-template");
		$this.slider( "values", [ min, max ] );
		$this.closest(".codenegar_slider_wrapper").next().children(".amount").html( codenegar_format_range(template, min, max) );
	});
	
	// reset dropdown
	jQuery(".codenegar_product_filter_wrap select option:first-child").each(function(){
		jQuery(this).attr("selected", "selected");
	});
	
	// push new url
	History.pushState({state:1}, codenegar_page_title, new_url);
	jQuery(".codenegar_product_filter_wrap ul li").removeClass("codenegar_applied_filter chosen");
});

jQuery(".codenegar_product_filter_wrap ul li a").live("click", function(e){
	e.preventDefault();
	var $this = jQuery(this);
	if($this.attr("data-key") == "cat" && $this.attr("data-widget") == "cat" ){
		$this.closest("li").siblings().removeClass("codenegar_applied_filter chosen");
	}
	$this.closest("li").toggleClass("codenegar_applied_filter chosen");
	var widget = $this.attr("data-widget");
	var type = $this.attr("data-type");
	var key = $this.attr("data-key");
	var value = $this.attr("data-value");
	
	var new_key = widget + "_" + key; // key for url
	var current_value = codenegar_get_parameter(new_key);
	//current_value += "," + value;
	if(current_value != "" && new_key != "cat_cat"){
		current_value = current_value.split(",");
	}else{
		current_value =[];
	}
	var value_index = jQuery.inArray(value, current_value);
	if(value_index > -1){  // if duplicated remove item to have toggle effect
		current_value.splice(value_index, 1);
	}else{
		current_value.push(value);
	}
	//current_value = jQuery.unique(current_value);
	new_value = current_value.join(",");
	if(new_key == "cat_cat" && codenegar_get_parameter("cat_cat") == new_value){
		new_value = ""; // toggle effect
	}
	codenegar_wcpf_add_parameter(widget, type, key, new_value);
});

jQuery(".codenegar_product_filter_wrap select").change(function(){
	var $this = jQuery('option:selected', this);
	var widget = $this.attr("data-widget");
	var type = $this.attr("data-type");
	var key = $this.attr("data-key");
	var value = $this.val();
	codenegar_wcpf_add_parameter(widget, type, key, value);
});

	var state = History.getState();
	var current_url = state.cleanUrl;
	var has_pushstate = !!(window.history && history.pushState);
	var has_hash = !!(current_url.indexOf("#?") != -1);
	var cnpf = !!(current_url.indexOf("cnpf") != -1);
	if(!has_pushstate && has_hash && cnpf){
		// load current url data by ajax
		current_url = current_url.replace('#?', "?");
		if(codenegar_page_title.length==0){
			codenegar_page_title = jQuery("title").text();
		}
		History.pushState({state:1}, codenegar_page_title, current_url);
	}
});

(function(window,undefined){

    // Prepare
    var History = window.History; // Note: We are using a capital H instead of a lower h
    if ( !History.enabled ) {
         // History.js is disabled for this browser.
         // This is because we can optionally choose to support HTML4 browsers or not.
        return false;
    }

    // Bind to StateChange Event
    History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
        var State = History.getState(); // Note: We are using History.getState() instead of event.state
        //History.log(State);
		var $wrap = jQuery("span.codenegar-shop-loop-wrapper");
		if($wrap.length==0){
			// theme is not standard an missing WooCommerce actions/hooks
			$wrap = jQuery("ul.products");
		}
		var height = $wrap.css("height");
		$wrap.hide().html('<div id="codenegar_ajax_loader"> <img src="' + codenegar_wcpf_config.loader_img + '" class="codenegar-ajax-loader"/> </div>')
				.css("height", height).fadeIn();
		
		if (typeof wcsl_before_ajax == 'function') {
		  wcsl_before_ajax();
		}
		// now load filtered products
		jQuery.get(State.cleanUrl, function(data) {
			var $data = jQuery(data);
			var shop_loop = $data.find('span.codenegar-shop-loop-wrapper');
			var shop_ul = $data.find('ul.products');
			if(shop_loop.length>0){
				$wrap.hide().html(shop_loop).fadeIn();
			}else if(shop_ul.length>0){
				$wrap.hide().html(shop_ul).fadeIn();
			}else{
				if(codenegar_wcpf_config.display_no_products_message=='yes'){
					$wrap.hide().html(codenegar_wcpf_config.no_products_message).fadeIn();
				}
			}
			if($data.find('.codenegar-shop-loop-wrapper').children(".codenegar-shop-pagination-wrapper").length==0){
				jQuery(".codenegar-shop-pagination-wrapper").hide().html($data.find(".codenegar-shop-pagination-wrapper")).fadeIn();
			}

			var selected_cat = 0;
			var url = State.cleanUrl;
			var last_parameter = url.substring(url.lastIndexOf('&') + 1);	
			if(last_parameter.length>0 && last_parameter.indexOf("=")>-1){
				last_parameter = last_parameter.split("=");
				if(last_parameter.length == 2 && last_parameter[0]== "cat_cat"){
					selected_cat = last_parameter[1];
				}
			}
			if(parseInt(selected_cat)>0 || selected_cat=="0"){
				var clicked_widget = jQuery('.codenegar_product_filter_wrap ul li a[data-value='+ selected_cat +']').closest(".codenegar_product_filter_wrap");
				var updated_widget = $data.find('.codenegar_product_filter_wrap ul li a[data-old-value='+ selected_cat +']').closest(".codenegar_product_filter_wrap").children();
				if(updated_widget.length>0){
					clicked_widget.hide().html(updated_widget).fadeIn();
				}
			}
			
			// custom are update
			if (typeof wcsl_on_update == 'function') { 
			  var areas = wcsl_on_update();
			  var length = areas.length;
			  for (var i = 0; i < length; i++){
				var element = areas[i];
				// if(element.length==0){
					// continue;
				// }
				var updated_area = $data.find(element);
				if(updated_area.length>0){
				jQuery(element).hide().html(updated_area).fadeIn();
			  }else{
				jQuery(element).hide().html("");
			  }
			}
			}
			
		}).done(function() {
			if (typeof wcsl_ajax_done == 'function') { 
			  wcsl_ajax_done(); 
			}
			})
		  .fail(function() {
			if (typeof wcsl_ajax_fail == 'function') { 
			  wcsl_ajax_fail(); 
			}
			//if(codenegar_wcpf_config.display_no_products_message=='yes'){
				$wrap.hide().html(codenegar_wcpf_config.no_products_message).fadeIn();
			//}
		   })
		 .always(function() {
			if (typeof wcsl_after_ajax == 'function') { 
			  wcsl_after_ajax(); 
			}
		   });;
    });

})(window);

(function($) {
	$(document).ready(function() {
		$(function() {
			
			jQuery("select.orderby").live("change",function(){ // use "live" to make WooCommerce default orderby work after ajax loading
				if(jQuery(this).hasClass("codenegar_product_filter_orderby")) return;
				jQuery(this).closest("form").submit();
			});
			
		});
	});
})(jQuery);