<?php
function ioSettingsWCRoute() {
    register_rest_route('io/v2','wc/settings',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getSettingsInfoWCIo' // notre fonfonction 
    ));

}
add_action('rest_api_init', 'ioSettingsWCRoute');

function getSettingsInfoWCIo() {
    return getSettingsDataWCIo();
}