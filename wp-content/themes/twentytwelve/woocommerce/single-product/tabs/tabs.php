<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post;
/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
/*
if ( ! empty( $tabs ) ) : ?>

	<div class="woocommerce-tabs">
		<ul class="tabs">
			<?php foreach ( $tabs as $key => $tab ) : ?>

				<li class="<?php echo $key ?>_tab">
					<a href="#tab-<?php echo $key ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
				</li>

			<?php endforeach; ?>
		</ul>
		<?php foreach ( $tabs as $key => $tab ) : ?>

			<div class="panel entry-content clear" id="tab-<?php echo $key ?>">
				<?php call_user_func( $tab['callback'], $key, $tab ) ?>
			</div>

		<?php endforeach; ?>
	</div>

<?php endif; */?>

<div class="woocommerce-tabs">
		<ul class="tabs">
				<li class="description_tab">
					<a href="#tab-description"><span class="decTab"></span>תיאור</a>
				</li>
				<li class="contactUs_tab active">
					<a href="#tab-contactUs"><span class="contactUstab"></span>צור קשר</a>
				</li>
				<li class="baysafe_tab">
					<a href="#tab-baysafe"><span class="bayTab"></span>קנייה בטוחה</a>
				</li>
				<li class="topsepping_tab">
					<a href="#tab-topsepping"><span class="topsepping"></span>משלוחים</a>
				</li>
				<li class="returns_tab">
					<a href="#tab-returns"><span class="returnTab"></span>החזרות</a>
				</li>
					</ul>
			<div class="panel entry-content clear" id="tab-description" style="display: none;">
			<?php 
		$heading = esc_html( apply_filters('woocommerce_product_description_heading', __( 'Product Description', 'woocommerce' ) ) );
				?>

			<h2><?php echo $heading; ?></h2>
			
			<?php the_content(); ?>

		</div>
	<div class="panel entry-content clear" id="tab-contactUs" style="display: block;">
			<?php $term=get_term_by( 'name', 'ראשי', 'tab_text');// var_dump($term)?>
			<?php $cus=get_field('contactUs', 'tab_text_'.$term->term_id); ?>		
			<?php if(!empty($cus)){?>		
					<p><?php echo $cus;?></p>
			<?php }else{?>
			<div class="tabcon contect">
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
			
		</div>
		<?php }?>
		
	</div>

		
	<div class="panel entry-content clear" id="tab-baysafe" style="display: none;">
				<div class="tabcon">
					<h2>קנייה בטוחה</h2>
					
					<p><?php the_field('baysafe', 'tab_text_'.$term->term_id); ?></p>
					
				</div>
			<div class="tabimg baysafet"></div>
		</div>

		
			<div class="panel entry-content clear" id="tab-topsepping" style="display: none;">
				<div class="tabcon">
					<h2>משלוחים</h2>

					<p><?php the_field('topsepping', 'tab_text_'.$term->term_id); ?></p>	
				</div>
				<div class="tabimg shpping"></div>
			</div>

		
			<div class="panel entry-content clear" id="tab-returns" style="display: none;">
				<div class="tabcon">
						<h2>החזרות מוצר</h2>
						<p><?php the_field('returns', 'tab_text_'.$term->term_id); ?></p>
					</div>			
				<div class="tabimg return"></div>
			</div>

			</div>