<?php
if (!defined('ABSPATH')) exit('No direct script access allowed');

 /**
 * CodeNegar WooCommerce AJAX Product Filter HTML class
 *
 * Generates HTML codes
 *
 * @package    	WooCommerce AJAX Product Filter
 * @author      Farhad Ahmadi <ahm.farhad@gmail.com>
 * @license     http://codecanyon.net/licenses
 * @link		http://codenegar.com/woocommerce-ajax-product-filter/
 * @version    	1.9
 */
 
class CodeNegar_wcpf_html{
	
	function __construct(){
		
	}
	
	public function attr_list($options){

		global $codenegar_wcpf;
		$attribs = $codenegar_wcpf->helper->get_attribute_values($options['parent']);
		?>
		<div class="codenegar_product_filter_wrap attr_list <?php echo 'attr_list_' . $options['parent'] ; ?>  <?php echo $options['custom_class']; ?>">
		<span class="codenegar_product_filter_title"><?php echo $options['sub_title'] ?></span>
		<ul>
		<?php
		$current_attr_values = array();
		$operator = 'o';
		if($options['operator']=='AND') { $operator = 'a'; }else{ $operator = 'o'; }
		$current_attr_name = "attr" . $operator . "_" . $options['parent'];
		if(isset($_GET[$current_attr_name])){
			$current_attr_values = $_GET[$current_attr_name];
			$current_attr_values = $_GET[$current_attr_name];
			$current_attr_values = explode(",", $current_attr_values);
			$current_attr_values = (array)$codenegar_wcpf->helper->prepare_parameter($current_attr_values, true);
		}
		foreach($attribs as $attrib){
			if(!isset($attrib->term_id)){ // removed attrib or something
				continue;
			}
			$att_name = (isset($attrib->name))? $attrib->name: "";
			if(!$att_name){
				$att_name = $attrib->slug;
				$att_name = str_replace('-', '', $att_name);
				$att_name = ucfirst($att_name);
			}
			?>
			<li <?php if(isset($_GET['cnpf']) && intval($_GET['cnpf']) ==1 && in_array($attrib->term_id, $current_attr_values)) { echo 'class="codenegar_applied_filter chosen"'; } ?> >

			<a href="#" data-key="<?php echo $options['parent']; ?>" data-value="<?php echo $attrib->term_id; ?>" data-widget="<?php echo $options['widget']; ?><?php if($options['operator']=='AND') { echo 'a'; }else{ echo 'o'; } ?>"  data-type="<?php echo $options['type']; ?>"><?php echo $att_name; ?></a>
				
			</li>
			<?php
		}
		?>
		</ul>
		</div>
		
		<?php
	}
	
	public function attr_slider($args){
		?>
		<div class="codenegar_product_filter_wrap woocommerce widget_price_filter attr_slider <?php echo 'attr_slider_' . $args['parent'] ; ?>  <?php echo $args['custom_class']; ?>">
		<span class="codenegar_product_filter_title"><?php echo $args['sub_title'] ?></span>
			<div class="codenegar_slider_wrapper">
				<div class="range_meta_slider" ></div>
			</div>
			<div 
				data-key="<?php echo $args['parent']; ?>"
				data-widget="<?php echo $args['widget'].'s'; // attr + Slider ?>"
				data-type="<?php echo $args['type']; ?>"
				data-min="<?php echo $args['min']; ?>"
				data-max="<?php echo $args['max']; ?>"
				data-template="<?php echo $args['range_template']; ?>"
				data-step="<?php echo $args['step']; ?>"
				class="price_slider_amount"
			>
				<input type="text" class="min_value" name="min_value" value="" data-min="<?php echo $args['min'] ?>" placeholder="Min price" style="display: none;">
				<input type="text" class="max_value" name="max_value" value="" data-max="<?php echo $args['max'] ?>" placeholder="Max price" style="display: none;">
				<div class="amount price_label" ></div>
			</div>
			
		</div>
		<?php
	}
	
	public function attr_dropdown($args){
		?>
		<div class="codenegar_product_filter_wrap attr_dropdown <?php echo 'attr_slider_' . $args['parent'] ; ?>  <?php echo $args['custom_class']; ?>">
			<span class="codenegar_product_filter_title"><?php echo $args['sub_title'] ?></span>
			<?php
			global $codenegar_wcpf;
			$options = array(
				'user_val' => '',
			);
			$options = codenegar_parse_args($options, $args);;
			$this->dropdown_attr_front($options);
			?>
			
		</div>
		
		<?php
	}
	
	public function get_cat_title($id){
		$out = get_term_by( 'id', intval($id), 'product_cat', 'OBJECT' );
		if(!$out){
			return "";
		}
		return $out->name;
	}
	
	public function cat_list($options){
		global $codenegar_wcpf;
		$defaults = array(
			'type'                     => 'post',
			'taxonomy'				   => 'product_cat',
			//'orderby'                  => 'id',
			'menu_order'			=> 'asc',
			'order'                    => 'ASC',
			'include_last_update_time' => false,
			'hierarchical'             => false,
			'pad_counts'               => false,
			'selected'				   => 0,
			'parent' 				   => 0, // direct child only
			'child_of'				   => 0 // direct and grands
		);
		$merged = codenegar_parse_args($options, $defaults);
		if(isset($_GET['cnpf']) && intval($_GET['cnpf']) ==1 && isset($_GET['cat_cat']) && intval($_GET['cat_cat'])>0){
			$merged['parent'] = intval($_GET['cat_cat']); // use url parameter
		}else{
			$merged['parent'] = $merged['selected']; // use admin option
		}
		$categories = get_categories($merged);
		?>
		<div class="codenegar_product_filter_wrap cat_list <?php echo 'cat_list_' . $merged['selected']; ?>  <?php echo $merged['custom_class']; ?>">
		<span class="codenegar_product_filter_title"><?php echo $merged['sub_title'] ?></span>
		<ul>
		<?php
		
		if($merged['parent'] != $merged['selected']){ // parent is passed by url
			?>
			<li class="codenegar_applied_filter_cat codenegar_applied_filter chosen"> 
				<a href="#" data-key="<?php echo $merged['widget']; ?>" data-value="<?php echo $merged['selected']; ?>" data-old-value="<?php echo intval($_GET['cat_cat']); ?>" data-widget="<?php echo $options['widget']; ?>"  data-type="<?php echo $options['type']; ?>"><?php echo $this->get_cat_title(intval($_GET['cat_cat'])); ?></a>
			</li>
			<?php
		}
		
		$current_cat = 0;
		if(isset($_GET['cnpf']) && intval($_GET['cnpf']) ==1 && isset($_GET['cat_cat']) && intval($_GET['cat_cat'])>0){
			$current_cat = intval($_GET['cat_cat']);
		}
		$flag = false;
		foreach($categories as $category){
			?>
			<li <?php if(isset($_GET['cnpf']) && intval($_GET['cnpf']) ==1 && $current_cat == $category->cat_ID) { echo 'class="codenegar_applied_filter chosen"'; } ?> > 
				<a href="#" data-key="<?php echo $merged['widget']; ?>" <?php if(!$flag && isset($_GET['cat_cat'])){ $flag = true; echo 'data-old-value="'.intval($_GET['cat_cat']).'"'; } ?> data-value="<?php echo $category->cat_ID; ?>" data-widget="<?php echo $options['widget']; ?>"  data-type="<?php echo $options['type']; ?>"><?php echo $category->cat_name; ?></a>
			</li>
			<?php
		}
		?>
		</ul>
		</div>
		<?php
	}
	
	public function cat_dropdown($args){
		?>
		<div class="codenegar_product_filter_wrap cat_dropdown <?php echo 'cat_dropdown_' . $args['selected']; ?>  <?php echo $args['custom_class']; ?>">
			<span class="codenegar_product_filter_title"><?php echo $args['sub_title'] ?></span>
			<?php
			$options = array(
				'child_of'	=> $args['selected'],
				'widget'	=> $args['widget'],
			);
			$this->dropdown_cat_front($args);
			?>
			
		</div>
		
		<?php
	}
	
	public function meta_slider($args){
		?>
		<div class="codenegar_product_filter_wrap woocommerce widget_price_filter meta_slider <?php echo 'meta_slider_' . $args['parent']; ?> <?php echo $args['custom_class']; ?>">
		<span class="codenegar_product_filter_title"><?php echo $args['sub_title'] ?></span>
			<div class="codenegar_slider_wrapper">
				<div class="range_meta_slider" ></div>
			</div>
			<div 
				data-key="<?php echo $args['parent']; ?>"
				data-widget="<?php echo $args['widget']; ?>"
				data-type="<?php echo $args['type']; ?>"
				data-min="<?php echo $args['min']; ?>"
				data-max="<?php echo $args['max']; ?>"
				data-template="<?php echo $args['range_template']; ?>"
				data-step="<?php echo $args['step']; ?>"
				class="price_slider_amount"
			>
				<input type="text" class="min_value" name="min_value" value="" data-min="<?php echo $args['min'] ?>" placeholder="Min price" style="display: none;">
				<input type="text" class="max_value" name="max_value" value="" data-max="<?php echo $args['max'] ?>" placeholder="Max price" style="display: none;">
				<div class="amount price_label" ></div>
			</div>
		</div>
		<?php
	}
	
	/**
	* HTML code needed for wordpress native uploader
	*/
	
	function image_upload_field($value='', $name='', $class='', $id='') {
		global $codenegar_wcpf;
		$jq_class = md5($name);
	?>
		
		<input class="<?php echo $jq_class; ?> <?php echo $class; ?>" type="text" size="90" name="<?php echo $name; //name may contain illegal chars ?>" value="<?php echo $value; ?>" />
		<input class="<?php echo $jq_class; ?>_button" data-id="<?php echo $name; ?>" type="button" value="<?php _e('Upload Image', $codenegar_wcpf->text_domain); ?>" />
		
		<script type='text/javascript' >
		var current_field = '';
		jQuery(document).ready(function() {
			jQuery("#TB_title").remove();
			jQuery('.<?php echo $jq_class; ?>_button').live("click", function() {
				current_field = '<?php echo $jq_class; ?>';
				formfield = jQuery('.<?php echo $jq_class; ?>').attr('name');
				tb_show('', 'media-upload.php?flash=0&simple_slideshow=true&TB_iframe=true'); /* post_id=1& */
				return false;
			});

			window.send_to_editor = function(html) {
				imgurl = jQuery('img',html).attr('src');
				jQuery('.'+current_field).val(imgurl);
				tb_remove();
			}
		});
		</script>
		
	<?php
	}
	
	
	function dropdown_attr_front( $options = '' ){
		global $codenegar_wcpf;
		$attribs = $codenegar_wcpf->helper->get_attribute_values($options['parent']);
		$url_val_name = 'attr_' . $options['parent'];
		$url_val = 0;
		if(isset($_GET[$url_val_name]) && intval($_GET[$url_val_name])>0){
			$url_val = intval($_GET[$url_val_name]);
		}
		$user_val = $url_val;
		?>
		<div class="codenegar_product_filter_wrap attr_dropdown <?php echo 'attr_dropdown_' . $options['parent']; ?>  <?php echo $options['custom_class']; ?>">
		<select name="attr_<?php echo $options['parent']; ?>" id="attr_<?php echo $options['parent']; ?>" class="<?php echo 'select_' . $options['parent']; ?>">
			<option
				data-widget="attr" 
				data-type="dropdown" 
				data-key="<?php echo $options['parent']; ?>" 
				value=""
				<?php if(!$user_val){ echo 'selected="selected"'; } ?> ><?php _e('Any', $codenegar_wcpf->text_domain) ?> <?php echo $options['sub_title']; ?>
			</option>
			<?php

		foreach($attribs as $attrib){

			$att_name = $attrib->name;
			if(!$att_name){
				$att_name = $attrib->slug;
				$att_name = str_replace('-', '', $att_name);
				$att_name = ucfirst($att_name);
			}

			?>
			<option 
				data-widget="<?php echo $options['widget']; ?>" 
				data-type="dropdown" 
				data-key="<?php echo $options['parent']; ?>" 
				value="<?php echo $attrib->term_id; ?>"
				<?php if($user_val == $attrib->term_id){ echo 'selected="selected"'; }?>
				><?php echo $att_name; ?>
			</option>
			<?php
		}
		?>
		</select>
		</div>
		<?php
	}

	function dropdown_cat_front( $options = '' ) {
        global $codenegar_wcpf;
		$defaults = array(
			'type'                     => 'post',
			'taxonomy'				   => 'product_cat',
			//'orderby'                  => 'id',
			'menu_order'			=> 'asc',
			'order'                    => 'ASC',
			'include_last_update_time' => false,
			'hierarchical'             => false,
			'pad_counts'               => false,
			'selected'				   => 0,
			'parent' 				   => 0, // direct child only
			'child_of'				   => 0,
		);
		$merged = codenegar_parse_args($options, $defaults);
		$merged['parent'] = $merged['selected']; // dropdown has no dynamic child loading
		$categories = get_categories($merged);
		$admin_cat = $merged['parent'];
		$user_cat = $merged['child_of'];
		$url_cat = 0;
		if(isset($_GET['cat_cat']) && intval($_GET['cat_cat'])>0){
			$url_cat = $_GET['cat_cat'];
		}
		?>
		<div class="codenegar_product_filter_wrap cat_dropdown <?php echo 'cat_dropdown_' . $merged['widget']; ?>  <?php echo $merged['custom_class']; ?>">
		<select name="cat_<?php echo $admin_cat; ?>" id="cat_<?php echo $merged['child_of']; ?>" class="<?php echo $merged['class']; ?>">
		
		<option
			data-widget="<?php echo $merged['widget']; ?>" 
			data-type="dropdown" 
			data-key="<?php echo $merged['widget']; ?>" 
			value=""
			<?php if($url_cat==0){ echo 'selected="selected"'; } ?>
		>
		<?php _e('Any Category', $codenegar_wcpf->text_domain); ?>
		</option>
		<?php
		foreach($categories as $category){
			?>
			<option 
				data-widget="<?php echo $merged['widget']; ?>" 
				data-type="dropdown" 
				data-key="<?php echo $merged['widget']; ?>" 
				value="<?php echo $category->cat_ID; ?>"
				<?php if($url_cat==$category->cat_ID){ echo 'selected="selected"'; } ?>
				>
				<?php echo $category->cat_name; ?>
			</option>
			<?php
		}
		?>
		</select>
		</div>
		<?php
	}
	
	public function orderby($options){
		global $codenegar_wcpf;
		$orderby = 'menu_order';
		if(isset($_GET['orderby']) && strlen($_GET['orderby'])>0){
			$orderby = $_GET['orderby'];
		}
		?>
		<div class="codenegar_product_filter_wrap woocommerce orderby_widget <?php echo $options['custom_class']; ?>"
		<span class="codenegar_product_filter_title"><?php echo $options['sub_title'] ?></span>
		<div class="codenegar_product_filter_wrap orderby_dropdown  <?php echo $options['custom_class']; ?>">
		<select name="orderby" class="orderby codenegar_product_filter_orderby">
			<option data-widget="orderby" data-type="dropdown" data-key="orderby" <?php if(isset($orderby) && $orderby=='menu_order') { echo 'selected="selected"'; } ?> value="menu_order"><?php _e('Default sorting', $codenegar_wcpf->text_domain); ?></option>
			<option data-widget="orderby" data-type="dropdown" data-key="orderby" <?php if(isset($orderby) && $orderby=='popularity') { echo 'selected="selected"'; } ?> value="popularity"><?php _e('Sort by popularity', $codenegar_wcpf->text_domain); ?></option>
			<option data-widget="orderby" data-type="dropdown" data-key="orderby" <?php if(isset($orderby) && $orderby=='rating') { echo 'selected="selected"'; } ?> value="rating"><?php _e('Sort by average rating', $codenegar_wcpf->text_domain); ?></option>
			<option data-widget="orderby" data-type="dropdown" data-key="orderby" <?php if(isset($orderby) && $orderby=='date') { echo 'selected="selected"'; } ?> value="date"><?php _e('Sort by newness', $codenegar_wcpf->text_domain); ?></option>
			<option data-widget="orderby" data-type="dropdown" data-key="orderby" <?php if(isset($orderby) && $orderby=='price') { echo 'selected="selected"'; } ?> value="price"><?php _e('Sort by price: low to high', $codenegar_wcpf->text_domain); ?></option>
			<option data-widget="orderby" data-type="dropdown" data-key="orderby" <?php if(isset($orderby) && $orderby=='price-desc') { echo 'selected="selected"'; } ?> value="price-desc"><?php _e('Sort by price: high to low', $codenegar_wcpf->text_domain); ?></option>
		</select>
		</div>
		</div>
		<?php
	}
	
	public function image_attr($options){
		global $codenegar_wcpf;
		$element = $options['element_type'];
		$width = $options['width'];
		$height = $options['height'];
		$parent = $options['parent'];
		?>
		<div class="codenegar_product_filter_wrap woocommerce orderby_widget <?php echo $options['custom_class']; ?>"
		<span class="codenegar_product_filter_title"><?php echo $options['sub_title'] ?></span>
		<div class="codenegar_product_filter_wrap orderby_dropdown  <?php echo $options['custom_class']; ?>">
		<?php
		$operator = ($options['operator']=='AND')? 'a' : 'o';
		if($element=="li") echo '<ul class="codenegar_wcpf_image_attr">';
		$current_attr_name = "attr" . $operator . "_" . $parent;
		$current_attr_values = array();
		if(isset($_GET[$current_attr_name])){
			$current_attr_values = $_GET[$current_attr_name];
			$current_attr_values = explode(",", $current_attr_values);
			$current_attr_values = (array)$codenegar_wcpf->helper->prepare_parameter($current_attr_values, true);
		}
		foreach($options as $key=>$val){
			if(strpos($key, "image_attribute")=== false){
				continue; // not an image upload field
			}
			$term_id = intval(str_replace(array("id_", "_image_attribute"), "", $key));
			$alt = $codenegar_wcpf->helper->get_attr_item_name($term_id);
			$lower_key = strtolower($key);
			$classes = '';
			if(isset($_GET['cnpf']) && intval($_GET['cnpf']) ==1 && in_array($term_id, $current_attr_values)) {
				 $classes = 'codenegar_applied_filter chosen';
			}
			echo "<{$element} class=\"codenegar_wcpf_image_attr_item wcpf_{$lower_key}_image {$classes}\">" .
			'<a href="#" data-key="'.$parent.'" data-value="'.$term_id.'" data-widget="attr'.$operator.'" data-type="list">'. // we user "attr" widget name so we don't need tp write filter again
			'<img alt="'.$alt.'" title="'.$alt.'" width="'.$width.'" height="'.$height.'" src="'.$val,'"/> </a>'
			."</{$element}>";
		}
		if($element=="li") echo "</ul>";
		?>
		</div>
		</div>
		<?php
	}
	
	public function nonh_cat($options){
		global $codenegar_wcpf;
		$operator = ($options['operator']=='AND')? 'a' : 'o';
		$parameter_name = "cat{$operator}_cat";
		if(isset($_GET[$parameter_name]) && strlen($_GET[$parameter_name])>0){
			$values = explode(",", $_GET[$parameter_name]);
		}else{
			$values = array();
		}
		if(count($values)>0){
			$values = $codenegar_wcpf->helper->prepare_parameter($values, true);
		}
		$defaults = array(
			'type'                     => 'post',
			'taxonomy'				   => 'product_cat',
			//'orderby'                  => 'id',
			'menu_order'			=> 'asc',
			'order'                    => 'ASC',
			'include_last_update_time' => false,
			'hierarchical'             => false,
			'pad_counts'               => false,
			'selected'				   => 0,
			//'parent' 				   => 0, // direct child only
			'child_of'				   => 0 // direct and grands
		);
		$merged = codenegar_parse_args($options, $defaults);
		$merged['child_of'] = $merged['selected']; // use admin option
		$categories = get_categories($merged);
		?>
		<div class="codenegar_product_filter_wrap wcpf_cat_list  wcpf_cat_list_<?php echo strtolower($options['operator']); ?> <?php echo 'cat_list_' . $merged['selected']; ?>  <?php echo $merged['custom_class']; ?>">
		<span class="codenegar_product_filter_title"><?php echo $merged['sub_title'] ?></span>
		<ul>
		<?php
		$current_cat = 0;
		if(isset($_GET['cnpf']) && intval($_GET['cnpf']) ==1 && isset($_GET[$parameter_name]) && intval($_GET[$parameter_name])>0){
			$current_cat = intval($_GET[$parameter_name]);
		}
		foreach($categories as $category){
			?>
			<li <?php if(isset($_GET['cnpf']) && intval($_GET['cnpf']) ==1 && in_array($category->cat_ID,$values)) { echo 'class="codenegar_applied_filter chosen"'; } ?> > 
				<a href="#" data-key="cat"  data-value="<?php echo $category->cat_ID; ?>" data-widget="cat<?php echo $operator; ?>"  data-type="<?php echo $options['type']; ?>"><?php echo $category->cat_name; ?></a>
			</li>
			<?php
		}
		?>
		</ul>
		</div>
		<?php
	}
}
?>