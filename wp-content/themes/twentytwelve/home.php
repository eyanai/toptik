<?php
/*
	Template Name:homeTemplet
*/
?>

<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?> ?>
		<section class="topBanner">
			<?php 
			$args = array(
					'post_type' => 'product',
					'posts_per_page' => 11,
				);
				$query = new WP_Query($args);?>
			<div class="bigSlide">
			<?php // The Loop
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						echo "<div class='bigbox'>";?>
						<a href="<?php the_permalink();?>"><?php the_post_thumbnail( array(719,419), $attr );?></a>
					<?php	echo "</div>";
					}
				} else {
					echo	" no posts found";
				}
				/* Restore original Post Data */
				wp_reset_postdata();?>	
			</div>
			<div class="topBannerBox right">
			</div>
			<div class="topBannerBox right">
			</div>
			<div class="topBannerBox">
			</div>
			<div class="topBannerBox">
			</div>
			<div class="topBannerBox">
			</div>
			<div class="topBannerBox">
			</div>
		</section>
	

<?php //get_sidebar(); ?>
<?php get_footer(); ?>