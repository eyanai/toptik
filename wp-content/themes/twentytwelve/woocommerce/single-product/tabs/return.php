<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post;

$heading = esc_html( apply_filters('woocommerce_product_description_heading', __( 'החזרות מוצר', 'woocommerce' ) ) );
?>
<div class="tabcon">
<h2><?php echo $heading; ?></h2>

<?php 
	$field = get_field('returns', $product->ID, false); 
	echo $field;
?>
<div class="tabimg return">
</div>
</div>