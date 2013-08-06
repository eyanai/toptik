<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	</div><!-- #main .wrapper -->
</div><!-- #page -->
<footer id="colophon" role="contentinfo">
		<div class="site-info clear">
			<section class="newsletter clear">
				<span class="news"></span>
				<?php $widgetNL = new WYSIJA_NL_Widget(true);
				echo $widgetNL->widget(array('form' => 1, 'form_type' => 'php'));
					?>
			</section>
			<section class="siteCat">
				<span class="prodCat "></span>
				<?php 
				$args = array(
					'number'     => $number,
					'orderby'    => $orderby,
					'order'      => $order,
					'hide_empty' => $hide_empty,
					'include'    => $ids
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
			</section>
			<section class="face_join">
				<span class="joinUs"></span>
				<a href="http://www.facebook.com" class="fbjoin" target="new"></a>
				<div class="fbLike">
					<div class="fb-like" data-href="http://www.toptik.co.il/" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></div>
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
				</div>				
				<div class="twitLike">
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.toptik.co.il/">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			</div>	
			<a href="tel:123456" class="telephon"></a>
			</section>
			<?php wp_nav_menu( array( 'menu_class' => 'footer_nav_rec','menu'=>'recommend') ); ?>
		</div><!-- .site-info -->
			
</footer><!-- #colophon -->

<?php wp_footer(); ?>
</body>
</html>