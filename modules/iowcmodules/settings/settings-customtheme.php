<?php

function paymentSectionStripe( $wp_customize) {
    //section
    $wp_customize->add_section(
        'sec_paiement_mode_stripe', array(
            'title' => 'Woocommerce Payment Mode Stripe',
            'description' => 'Maintenance Mode Setting'
        )
    );

        $wp_customize->add_setting(
            'payment_mode_stripe_is_activate', array(
                'type'  => 'theme_mod',
                'default' => '',
                'sanitize_callback' => 'theme_slug_sanitize_checkbox'
            )
        );

        $wp_customize->add_control(
            'payment_mode_stripe_is_activate', array(
                'label' => 'Activate Stripe',
                'description' => '',
                'section' => 'sec_paiement_mode_stripe',
                'type' => 'checkbox'
            )
        );


        $wp_customize->add_setting(
            'payment_mode_stripe_test_is_activate', array(
                'type'  => 'theme_mod',
                'default' => '',
                'sanitize_callback' => 'theme_slug_sanitize_checkbox'
            )
        );

        $wp_customize->add_control(
            'payment_mode_stripe_test_is_activate', array(
                'label' => 'Activate Stripe Test',
                'description' => '',
                'section' => 'sec_paiement_mode_stripe',
                'type' => 'checkbox'
            )
        );
}


function io_theme_settings_customizer_wc($wp_customize){

    paymentSectionStripe($wp_customize);
}


add_action('customize_register', 'io_theme_settings_customizer_wc');