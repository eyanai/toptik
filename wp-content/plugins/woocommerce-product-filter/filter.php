<?php
if (!defined('ABSPATH')) exit('No direct script access allowed');

 /**
 * CodeNegar WooCommerce AJAX Product Filter filter class
 *
 * changes wp_query global object to filter products
 *
 * @package    	WooCommerce AJAX Product Filter
 * @author      Farhad Ahmadi <ahm.farhad@gmail.com>
 * @license     http://codecanyon.net/licenses
 * @link		http://codenegar.com/woocommerce-ajax-product-filter/
 * @version    	1.9
 */
 
class CodeNegar_wcpf_filter{
	
	function __construct(){
		
	}
	
	public function set_s(&$q){
		$s = "";
		if(isset($_GET['s']) && strlen($_GET['s'])>0){
			$s = $_GET['s'];
		}else{
			return;
		}
		$q->set('s', $s);
	}

	public function set_order(&$q){
		global $woocommerce;
		$ordering_args = $woocommerce->query->get_catalog_ordering_args();
		$orderby = $ordering_args['orderby'];
		$order = $ordering_args['order'];
		$q->set('orderby', $orderby);
		$q->set('order', $order);
		if ( isset( $ordering_args['meta_key'] ) ) {
			$q->set('meta_key', $ordering_args['meta_key']);
		}
	}

	public function set_meta_query(&$q){
		global $codenegar_wcpf;
		$valid_list = $codenegar_wcpf->helper->get_meta_list();
        $valid_list = array_keys($valid_list);
        $get = $_GET;
        $selected_filters = array();
        foreach($get as $key=>$value){
            if(!(strpos($key,"meta_") === false)){ // if key has meta_ string
                $new_key = str_replace("meta_", "", $key);
                if(in_array($new_key, $valid_list)){
                    $selected_filters[$new_key] =  $value;
                }
            }
        }
        $metaquery = array();
        foreach($selected_filters as $key=>$value){
            $values = explode(",", $value);
            if(count($values)!=2){ continue; } // skip this filter
            $min = intval($values[0]);
            $max = intval($values[1]);
            $metaquery[] = array(
                'key' => $key,
                'value' => array($min, $max),
                'type' => 'numeric',
                'compare' => 'BETWEEN'
            );
        }
		
		if(count($metaquery)>0){
			$q->set('meta_query', $metaquery);
		}
	}

	public function set_tax_query(&$q){
		global $codenegar_wcpf;
        $taxquery = array(
            'relation' => 'AND'
        );
		// we only apply on of these three types of category filtring
		// hierarchal category filter
        if(isset($_GET['cat_cat']) && intval($_GET['cat_cat'])>0){ // if category filter is applied
            $taxquery[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => intval($_GET['cat_cat']),
				//'include_children' => true,
                'operator'=> 'IN'
            );
		// "OR" categories filter
        }elseif(isset($_GET['cato_cat']) && strlen($_GET['cato_cat'])>0){
			$values = explode(",", $_GET['cato_cat']);
            if(count($values)>0){
				$values = $codenegar_wcpf->helper->prepare_parameter($values, true);
				$taxquery[] = array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $values,
					'include_children' => false, // There may be a bug in wordpress for this parameter it should be true! but false works fow now!
					'operator'=> 'IN'
				);
			}
		// "AND" categories filter
		}elseif(isset($_GET['cata_cat']) && strlen($_GET['cata_cat'])>0){
			$values = explode(",", $_GET['cata_cat']);
            if(count($values)>0){
				$values = $codenegar_wcpf->helper->prepare_parameter($values, true);
				$taxquery[] = array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $values,
					'include_children' => false, // There may be a bug in wordpress for this parameter it should be true! but false works fow now!
					'operator'=> 'AND'
				);
			}
		}
		
		$valid_list = $codenegar_wcpf->helper->get_attributes();
		$temp = array();
		foreach($valid_list as $attr){
            $temp[] = $attr->attribute_name;
        }
        $valid_list = $temp; unset($temp);
        $get = $_GET;
        $selected_filters = array();
        foreach($get as $key=>$value){
            if((strpos($key,"attra_") !== false) || (strpos($key,"attro_") !== false) || (strpos($key,"attr_") !== false)){ // if key has "attr_" string; attr + And, attr + Or
				if((strpos($key,"attra_") !== false)){
					$operator = 'AND';
				}else{
					$operator = 'IN';
				}
                $new_key = str_replace(array("attra_", "attro_", "attr_"), "", $key); // clean the key
                if(in_array($new_key, $valid_list)){
                    $selected_filters[$new_key] =  $value;
                }
            }
        }
		
		foreach($selected_filters as $key=>$value){
			if(strlen($value)==0){ continue; } // skip this filter
            $values = explode(",", $value);
            if(count($values)==0){ continue; } // skip this filter
			
			$values = $codenegar_wcpf->helper->prepare_parameter($values, true);
            $taxquery[] =array(
					'taxonomy' => 'pa_' . $key,
					'field' => 'id',
					'terms' => $values,
					'include_children' => true,
					'operator' => $operator
				);
        }
		
		if(count($taxquery)>0){
			$q->set('tax_query', $taxquery);
		}
	}
	
	public function posts_where($where){
		global $codenegar_wcpf, $wpdb;
		if(
			!$codenegar_wcpf->helper->is_attrs()
			&& strpos(str_replace(array('"'," "), array("'",""), $where),"post_type='product'") === false // removes space and double quote and check product post type
		)
		{
			return $where;
		}
		
		$operator = ' AND ';
		
        $get = $_GET;
        $selected_filters = array();
        foreach($get as $key=>$value){
            if(!(strpos($key,"attrs_") === false)){ // if key has "attrS_" string: attr + slider
                $new_key = str_replace("attrs_", "", $key); // clean the key
				$selected_filters[$new_key] =  $value;
            }
        }
		
		$new_where = '';
		$i = 1;
		foreach($selected_filters as $key=>$value){
			if(strlen($value)==0){ continue; } // skip this filter
            $values = explode(",", $value);
            if(count($values)!=2){ continue; } // skip this filter, min and max
			
			$values = $codenegar_wcpf->helper->prepare_parameter($values, true); // values are integer
			
			$new_where .= $operator . " ((cntt{$i}.taxonomy='pa_{$key}') AND CAST(cnt{$i}.name AS SIGNED) BETWEEN '{$values[0]}' AND '{$values[1]}') ";
			$i++;
        }
		$where .= $new_where;
		return $where;
	}
	
	public function posts_join($join){
		global $codenegar_wcpf;
		if(!$codenegar_wcpf->helper->is_attrs()){
			return $join;
		}
		
		// okay attribute slider exists
		
		$get = array_keys($_GET);
        $get = implode(" ", $get);
		
		$count_attrs = substr_count($get, 'attrs_');
		global $wpdb;
		for($i=1; $i<=$count_attrs;$i++){
			$join .= " LEFT JOIN {$wpdb->term_relationships} cntr{$i} ON {$wpdb->posts}.ID = cntr{$i}.object_id INNER JOIN {$wpdb->term_taxonomy} cntt{$i} ON cntt{$i}.term_taxonomy_id=cntr{$i}.term_taxonomy_id INNER JOIN {$wpdb->terms} cnt{$i} ON cnt{$i}.term_id = cntt{$i}.term_id ";
		}
		return $join;
	}
	
	public function posts_groupby($groupby){
		global $codenegar_wcpf;
		if(!$codenegar_wcpf->helper->is_attrs()){
			return $groupby;
		}
		
		global $wpdb;
		// we need to group on post ID
		$groupby_id = "{$wpdb->posts}.ID";
		if(strpos($groupby, $groupby_id) !== false) return $groupby;

		// groupby was empty, use ours
		if(!strlen(trim($groupby))) return $groupby_id;

		// wasn't empty, append ours
		return $groupby.", ".$groupby_id;
	}
	
	public function set_date(&$q){ // product publish date filter
		// Maybe in future versions
	}

	public function filter_products(&$q){
		// We only want to affect the main query of shop pages
			if(	
				!isset($_GET['cnpf']) 
				|| $_GET['cnpf'] != "1" 
				|| !$q->is_main_query() 
				|| (!is_tax( 'product_cat' ) && !is_post_type_archive('product') && !is_tax( 'product_tag' ))
				){
			return;
			}
		global $wp_query;

		$this->set_tax_query($q);
		$this->set_order($q);
		$this->set_meta_query($q);
		$this->set_s($q);
		if(isset($_GET['cnep']) && intval($_GET['cnep']) == 0){
			$wp_query->set('paged', 1);
		}
		return; // by reference; no return value
	}
}
?>