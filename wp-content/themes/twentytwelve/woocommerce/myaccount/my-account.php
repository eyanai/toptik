<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $current_user;

$all=get_currentuserinfo();
//var_dump($current_user);
 $user_ID = get_current_user_id();
//var_dump(get_user_meta($user_ID));
 //echo $user_ID;
 //echo (get_user_meta($user_ID,'credit',true));
// echo print_r($user,1);
//update_user_meta($user_ID,'credit','5000');
 //if(update_user_option( $user_ID, 'credit', '500' )){echo 'up';}else{'nop';};

//user info cack
if(isset($_POST['infoSub'])){
	//validation
	$billing_first_name=filter_input(INPUT_POST,'first_name',FILTER_SANITIZE_SPECIAL_CHARS);
	$billing_last_name=filter_input(INPUT_POST,'last_name',FILTER_SANITIZE_STRING);
	$user_sex=filter_input(INPUT_POST,'user_sex');
	$billing_company=filter_input(INPUT_POST,'birth',FILTER_SANITIZE_STRING);
	$billing_email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_STRING);
	$billing_address_1=filter_input(INPUT_POST,'address',FILTER_SANITIZE_STRING);
	$billing_address_2=filter_input(INPUT_POST,'mailBox',FILTER_SANITIZE_STRING);
	$billing_city=filter_input(INPUT_POST,'city',FILTER_SANITIZE_STRING);
	$billing_post_code=filter_input(INPUT_POST,'zipcode',FILTER_SANITIZE_STRING);
	
//$billing_first_name.'-'.$billing_last_name.'-'.$user_sex.'-'.$billing_company.'-'.
//$billing_email.'-'.$billing_address_1.'-'.$billing_address_2.'-'.	$billing_city.'-'.	$billing_post_code;
	
	update_user_meta($user_ID,'billing_first_name',$billing_first_name);
	update_user_meta($user_ID,'billing_last_name',$billing_last_name);
	update_user_meta($user_ID,'user_sex',$user_sex);
	update_user_meta($user_ID,'billing_company',$billing_company);
	update_user_meta($user_ID,'billing_email',$billing_email);
	update_user_meta($user_ID,'billing_address_1',$billing_address_1);
	update_user_meta($user_ID,'billing_address_2',$billing_address_2);
	update_user_meta($user_ID,'billing_city',$billing_city);
	update_user_meta($user_ID,'billing_post_code',$billing_post_code);
	
	$update="פרטים עודכנו בהצלחה";
}


//user pass
if(isset($_POST['passSub'])){
	//validation
	$nickname=filter_input(INPUT_POST,'new_usename',FILTER_SANITIZE_SPECIAL_CHARS);
	$pold=filter_input(INPUT_POST,'password_old',FILTER_SANITIZE_STRING);
	$pass=filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
	$passr=filter_input(INPUT_POST,'password_r',FILTER_SANITIZE_STRING);
	
	if (wp_check_password( $pold,$current_user->data->user_pass) ){
		if($pass!=$passr || empty($passr) || empty($pass)){
			$eroor='סיסמאות לא תואמות';
		}else{
		//update user
			wp_set_password($pass,$user_ID);
			$good='סיסמא הוחלפה בהצלחה- התחבר מחדש';
			?>
				<script>
					setTimeout(function(){
	//					document.write('hallo111');
					},3000);
				</script>
			<?php
		}
	}else{
   		$eroor='סיסמא ישנה לא מתאימה- אנא נסה שנית';	
		
	}
	
	
	
	//echo $pass."--".$passr;
}


//wp_set_password('admin',1);
///
$woocommerce->show_messages(); ?>

<p class="myaccount_user">
	<?php
	/*printf(
		__( 'Hello, <strong>%s</strong>. From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">change your password</a>.', 'woocommerce' ),
		$current_user->display_name,
		get_permalink( woocommerce_get_page_id( 'change_password' ) )
	);*/
	?>
</p>

<?php do_action( 'woocommerce_before_my_account' ); ?>
<div class="personalArea cf">
	<span class="myinfo infonev act"></span>
	<span class="myorder infonev"></span>
	<span class="passChang infonev"></span>	
	
	
	<div class="myinfo_div cf">
		<h2 class="normaldevid"><span class="long">סה"כ נקודות קרדיט בחשבונך</span></h2>
		<div class="sumCredit">
		<?php 
			echo  "<span>סה\"כ קרדיט:</span> ".(get_user_meta($user_ID,'credit',true)." ₪ ");
		?>
		</div>
		<hr>
		<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" id="infoForm">
		<div class="infoR">
			<h2 class="newH2"><span class="newH2span">פרטים אישיים</span> [<span class="red">*</span>] שדות חובה</h2>
				<?php if(!empty($update)){echo "<span class=\"good\">".$update."</span>";}?>
			<span class="flotr"><input name="user_sex" type="radio" value="זכר">זכר</span>
			<span class="flotr"><input name="user_sex" type="radio" value="נקבה">נקבה<span><br>
			<label for="first_name"><span class="red">*</span>שם פרטי</label><input type="text" name="first_name" required  value="<?php echo get_user_meta($user_ID,'billing_first_name',true)?>" ><br>
			<label for="last_name"><span class="red">*</span>משפחה פרטי</label><input type="text" name="last_name" required value="<?php echo get_user_meta($user_ID,'billing_last_name',true)?>"><br>
			<label for="birth"><span class="red">*</span>תאריך לידה</label><input type="date" name="birth" required value="<?php echo get_user_meta($user_ID,'billing_company',true)?>"><br>
			<label for="email"><span class="red">*</span>אימייל</label><input type="email" name="email" required value="<?php echo get_user_meta($user_ID,'billing_email',true)?>"><br>
			<label for="phone"><span class="red">*</span>טלפון</label><input type="number" name="phone" required value="<?php echo get_user_meta($user_ID,'billing_phone',true)?>">
		</div>
		<div class="infoL">
			<h2 class="newH2"><span class="newH2span"><span class="red">*</span>כתובת</span> [<span class="red">*</span>] שדות חובה</h2><br>
			<label for="address"><span class="red">*</span>כתובת מלאה</label><input type="text" name="address" required value="<?php if(get_user_meta($user_ID,'billing_address_1',true)!=''){echo get_user_meta($user_ID,'billing_address_1',true);}else{echo "רחוב";}?>"><br>
			<label for="city"><span class="red">*</span>עיר</label><input type="text" name="city" value="<?php echo get_user_meta($user_ID,'billing_city',true)?>" required><br>
			<label for="zipcode"><span class="red">*</span>מיקוד</label><input type="number" name="zipcode" value="<?php echo get_user_meta($user_ID,'billing_post_code',true)?>" required><br>
			<label for="mailBox"><span class="red">*</span>ת"ד</label><input type="text" name="mailBox" value="<?php echo get_user_meta($user_ID,'billing_address_2',true)?>" required>
			
		</div>
		
		<input type="submit" name="infoSub" id="infobottom" value="עדכן ושמור פרטים">
		</form>
 

	</div>
	
<?php // woocommerce_get_template( 'myaccount/my-downloads.php' ); ?>
<div class="personalorder cf">
<?php woocommerce_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>
</div>



	<!--pass section-->
	<div class="passwordC">
		<h2 class="normaldevid"><span>שינוי סיסמא</span></h2>
		<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" id="passForm">
	<?php if(!empty($eroor)){echo "<span class=\"eroor\">".$eroor."</span>";}?>
	<?php if(!empty($good)){echo "<span class=\"good\">".$good."</span>";}?>
			<label for="oldusername">שם משתמש</label><input type="text" name="oldusername" placeholder="<?php echo  $current_user->user_login;?>" disabled><br>
<!--			<label for="new_usename">שם משתמש חדש</label><input type="text" name="new_usename" ><br>
-->			<label for="password_old"><span class="red">*</span>סיסמא נוכחית</label><input type="password" name="password_old" required> <br>
			<label for="password"><span class="red">*</span>סיסמא חדשה</label><input type="password" name="password" required><br>
			<label for="password_r"><span class="red">*</span>אשר סיסמא</label><input type="password" name="password_r" required>
	
		<input type="submit" name="passSub" id="passbottom" value="שנה סיסמא">

	</form>
	</div>
</div>

<?php //woocommerce_get_template( 'myaccount/my-address.php' ); ?>

<?php do_action( 'woocommerce_after_my_account' ); ?>