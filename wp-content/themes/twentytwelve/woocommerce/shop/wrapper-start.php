<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$template = get_option('template');

switch( $template ) {
	case 'twentyeleven' :
		echo '<div id="primary"><div id="content" role="main">';
		break;
	case 'twentytwelve' :
	
		 
	
		if ( !function_exists('dynamic_sidebar') ||
           !dynamic_sidebar('איזור בנארים') ) : ?>
  <!-- This will be displayed if the sidebar is empty -->
  		
<?php endif;
		if (is_product_category()){
				global $wp_query;
				// get the query object
				$cat = $wp_query->get_queried_object();
				// get the thumbnail id user the term_id
				$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true ); 
				// get the image URL
				$image = wp_get_attachment_url( $thumbnail_id ); 
				// print the IMG HTML
				echo '<img src="'.$image.'" alt="" class="catImg" />';
				}
			if(!is_single()){
				echo '<div id="primary" class="site-content"><div id="content" role="main">';
			}else{
				echo '<div id="primary" class="site-content singelPage"><div id="content" role="main">';
			}	
		
		break;
	case 'twentythirteen' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content twentythirteen">';
		break;
	default :
		echo '<div id="container"><div id="content" role="main">';
		break;
}