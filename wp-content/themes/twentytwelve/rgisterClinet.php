<?php
/*
Template Name: רישום משתמש חדש
*/
get_header();
?>
<div class="titelWcon"></div>
		<?php echo get_the_post_thumbnail($post->ID,'full')?>
<div class="titelWcon"></div>	
<span class="titelWcon"><h1 class="entry-title newcastomer reg">לקוח חדש? הרשם לאתר</h1></span>		



<div class="registerClinet clear">
<?php myplugin_register_form();?>
<pre><?php  $alldata=get_user_meta($current_user->ID);
		//echo print_r($alldata,1);?></pre>
<?php /*?>	<form action="">
		<h2 class="newH2"><span class="newH2span">פרטים אישיים</span> [<span class="red">*</span>] שדות חובה</h2>
		
		<input name="sex" type="radio" value="זכר"><label for="sex">זכר</label>
		<input name="sex" type="radio" value="נקבה"><label for="sex">נקבה</label>
		
		
		</form>
		<h2>לקוח קיים חדש </h2>
		<?php
		echo woocommerce_login_form();
		 global $current_user;
		if($user_login){
			echo "אתה כבר מחובר...";?>
		
			<a hidden="<?php echo get_permalink(get_page_by_path('checkout'));?>" class="submit_btb_cont login">המשך</a>	
		<?php	
		
		}else{
			
		}
		 $alldata=get_user_meta($current_user->ID);
		//echo "<pre>".print_r($alldata,1)."</pre>";
	?><?php */?>
	
	
</div>	
<?php
get_footer(); 