<?php

function ioProductRoute() {

    register_rest_route('io/v2','products',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ioGetProduct' // notre fonfonction 
    ));

    register_rest_route('io/v2','products/(?P<id>\d+)',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ioGetSingleProduct' // notre fonfonction 
    ));


}
add_action('rest_api_init', 'ioProductRoute');

function ioGetProduct(WP_REST_Request $request) {
   

    return array(
        'query' => ioConvertToQuery(array('product'), $request ),
        'result' => ioGetProductData(ioConvertToQuery(array('product'), $request )));
}


function ioGetSingleProduct($param){
    return ioGetSingleProductData($param['id']);
}