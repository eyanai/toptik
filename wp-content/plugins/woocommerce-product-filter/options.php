<?php
if (!defined('ABSPATH')) exit('No direct script access allowed');

 /**
 * CodeNegar WooCommerce AJAX Product Filter options page
 *
 * Options pages controls how plugin works
 *
 * @package    	WooCommerce AJAX Product Filter
 * @author      Farhad Ahmadi <ahm.farhad@gmail.com>
 * @license     http://codecanyon.net/licenses
 * @link		http://codenegar.com/woocommerce-ajax-product-filter/
 * @version    	1.9
 */
 

global $codenegar_wcpf;
// Assets are loaded by hooks
$is_wc = $codenegar_wcpf->helper->is_wc();

$is_secure = false;

if(isset($_POST['product_filter_submit'])){
	$is_secure = wp_verify_nonce($_REQUEST['security'], $codenegar_wcpf->security);
}

if(isset($_POST['product_filter_submit']) && $is_secure) {
	$codenegar_product_filter = array();
	$codenegar_product_filter['loader_image'] = $codenegar_wcpf->helper->prepare_parameter($_POST['loader_image'], false);
	$codenegar_product_filter['display_no_products_message'] = $codenegar_wcpf->helper->prepare_parameter($_POST['display_no_products_message'], false);
	$codenegar_product_filter['sidebars'] = $codenegar_wcpf->helper->prepare_sidebars($_POST['sidebars']);
	$codenegar_product_filter['hashtag_fallback'] = (isset($_POST['hashtag_fallback']) && $_POST['hashtag_fallback'] =='checked')? 'true': 'false';	

	// Store array of settings to database
	update_option('codenegar_product_filter',$codenegar_product_filter);
	
	?>
	<div class="updated"><p><?php _e("Changes Saved.", $codenegar_wcpf->text_domain); ?></p></div>
	<?php
}

if (isset($_POST['product_filter_submit']) && !$is_secure){
	?>
	<div class="error"><p><?php _e('Security check failed.', $codenegar_wcpf->text_domain); ?></p></div>
	<?php
}
if(!$is_wc){
	?>
	<div class="error"><p><?php _e('Woocommerce not found. Get a version at <a href="http://www.woothemes.com/woocommerce/">woothemes</a>.', $codenegar_wcpf->text_domain); ?></p></div>
	<?php
	die();
}

$codenegar_product_filter_options = get_option('codenegar_product_filter');
$defaults = $codenegar_wcpf->helper->default_options();
$codenegar_product_filter_options = codenegar_parse_args($codenegar_product_filter_options, $defaults);
?>
<script>
var sidebars = [ <?php echo $codenegar_wcpf->helper->get_sidebars_object($codenegar_product_filter_options['sidebars']); ?>];
(function($) {
$(document).ready(function() {
		$(function() {
			$(".chzn-select").chosen({width: "162px"});
		});
	});
})(jQuery);
</script>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2><?php _e('Woocommerce AJAX Product Filter', $codenegar_wcpf->text_domain); ?></h2>
<form method="post">
<div id="poststuff" class="metabox-holder" >
	<div class="postbox" >
	<div  class="handlediv"></div>
	<h3 class="hndle"><span><?php _e('Options', $codenegar_wcpf->text_domain); ?></span></h3>
	<div class="inside" >
	<br />
	<label for="loader_image"><?php _e('Loader Image', $codenegar_wcpf->text_domain); ?>:<span id="form_label"></span></label>
	<?php
	$codenegar_wcpf->html->image_upload_field($codenegar_product_filter_options['loader_image'], 'loader_image');
	?>
	<br />
	<br />
	<label><?php _e('Display No Products Message', $codenegar_wcpf->text_domain); ?>:<span id="postform"></span></label>
	<select name="display_no_products_message">
		<option <?php if($codenegar_product_filter_options['display_no_products_message']=='yes') echo 'selected="selected"' ?> value="yes">Yes</option>
		<option <?php if($codenegar_product_filter_options['display_no_products_message']=='no') echo 'selected="selected"' ?> value="no">No</option>
	</select>
	<br />
	<br />
	<label><?php _e('Sidebars', $codenegar_wcpf->text_domain); ?>:<span id="postform"></span></label>
	
	<div id="dialog-form" title="<?php _e('Create new sidebar', $codenegar_wcpf->text_domain); ?>">

	  <fieldset>
		<label for="title"><?php _e('Title', $codenegar_wcpf->text_domain); ?>:</label>
		<input type="text" name="title" id="sidebar_title" autocomplete="off" id="title" onkeyup="this.value=this.value.replace(/[^a-z0-9_]/g,'');" class="text ui-widget-content ui-corner-all" />
		<br/>
		<br/>
		<label for="visible_to"><?php _e('Visible To', $codenegar_wcpf->text_domain); ?>:</label>
		<br/>
		<select name="visible_to" class="visible_to chzn-select" id="visible_to">
		<option value="all"><?php _e('All Shop Archive', $codenegar_wcpf->text_domain); ?></option>
		<option value="cat"><?php _e('Category', $codenegar_wcpf->text_domain); ?></option> 
		<option value="attr"><?php _e('Attribute', $codenegar_wcpf->text_domain); ?></option> 
		</select>
		<span id="cat_drp" style="display: none;">
		<?php
			$cats = $codenegar_wcpf->helper->dropdown_cat(array('echo'=> 0, 'class'=> 'postform chzn-select', 'show_option_all'=>''));
			$cats = str_replace('<select', '<select multiple data-placeholder="Choose some cats"', $cats);
			echo $cats;
		?>
		</span>
		<span id="attr_drp" style="display: none;">
		<select multiple name="attrib" class="attrib chzn-select" id = "attrib">
		<option value></option>
			<?php
			$attributes = (array) ($codenegar_wcpf->helper->get_attributes());
			$count = count($attributes);
			if($count>0){
				for($i=0; $i<$count; $i++){
					$label = $attributes[$i]->attribute_label;
					if(!$label){
						$label = $attributes[$i]->attribute_name;
					}
					?>
					<option value="<?php echo $attributes[$i]->attribute_name; ?>"><?php echo $label; ?></option> 
					<?php
				}
			}
			?>
		</select>
		</span>
	  </fieldset>

	</div>
	 
	<div id="sidebars-contain" class="ui-widget">
	  <table id="sidebars" class="ui-widget ui-widget-content">
		<thead>
		  <tr class="ui-widget-header">
			<th><?php _e('Title', $codenegar_wcpf->text_domain); ?></th>
			<th><?php _e('Visible To', $codenegar_wcpf->text_domain); ?></th>
			<th><?php _e('Shortcode', $codenegar_wcpf->text_domain); ?></th>
			<th><?php _e('Action', $codenegar_wcpf->text_domain); ?></th>
		  </tr>
		</thead>
		<tbody>
		  <?php echo $codenegar_wcpf->helper->get_sidebars_table($codenegar_product_filter_options['sidebars']); ?>
		</tbody>
	  </table>
	  <button id="create-sidebar"><?php _e('New Sidebar', $codenegar_wcpf->text_domain); ?></button>
	</div>
	
	<input type="hidden" id="sidebars" name="sidebars" value="<?php echo str_replace('"', "'", $codenegar_product_filter_options['sidebars']); ?>">
	
	<?php wp_nonce_field($codenegar_wcpf->security, 'security') ?>
	</div>
	</div>
	</div>

	<tr valign="top">
	<th scope="row"></th>
		<td><p class="submit"><input type="submit" name="product_filter_submit" class="button-primary" value="<?php _e('Save Changes', $codenegar_wcpf->text_domain); ?>" /></p></td>
	</tr>
</form>
</div>