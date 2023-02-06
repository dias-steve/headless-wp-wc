<?php
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

  function customRoutesSettings(){
    remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
    add_filter( 'rest_pre_serve_request', 'initCors');
}
  
add_action('rest_api_init', 'customRoutesSettings', 15);


/**
 * New
 */
require get_template_directory() . '/utils/utils.php';
require get_template_directory() . '/modules/iowcmodules/products/products-functions.php';
require get_template_directory() . '/modules/iowcmodules/productcategory/productcategory-functions.php';
require get_template_directory() . '/modules/iowcmodules/shipping/shipping-functions.php';
require get_template_directory() . '/modules/iowcmodules/coupon/coupon-functions.php';
require get_template_directory() . '/modules/iowcmodules/orders/orders-functions.php';














