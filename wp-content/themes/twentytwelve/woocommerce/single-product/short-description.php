<?php
/**
 * Single product short description
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

if ( ! $post->post_excerpt ) return;
?>

<div itemprop="description" class="socialDescrip">
<div class="titelWcon"></div>	
	<div class="fave cf">
		<!--<span class="addFav"></span>
		 <a href="mailto:name@email.com?subject=מומלץ מאתר טופתיק" class="sendFr"></a>-->
		<span class="printF"></span>
	</div>


<?php echo '<div class="social"><div class="titelWcon"></div>'.do_shortcode('[ssba]').'<div class="titelWcon"></div></div>'; 
	?>
	<?php //echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
</div>
