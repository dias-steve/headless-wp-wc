<?php

/**
 *Get all products
 * @since IOWC 1
 *
 * @return array json
 */
function ioGetProductData($queryArray){

    $results = array();
    $mainQuery = new WP_Query($queryArray);

    while ($mainQuery->have_posts()) {


        $mainQuery->the_post();
 


        $productData =  ioGetProductDataAperçuFormated();

        array_push($results, 
        $productData
        );
    }
    

    return $results;

}


/**
 *Format data for the front
 * @since IOWC 1
 *
 * @return array json
 */
function ioGetProductDataAperçuFormated(){

    $product = wc_get_product(get_the_ID());
    $id_post= get_the_ID();



    return array(
        'id' =>  $id_post,
        'title' => get_the_title(),
        'name' => $product->get_name(),
        'price' => ioPriceValidFilter($product->get_price()),
        'regular_price' => $product->get_regular_price(),
        'sale_price' => $product->get_sale_price(),
        'thumbnail' => ioGetThumbnailFormated( $id_post),
        'link' => ioGetFrontendLink($id_post),
        'stock_status' => $product->get_stock_status(),
        'images_gallery' =>  ioGetImagesGalleryProduct($product),
        'ship_class' => $product->get_shipping_class(),
        'categories' => $product->get_category_ids(),
        'date_created' => $product->get_date_created() 
    );
}



/**
 *Convert Price
 * @since IOWC 1
 *
 * @return  Float
 */

function ioPriceValidFilter($price){
    if(IoIsValidPrice($price)){
        return $price;
    }else{
        return"";
    }
}

function ioIsValidPrice($price){
    if($price === "" || $price === null){
        return false;
    }else{
        if(floatval($price)){
            return true;
        }
       
    }
}

function ioGetImagesGalleryProduct($product){
    $images_id = $product->get_gallery_attachment_ids();
    $images = array();

    foreach ($images_id as $attachment_id) {
        $image_link = wp_get_attachment_url($attachment_id);
        array_push($images, array(
            'attachement_id' => $attachment_id,
            'url' => $image_link,
            'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true) 
        ));
    }

    return $images;
}

function ioGetChildrensProduct($product){
    $children_ids = $product->get_children();
    $children_data = array();
    $on_sale_parent = false;
    $multi_price = false;
    $price_max = 0;
    $price_min = 0;

    foreach ($children_ids as $child_id) {
        $childData = wc_get_product($child_id);
        $thumbnail_id_child = get_post_thumbnail_id($child_id);
        $alt_child = get_post_meta($thumbnail_id_child, '_wp_attachment_image_alt', true); 
        $thumbnail_url_child = get_the_post_thumbnail_url($child_id);
        $on_sale = false;
        $shipping_cost_unit= get_field('shippement_cost_unit');
        $is_free_shippement = get_field('free_shippement');


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
            'variation_name' => 'ok',//$childData->get_variation_attributes(),
            'ship_class' => $childData->get_shipping_class(),
            'free_shippement' =>  $is_free_shippement,
            'on_sale'=> $on_sale,
            'shipping_cost_unit' => $shipping_cost_unit,
            'thumnail'=> array(
                'url' => $thumbnail_url_child ? $thumbnail_url_child :get_the_post_thumbnail_url() ,
                'alt' =>  $thumbnail_url_child ? $alt_child : 'alt_parent'
            ),
        ));
    }

  
}