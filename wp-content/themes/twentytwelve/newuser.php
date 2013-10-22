<?php
/*
Template Name: new user or login
*/
get_header();
global $woocommerce;
?>
<?php /*?><pre style="direction:ltr ;"><?php   $order_id=$woocommerce->cart->cart_contents[1];echo $order_id; echo print_r($woocommerce->cart->cart_contents[0],1);?></pre>
<?php */?><div class="titelWcon"></div>
		<?php echo get_the_post_thumbnail($post->ID,'full')?>
<div class="titelWcon"></div>	
<span class="titelWcon"><h1 class="entry-title newcastomer">אנא בחרו את הדרך הנוחה לכם לביצוע תשלום</h1></span>		



<div class="optionNewCastuomer clear">
	<div class="optionCas">
		<h2 class="newH2"><span class="newH2span">לקוח קיים</span> [<span class="red">*</span>] שדות חובה</h2>
		<?php
		echo woocommerce_login_form();
		 global $current_user;
		if($user_login){
			echo "אתה כבר מחובר...<br> מיד תעבור לשלב הבא";?>
			
		<script>
				setTimeout(function(){
					window.location= '<?php echo get_permalink(get_page_by_path('checkout'));?>';
				},5000);
		</script>	
		
			<a href="<?php echo get_permalink(get_page_by_path('checkout'));?>" class="submit_btb_cont login">המשך</a>	
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
		<a href="<?php echo get_permalink(get_page_by_path('הרשמה'));?>" class="submit_btb_cont">המשך</a>
		<?php 
		//generate_paypal_form();?>	
	</div>
	<?php /*?><span class="orCastoumer"></span>
	<div class="optionCas">
		<h2 class="newH2span">הזמנה מהירה</h2>
		<p>במידה ויש לך חשבון פייפאל או אם ברצונך<br>
			לבצע את התשלום באמצעות מערכת פייפאל<br>
			לחץ על הכפתור הבא:</p>
			<a href="" class="submit_btb_paypal"></a>
	</div><?php */?>
</div>

<?php
get_footer(); 