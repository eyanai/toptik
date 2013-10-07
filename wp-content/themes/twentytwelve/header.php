<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url');?>/css/toptic.css">
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="topHeader">
	<header class="topHeaderNav clear">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'top_nav-menu','menu'=>'top_nav_menu') ); ?>
		
		<div class="namItem">
		<?php global $woocommerce; ?>
		<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>">
		<?php echo sprintf(_n('%d <strong>מוצר ב- </strong>', '%d <strong>מוצרים ב- </strong>', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php //echo $woocommerce->cart->get_cart_total(); ?>
		</a>
		
			<div id="minicart">
				<?php woocommerce_mini_cart();?>
			</div>	
				
		</div>
		<div class="searchItem">
			<?php toptikSearch();?>
		</div>
	</header>
</div>
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
	<div class="avatar">
		<span class="avatarIcon"></span>
		<h2 class="blue">החשבון שלך</h2>
		<?php $pageUrl = get_page_by_title( 'My Account' ); ?>
		<a href="<?php echo $pageUrl->guid;?>">הכנס</a> לחשבון שלך או <a href="<?php echo $pageUrl->guid;?>">הצטרף</a>
	</div>
	
	<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<div class="logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
			</div>
		<?php endif; ?>
		<div class="tell">
		<?php $pageUrl = get_page_by_title( 'סניפים' ); ?>
			<span class="tellIcon"></span>
			<hgroup>
				<h3>לכל שאלה התקשרו אלינו</h3>
				<h3>בין חמישי לשיש בין השעות 8:30 עד 17:30</h3>
				<h3><a href="<?php echo $pageUrl->guid;?>" class="black">לחצו כאן</a> ליצירת קשר </h3>
		</hgroup>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<?php wp_nav_menu( array( 'menu_class' => 'nav-menu','menu'=>'main_nav' ) ); ?>
		</nav><!-- #site-navigation -->

		
	</header><!-- #masthead -->

	<div id="main" class="wrapper">