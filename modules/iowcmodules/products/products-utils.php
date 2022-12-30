<?php
    require get_template_directory() . '/modules/iowcmodules/products/ChildrenObject.php';
/**
 *Get all products
 * @since IOWC 1
 *
 * @return array json
 */
function ioGetProductData($queryArray){

    $results = array();
    //$queryArray['post_status' ] = 'publish'; //   'post_status' => 'publish',
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

function ioGetSingleProductData($id_post){

    $results = null;
    $queryArray = array(
        'post_type' => array('product'),
        'post_id' => $id_post,
    );
    $queryArray['post_status'] = 'publish'; //   'post_status' => 'publish',
    $mainQuery = new WP_Query(  $queryArray);

    while ($mainQuery->have_posts()) {


        $mainQuery->the_post();
       
        if(get_the_ID() == $id_post){

            $productData =  ioGetSingleProductDataFormated();

            $results = $productData;
        }

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
 *Format data for the front
 * @since IOWC 1
 *
 * @return array json
 */
function ioGetSingleProductDataFormated(){

    $product = wc_get_product(get_the_ID());
    $id_post= get_the_ID();
    $children_data = new ChildrenProduct($product,get_field('shippement_cost_unit'), get_field('free_shippement'));


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
        'product_is_individual' => get_field('produit_is_individual'),
        'categories' => $product->get_category_ids(),
        'date_created' => $product->get_date_created(),
        'children' =>$children_data->list,

        'on_sale' =>$children_data->haveOnSaleChild(),//|| 'parent',
        'in_stock' => $product->get_stock_status(),
        'product_is_in_stock'=>$children_data->productIsInStock(),
        'list_variations' =>$children_data->getListVariationAvailble(),
        'product_is_variable'=>  $children_data->haveVariations(),
        'multi_price' => array(
            'have_multi_price' =>$children_data->isMultiPrice(),
            'price_min' => $children_data->getMinPrice(),
            'price_max' =>$children_data->getMaxPrice(),
        ),
    
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

