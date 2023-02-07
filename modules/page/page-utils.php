<?php



function getPageById($idPage){
    $result = null;


    $mainQuery = new WP_Query(array(
        'post_type' => array('page'),
        'post_status' => 'publish',
        'post_id' => $idPage // liste des types de posts que nous cherchons
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if(!get_field('is_unactive_page')){
            if(get_the_ID() == $idPage){ 
                $result = getPageDatailformat();
                
            }
        }

    }
    return $result;
}
function get_all_pages(){
    $result = array();


    $mainQuery = new WP_Query(array(
        'post_type' => array('page'),
        'posts_per_page' => -1,
        'post_status' => 'publish',
     // liste des types de posts que nous cherchons
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if(!get_field('is_unactive_page')){
                array_push($result, getPageDatailformat());
        }

    }
    return $result;
}

function getPageDatailformat(){
    return array(
        'id' => get_the_ID(),
        'title' => get_the_title(),
        'thumbnail' => iogetThumbnailFormated(get_the_ID()),
        'content' => convert_page_content_list_io(get_field('content_list')),
        'desactivated_page' => get_field('is_unactive_page'),
        'link' => get_the_post_link_front_io(),
        'seo' =>  get_seo_data_io(),
    );
}