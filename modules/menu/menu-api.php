<?php



function menuRoute() {
    register_rest_route('io/v2','menus/',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getMenuDetail' // notre fonfonction 
    ));

}
add_action('rest_api_init', 'menuRoute');

function getMenuDetail() {
    return getAllMenuKeyDetail();
}