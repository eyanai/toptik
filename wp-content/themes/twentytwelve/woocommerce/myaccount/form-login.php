<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce; ?>
<?php $woocommerce->show_messages(); ?>

<?php do_action('woocommerce_before_customer_login_form'); ?>

<?php if (get_option('woocommerce_enable_myaccount_registration')=='yes') : ?>

<div class="col2-set" id="customer_login">

<?php endif; ?>
<?php /*?><pre style="direction:ltr ;"><?php   $order_id=$woocommerce->cart->cart_contents[1];echo $order_id; echo print_r($woocommerce->cart->cart_contents[0],1);?></pre>
<?php */?>



<div class="optionNewCastuomer clear">
	<div class="optionCas">
		<h2 class="newH2"><span class="newH2span">לקוח קיים</span> [<span class="red">*</span>] שדות חובה</h2>
		<?php
		echo woocommerce_login_form();
		 global $current_user;
		if($user_login){
			echo "אתה כבר מחובר...";?>
		
			<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'החשבון שלי' ) ) );?>" class="submit_btb_cont login">המשך</a>	
		<?php	
		
		}else{
			
		}
		 $alldata=get_user_meta($current_user->ID);
		//echo "<pre>".print_r($alldata,1)."</pre>";
	?>
	</div>
	<span class="orCastoumer"></span>
	<div class="optionCas">
		<h2 class="newH2span">לקוח חדש?</h2>
		<p>על מנת להשלים את תהליך הקנייה<br>
			אנו זקוקים למספר פרטים ממך.<br>
			אנא לחצ/י על כפתור "המשך" למעבר לטופס<br>
			הרישום.</p>
		<a href="<?php echo get_permalink(get_page_by_path('הצטרף'));?>" class="submit_btb_cont">המשך</a>
		<?php 
		//generate_paypal_form();?>	
	</div>
	<?php /*?><span class="orCastoumer"></span>
	<div class="optionCas">
		<h2 class="newH2span">הזמנה מהירה</h2>
		<p>במידה ויש לך חשבון פייפאל או אם ברצונך<br>
			לבצע את התשלום באמצעות מערכת פייפאל<br>
			לחץ על הכפתור הבא:</p>
			<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'עגלת קניות' ))); ?>" class="submit_btb_paypal"></a>
	</div><?php */?>

</div>	

		<?php /*?>
		<h2><?php _e( 'Login', 'woocommerce' ); ?></h2>
		<form method="post" class="login">
			<p class="form-row form-row-first">
				<label for="username"><?php _e( 'Username or email', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="text" class="input-text" name="username" id="username" />
			</p>
			<p class="form-row form-row-last">
				<label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input class="input-text" type="password" name="password" id="password" />
			</p>
			<div class="clear"></div>

			<p class="form-row">
				<?php $woocommerce->nonce_field('login', 'login') ?>
				<input type="submit" class="button" name="login" value="<?php _e( 'Login', 'woocommerce' ); ?>" />
				<a class="lost_password" href="<?php

				$lost_password_page_id = woocommerce_get_page_id( 'lost_password' );

				if ( $lost_password_page_id )
					echo esc_url( get_permalink( $lost_password_page_id ) );
				else
					echo esc_url( wp_lostpassword_url( home_url() ) );

				?>"><?php _e( 'Lost Password?', 'woocommerce' ); ?></a>
			</p>
		</form>
<?php */?>
<?php if (get_option('woocommerce_enable_myaccount_registration')=='yes') : ?>

	</div>

	<?php /*?><div class="col-2">

		<h2><?php _e( 'Register', 'woocommerce' ); ?></h2>
		<form method="post" class="register">

			<?php if ( get_option( 'woocommerce_registration_email_for_username' ) == 'no' ) : ?>

				<p class="form-row form-row-first">
					<label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="text" class="input-text" name="username" id="reg_username" value="<?php if (isset($_POST['username'])) echo esc_attr($_POST['username']); ?>" />
				</p>

				<p class="form-row form-row-last">

			<?php else : ?>

				<p class="form-row form-row-wide">

			<?php endif; ?>

				<label for="reg_email"><?php _e( 'Email', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="email" class="input-text" name="email" id="reg_email" value="<?php if (isset($_POST['email'])) echo esc_attr($_POST['email']); ?>" />
			</p>

			<div class="clear"></div>

			<p class="form-row form-row-first">
				<label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password" id="reg_password" value="<?php if (isset($_POST['password'])) echo esc_attr($_POST['password']); ?>" />
			</p>
			<p class="form-row form-row-last">
				<label for="reg_password2"><?php _e( 'Re-enter password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password2" id="reg_password2" value="<?php if (isset($_POST['password2'])) echo esc_attr($_POST['password2']); ?>" />
			</p>
			<div class="clear"></div>

			<!-- Spam Trap -->
			<div style="left:-999em; position:absolute;"><label for="trap">Anti-spam</label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

			<?php do_action( 'register_form' ); ?>

			<p class="form-row">
				<?php $woocommerce->nonce_field('register', 'register') ?>
				<input type="submit" class="button" name="register" value="<?php _e( 'Register', 'woocommerce' ); ?>" />
			</p>

		</form>

	</div><?php */?>

</div>
<?php endif; ?>

<?php do_action('woocommerce_after_customer_login_form'); ?>