<?php
/**
 * Local admin CSS
 **/
function local_admin_css() {
	$url = content_url();
	$url = $url . '/languages/he_IL.css';
	echo '<link rel="stylesheet" type="text/css" href="' . $url . '" />';
}
add_action( 'admin_head', 'local_admin_css' );
add_action( 'login_head', 'local_admin_css' );

?>