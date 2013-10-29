<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if(!empty($_GET['added-to-cart'])){
	?>
	<script>
		window.location= '<?php echo get_permalink(get_page_by_path('cart'));?>';
	</script>
<?php		
	}


get_header('shop'); ?>


	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action('woocommerce_before_main_content');
	?>

		<?php while ( have_posts() ) : the_post(); 
			 if($post->post_title=='הצטרפות למועדון לקוחות'){
				 woocommerce_get_template_part('content', 'single-product_toptik' );
			}else{
		?>

			<?php woocommerce_get_template_part( 'content', 'single-product' ); ?>

		<?php } 
		endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>

	<?php if(!is_single()):?>
	<div class="singel_r">
	
	<?php 
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		 
		do_action('woocommerce_sidebar');
		
	?>
	</div>
	
	<?php endif;?>
	 <?php if($post->post_title!='הצטרפות למועדון לקוחות'){
	 	get_sidebar('midel');
	}else{
		get_footer('club');
	}?>
<?php get_footer('shop'); ?>