<?php
function ioShippingRoute() {

    register_rest_route('io/v2','shippings',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ioGetAllShippings' // notre fonfonction 
    ));
}
add_action('rest_api_init', 'ioShippingRoute');

function ioGetAllShippings(){
    return ioGetAllShipmentsData();
}