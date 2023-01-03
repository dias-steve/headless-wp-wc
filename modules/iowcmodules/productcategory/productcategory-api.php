<?php

function ioProductCategoryRoute() {

    register_rest_route('io/v2/products','categories',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ioGetCategories' // notre fonfonction 
    ));
}
add_action('rest_api_init', 'ioProductCategoryRoute');

function ioGetCategories(WP_REST_Request $request) {
   

    return  ioGetAllPorductCategoriesWhitTree();
}


