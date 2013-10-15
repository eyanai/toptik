<?php
/**
 * Order details
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce,$current_user;

$order = new WC_Order( $order_id );


?>
	<div class="orderFinish">
	<div class="orderFImg"></div>
		<p>הזמנתך התבצעה בהצלחה:<br>
			אישור הזמנה מספר <?php echo $order->id; ?> נשלח אליך למייל השמור המערכת
		</p>
		<p>תודה שקנית אצלינו</p>
		<a href="<?php echo home_url(); ?>" class="soppingMore">להמשך קנייה</a>
	</div>
<div class="clear"></div>
<?php do_action('woocommerce_credit_reset'); ?>
