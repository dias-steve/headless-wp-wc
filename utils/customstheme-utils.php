<?php
function theme_slug_sanitize_checkbox( $input ){
              
    //returns true if checkbox is checked
    
    return ( $input);
}

    //file input sanitization function
function theme_slug_sanitize_file( $file, $setting ) {
        
    //allowed file types
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon',
        'svg'   => 'image/svg',
    );
        
    //check file type from file name
    $file_ext = wp_check_filetype( $file, $mimes );
        
    //if file has a valid mime type return it, otherwise return default
    return ( $file_ext['ext'] ? $file : $setting->default );
}