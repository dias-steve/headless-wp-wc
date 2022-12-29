<?php

/**
 * REQUIRED FILES
 * Include required files.
 */
require get_template_directory() . '/inc/utils/utils-product.php';

function getProductInfo($data)
{

    $results = null;

    $mainQuery = new WP_Query(array(
        'post_type' => array('product'),
        'post_status' => 'publish',
        'post_id' => $data['id'] // liste des types de posts que nous cherchons
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));


    $on_sale_parent = false;
    $multi_price = false;
    $price_max = 0;
    $price_min = 0;
    
    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();
        $shipping_cost_unit= get_field('shippement_cost_unit');
        $is_free_shippement = get_field('free_shippement');
        if (get_the_ID() == $data['id']) {
            $product = wc_get_product(get_the_ID());
            $images_id = $product->get_gallery_attachment_ids();
            $images = array();
            $attributes = wc_get_attribute_taxonomies();
            foreach ($attributes as $attribute) {
                $attribute->attribute_terms = get_terms(array(
                    'taxonomy' => 'pa_' . $attribute->attribute_name,
                    'hide_empty' => false,
                ));
            }
            $children_ids = $product->get_children();
            $children_data = array();
            foreach ($images_id as $attachment_id) {
                $image_link = wp_get_attachment_url($attachment_id);
                array_push($images, array(
                    'attachement_id' => $attachment_id,
                    'url' => $image_link,
                    'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true) 
                ));
            }
            $thumbnail_id = get_post_thumbnail_id(get_the_ID());
            $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 

            foreach ($children_ids as $child_id) {
                $childData = wc_get_product($child_id);
                $thumbnail_id_child = get_post_thumbnail_id($child_id);
                $alt_child = get_post_meta($thumbnail_id_child, '_wp_attachment_image_alt', true); 
                $thumbnail_url_child = get_the_post_thumbnail_url($child_id);
                $on_sale = false;


                if(($childData->get_regular_price() !=="")&&($childData->get_regular_price()!==$childData->get_price())){
                    $on_sale= true;
                    $on_sale_parent=true;
                }
                
                if((float)$childData->get_price() > $price_max){
                    $price_max = (float)$childData->get_price(); 
                }
                if( $price_min > 0){
                    if((float)$childData->get_price() < $price_min){
                        $price_min = (float)$childData->get_price(); 
                    }
                }else{
                    $price_min = (float)$childData->get_price(); 
                }


                array_push($children_data, array(
                    'id' => $child_id,
                    'name' => $childData->get_name(),
                    'price' =>priceValidFilter($childData->get_price()),
                    'regular_price' => $childData->get_regular_price(),
                    'stock_status' => $childData->get_stock_status(),
                    'variation_name' => $childData->get_variation_attributes(),
                    'ship_class' => $childData->get_shipping_class(),
                    'free_shippement' =>  $is_free_shippement,
                    'on_sale'=> $on_sale,
                    'shipping_cost_unit' => $shipping_cost_unit,
                    'thumnail'=> array(
                        'url' => $thumbnail_url_child ? $thumbnail_url_child :get_the_post_thumbnail_url() ,
                        'alt' =>  $thumbnail_url_child ? $alt_child : $alt 
                    ),
                ));
            }

            if($price_max !== $price_min){
                $multi_price= true;
            }
            if(($product->get_regular_price()!=="")&& ($product->get_regular_price()!==$product->get_price())){
                $on_sale_parent = true;
            }
            $listVariation = getListVariationAvailble($children_data);
            $results = array(

                'id' => get_the_ID(),
                'seo'=> get_seo_data(),
                'title' => get_the_title(),
                'name' => $product->get_name(),
                'price' => priceValidFilter($product->get_price()),
                'attributes' => $attributes,
                'description' => $product->get_description(),
                'short_description' => $product->get_short_description(),
                'regular_price' => $product->get_regular_price(),
                'sale_price' => $product->get_sale_price(),
                'stock_status' => $product->get_stock_status(),
                'shipping_cost_unit' => $shipping_cost_unit,
                'free_shippement' =>  $is_free_shippement,
                'guide_taille' => array(
                    'message_mesure' => get_theme_mod( 'sec_message_guide'),
                    'image_tab'=> get_field('guide_taille_tab'),
                    'image_illustration'=> array(
                        'url' => get_theme_mod( 'sec_guide_image_mesure'),
                        'alt' => 'instructions de mesure'
                    )
                    ),
                'product_is_in_stock' => productIsInStock($product->get_stock_status(),$listVariation, $product->get_price(),$children_data  ),
                'on_sale' => $on_sale_parent,
              
                'multi_price' => array(
                    'have_multi_price' =>$multi_price,
                    'price_min' => $price_min,
                    'price_max' => $price_max,
                ),

                'thumnail'=> array(
                    'url' => get_the_post_thumbnail_url(),
                    'alt' =>  $alt
                ),
                'images' => $images,
                'product_look_id' => get_field('produit_look'),
                'video' => array(
                    'url' => get_field('video_url'),
                    'alt' => get_field('video_alt')
                    ),
                'interlude' => get_field('interlude_product'),
                'ship_class' => $product->get_shipping_class(),
                'categories' => $product->get_category_ids(),
                'childrens' => $children_data,
                'product_is_individual' => get_field('produit_is_individual'),
                
                'list_variations' =>$listVariation ,
                'product_is_variable'=>  haveVariations($listVariation),
               
                'info_build' => array(
                    'build_list' => get_field('build_product')
                    
                ),
                'other_info' => get_field('other_informations'),
                'product_look_list' => getProductlooklist(get_the_ID(), get_field('produit_look')),
          

            );
        }
    }
    return $results;
}



function getProductByID($product_id_parent){
    $results = null;

    $mainQuery = new WP_Query(array(
        'post_type' => array('product'),
        'post_status' => 'publish',
        'post_id' => $product_id_parent // liste des types de posts que nous cherchons
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));


    $on_sale_parent = false;
    $multi_price = false;
    $price_max = 0;
    $price_min = 0;
    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if (get_the_ID() == $product_id_parent) {
            $product = wc_get_product(get_the_ID());

            $images = array();
         
  
            $children_ids = $product->get_children();
            $children_data = array();
  
            $thumbnail_id = get_post_thumbnail_id(get_the_ID());
            $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 

            foreach ($children_ids as $child_id) {
                $childData = wc_get_product($child_id);
                $thumbnail_id_child = get_post_thumbnail_id($child_id);
                $alt_child = get_post_meta($thumbnail_id_child, '_wp_attachment_image_alt', true); 
                $thumbnail_url_child = get_the_post_thumbnail_url($child_id);
                $on_sale = false;


                if(($childData->get_regular_price() !=="")&&($childData->get_regular_price()!==$childData->get_price())){
            
                    $on_sale_parent=true;
                }
                
                if((float)$childData->get_price() > $price_max){
                    $price_max = (float)$childData->get_price(); 
                }
                if( $price_min > 0){
                    if((float)$childData->get_price() < $price_min){
                        $price_min = (float)$childData->get_price(); 
                    }
                }else{
                    $price_min = (float)$childData->get_price(); 
                }


            }

            if($price_max !== $price_min){
                $multi_price= true;
            }
            if(($product->get_regular_price()!=="")&& ($product->get_regular_price()!==$product->get_price())){
                $on_sale_parent = true;
            }
           
            $results = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'name' => $product->get_name(),
                'price' => priceValidFilter($product->get_price()),
                'regular_price' => $product->get_regular_price(),
                'sale_price' => $product->get_sale_price(),
                'stock_status' => $product->get_stock_status(),
                'on_sale' => $on_sale_parent,
                'multi_price' => array(
                    'have_multi_price' =>$multi_price,
                    'price_min' => $price_min,
                    'price_max' => $price_max,
                ),
                'thumnail'=> array(
                    'url' => get_the_post_thumbnail_url(),
                    'alt' =>  $alt
                ),
                'image_product' => array(
                    'url' => get_the_post_thumbnail_url(),
                    'alt' =>  $alt
                ),
                'images' => $images,
                'categories' => $product->get_category_ids(),
                'product_is_individual' => get_field('produit_is_individual'),


            );
        }
    }
    return $results;
}

function getProductlooklist($productId, $lookId)
{
    $results = array();
    $mainQuery = new WP_Query(array(
        'post_type' => array('product'),
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $lookId,
                ''
            ),
        ),
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while ($mainQuery->have_posts()) {


        $mainQuery->the_post();
        if (get_the_ID() !== $productId) {
            $product = wc_get_product(get_the_ID());
            $productData =  getProductByID(get_the_ID());
            if($productData !== null){
            array_push($results, 
            $productData
            );
            }
        }
    }

    return $results;
}




function listProductsCollectionInfo($idcategory)
{
    $results = array();
    $mainQuery = new WP_Query(array(
        'post_type' => array('product'),
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $idcategory,
                ''
            ),
        ),
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while ($mainQuery->have_posts()) {


        $mainQuery->the_post();
        $product = wc_get_product(get_the_ID());

        $thumbnail_id = get_post_thumbnail_id(get_the_ID());
        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 
        //si c'est un post ou page mettre dans le tableau information générale
        $productData =  getProductByID(get_the_ID());
        if($productData !== null){
        array_push($results, 
        $productData
        );
        }

    }

    return $results;
}

function getAllProducts()
{
    $results = array();
    $mainQuery = new WP_Query(array(
        'post_type' => array('product'),
        'posts_per_page' => -1,
        'post_status' => 'publish'

    ));

    while ($mainQuery->have_posts()) {


        $mainQuery->the_post();
 


        $productData =  getProductByID(get_the_ID());
        if($productData !== null){
        array_push($results, 
        $productData
        );
        }
    }

    return $results;
}

//Vérification des stock pour une liste de items 
function getProductStock($req)
{
    $resultsItems = array();
    $items = $req['items'];
    $allInStock = true;

    foreach ($items as $item) {
        $mainQuery = new WP_Query(array(
            'post_type' => array('product'),
            'post_id' => $item['id_parent']

        ));

        while ($mainQuery->have_posts()) {


            $mainQuery->the_post();

            $available = true;
            $code_error = 0;
            if (get_the_ID() === $item['id_parent']) {

                $product = wc_get_product($item['id']);
                if ($product->is_in_stock() === false) {
                    $allInStock = false;
                    $code_error = 10;
                    array_push($resultsItems, array(
                        'id' => $product->get_id(),
                        'title' => $product->get_name(),
                        'in_stock' => $product->is_in_stock(),
                        'stock_quantity' => $product->get_stock_quantity(),
                        'available' => $available,
                        'code_error' => $code_error
                    ));
                } elseif ($product->get_stock_quantity()) {
                    $quantityRest = $product->get_stock_quantity() - $item['quantity'];
                    if ($quantityRest < 0) {
                        $available = false;
                        $allInStock = false;
                        $code_error = 20;
                        array_push($resultsItems, array(
                            'id' => $product->get_id(),
                            'title' =>  $product->get_name(),
                            'in_stock' => $product->is_in_stock(),
                            'stock_quantity' => $product->get_stock_quantity(),
                            'available' => $available,
                            'code_error' => $code_error
                        ));
                    }
                }
            }
        }
    }

    $results = array(
        'all_in_stock' =>  $allInStock,
        'items_no_stock' => $resultsItems,
    );

    return $results;
}
