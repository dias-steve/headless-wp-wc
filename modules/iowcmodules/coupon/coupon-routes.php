<?php
function ioCouponRoute() {

    register_rest_route('io/v2','coupons',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ioGetAllCoupons' // notre fonfonction 
    ));
}
add_action('rest_api_init', 'ioCouponRoute');

