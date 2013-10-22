<?php
/*
Template Name: עידכון פרטים- שלב 2
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $current_user;

$all=get_currentuserinfo();
//var_dump($current_user);
 $user_ID = get_current_user_id();
//var_dump(get_user_meta($user_ID));
get_header();

	if(isset($_POST['addreesSub'])){
		
	$shipping_first_name=filter_input(INPUT_POST,'first_name',FILTER_SANITIZE_STRING);
	$shipping_last_name=filter_input(INPUT_POST,'last_name',FILTER_SANITIZE_STRING);
	$shipping_address_1=filter_input(INPUT_POST,'addrees',FILTER_SANITIZE_STRING);
	$shipping_address_2=filter_input(INPUT_POST,'mailbox',FILTER_SANITIZE_STRING);
	$shipping_city=filter_input(INPUT_POST,'city',FILTER_SANITIZE_STRING);
	$shipping_post_code=filter_input(INPUT_POST,'zipcode',FILTER_SANITIZE_STRING);

	update_user_meta($user_ID,'shipping_first_name',$shipping_first_name);
	update_user_meta($user_ID,'shipping_last_name',$shipping_last_name);
	update_user_meta($user_ID,'shipping_address_1',$shipping_address_1);
	update_user_meta($user_ID,'shipping_address_2',$shipping_address_2);
	update_user_meta($user_ID,'shipping_city',$shipping_city);
	update_user_meta($user_ID,'shipping_post_code',$shipping_post_code);
	
	$update="פרטים עודכנו בהצלחה";
		
	}



	$billing_first_name=get_user_meta($user_ID,'billing_first_name',true);
	$billing_last_name=get_user_meta($user_ID,'billing_last_name',true);
	$billing_email=get_user_meta($user_ID,'billing_email',true);
	$billing_address_1=get_user_meta($user_ID,'billing_address_1',true);
	$billing_address_2=get_user_meta($user_ID,'billing_address_2',true);
	$billing_city=get_user_meta($user_ID,'billing_city',true);
	$billing_post_code=get_user_meta($user_ID,'billing_post_code',true);
	$billing_phone=get_user_meta($user_ID,'billing_phone',true);
	
	$shipping_first_name=get_user_meta($user_ID,'shipping_first_name',true);
	$shipping_last_name=get_user_meta($user_ID,'shipping_last_name',true);
	$shipping_email=get_user_meta($user_ID,'shipping_email',true);
	$shipping_address_1=get_user_meta($user_ID,'shipping_address_1',true);
	$shipping_address_2=get_user_meta($user_ID,'shipping_address_2',true);
	$shipping_city=get_user_meta($user_ID,'shipping_city',true);
	$shipping_post_code=get_user_meta($user_ID,'shipping_post_code',true);
	$shipping_phone=get_user_meta($user_ID,'shipping_phone',true);
	
	
	

?>
<div class="titelWcon"></div>
		<?php echo get_the_post_thumbnail($post->ID,'full')?>
<div class="titelWcon"></div>
<span class="titelWcon"><h1 class="entry-title-address"><?php the_title(); ?></h1></span>
<div class="adressEditcon">
<?php 	
		global $current_user;
		if(!$user_login){
			echo "אתה לא מחובר,<br> מיד תעבור לעמוד רישום והתחברות";?>
			
		<script>
				setTimeout(function(){
					window.location= '<?php echo esc_url( get_permalink( get_page_by_title( 'לקוח חדש' ) ) ); ?>';
				},5000);
		</script>	
		<?php }else{?>

	<input type="radio" class="addreesType" data-val="reg" name="newaddrees" id="regAddrees" checked><label for="reg" class="regLabel" data-val='reg'>כתובת חיוב</label> 
	<input type="radio" class="addreesType" data-val="new" name="newaddrees" id="newAddrees"><label for="reg"  class="regLabel" data-val='new'>כתובת משלוח</label>
	<div class="reg_addrees">
		<label for="first_name">שם פרטי</label><input type="text" name="first_name"  disabled value="<?php echo $billing_first_name;?>" ><br>
			<label for="last_name">שם משפחה </label><input type="text" name="last_name" disabled value="<?php echo $billing_last_name;?>"><br>
			<label for="addrees">כתובת מלאה</label><input type="text" disabled name="addrees"  value="<?php echo $billing_address_1;?>"><br>
			<label for="city">עיר</label><input type="text" name="city" disabled  value="<?php echo $billing_city;?>"><br>
			<label for="zipcode">מיקוד</label><input type="text" name="zipcode" disabled  value="<?php echo $billing_post_code;?>"><br>
			<label for="mailbox">ת"ד</label><input type="text" name="mailbox" disabled  value="<?php echo $billing_address_2;?>">
	
	</div>
	<div class="new_addrees">
		<h2 class="newH2"><span class="newH2span">כתובת למשלוח</span> [<span class="red">*</span>] שדות חובה</h2>
		<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" id="addreesForm">

			<label for="first_name"><span class="red">*</span> שם פרטי</label><input type="text" name="first_name"  required value="<?php echo $shipping_first_name;?>" ><br>
			<label for="last_name"><span class="red">*</span>שם משפחה</label><input type="text" name="last_name" required value="<?php echo $shipping_last_name;?>"><br>
			<label for="addrees"><span class="red">*</span> כתובת מלאה</label><input type="text" required name="addrees"  value="<?php echo $shipping_address_1;?>"><br>
			<label for="city"><span class="red">*</span> עיר</label><input type="text" name="city" required  value="<?php echo $shipping_city;?>"><br>
			<label for="zipcode">מיקוד</label><input type="text" name="zipcode" required  value="<?php echo $shipping_post_code;?>"><br>
			<label for="mailbox"><span class="red">*</span>ת"ד</label><input type="text" name="mailbox" required  value="<?php echo $shipping_address_2;?>">
			<input type="submit" class="newuser" id="addreesSub" name="addreesSub" value="עדכן והמשך">
		</form>
	</div>
	<?php }?>
</div>
<div class="casess">
</div>
<?php 
	get_footer();
?>