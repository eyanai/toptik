<?php
if ( has_nav_menu( 'brands' ) ) {?>
	<div class="brandsCon register clear">
		
<?php
    wp_nav_menu(array('theme_location'  => 'brands','container'=> 'div','container_class' => 'brandsMenu',));
	echo "</div>";
}  
			
 ?>
 
