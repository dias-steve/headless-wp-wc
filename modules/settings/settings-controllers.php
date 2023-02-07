<?php
    function getSettingsDataWCIo(){
        return array(
            'stripe_settings'=>getStripeSetting(),
        );
    }

    function getStripeSetting() {
        return array(
            'prod_mode_is_activated' => get_theme_mod('payment_mode_stripe_is_activate'),
            'test_mode_is_activated' => get_theme_mod('payment_mode_stripe_test_is_activate'),
        );
    }