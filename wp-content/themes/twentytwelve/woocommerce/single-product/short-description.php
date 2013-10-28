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

//if ( ! $post->post_excerpt ) return;
?>

<div itemprop="description" class="socialDescrip">
<div class="titelWcon"></div>	
	<div class="fave cf">
		<span class="addFav"></span>
		 <a href="mailto:name@email.com?subject=מומלץ מאתר טופתיק&body=<?php echo get_permalink( $post->ID ); ?>" class="sendFr"></a>
		<span class="printF"></span>
	</div>


<?php // echo '<div class="social"><div class="titelWcon"></div>'.do_shortcode('[ssba]').'<div class="titelWcon"></div></div>'; 
	?>
	<?php //echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
 	<div class="social">
		<div class="titelWcon"></div>
		
			<span class="socSpan pin">
				<a href="//www.pinterest.com/pin/create/button/?url=<?php echo get_permalink( $post->ID )?>%2F&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>
			</span>	
			<!-- Place this tag where you want the +1 button to render. -->
			<span class="socSpan goog">
			<div class="g-plusone" data-size="medium" data-annotation="none"></div>
			</span>
			<span class="socSpan">			
				<a href="https://twitter.com/share" class="twitter-share-button soc" data-url="<?php echo get_permalink( $post->ID ); ?>">Tweet</a>
			</span>
				
			
			<script type="text/javascript">
			  window.___gcfg = {lang: 'iw'};
			
			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>	
		</span>
			<span class="socSpan fb">
				<div class="fb-like" data-href="http://toptik.co.il.tigris.nethost.co.il/shop/luggage/%D7%9E%D7%96%D7%95%D7%95%D7%93%D7%94-tank-trolly-63cm-%D7%9E%D7%A0%D7%93%D7%A8%D7%99%D7%A0%D7%94-%D7%93%D7%90%D7%A7-mandarinaduck/" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="true" data-send="false"></div>
			</span>
				
					
					<!-- Place this tag after the last +1 button tag. -->

		<?php //echo get_permalink( $post->ID ); ?>
		<div class="titelWcon"></div>
	</div>
</div>
