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

    register_rest_route('io/v2','products/stocks',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ioGetStockProduct' // notre fonfonction 
    ));

}
add_action('rest_api_init', 'ioProductRoute');

function ioGetProduct(WP_REST_Request $request) {
   
    $withLocal = $request->get_param('withlocale') === 'true' ? true : false;

    return array(
        'status' => 200,
        'data' => array(
            'query' => ioConvertToQuery(array('product'), $request ),
            'result' => ioGetProductData(ioConvertToQuery(array('product'), $request, ),  $withLocal)
            )
        );
}


function ioGetSingleProduct($param){
    return array( 
        'status' => 200,
        'data'=> ioGetSingleProductData($param['id']));
}

function ioGetStockProduct(WP_REST_Request $request){
    $listProductStockRequest = ioConvertStringRequestStockToObjectList($request);
    return ioGetProductStockData($listProductStockRequest);
}

