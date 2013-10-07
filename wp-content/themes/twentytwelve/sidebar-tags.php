<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 if ( ! is_active_sidebar( 'sidebar-atgs' ))
	return;

?>
<?php if ( is_active_sidebar( 'sidebar-atgs' ) ) : ?>
		<div id="secondary" class="widget-area right topSsidebar tags" role="complementary">
		<?php
			global $post;
 
	// load all 'category' terms for the post
	$queried_object = get_queried_object(); 
		$taxonomy = $queried_object->taxonomy;
		$term_id = $queried_object->term_id;  
	// echo $post->ID;
	
	$big_banner = get_field('big_banner', $taxonomy . '_' . $term_id);
	$url_big_banner = get_field('url_big_banner', $taxonomy . '_' . $term_id);
	if(!empty($big_banner)){
			echo "<a href='$url_big_banner' target='new'>";
			echo "<div class='useful_banner_manager_banner'>
					<img src='".$big_banner['url']."' width='249' height='170'>
					</div>	
							</a>
				";
			}else{
			dynamic_sidebar( 'sidebar-4' );
			}
		
		
		?>
		
			<span class="titelWcon"><h3 class="widget-title">סנן תוצאות לפי ...</h3></span>
			<?php dynamic_sidebar('sidebar-atgs'); ?>
		</div><!-- #secondary -->
		
		
<?php endif; ?>