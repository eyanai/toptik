<?php
if ( has_nav_menu( 'brands' ) ) {?>
	<div class="brandsCon clear">
		<div class="recommendedtop"><h2>המותגים שלנו</h2></div>
<?php
    wp_nav_menu(array('theme_location'  => 'brands','container'=> 'div','container_class' => 'brandsMenu',));
	echo "</div>";
}  
			
 ?>
 	<div class="recommendedtop goto"><h2 class="gotop"></h2></div>