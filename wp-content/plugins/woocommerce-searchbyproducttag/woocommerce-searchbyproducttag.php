<?php

/*
  Plugin Name: Search By Product tag - for Woocommerce
  Plugin URI: http://www.mattyl.co.uk/2012/12/14/woocommerce-wordpress-plugin-to-search-for-products-by-tag/
  Description: The search functionality in woocommerce doesn't search by product tags by default. This simple plugin adds this functionality to both the admin site and regular search
  Author: Matthew Lawson
  Version: 0.2
  Author URI: http://www.mattyl.co.uk/
 */


add_filter('the_posts', 'search_by_product_tag');

function search_by_product_tag($posts, $query = false) {
    if (is_search()) {
        //get_search_query does sanitization
        //Split and loop on comma possibly ?
//        echo get_search_query();
//        die();
        $tags = explode(',', get_search_query());
        //var_dump($tags);die();
        foreach($tags as $tag)
        {
            //Ignore already found posts from query..
            $ignoreIds = array(0);
            foreach($posts as $post)
            {
                $ignoreIds[] = $post->ID;
            }
        
            $matchedTags = get_post_by_tag(trim($tag), $ignoreIds);
            //var_dump($matchedTags);
            if ($matchedTags) 
            {
                foreach($matchedTags as $product_id)
                {   
                    $posts[] = get_post($product_id->object_id);
                }

            }
        }
        
        //var_dump($posts);
        return $posts;
    }

    return $posts;
}

function get_post_by_tag($tag, $ignoreIds) {
    //Check for 
    global $wpdb, $wp_query;
    $ignoreIdsForMySql = implode(",", $ignoreIds);
    //var_dump($ignoreIdsForMySql);
    $query = "
            select tr.object_id from $wpdb->terms t
            join $wpdb->term_taxonomy tt
            on t.term_id = tt.term_id
            join $wpdb->term_relationships tr
            on tt.term_taxonomy_id = tr.term_taxonomy_id
            join $wpdb->posts p
            on p.ID = tr.object_id
            join $wpdb->postmeta visibility
            on p.ID = visibility.post_id    
            and visibility.meta_key = '_visibility'
            and (visibility.meta_value = 'visible' or visibility.meta_value ='search') 
            WHERE 
            tt.taxonomy = 'product_tag' and
            t.name LIKE '%$tag%'
            and p.post_status = 'publish'
            and p.post_type = 'product'
            and (p.post_parent = 0 or p.post_parent is null)
            and p.ID not in ($ignoreIdsForMySql)
             ;
";
    
    //echo $query;
    //Search for the sku of a variation and return the parent.
    $matchedProducts = $wpdb->get_results($query) ;
    
    //var_dump($matchedProducts);
    if(is_array($matchedProducts) && !empty($matchedProducts))
    {
        //var_dump($matchedProducts);
        $wp_query->found_posts += sizeof($matchedProducts);
        //var_dump($wp_query->found_posts, sizeof($matchedProducts));
        return $matchedProducts;
    
    }
   
    //return get_post($product_id);
    
    return null;
}

?>
