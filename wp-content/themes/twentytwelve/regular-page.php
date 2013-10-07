<?php
/*
Template Name: דף תוכן רגיל
*/
get_header();

	 if (have_posts()): while (have_posts()) : the_post(); ?>
	 
	 <header class="entry-header">
		<div class="titelWcon"></div>
		<?php echo get_the_post_thumbnail($post->ID,'full')?>
		<div class="titelWcon"></div>	
			<span class="titelWcon"><h1 class="entry-title"><?php the_title(); ?></h1></span>
		</header>

<section class="regular-con">
<?php echo the_content();?>


</section>
		<?php endwhile;endif;//get_template_part('pagination'); ?>

<?php get_sidebar('reg');?>
<?php
get_footer('wide');
get_footer();
?>
