<?php
function pageRoute() {
    register_rest_route('io/v1','pages/(?P<id>\d+)',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getPageDetail' // notre fonfonction 
    ));


    register_rest_route('io/v1','pages',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getAllPages' // notre fonfonction 
    ));


}
add_action('rest_api_init', 'pageRoute');

function getPageDetail($data){
    return getPageById($data['id']);
}


function getAllPages(){
    return get_all_pages();
}

