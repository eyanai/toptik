<?php
/*
Template Name: tranzila payment
*/

get_header();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="titelWcon"></div>
		<?php echo get_the_post_thumbnail($post->ID,'full')?>
		<div class="titelWcon"></div>
		<span class="titelWcon">
		<h1 class="entry-title">
			אמצעי תשלום
		</h1>
		</span> </header>
	<div class="entry-content tranzilaRight">
		<form action="https://secure5.tranzila.com/cgi-bin/tranzila31.cgi" method="POST">
			<input type="hidden" name="supplier" value="terminalname">
			<table align="center" id="tranzilaTable">
				<tr>
					<td colspan="2" align="center">הזן את הפרטים </td>
				</tr>
				<tr>
					<td>סכום העסקה </td>
					<td><input type="text" name="sum"></td>
				</tr>
				<tr>
					<td>סוג הכרטיס</td>
					<td>
						<select name="cardissuer" class="tranzilaSelect">
							<option value="1">ישראכארט</option>
							<option value="2">ויזה כ.א.ל</option>
							<option value="3">דיינרס</option>
							<option value="4">אמריקן אקספרס</option>
							<option value="6">לאומי כארד</option>
						</select></td>
				</tr>
				<tr>
					<td>שם פרטי </td>
					<td><input type="text" name="first_name"></td>
				</tr>
				<tr>
					<td>שם משפחה </td>
					<td><input type="text" name="last_name"></td>
				</tr>
				<tr>
					<td>מספר כרטיס אשראי </td>
					<td><input type="text" name="ccno"></td>
				</tr>
				<tr>
					<td>קוד אבטחה </td>
					<td><input type="text" name="mycvv"></td>
				</tr>
				<tr>
					<td>תוקף הכרטיס </td>
					<td><select name="expmonth" class="tranzilaSelect">
							<option value="01">01</option>
							<option value="02">02</option>
							<option value="03">03</option>
							<option value="04">04</option>
							<option value="05">05</option>
							<option value="06">06</option>
							<option value="07">07</option>
							<option value="08">08</option>
							<option value="09">09</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>
						<select name="expyear" class="tranzilaSelect">
							<option value="12">2012</option>
							<option value="13">2013</option>
							<option value="14">2014</option>
							<option value="15">2015</option>
							<option value="16">2016</option>
							<option value="17">2017</option>
							
						</select></td>
				</tr>
				
				<tr>
					<td colspan="2" align="center"><input type="submit" value="בצע תשלום "></td>
				</tr>
			</table>
		</form>
	</div>
	<span class="orCastoumer"></span>
	<div class="tranzilaLeft">
		<h2 class="newH2span">תשלום באמצעות PAYPAL</h2>
		<?php do_action('toptik_get');?>
		<?php
			$pay=new WC_Gateway_Paypal;
			$pay->toptik_get();
		?>
	</div>
	<!-- .entry-content --> 
	
</article>
<!-- #post -->
<?php 
	get_footer();
?>
