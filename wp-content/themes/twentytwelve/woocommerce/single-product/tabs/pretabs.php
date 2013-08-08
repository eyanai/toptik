<?php
/**
 * Additional Information tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post, $product;

$heading = apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional Information', 'woocommerce' ) );
?>
<div class="tecdeitel">
	<div class="mandetail"></div>
	<span class="titelWcon singel"><h3>מפרט טכני</h3></span>
	<?php $product->list_attributes(); ?>
</div>