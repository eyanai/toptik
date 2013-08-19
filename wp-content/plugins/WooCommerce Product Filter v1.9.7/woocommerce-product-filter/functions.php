<?php
if (!defined('ABSPATH')) exit('No direct script access allowed');

 /**
 * CodeNegar WooCommerce AJAX Product Filter functions
 *
 * Non object oriented functions
 *
 * @package    	WooCommerce AJAX Product Filter
 * @author      Farhad Ahmadi <ahm.farhad@gmail.com>
 * @license     http://codecanyon.net/licenses
 * @link		http://codenegar.com/woocommerce-ajax-product-filter/
 * @version    	1.9
 */
 

function codenegar_wcpf_generate_widget($args){
	global $codenegar_wcpf;
	wp_enqueue_style('codenegar-wcpf-frontend-style');
	wp_enqueue_script('jquery');
	wp_enqueue_script('codenegar-ajax-search-migrate');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('codenegar-wcpf-history-js');
	wp_enqueue_script('codenegar-wcpf-frontend');
	
	if(!$codenegar_wcpf->is_localized){
		$codenegar_wcpf->localize_script_config();
		$codenegar_wcpf->is_localized = true;
	}
	
	$widget = $args['widget'];
	$type = $args['type'];
	switch ($widget){
        case 'attr':
			switch ($type){
				case 'list':
					$codenegar_wcpf->html->attr_list($args);
				break;
				case 'slider':
					$codenegar_wcpf->html->attr_slider($args);
				break;
				case 'dropdown':
					$codenegar_wcpf->html->attr_dropdown($args);
				break;
			}
        break;
        case 'cat':
			switch ($type){
				case 'list':
					$codenegar_wcpf->html->cat_list($args);
				break;
				case 'dropdown':
					$codenegar_wcpf->html->cat_dropdown($args);
				break;
			}
        break;
        case 'meta':
			switch ($type){
				case 'slider':
					$codenegar_wcpf->html->meta_slider($args);
				break;
			}
		break;
		case 'orderby':
			$codenegar_wcpf->html->orderby($args);
		break;
		case 'image_attr':
			$codenegar_wcpf->html->image_attr($args);
		break;
		case 'nonh_cat':
			$codenegar_wcpf->html->nonh_cat($args);
		break;
    }
}

if(!function_exists('codenegar_parse_args')){

	function codenegar_parse_args($args, $defaults = ''){
		if ( is_object( $args ) )
			$r = get_object_vars( $args );
		elseif ( is_array( $args ) )
			$r =& $args;
		else{
            $r = array();
			wp_parse_str( $args, $r ); // second parameter is output
        }
		if ( is_array( $defaults ) )
			return codenegar_array_merge( $defaults, $r );
		return $r;
	}
}

if(!function_exists('codenegar_array_merge')){

	function codenegar_array_merge(){
		$params = func_get_args();
		$merged = array_shift($params); // using first array as base
	 
		foreach ($params as $array){
			foreach ($array as $key => $value){
				if (isset($merged[$key]) && is_array($value) && is_array($merged[$key])){
					$merged[$key] = codenegar_array_merge($merged[$key], $value);
				}
				else{
					$merged[$key] = $value;
				}
			}
		}
		return $merged;
	}
}

function ajax_product_filter($data){
	echo do_shortcode('[ajax_product_filter data="'.$data.'"]');
}
?>