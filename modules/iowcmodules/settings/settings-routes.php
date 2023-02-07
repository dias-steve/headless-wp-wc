<?php
function ioSettingsRoute() {
    register_rest_route('io/v2','settings/',array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getSettingsInfoIo' // notre fonfonction 
    ));

}
add_action('rest_api_init', 'ioSettingsRoute');

function getSettingsInfoIo() {
    return getSettingsDataIo();
}