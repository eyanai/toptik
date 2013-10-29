<?php
/*
Template Name: דף תוכן רגיל
*/
get_header();

global $post, $product, $woocommerce;

	 if (have_posts()): while (have_posts()) : the_post(); ?>
	 
	 <header class="entry-header">
	
		<div class="titelWcon"></div>
		 <?php woocommerce_breadcrumb();?>
		<div class="titelWcon"></div>
		<?php get_sidebar('banner');?>
		<?php echo get_the_post_thumbnail($post->ID,'full')?>
		
		</header>

<section class="regular-con">
<h1 class="entry-title"><?php the_title(); ?></h1>
			<span class="titelWcon"></span>
			
<?php echo the_content();?>


</section>
		<?php endwhile;endif;//get_template_part('pagination'); ?>
<div class="sidebarReg">
<?php get_sidebar('reg');?>
</div>
<?php
get_footer('wide');
get_footer();
?>
