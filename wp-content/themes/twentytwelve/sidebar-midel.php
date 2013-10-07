<?php
/**
 * The sidebar containing the front page widget areas.
 *
 * If no active widgets in either sidebar, they will be hidden completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

/*
 * The front page widget area is triggered if any of the areas
 * have widgets. So let's check that first.
 *
 * If none of the sidebars have widgets, then let's bail early.
 */
if ( ! is_active_sidebar( 'sidebar-5' ) && ! is_active_sidebar( 'sidebar-6' ) )
	return;

// If we get this far, we have widgets. Let do this.
?>

<div id="banners" class="widget-area middel" role="complementary">
	<?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
	<div class="first banner">
		
		<?php if(!is_product_category() && !is_product_tag()){?>
	
		<?php dynamic_sidebar( 'sidebar-5' ); ?>
	
	<?php
		}
		elseif(is_product_category())
		{
		global $post;
 
	// load all 'category' terms for the post
	$queried_object = get_queried_object(); 
		$taxonomy = $queried_object->taxonomy;
		$term_id = $queried_object->term_id;  
	// echo $post->ID;
	
	$big_banner = get_field('medium_banner', $taxonomy . '_' . $term_id);
	$url_big_banner = get_field('url_medium_banner', $taxonomy . '_' . $term_id);
	
		if(!empty($big_banner)){
			echo "<a href='$url_big_banner' target='new'>";
			echo "<div class='useful_banner_manager_banner'>
					<img src='".$big_banner['url']."' width='675' height='112'>
					</div>	
					</a>
				";
			}else{
			dynamic_sidebar( 'sidebar-5' );
			}
		}elseif(is_product_tag()){
			
			global $post;
 
	// load all 'category' terms for the post
	$queried_object = get_queried_object(); 
		$taxonomy = $queried_object->taxonomy;
		$term_id = $queried_object->term_id;
  
	// echo $post->ID;
	
	$big_banner = get_field('medium_banner', $taxonomy . '_' . $term_id);
	$url_big= get_field('url_medium_banner', $taxonomy . '_' . $term_id);
	echo $url_big_banner;
	//echo print_r($big_banner,1);	
		if(!empty($big_banner)){
			echo "<a href='$url_big' target='new'>";
			echo "<div class='useful_banner_manager_banner'>
					<img src='".$big_banner['url']."' width='675' height='112'>
					</div>	
					</a>
				";
			}else{
			dynamic_sidebar( 'sidebar-5' );
			}
		
		}
?> 
	
	
	</div><!-- .first -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-6' ) ) : ?>
	<div class="second banner">
	<?php if(!is_product_category() && !is_product_tag()){?>
	
		<?php dynamic_sidebar( 'sidebar-6' ); ?>
		
	<?php
		}
		elseif(is_product_category())
		{
		global $post;
 
	// load all 'category' terms for the post
	$queried_object = get_queried_object(); 
		$taxonomy = $queried_object->taxonomy;
		$term_id = $queried_object->term_id;  
	// echo $post->ID;
	
	$big_banner = get_field('small_banner', $taxonomy . '_' . $term_id);
	$url_big_banner = get_field('url_small_banner', $taxonomy . '_' . $term_id);
		if(!empty($big_banner)){
			echo "<a href='$url_big_banner' target='new'>";
			echo "<div class='useful_banner_manager_banner'>
					<img src='".$big_banner['url']."' width='270' height='112'>
					</div>	
						</a>
				";
			}else{
			dynamic_sidebar( 'sidebar-6' );
			}
	}elseif(is_product_tag()){
			
			global $post;
 
	// load all 'category' terms for the post
	$queried_object = get_queried_object(); 
		$taxonomy = $queried_object->taxonomy;
		$term_id = $queried_object->term_id;  
	// echo $post->ID;
	
	$big_banner = get_field('small_banner', $taxonomy . '_' . $term_id);
	$url_big_banner = get_field('url_small_banner', $taxonomy . '_' . $term_id);
		if(!empty($big_banner)){
			echo "<a href='$url_big_banner' target='new'>";
			echo "<div class='useful_banner_manager_banner'>
					<img src='".$big_banner['url']."' width='270' height='112'>
					</div>	
						</a>
				";
			}else{
			dynamic_sidebar( 'sidebar-6' );
			}
		
		}
?> 	
		
	</div><!-- .second -->
	<?php endif; ?>
</div><!-- #secondary -->