<?php
    require get_template_directory() . '/modules/iowcmodules/products/ChildrenObject.php';
/**
 *Get all products
 * @since IOWC 1
 *
 * @return array json
 */
function ioGetProductData($queryArray,$withLocal){

    $results = array();
    //$queryArray['post_status' ] = 'publish'; //   'post_status' => 'publish',
    $mainQuery = new WP_Query($queryArray);

    while ($mainQuery->have_posts()) {


        $mainQuery->the_post();
 


        $productData =  ioGetProductDataAperçuFormated();

        if( $withLocal){

            $withLocalPostList = ioMultilangPostUtils($productData);
            foreach( $withLocalPostList as $withLocalPost){
                array_push($results, 
                $withLocalPost
                );
            }
        }else{
            array_push($results, 
            $productData
            );
        }

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
    $children_data = null;
    if(is_array($product->get_children()) && count($product->get_children()) >0){
        $children_data = new ChildrenProduct($product,get_field('shippement_cost_unit'), get_field('free_shippement'));
    }


    return array(
        'id' =>  $id_post,
        'title' => get_the_title(),
        'name' => $product->get_name(),
        'price' => ioPriceValidFilter($product->get_price()),
        'product_is_variable'=>   !$children_data ? false : $children_data->haveVariations(),
        'multi_price' => array(
            'have_multi_price' => !$children_data ? null : $children_data->isMultiPrice(),
            'price_min' => !$children_data ? null : $children_data->getMinPrice(),
            'price_max' => !$children_data ? null : $children_data->getMaxPrice(),
        ),
        'regular_price' => $product->get_regular_price(),
        'sale_price' => $product->get_sale_price(),
        'thumbnail' => ioGetThumbnailFormated( $id_post),
        'link' => ioGetFrontendLink($id_post),
        'stock_status' => $product->get_stock_status(),
        'images_gallery' =>  ioGetImagesGalleryProduct($product),
        'ship_class' => $product->get_shipping_class(),
        'categories' => $product->get_category_ids(),
        'date_created' => $product->get_date_created(),
        'on_sale' => !$children_data ? (($product->get_regular_price() !== "") && ($product->get_regular_price() !== $product->get_price())) : $children_data->haveOnSaleChild(),
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
    $children_data = null;
    if(is_array($product->get_children()) && count($product->get_children()) >0){
        $children_data = new ChildrenProduct($product,get_field('shippement_cost_unit'), get_field('free_shippement'));
    }


    $result = array(
        'id' =>  $id_post,
        'id_parent' =>  $id_post,
        'title' => get_the_title(),
        'name' => $product->get_name(),

        'seo' => get_seo_data_io(),
        'free_shippement' =>get_field('free_shippement'),
        'shippement_cost_unit'=>get_field('shippement_cost_unit'),
        'price' => ioPriceValidFilter($product->get_price()),
        'regular_price' => $product->get_regular_price(),
        'sale_price' => $product->get_sale_price(),
        'thumbnail' => ioGetThumbnailFormated( $id_post),
        'link' => ioGetFrontendLink($id_post),
        'stock_status' => $product->get_stock_status(),
        'images_gallery' =>  ioGetImagesGalleryProduct($product),
        'ship_class' => $product->get_shipping_class(),
        'sold_individualy' => $product->is_sold_individually(),
        'categories' => $product->get_category_ids(),
        'date_created' => $product->get_date_created(),
        'children' => !$children_data ? null : $children_data->reformater_children_list,
     
        'description' => get_field('description_product'),

        'on_sale' => !$children_data ? (($product->get_regular_price() !== "") && ($product->get_regular_price() !== $product->get_price())) : $children_data->haveOnSaleChild(),//|| 'parent',
        'in_stock' => $product->get_stock_status(),
        'product_is_in_stock'=> !$children_data ? (ioIsValidPrice(ioPriceValidFilter($product->get_price())) &&(ioInStockConverterToBoolean($product->get_stock_status()))) : $children_data->productIsInStock(),
        'list_variations' => !$children_data ? null : $children_data->getListVariationAvailble(),
        'variation_list_detail'=>  !$children_data ? null : isGetDescriptionAttribut($product, $id_post),
        'variation_list_detail_v2' =>!$children_data ? null : isGetDescriptionAttributV2($product, $id_post),
        'product_is_variable'=>   !$children_data ? false : $children_data->haveVariations(),
        'multi_price' => array(
            'have_multi_price' => !$children_data ? null : $children_data->isMultiPrice(),
            'price_min' => !$children_data ? null : $children_data->getMinPrice(),
            'price_max' => !$children_data ? null : $children_data->getMaxPrice(),
        ),
        'variations_selected' => null,

    );

    return $result;
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
    if(is_array(  $images_id)){
    foreach ($images_id as $attachment_id) {
        $image_link = wp_get_attachment_url($attachment_id);
        array_push($images, array(
            'attachement_id' => $attachment_id,
            'url' => $image_link,
            'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true) 
        ));
    }
}

    return $images;
}

function isGetDescriptionAttribut($product, $post_id){
    
    $result = array();
    $all_attributes = $product->get_attributes();
    $attributes = array();
  
    if (!empty($all_attributes)&& is_array($all_attributes)) {
        foreach ($all_attributes as $attr_mame => $value) {
            if ($all_attributes[$attr_mame]['is_taxonomy']) {
            
                    $termio =wc_get_product_terms($post_id, $attr_mame, array('fields'=> 'all'));
                    $attributes['attribute_'.$attr_mame] = array_reduce($termio, function ($carry, $item){
                        if(get_field('alt_gallery_is_actived')){
                            $galleryList = get_field('alt_gallery');
                            $item->thumnail = getThumbnailVariationByKeyvalue( 'attribute_'.$item->taxonomy,$item->slug);
                        }
                      
                        $carry[$item->slug]= $item; 
                        return $carry;
                    }, array());
            } else {
                $attributes[$attr_mame] = $product->get_attribute($attr_mame);
            }
        }
    }


    return $attributes;
}

function isGetDescriptionAttributV2($product, $post_id){
    
    $result = array();
    $all_attributes = $product->get_attributes();
    $attributes = array();
  
    if (!empty($all_attributes)&& is_array($all_attributes)) {
        foreach ($all_attributes as $attr_mame => $value) {

            if ($all_attributes[$attr_mame]['is_taxonomy']) {
            
                    $termio =wc_get_product_terms($post_id, $attr_mame, array('fields'=> 'all'));
                    $attributes['attribute_'.$attr_mame] = array_reduce($termio, function ($carry, $item){
                        if(get_field('alt_gallery_is_actived')){
                            $galleryList = get_field('alt_gallery');
                            $item->thumnail = getThumbnailVariationByKeyvalue( 'attribute_'.$item->taxonomy,$item->slug);
                        }
                      
                        $name = $item->taxonomy;
                        $carry['key'] = 'attribute_'.$name;
                        $carry['name'] = ioGetNameVariationByVariationKey($name);
                        $carry['value_list'][$item->slug]= $item; 
                        return $carry;
                    }, array());
            } else {
                $attributes[$attr_mame] = array( 
                    'key' => $attr_mame,
                    'name' =>ioGetNameVariationByVariationKey($attr_mame),
                    'value_list'=>$product->get_attribute($attr_mame)
                    );
            }
        }
    }


    return $attributes;
}


 function ioGetNameVariationByVariationKey($variation_key){
    $variation_name_raw = array_pop(explode("_",$variation_key));
    $variation_name = str_replace("-", " ",$variation_name_raw);
    return $variation_name;
}

function getThumbnailVariationByKeyvalue($attributName, $valueVariationSlug){
    $galleryList = get_field('alt_gallery');
    if(is_array($galleryList)){
   foreach( $galleryList as $gallery){
        $gallery["key_variation"];
        $convertedKey= "attribute_pa_".$gallery["key_variation"];
        if( $attributName=== $convertedKey && $gallery["value_variation"] ===   $valueVariationSlug){
            return $gallery["thumbnail_term"];
        }
    }
    }
    return null;
}

//Vérification des stock pour une liste de items 
function ioGetProductStockData($listItem)
{
    $resultsItems = array();
    $items = $listItem;
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
            if (get_the_ID() === (Int)$item['id_parent']) {

                $product = wc_get_product($item['id']);
                if ($product->is_in_stock() === false) {
                    $allInStock = false;
                    $code_error = 10;
                    $available = false;
                  $resultsItems[$product->get_id()] = array(
                        'id' => $product->get_id(),
                        'title' => $product->get_name(),
                        'in_stock' => $product->is_in_stock(),
                        'stock_quantity' => $product->get_stock_quantity(),
                        'available' => $available,
                        'code_error' => $code_error
                    );
                } elseif ($product->get_stock_quantity()) {
                    $quantityRest = $product->get_stock_quantity() - $item['quantity'];
                    if ($quantityRest < 0) {
                        $allInStock = false;
                        $code_error = 20;
                        $resultsItems[$product->get_id()] = array(
                            'id' => $product->get_id(),
                            'title' =>  $product->get_name(),
                            'in_stock' => $product->is_in_stock(),
                            'stock_quantity' => $product->get_stock_quantity(),
                            'available' => $available,
                            'code_error' => $code_error
                        );
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


function ioConvertStringRequestStockToObjectList($request){   
    $productList = explode('!', $request->get_param('products'));
    
    $productmap = array_map(
        function($productString){
            $productAttList= explode(',', $productString);

            $productObject=array_reduce($productAttList, function($object,$productAtt ){
                $productAttExp =  explode('=',$productAtt);
                $object[$productAttExp[0]] = $productAttExp[1];
                return $object;
            }, array());

            return $productObject;
        }
        ,  $productList);
    return   $productmap;
}


function ioInStockConverterToBoolean($value) {
    if($value ==='instock'){
        return true;
    }else{
        return false;
    }
}


function ioCheckSanityProductChild($childrens){

    foreach($childrens as $child){
        if(!ioIsValidPrice($child['price']) ){
            return false;
        }

    }
    return true;
}