<?php

/**
 * Post Types
 */
function unadn_post_types(){
    register_post_type('collections', array(
        'public' => true,
        'label' =>'Collections',
        'show_in_rest'=> true,
        'supports' => array('title','thumbnail'),
        'labels' => array( //gestion des labels liés à ce postType
            'name' => 'Collections', // nom sur menu 
            'add_new_item' =>'Add new collection', //changement du label add new post
            'edit_item' => 'Edit Collection',
            'all_items' => 'All Collections',
            'singular_name' => 'Collection',
        ),
        'menu_icon' => 'dashicons-grid-view',
    ));


    register_post_type('shootbooks', array(
        'public' => true,
        'label' =>'shootbooks',
        'show_in_rest'=> true,
        'menu_icon' => 'dashicons-images-alt2',
        'supports' => array('title','thumbnail'),
        'labels' => array( //gestion des labels liés à ce postType
            'name' => 'Shootbooks', // nom sur menu 
            'add_new_item' =>'Add new Shootbook', //changement du label add new post
            'edit_item' => 'Edit Shootbook',
            'all_items' => 'All Shootbook',
            'singular_name' => 'Shootbook',
        ),
    ));


    register_post_type('frontaccess', array(
        'public' => true,
        'label' =>'frontaccess',
        'show_in_rest'=> true,
        'menu_icon' => 'dashicons-tickets-alt',
        'supports' => array('title'),
        'labels' => array( //gestion des labels liés à ce postType
            'name' => 'Frontaccess', // nom sur menu 
            'add_new_item' =>'Add new Frontaccess ticket', //changement du label add new post
            'edit_item' => 'Edit Frontaccess ticket',
            'all_items' => 'All Frontaccess ticket',
            'singular_name' => 'frontaccess ticket',
        ),
    ));
}

add_action('init', 'unadn_post_types');

/*
* Custom api
*/

function initCors( $value ) {
    $origin_url = '*';
  
    /*Check if production environment or not
    if (ENVIRONMENT === 'production') {
      $origin_url = 'https://linguinecode.com';
    }
  */
    header( 'Access-Control-Allow-Origin: ' . $origin_url );
    header( 'Access-Control-Allow-Methods: GET' );
    header( 'Access-Control-Allow-Credentials: true' );
    return $value;
  }
  
function adnCustomRoutes() {

    //route Home data
    register_rest_route('adn/v1','homedata',array(
        'methods' => 'GET',
        'callback' => 'getHomeData' // notre fonction 
    ));

    //route Home data
    register_rest_route('adn/v1','collections/(?P<id>\d+)',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getCollectionInfo' // notre fonction 
    ));

    //route Home data
    register_rest_route('adn/v1','collections',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getAllcollection' // notre fonction 
    ));

    register_rest_route('adn/v1','shootbooks/(?P<id>\d+)',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getShootbookInfo' // notre fonction
    ));

    register_rest_route('adn/v1','shootbooks',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getAllShootbooks' // notre fonction 
    ));

    register_rest_route('adn/v1','products/(?P<id>\d+)',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getProductInfo' // notre fonction 
    ));

    register_rest_route('adn/v1','products',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getAllProducts' // notre fonction 
    ));

    register_rest_route('adn/v1','shipments',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getAllShipments' // notre fonction 
    ));

    register_rest_route('adn/v1','shipmentsclasses',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getShippingClasses' // notre fonction 
    ));

    register_rest_route('adn/v1','productstock',array(
        'methods' => 'POST',
        'callback' => 'getProductStock' // notre fonfonction 
    ));

    register_rest_route('adn/v1','productcategory',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'AllPorductCategoriesWhitTree' // notre fonfonction 
    ));

    register_rest_route('adn/v1','menu',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getMenu' // notre fonfonction 
    ));

    register_rest_route('adn/v1','search',array(
        'methods' => 'POST',
        'callback' => 'getPostByKeyword' // notre fonfonction 
    ));

    register_rest_route('adn/v1','generalsettings',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getGeneralSettings' // notre fonfonction 
    ));

    register_rest_route('adn/v1','product_cat/(?P<id>\d+)',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getProductCat' // notre fonction 
    ));

    register_rest_route('adn/v1','product_cat',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getAllProductCat' // notre fonction 
    ));

    register_rest_route('adn/v1','page/(?P<id>\d+)',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getPageInfo' // notre fonction 
    ));

    register_rest_route('adn/v1','page',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getAllPages' // notre fonction 
    ));


    register_rest_route('adn/v1','frontaccessauth',array(
        'methods' => 'POST',
        'callback' => 'getAuthAccessFront' // notre fonfonction 
    ));


    register_rest_route('adn/v1','contactmessage',array(
        'methods' => 'POST',
        'callback' => 'sendMessageContact' // notre fonfonction 
    ));


    register_rest_route('adn/v1','aproposData',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'aProposData' // notre fonfonction 
    ));
    remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );

	add_filter( 'rest_pre_serve_request', 'initCors');
}

/**
 * REQUIRED FILES
 * Include required files.
 */
require get_template_directory() . '/inc/api-home.php';

require get_template_directory() . '/inc/api-collection.php';

require get_template_directory() . '/inc/api-shootbook.php';

require get_template_directory() . '/inc/api-product.php';

require get_template_directory() . '/inc/api-shipping.php';

require get_template_directory() . '/inc/api-product-category.php';

require get_template_directory() . '/inc/api-search.php';

require get_template_directory() . '/inc/api-menu.php';

require get_template_directory() . '/inc/settings/general-settings.php';

require get_template_directory() . '/inc/api-general-settings.php';

require get_template_directory() . '/inc/api-page.php';

require get_template_directory() . '/inc/api-frontacess.php';
require get_template_directory() . '/inc/api-sendmail.php';
require get_template_directory() . '/inc/api-apropos.php';

require get_template_directory() . '/inc/utils/utils-seo.php';










add_action('rest_api_init', 'adnCustomRoutes', 15);

