<?php


function getPostByKeyword($req) {

    $results = array(
        'post_types_found'=> array(),
        'general_info' => array(),
        'collections' => array(),
        'shootbooks' => array(),
        'product' => array()
    );

    $mainQuery = new WP_Query(
        array(
        'post_type' => array('post', 'product', 'collections', 'page','shootbooks'),
        'post_status' => 'publish',
        's' => sanitize_text_field($req['term']),

    ));

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        $thumbnail_id = get_post_thumbnail_id(get_the_ID());
        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 

        if(get_post_type() == 'page') {
            if(!get_field('is_unactive_page')){
                array_push($results['general_info'], array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'post_type' => get_post_type(),
                    'thumnail_post' => array(
                        "url" => get_the_post_thumbnail_url(),
                        "alt" => $alt
                    ),
                    'link' => '/page/'.get_the_ID(),
                
                ));
                if (!in_array('general_info',$results['post_types_found'])){
                    array_push($results['post_types_found'],'general_info');
                }
            }
        }

        if(get_post_type() == 'collections') {
        
            array_push($results['collections'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'post_type' => get_post_type(),
                'thumnail_post' => getCollectionData(get_the_ID())['image_principale_v2'],
                'link' => '/collection/'.get_the_ID(),
            ));
            if (!in_array('collections',$results['post_types_found'])){
                array_push($results['post_types_found'],'collections');
            }
        }

        if(get_post_type() == 'shootbooks') {
            array_push($results['shootbooks'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'post_type' => get_post_type(),
                'thumnail_post' => array(
                    "url" => get_the_post_thumbnail_url(),
                    "alt" => $alt
                ), 
                'link' => '/lookbook/'.get_the_ID(),
            ));

            if (!in_array('shootbooks',$results['post_types_found'])){
                array_push($results['post_types_found'],'shootbooks');
            }
        }


        if(get_post_type() == 'product') {
            $product = wc_get_product(get_the_ID());
            array_push($results['product'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'post_type' => get_post_type(),
                'thumnail_post' => array(
                    "url" => get_the_post_thumbnail_url(),
                    "alt" => $alt
                ),
                'categories' => $product->get_category_ids(),
                'link' => '/product/'.get_the_ID(),
            ));
            if (!in_array('product',$results['post_types_found'])){
                array_push($results['post_types_found'],'product');
            }
        }
        
    }

    return $results;
}



