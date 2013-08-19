<?php
if (!defined('ABSPATH')) exit('No direct script access allowed');

 /**
 * CodeNegar WooCommerce AJAX Product Filter helper class
 *
 * Contains common plugin methods
 *
 * @package    	WooCommerce AJAX Product Filter
 * @author      Farhad Ahmadi <ahm.farhad@gmail.com>
 * @license     http://codecanyon.net/licenses
 * @link		http://codenegar.com/woocommerce-ajax-product-filter/
 * @version    	1.9
 */
 
class CodeNegar_wcpf_helper{
	
	function __construct(){
		
	}
	
	/**
	* Converts string to int and makes sure string parameters are safe
	* @param string/int/array $input, user input value
	* @param boolean $is_int, force convert to int 
	* @return string/int safe parameter
	*/
	
	public function prepare_parameter($input, $is_int=false){
	
		if(is_array($input)){
			foreach($input as $key=>$value){
				if($is_int){
					$input[$key] = intval($value);
				}else{
					$input[$key] = trim(stripslashes(strip_tags($value)));
				}
			}
		}else{
			if($is_int){
				$input = intval($input);
			}else{
				$input = trim(stripslashes(strip_tags($input)));
			}
		}
		return $input;
	}
	
	public function prepare_sidebars($sidebars){
		$sidebars = str_replace("'", '"', $sidebars);
		$sidebars = stripslashes ($sidebars);
		$sidebars = (array) json_decode($sidebars, true);
        $return = array();
        foreach($sidebars as $sidebar){
            if(count($sidebar)>0){
				$sidebar['apply_to'] = implode(',', (array)$sidebar['apply_to']);
                $return[] = $sidebar;
            }
        }
        $return = json_encode($return);
        return $return;
	}
	
	public function get_sidebars_table($sidebars){
		global $codenegar_wcpf;
		$sidebars = stripslashes ($sidebars);
		$sidebars = (array) json_decode($sidebars, true);
		$return = "";
		$cat_title = __('Category', $codenegar_wcpf->text_domain);
		$attr_title = __('Attribute', $codenegar_wcpf->text_domain);
		$all_archive_title = __('All Shop Archive', $codenegar_wcpf->text_domain);
		$template = '<tr data-row-id="%id%"> <td>%title%</td> <td>%visible_to%</td> <td>%shortcode%</td> <td><a href="#" class="remove_row">' . __('Remove', $codenegar_wcpf->text_domain) . '</a></td> </tr>';
        $id = 0;
		foreach($sidebars as $sidebar){
			$visible_to = str_replace(array('cat', 'attr', 'all'), array($cat_title, $attr_title, $all_archive_title), $sidebar['visible_to']);
		    $return .= str_replace(
                array('%id%', '%title%', '%visible_to%', '%apply_to%', '%shortcode%'),
                array($id, $sidebar['title'], $visible_to, $sidebar['apply_to'], $this->get_shortcode_string($sidebar)),
                $template
            );
            $id++;
		}
        return $return;
	}
	
	public function get_sidebars_object($sidebars){
		$sidebars = stripslashes ($sidebars);
		$sidebars = (array) json_decode($sidebars, true);
		$return = "";
		$template = '{title: "%title%", visible_to: "%visible_to%", apply_to: "%apply_to%"}, ';
        $id = 0;
		foreach($sidebars as $sidebar){
		    $return .= str_replace(
                array('%id%', '%title%', '%visible_to%', '%apply_to%'),
                array($id, $sidebar['title'], $sidebar['visible_to'], $sidebar['apply_to']),
                $template
            );
            $id++;
		}
        return $return;
	}
	
	public function get_shortcode_string($sidebar){
		$sidebar['apply_to'] = ($sidebar['apply_to'] != '')? $sidebar['apply_to'] : 0;
        return '[ajax_product_filter data=' . $sidebar['title'] . ':' . $sidebar['visible_to'] . ':' . $sidebar['apply_to'] . ']';
    }
	
	public function get_function_string($sidebar){
		$sidebar['apply_to'] = ($sidebar['apply_to'] != '')? $sidebar['apply_to'] : 0;
        return '<?php ajax_product_filter( "' . $sidebar['title'] . ':' . $sidebar['visible_to'] . ':' . $sidebar['apply_to'] . '" ); ?>';
    }
	
	/**
	* Return limit length of a Wordpress post
	* @param int $limit, number of maximum characters to return
	* @return string limited character of input
	*/
	
	public function limit_str($str, $limit=100) {
        $str = trim(strip_tags($str));
		$str = strip_shortcodes($str);
		$excerpt = mb_substr($str,0,$limit);
		if (strlen($excerpt)<strlen($str)) {
			$excerpt .= '...';
		}
		return $excerpt;
	}
	
	/**
	* Registers sidebars for using Filters anywhere
	*/
	
	public function register_sidebars(){
		global $codenegar_wcpf;
		$sidebars = $codenegar_wcpf->options->sidebars;
		$sidebars = stripslashes ($sidebars);
		$sidebars = (array) json_decode($sidebars, true);
		foreach($sidebars as $sidebar){
			register_sidebar(array(
				'name'=>$sidebar['title'],
				'id' => 'codenegar_wcpf_sidebar_' . strtolower($sidebar['title']),
				'description' => __('Add Product Filters here and use shortcode '.$this->get_shortcode_string($sidebar).' or function  '.$this->get_function_string($sidebar).'.', $codenegar_wcpf->text_domain),
				'before_widget' => '<div class="codenegar_wcpf_widget_"'. $sidebar['title'].'>',
				'after_widget' => "</div>",
				'before_title' => '<h3 id="codenegar_wcpf_title_'. $sidebar['title'] .'">',
				'after_title' => "</h3>"
			));
		}
	}
	
	/**
	* Registers a shortcode for using Filters anywhere
	*/
	
	public function shortcode($atts=false){
		if(!$atts || !isset($atts['data']) || strlen($atts['data'])==0 || substr_count($atts['data'], ':')!=2 ){
            return;
        }

		$atts = strtolower($atts['data']);
        $atts = explode(':', $atts);
        $sidebar_id = 'codenegar_wcpf_sidebar_' . $atts[0];
		if($atts[2] == 0){
			$atts[2] = ''; // set is_tax second parameter to none
		}
		$apply_to = explode(',', $atts[2]);
		
		if(!in_array($atts[1], array('all', 'cat', 'attr'))){
            return;
        }
		
		if($atts[1] == 'cat' && !is_tax('product_cat', $apply_to)){
			return;
		}else if($atts[1] == 'attr' && !is_tax('product_tag', $apply_to)){
			return;
		}else if($atts[1] == 'all' && !is_tax('product_cat') && !is_post_type_archive('product') && !is_tax('product_tag')){
			return;
		}

		ob_start();
		dynamic_sidebar($sidebar_id);
		return ob_get_clean();
	}
	
	/**
	* Registers a shortcode for using widget anywhere
	*/
	
	public function register_shortcode(){
		add_shortcode("ajax_product_filter", array(&$this, 'shortcode'));
	}
	
	/**
	* Converts array to stdClass
	* @return stdClass of input
	*/
	
	public function array_to_object($input){
		if (is_array($input)) {
			return (object) array_map(array(&$this, 'array_to_object'), $input);
		}
		else {
			return $input;
		}
	}
	
	/**
	* Woocommerce Product Filter default options
	* @return array of default options
	*/
	
	public function default_options(){
		$defaults = array(
			'loader_image' => WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/' . 'images/ajax-loader2x.gif',
			'sidebars'=> "",
			'display_no_products_message'=>'no'
		);
		return $defaults;
	}
	
	public function dropdown_cat($options=array()) {
		global $codenegar_wcpf;
		$defaults = array(
			'type'                     => 'post',
			'taxonomy'				   => 'product_cat',
			'orderby'                  => 'id',
			'order'                    => 'ASC',
			'include_last_update_time' => false,
			'hierarchical'             => true,
			'pad_counts'               => false,
			'selected'				   => 0,
			'name' 					   => 'cat',
			'id'                       => '',
			'class'                    => 'postform',
			'echo' 					   => true,
			'child_of'				   => 0,
			'depth' 				   => 0,
			'show_option_all' 		   => __('Any category', $codenegar_wcpf->text_domain),
			'echo'               	   => 1
		);
		$merged = codenegar_parse_args($options, $defaults);
		return wp_dropdown_categories($merged);
	}
	
	public function get_attributes(){
		global $woocommerce;
		$attribute_taxonomies = $woocommerce->get_attribute_taxonomies();
		return $attribute_taxonomies;
	}

	public function get_attribute_values($attribute_name){
		global $woocommerce;
		$attribute_taxonomy_name = $woocommerce->attribute_taxonomy_name( $attribute_name );
		$all_terms = get_terms( $attribute_taxonomy_name, 'orderby=name&hide_empty=1' );
		return $all_terms;
	}
	
	public function is_attrs(){ // attr + slider
		if(	
			!isset($_GET['cnpf']) 
			|| $_GET['cnpf'] != "1"
			){
		return false;
		}
		$get = array_keys($_GET);
        $get = implode(" ", $get);
        if(strpos($get, "attrs_")===false){
            return false;
        }
		return true;
	}
	
	public function get_meta_list(){
		global $codenegar_wcpf;
		$meta_list = array(
			'_rate' => __('Rate', $codenegar_wcpf->text_domain),
			'_price' => __('Price', $codenegar_wcpf->text_domain),
			'_weight' => __('Weight', $codenegar_wcpf->text_domain),
			'total_sales' => __('Total Sales', $codenegar_wcpf->text_domain),
			'_length' => __('Length', $codenegar_wcpf->text_domain),
			'_width' => __('Width', $codenegar_wcpf->text_domain),
			'_height' => __('Height', $codenegar_wcpf->text_domain)
		);
		return $meta_list;
	}
	
	public function is_wc(){
		global $woocommerce;
		if(!isset($woocommerce) || !is_object($woocommerce)){
			return false;
		}
		return true;
	}
	
	public function add_layered_class($before_widget){
		$layered_class = 'woocommerce widget_layered_nav ';
		if(strpos($before_widget, 'class') === false ){
			$before_widget = str_replace('>', 'class="'. $layered_class . '"', $before_widget);
		}
		else{
			$before_widget = str_replace('class="', 'class="'. $layered_class, $before_widget);
		}
		return $before_widget;
	}
	
	public function remove_filter_parameters($url){
		$url_parts = parse_url($url);
		if(isset($url_parts['query'])){
			parse_str( $url_parts['query'], $params);
		}else{
			$params = array();
		}
		
		// Remove the "tracking" parameter
		if( isset( $params['cnpf'])) {
			unset( $params['cnpf']); 
		}
		
		if( isset( $params['cnep'])) {
			unset( $params['cnep']); 
		}
		foreach($params as $key=>$val){
			if(
			(strpos($key, "attr_") !== false) ||
			(strpos($key, "attro_") !== false) ||
			(strpos($key, "attra_") !== false) ||
			(strpos($key, "attrs_") !== false) ||
			(strpos($key, "cat_cat") !== false)
			)
			{
				unset( $params[$key]);
			}
		}
		
		$return = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . http_build_query($params);
		return $return;
	}
	
	public function get_attr_item_name($id){
		global $wpdb;
		$sql = "SELECT name from " . $wpdb->terms . " WHERE term_id = " . intval($id);
		$title = $wpdb->get_var($sql);
		if($title && strlen($title)>0){
			return (string) $title;
		}
		return '';
	}
}
?>