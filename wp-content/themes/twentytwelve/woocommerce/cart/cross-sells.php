<?php
/**
 * Cross-sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

?>
<div class="popupCart">      
		<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'החשבון שלי' ) ) ); ?>" class="members"></a>
		<a href="#" class="lock"></a>
</div>
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce_loop, $woocommerce, $product;

$crosssells = $woocommerce->cart->get_cross_sells();

if ( sizeof( $crosssells ) == 0 ) return;

$meta_query = $woocommerce->query->get_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => apply_filters( 'woocommerce_cross_sells_total', 4),
	'no_found_rows'       => 1,
	'orderby'             => 'rand',
	'post__in'            => $crosssells,
	'meta_query'          => $meta_query
);

$products = new WP_Query( $args );

$woocommerce_loop['columns'] 	= apply_filters( 'woocommerce_cross_sells_columns', 2 );

if ( $products->have_posts() ) : ?>

	<div class="cross-sells">
		<div class="recommendedtop">
		<h2 class="crossrec"><?php _e( 'You may be interested in&hellip;', 'woocommerce' ) ?></h2>
		</div>
		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php woocommerce_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end(); ?>

	</div>

<?php endif;
get_sidebar('midel');
wp_reset_query();