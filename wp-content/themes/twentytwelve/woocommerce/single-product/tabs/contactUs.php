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

$heading = esc_html( apply_filters('woocommerce_product_description_heading', __( 'צור קשר', 'woocommerce' ) ) );
?>
<div class="tabcon contect">
<?php if(	$field = get_field('contactUs', $product->ID, false) ):?>


<h2><?php echo $heading; ?></h2>

<?php 
	echo $field;
	else:
?>

	<section class="clear">
		<span class="phoneicon"></span>
		<div class="maincon"><h2>טלפון</h2>
		<p>התקשרו אלינו ללא חיוב למספר : 03-6958794</p>
		<span class="littel">אנחנו זמינים מראשון לחמישי בין השעות 8:30 עד 17:30</span></div>
	</section>
	<section class="clear">
		<span class="mailicon"></span>
		<div class="maincon"><h2>אימייל</h2>
		<p>	שלחו לנו איימיל ואחד מנציגנו יהיה לשירותכם</p>
		<span class="littel">לשליחת מייל לשאלה או הצעה <a href="#">אנא לחצו כאן</a></span></div>
	</section>
	<section class="clear">
		<span class="manicon"></span>
		<div class="maincon"><h2>שירות לקוחות</h2>
		<p>מצא מידע ע כל הנושאים כגון משלוים החזרות וכ"ו</p>
		<span class="littel">לקריאת מידע לעזרה<a href="#"> אנא לחץ כאן</a></span></div>
	</section>
	<section class="clear">
		<span class="lighticon"></span>
		<div class="maincon"><h2>הצעות למערכת</h2>
		<p>ההיתם רוצים לראות מותגים נוספים? יש לכם הצעות ייעול לשיפור החוויה באתר? אנא ידעו אותנו</p>
		<span class="littel">לשלחית הצעתכם<a href="#"> אנא ליחצו כאן</a></span></div>
	</section>
	

<?php endif;?>	
</div>
