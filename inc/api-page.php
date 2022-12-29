<?php

function getPageInfo($data){
    $result = null;


    $mainQuery = new WP_Query(array(
        'post_type' => array('page'),
        'post_status' => 'publish',
        'post_id' => $data['id'] // liste des types de posts que nous cherchons
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if(!get_field('is_unactive_page')){
            if(get_the_ID() == $data['id']){
                $thumbnail_id = get_post_thumbnail_id(get_the_ID());
                $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 
                $result = array(
                    'seo'=> get_seo_data(),
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'thumbnail' => array(
                        'url'=> get_the_post_thumbnail_url(),
                        'alt' => $alt
                    ),
                    'content' => get_the_content(),
                    'excerpt' => get_the_excerpt(),
                    'desactivated_page' => get_field('is_unactive_page'),
                    'link' => '/page/'.get_the_ID()
                
                );
                
            }
        }

    }
    return $result;
}


function getAllPages(){
    $result = array();

    $results = null;

    $mainQuery = new WP_Query(array(
        'post_type' => array('page'),
        'post_status' => 'publish',

        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if(!get_field('is_unactive_page')){
            $thumbnail_id = get_post_thumbnail_id(get_the_ID());
            $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 
            array_push($result, array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'thumbnail' => array(
                    'url'=> get_the_post_thumbnail_url(),
                    'alt' => $alt
                ),
                'excerpt' => get_the_excerpt(),
                'desactivated_page' => get_field('is_unactive_page'),
                'link' => '/page/'.get_the_ID()
            ));
        }


    }
    return $result;
}