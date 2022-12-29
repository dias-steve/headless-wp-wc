<?php
function getAllShootbooks(){

    $results = array();
    
    $mainQuery = new WP_Query(array(
        'post_type' => array('shootbooks'),
        'post_status' => 'publish',
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        array_push($results,getShootbookInfoData(get_the_ID()));
        
    }
    return $results;
}

function getShootbookInfo($data){

 
    return getShootbookInfoData($data['id']);
}

function getShootbookInfoData($id){

    $results = null;
    
    $mainQuery = new WP_Query(array(
        'post_type' => array('shootbooks'),
        'post_status' => 'publish',
        'post_id' => $id// liste des types de posts que nous cherchons
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if(get_the_ID() == $id){
            $results = array (
                'id' => get_the_ID(),
                'seo'=> get_seo_data(),
                'title' => get_the_title(),
                'decription_shootbook'=> get_field('decription_shootbook'),
                'images_apercu' => get_field('images_apercu'),
             
                
                'media_list' => get_field('media_list')
        
            );
        }
    }
    return $results;
}