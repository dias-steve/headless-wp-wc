<?php 

function getGeneralSettings() {
    $result = array(
        'maintenance_mode' => array(
            'is_activated' => get_theme_mod( 'set_maintenance_mode'),
            'maintenance_message' => get_theme_mod( 'set_maintenance_message' ),
            'maintenance_thumbnail' => array(
                'url' => get_theme_mod( 'set_maintenance_image'),
                'alt' => get_theme_mod( 'set_maintenance_image_alt')
            ),
            'maintenance_image_logo' => array(
                'url' => get_theme_mod( 'set_maintenance_image_logo'),
                'alt' => 'logo-icon'
            ),
            'seo' => array(
                'meta_description' => get_theme_mod( 'set_maintenance_seo_desc')
            ),


    ),
    'payment_info' => array(
        'title' => get_theme_mod( 'payment_title'),
        'logo_image' => array(
            'url' =>get_theme_mod( 'image_payement'),
            'alt' => 'payment logo'
        ),
        'description' => get_theme_mod( 'payment_description'),
        'link_learn_more' => get_theme_mod('link_learn_more_payment'),
    ),
    'shipment_info' => array(
        'title' => get_theme_mod( 'shipment_title'),
        'description' => get_theme_mod( 'shipment_description'),
        'link_learn_more' => get_theme_mod(  'link_learn_more_shipment'),
    ),
    'rgpd_pop_up_data'=> array(
        'title' => get_theme_mod( 'rgpd_title'),
        'description' => get_theme_mod( 'rgpd_description' ),
        'link' => get_theme_mod('rgpd_link'),
    )
);

    return $result;
}