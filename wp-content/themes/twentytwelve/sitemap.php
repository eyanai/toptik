<?php
/*
Template Name:מפת אתר
*/
get_header();

	 if (have_posts()): while (have_posts()) : the_post(); ?>
	 
	 <header class="entry-header">
		<div class="titelWcon"></div>
		<?php echo get_the_post_thumbnail($post->ID,'full')?>
		<div class="titelWcon"></div>	
			<span class="titelWcon"><h1 class="entry-title"><?php the_title(); ?></h1></span>
		</header>

<section class="site-map">
<?php echo the_content();?>
		
		<span class="prodCat "></span>
				<?php 
				$args = array(
					'number'     => $number,
					'orderby'    => $orderby,
					'order'      => $order,
					'hide_empty' => $hide_empty,
					'include'    => $ids,
				);

			$product_categories = get_terms( 'product_cat', $args );//array( 'parent' => 0 )
			?>
			<div class="linkCats clear">
			<?php
			 foreach( $product_categories as $cat ) { 
			 $tarmId=$cat->term_id;
			 $cate='product_cat';
			 $link=get_term_link($cat);
			//	echo "<pre>".$link."</pre>";
				?>
			<a href="<?php echo $link;?>" class="proCatLink"><?php	echo $cat->name;?></a> 
			 
			 <?php
			 
			 }
			?>
			</div>
		<?php endwhile;endif;//get_template_part('pagination'); ?>

</section>

	
<?php get_sidebar('reg');?>
<?php
get_footer('wide');
get_footer();
?>
