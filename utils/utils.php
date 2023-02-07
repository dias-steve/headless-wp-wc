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