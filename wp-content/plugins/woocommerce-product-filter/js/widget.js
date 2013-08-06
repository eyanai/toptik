// JavaScript document
function update_form(element){
	var val = element.val();
	if(val=='slider'){
		element.closest(".widget-content").children(".slider_option").slideDown();
	}else{
		element.closest(".widget-content").children(".slider_option").slideUp();
	}
}

(function($) {
$(document).ready(function() {
	$(function() {
		 $('.widget_type').live("change", function(){
			 update_form($(this));
		 });
	});
	
	$(function() {
		 $('.wcsl_attr_image').live("change", function(){
			var $this = $(this);
			$this.closest("form").find('[type="submit"]').trigger("click");
			 
		 });
	});
	
});
})(jQuery);