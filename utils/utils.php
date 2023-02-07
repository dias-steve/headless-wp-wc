<?php
function ioGetThumbnailFormated($id_post){
    $thumbnail_id = get_post_thumbnail_id($id_post);
    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 

    return array(
        'url' => get_the_post_thumbnail_url($id_post),
        'alt' => $alt,
    );
}

function ioGetFrontendLink($id_post){
    $postType = get_post_type($id_post);
    return '/'.$postType.'/'.$id_post;
}

function ioOperatorExtractor($str)
{

    $operatorlist = array('<=', '>=', '=', '<', '>');

    foreach ($operatorlist as $operator) {
        if (strpos($str, $operator)) {
            return $operator;
        }
    }

    return null;
}

function ioMultilangPostUtils($postData){
    $langList = array('FR','EN');
    $result = array();
 
    foreach ($langList as $lang){
        $postlang = $postData;
        $postlang['locale'] = $lang;
        array_push($result, $postlang);
    }

    return $result;
}

function get_the_post_link_front_io(){
    return '/'.get_post_type().'/'.get_the_ID();
}

function get_seo_data_io(){
    return array(
        'title_seo' => get_field('title_seo'),
        'meta_description_seo' => get_field('meta_description_seo'),
        'other_data_seo' => get_field('other_data_seo'),
    );
}
