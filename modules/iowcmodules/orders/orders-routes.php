<?php
function ioOrderRoutes() {
    register_rest_route('io/v2','orders/total',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ioGetTotal' // notre fonfonction 
    ));
}

add_action('rest_api_init', 'ioOrderRoutes');

function ioGetTotal(WP_REST_Request $request){
     $orderObject = ioConvertStringRequestTotalOrder($request);
    return  ioGetTotalData($orderObject);
}

