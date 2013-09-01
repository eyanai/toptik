<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>

	<?php
		/**
		 * woocommerce_before_mainh_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked
		  woocommerce_breadcrumb - 20
		 */
		
		if(!is_front_page()){
		$caunter=0;
		do_action('woocommerce_before_main_content');
			

	?>
		

		<div class="tag-desc">
			<?php do_action( 'woocommerce_archive_description'); ?>
		</div>
		
		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<h1 class="page-title"><?php //woocommerce_page_title(); ?></h1>

		<?php endif; ?>

		

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>
					
					<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					<?php $caunter++;?>
				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
		
	?>
	
	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		 
		
		do_action('woocommerce_sidebar');
		?>
		
		<div>
		<?php get_footer('wide');?>
		</div>
		
<?php		
		
		}//end if is not home page
		
		
		///////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////
		
		
		
		
	?>

	<?php  if(is_front_page()){ ?>
		<div class="bigSlide">
			<div class="bigbox">
<?php 		
			/*	$args = array(
				'post_type' => 'product',
				'posts_per_page' => 5,
				'tax_query' => array(
						'taxonomy' => 'home_options',
						'field' => 'slug',
						'terms' => array( 'גלריה-ראשית' )
					
				)
			);*/
			$args = array( 'post_type' => 'product', 'posts_per_page' => 5,'home_options' => "גלריה-ראשית");

			$query = new WP_Query( $args );
			// The Loop
			$gallmainCount=1;
			if ( $query->have_posts() ) {
				
				while ( $query->have_posts() ) {
					$query->the_post();
			?>
					
				<?php 
					$act=($gallmainCount==1)?'actgall':'';
					echo get_the_post_thumbnail( $post->ID,array(719,419), array('id' =>$gallmainCount,'class'=>$act)); ?> 
							
			<?php
				$gallmainCount++;
				}
			} else {
				//echo "no posts found";
			}
			/* Restore original Post Data */
			wp_reset_postdata();

?>			
			<?php if($gallmainCount>1):
			?>
				<div class="pointergall">
				<?php
					for ($i=1; $i<=$gallmainCount-1; $i++)
						  {
						 ?>
						 <span class="pointer<?php if($i==1)echo ' act'?>" data-idpoint=<?php echo $i;?>><?php echo $i;?></span>
				<?php		 
						  }
				?>	
					
				</div>
			<?php endif?>
			</div><!--bigBox-->
		</div><!--bigSlide-->
		<!----------------------r side--------------------------------->
		<?php
		$args = array( 'post_type' => 'product', 'posts_per_page' => 2,'home_options' => "גלריה-ראשית");

			$query = new WP_Query( $args );
			// The Loop
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
			?>
			<div class="topBannerBox">
				<span <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>

		<h3><?php the_title(); ?></h3>

		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

	</a>
	<a href="<?php the_permalink(); ?>" class="more"></a>
	<div class="ietmClass">
		<span class="req"></span>
			<?php
			
		//$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
//		echo $product->get_categories( ', ', '<span class="b_cat">' . _n( '', '', $size, 'woocommerce' ) . ' ', '.</span>' );
	?>
	</div>
	<?php //do_action( 'woocommerce_after_shop_loop_item' ); ?>

</span>
	</a>
	<a href="<?php the_permalink(); ?>" class="more"></a>
	<div class="ietmClass">
		<span class="req"></span>
			</div>
			</div><!--bigBox-->
			<?php
			
			
				}
			} else {
				//echo "no posts found";
			}
			/* Restore original Post Data */
			wp_reset_postdata();

?>			
		</div><!--bigSlide-->
		<?php get_sidebar('lgall');?>
		
		<div class="gallery_3_home">
		<?php //3 gall botoom......********************************************************************	
		$args = array( 'post_type' => 'product', 'posts_per_page' => 3,'home_options' => "גלריה-תחתונה");

			$query = new WP_Query( $args );
			// The Loop
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
			?>
			<div class="small_3">
				<h2><?php the_title(); ?></h2>
				<span class="sText"><?php echo the_excerpt(); ?></span>
				<?php if(get_the_post_thumbnail('small3')){
						echo get_the_post_thumbnail('small3');
						}else{ ?>
				<?php echo get_the_post_thumbnail( $post->ID,array(100,120));} ?> 
				<div class="mata_3">
					<h2>רק</h2>
					<h3><?php echo $product->get_price(); ?> ש"ח</h3>
					
					<a href="<?php the_permalink(); ?>" class="baynew"></a>

				</div>
			</div>
			<?php
				}
			} else {
				//echo "no posts found";
			}
			/* Restore original Post Data */
			wp_reset_postdata();

		?>
			</div><!--gallery_3_home-->
			
			<?php get_sidebar('banner');?>
			
			
			
	<!------------------------------top recommend-------------------------------------------------->	
	 <?php 
	 	$terms = get_terms("home_options"); 
	 	$mach=get_option('ye_plugin_options');
	 	$top=$mach['ye_rec_top'];
		$down=$mach['ye_rec_down'];
			//echo "<pre>".print_r($terms,1)."</pre>";
	 ?>
	 
	<div class="recommendedtop"><h2><?php echo $terms[2]->description;?></h2></div>
		<?php
		$args = array( 'post_type' => 'product', 'posts_per_page' => $top,'home_options' => "מומלצים-עליון");
			
		$query = new WP_Query( $args );
		echo "<ul class='products homePage'>";
			// The Loop
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
			?>

			
				
				<li <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>

		<h3><?php the_title(); ?></h3>

		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

	</a>
	<a href="<?php the_permalink(); ?>" class="more"></a>
	<!--<div class="ietmClass">
		<span class="req"></span>-->
			<?php
			
		//$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
		//echo $product->get_categories( ', ', '<span class="b_cat">' . _n( '', '', $size, 'woocommerce' ) . ' ', '.</span>' );
	?>
	<!--</div>-->
	<?php //do_action( 'woocommerce_after_shop_loop_item' ); ?>

</li>
		<?php
				}
	echo "</ul>";
			} else {
				//echo "no posts found";
			}
			/* Restore original Post Data */
			wp_reset_postdata();
?>
<div class="recommendedtop bottom"></div>

<?php get_sidebar('midel')?>
	<!------------------------------botoom recommend-------------------------------------------------->	
	 <?php $terms = get_terms("home_options"); ?>
	 
	<div class="recommendedtop"><h2><?php echo $terms[3]->description;?></h2></div>
		<?php
		$args = array( 'post_type' => 'product', 'posts_per_page' => $down,'home_options' => "מומלצים-תחתון");
			
		$query = new WP_Query( $args );
		echo "<ul class='products homePage'>";
			// The Loop
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
			?>
			
				
				<li <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>

		<h3><?php the_title(); ?></h3>

		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

	</a>
	<a href="<?php the_permalink(); ?>" class="more"></a>
	<div class="ietmClass">
		<span class="req"></span>
			<?php
			
		//$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
//		echo $product->get_categories( ', ', '<span class="b_cat">' . _n( '', '', $size, 'woocommerce' ) . ' ', '.</span>' );
	?>
	</div>
	<?php //do_action( 'woocommerce_after_shop_loop_item' ); ?>

</li>
		<?php
				}
	echo "</ul>";
			} else {
				//echo "no posts found";
			}
			/* Restore original Post Data */
			wp_reset_postdata();
?>
		
<?php get_footer('reg');?>


<?php	
	}//////////////////////////////////////////////////////////////////if is front page
	?>
			
	
<?php get_footer('shop'); ?>