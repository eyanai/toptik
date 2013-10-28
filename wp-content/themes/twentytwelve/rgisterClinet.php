<?php
/*
Template Name: רישום משתמש חדש
*/
get_header();

global $current_user;
//$alldata=get_user_meta($current_user->ID);
//var_dump($alldata); 
if(isset($_POST['newuser'])){
	//echo "new";
	
	$billing_first_name=filter_input(INPUT_POST,'first_name',FILTER_SANITIZE_SPECIAL_CHARS);
	$billing_last_name=filter_input(INPUT_POST,'last_name',FILTER_SANITIZE_STRING);
	$user_sex=filter_input(INPUT_POST,'sex');
	$billing_company=filter_input(INPUT_POST,'birth',FILTER_SANITIZE_STRING);
	$billing_email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_STRING );
	$billing_address_1=filter_input(INPUT_POST,'adress',FILTER_SANITIZE_STRING);
	$billing_address_2=filter_input(INPUT_POST,'mailBox',FILTER_SANITIZE_STRING);
	$billing_city=filter_input(INPUT_POST,'city',FILTER_SANITIZE_STRING);
	$billing_phone=filter_input(INPUT_POST,'phone',FILTER_SANITIZE_STRING);
	$billing_post_code=filter_input(INPUT_POST,'zipcode',FILTER_SANITIZE_STRING);
	$pass=filter_input(INPUT_POST,'pass',FILTER_SANITIZE_STRING);
	$passr=filter_input(INPUT_POST,'pass',FILTER_SANITIZE_STRING);
	$nickname=filter_input(INPUT_POST,'nickname',FILTER_SANITIZE_STRING);
	
	
	//echo $billing_email.$billing_address_1;
	
	if(empty($billing_first_name)||empty($billing_last_name)||empty($user_sex)||empty($billing_company)||empty($billing_email)
		||empty($billing_address_1)||empty($billing_city)||empty($billing_post_code)||empty($pass)){
		$eroor='ישנם שדות חסרים- אנא נסה שנית';	
	}	
	
	if($pass!=$passr || empty($passr) || empty($pass)){
			$eroor='סיסמאות לא תואמות';
	}else{
		if( email_exists( $billing_email )) {
      		$eroor='אימייל כבר קיים במערכת';
   		}//elseif(username_exists( $username ) ){
           //echo "Username In Use!";
		//}
		elseif(empty($eroor)){
			$uid=wp_insert_user(array(
			'user_login'  => $billing_email,
			'user_pass' => $pass,
			'first_name'  => $billing_first_name,
			'last_name' => $billing_last_name,
			'user_email'  => $billing_email,
			'display_name'  => $billing_first_name . ' ' . $billing_last_name,
			'nickname'  => $nickname,
			'role'    => 'None'

			  ));
		
			if($uid){
				update_user_meta($uid,'user_sex',$user_sex);
				update_user_meta($uid,'billing_company',$billing_company);
				update_user_meta($uid,'billing_address_1',$billing_address_1);
				update_user_meta($uid,'billing_address_2',$billing_address_2);
				update_user_meta($uid,'billing_city',$billing_city);
				update_user_meta($uid,'billing_post_code',$billing_post_code);
				update_user_meta($uid,'billing_phone',$billing_phone);
				update_user_meta($uid,'billing_email',$billing_email);
				
				
				update_user_meta($uid,'shipping_first_name',$billing_first_name);
				update_user_meta($uid,'shipping_last_name',$billing_last_name);
				update_user_meta($uid,'shipping_address_1',$billing_address_1);
				update_user_meta($uid,'shipping_address_2',$billing_address_2);
				update_user_meta($uid,'shipping_city',$billing_city);
				update_user_meta($uid,'shipping_post_code',$billing_post_code);
				update_user_meta($uid,'shipping_phone',$billing_phone);
				update_user_meta($uid,'shipping_email',$billing_email);
			
				$good='משתמש נוצר בהצלחה...';
			}
			
		}
	
	}
	
	
	
		  
		

}



?>



<div class="titelWcon"></div>
		<?php echo get_the_post_thumbnail($post->ID,'full')?>
<div class="titelWcon"></div>
<?php global $current_user;
		if(!$user_login):?>

<?php if(!empty($good)){echo "<span class=\"good\">".$good."<br></span>";?>
			מיד תעבור לעמוד התחברות
			<script>
			setTimeout(function(){
				window.location='<?php echo esc_url(get_permalink( get_page_by_title( 'החשבון שלי' ) ) );?>';
				},5000);
			</script>	
<?php }else{?>


	<?php if(!empty($eroor)){echo "<span class=\"eroor\">".$eroor."</span>";}?>

<span class="titelWcon"><h1 class="entry-title newcastomer reg">לקוח חדש? הרשם לאתר</h1></span>		



<div class="registerClinet clear">
<?php 

/*	function wp_create_user($username, $password, $email = '') {
	$user_login = wp_slash('dammi4');
	$user_email = wp_slash('dammi4@mail.com'   );
	$user_pass = 'dammi4';

	serdata = compact('user_login', 'user_email', 'user_pass');
	return wp_insert_user($userdata);
*/


 


		//echo print_r($alldata,1);?></pre>
	<form action="<?php $_SERVER['PHP_SELF'];?>" method="post" class="registerForm">
<h2 class="newH2"><span class="newH2span">פרטים אישיים</span> [<span class="red">*</span>] שדות חובה</h2>
			
            <label for="first_name" class="sex first">זכר</label>
             <input type="radio" name="sex"  value="זכר"/>

			 <label for="first_name" class="sex">נקבה</label>
             <input type="radio" name="sex"  value="נקבה"/><br>
			
			<label for="first_name"><span class="red">* </span> <?php _e('שם פרטי','mydomain') ?></label>
             <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr(stripslashes($first_name)); ?>" size="25" /><br>

            <label for="last_name"><span class="red">* </span> <?php _e('שם משפחה','mydomain') ?></label>
                <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr(stripslashes($last_name)); ?>" size="25" /><br>

            <label for="birth"><span class="red">* </span> <?php _e('תאריך לידה','mydomain') ?></label>
                <input type="date" name="birth" id="birth" class="date"><br>

            <label for="phone"><span class="red">* </span><?php _e('טלפון','mydomain') ?></label>
                <input type="tel" name="phone" id="phone" class="input">
        
		<h2 class="newH2"><span class="newH2span">כתובת למשלוח</span> [<span class="red">*</span>] שדות חובה</h2>
		    <label for="adress"><span class="red">* </span> <?php _e('כתובת','mydomain') ?></label>
                <input type="text" name="adress" id="adress" class="input" ><br>

            <label for="city"><span class="red">* </span> <?php _e('עיר','mydomain') ?></label>
                <input type="text" name="city" id="city" class="input"><br>

            <label for="zipcode"><span class="red">* </span><?php _e('מיקוד','mydomain') ?></label>
                <input type="number" name="zipcode" id="zipcode" class="input"><br>
			
			 <label for="zipcode"><span class="red">* </span><?php _e('ת"ד','mydomain') ?></label>
                <input type="text" name="mailBox" id="mailBox" class="input"><br>

<h2 class="newH2"><span class="newH2span">סיסמא</span> [<span class="red">*</span>] שדות חובה</h2>
		    <?php /*?><label for="nickname"><span class="red">* </span> <?php _e('nickname','mydomain') ?></label>
             <input type="text" name="nickname" id="nickname" class="input"  size="25" /><br>
<?php */?>
            <label for="email"><span class="red">* </span> <?php _e('אימייל','mydomain') ?></label>
             <input type="email" name="email" id="email" class="input"  size="25" /><br>

            <label for="pass">[<span class="red">*</span>] <?php _e('סיסמא','mydomain') ?></label>
             <input type="password" name="pass" id="pass" class="input"  size="25" /><br>

            <label for="passr">[<span class="red">*</span>] <?php _e('אשר סיסמא','mydomain') ?></label>
             <input type="password" name="passr" id="passr" class="input"  size="25" /><br>

        
		<input type="submit" value="הרשם לאתר" name="newuser" class="newuser">	 	
	</form>
	
<?php }?>	
	
<?php endif;?>	
	
<?php
//		echo woocommerce_login_form();
//		 global $current_user;
		if($user_login):?>
<div class="alredyRegister">
		 
		 אתה כבר מחובר...<br>
		מיד תעבור לדף הרלוונטי<br>
		או לחץ<br>

				 

<?php 
	$title=get_the_title();
	if($title=='הצטרף'){
		$urlrederct=esc_url( get_permalink( get_page_by_title( 'החשבון שלי' ) ) );
	}else{
		$urlrederct=esc_url(get_permalink( woocommerce_get_page_id( 'checkout') ) );
	}
?>
	<a href="<?php echo $urlrederct;?>" class="button contin">המשך</a>
</div>	
<script>
		setTimeout(function(){
			window.location='<?php echo $urlrederct; ?>';
		},5000);
</script>	

<?php endif; ?>
<div class="registerLeftBG">
	<div class="leftside_register">
	<?php get_footer('claer');?>
	</div>

</div> 

		
</div>	
<?php
get_footer(); 