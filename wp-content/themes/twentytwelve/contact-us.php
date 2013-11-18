<?php
/*
Template Name: contact us
*/
get_header();

	 if (have_posts()): while (have_posts()) : the_post(); ?>
	 
	 <header class="entry-header">
		<div class="titelWcon"></div>
		<?php echo get_the_post_thumbnail($post->ID,'full')?>
		<div class="titelWcon"></div>	
			<span class="titelWcon"><h1 class="entry-title"><?php the_title(); ?></h1></span>
		</header>

<?php 
	if(isset($_POST['consub'])){
	
	$name=filter_input(INPUT_POST,'fullname',FILTER_SANITIZE_SPECIAL_CHARS);
	$tel=filter_input(INPUT_POST,'tel',FILTER_SANITIZE_STRING);
	$email=filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
	$contace=filter_input(INPUT_POST,'contace',FILTER_SANITIZE_STRING);
	
	 $headers = 'From:  <'.$email.'>' . "\r\n";
	 $subj="נתקבל מייל מהאתר";

	if(!$email){
		  $mes='אימייל לא תיקני';
		  }
		else
		  {
		  $admin_email = get_settings('admin_email');
			wp_mail($admin_email,$subj,$contace,$headers);
		  $mes='אימייל נשלח בהצלחה';
		  
		  
		  }	
	
	
	}


?>


<section class="contactUs">
	<div class="contectText">
	<?php echo the_content();?>
	</div>

<div class="contactForm">

	<form method="post" action="<?php $_SERVER['PHP_SELF'];?>">
<?php if(!empty($mes)){echo "<span class=\"red\">".$mes."</span>";}?>
		<label for="fullname"><span class="red">*</span> שם מלא</label>
		<input name="fullname" type="text" required id="fullname"><br>
		<label for="tel"><span class="red">*</span> טלפון</label>
		<input type="tel" required name="tel"><br>
		<label for="email"><span class="red">*</span> אימייל</label>
		<input name="email" type="email" required id="email"><br>
		<label for="contace"><span class="red">*</span> תוכן</label>
		<textarea name="contace" required rows="20" cols="30" ></textarea>
		<input type="submit" value="שלח" name="consub">
	</form>

</div>

</section>
		<?php endwhile;endif;//get_template_part('pagination'); ?>

<?php
get_footer();
?>
