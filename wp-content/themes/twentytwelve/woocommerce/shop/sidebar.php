<?php
/**
 * Sidebar
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	if(!is_product_tag()){
		get_sidebar('shop'); 
	}else{
		get_sidebar('tags'); 
	}
?>