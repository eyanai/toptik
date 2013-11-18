<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="singelPrice">
	<?php if ($product->is_on_sale()) : ?>
	
		<?php echo apply_filters('woocommerce_sale_flash', '<span class="onsale singel">'.__( '', 'woocommerce' ).'</span>', $post, $product); ?>
	
	<?php endif; ?>

	<p itemprop="price" class="price"><?php echo $product->get_price_html(); ?></p>

	<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
	<div class="singelCredit">
			<?php 
				$cPrice=$product->get_price();
				$topCredit=getCredit();
				
				echo "<span class='credirArrow'> &nbsp;".$cPrice*$topCredit ."</span> &nbsp; נקודות קרדיט שתצבור ברכישת מוצר זה <span class='topPlus'>+</span>";
			?>
		</div>
</div>

<?php
 
/*
*  View array data (for debugging)
*/
 
 
/*
*  Loop through post objects ( setup postdata )
*  Using this method, you can use all the normal WP functions as the $post object is temporarily initialized within the loop
*  Read more: http://codex.wordpress.org/Template_Tags/get_posts#Reset_after_Postlists_with_offset
*/
 
$posts = get_field('similar_pro');
 
if( $posts ): ?>
<span class="titelWcon singel color"><h3>בחר צבע</h3></span>
	<div class="imageVer">
	<span class="rver">‹</span>
		<div class="imagvarCon">
			<div class="varslide">
	<?php foreach( $posts as $post): // variable must be called $post (IMPORTANT) ?>
		<?php setup_postdata($post); ?>
	    
			<?php $color=get_field( "color_pro" ); ?>
	    	<a href="<?php echo post_permalink($post->ID);//the_permalink(); ?>" alt='<?php the_title(); ?>' >
			<?php echo get_the_post_thumbnail( $post->ID, array(60,60), array('title'=>$color,'alt'=>$color,'class'=>'imgcolor')); ?>
	    	</a>

	<?php endforeach; ?>
	
	<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
			</div>
		</div>
	<span class="lver">›</span>
	</div>
<br>


<?php endif;
 $user_ID = get_current_user_id();
 //echo $user_ID;
 //echo (get_user_meta($user_ID,'credit',true));
// echo print_r($user,1);
//update_user_meta($user_ID,'credit','5000');
 //if(update_user_option( $user_ID, 'credit', '500' )){echo 'up';}else{'nop';};

?>