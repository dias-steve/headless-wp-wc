<?php
    function getSettingsDataIo(){
        return array(
            'logo_src'=>get_theme_mod('logo_website'),
            'url_front'=>get_theme_mod('url_front_website'),
            'name_site' => get_theme_mod('name_web_site'),
            'homepage_id_page' => get_option('page_on_front'),
            'copyright' => get_theme_mod('set_copyright'),
            'maintenance_mode' => getMaintenanceModeData(),
            'languages_supported_list' =>  getLangList(get_theme_mod('set_mutil_lang_list')),
        );
    }

    function getMaintenanceModeData() {
        return array(
            'is_activated' => get_theme_mod('maintenance_mode_is_activate'),
            'page_maintenance_id' => get_theme_mod('page_maintenance_mode'),
        );
    }

    function getLangList($langText){
        $result = explode('|', $langText);
        if($result[0]===""){
            return null;
        }else{
            return $result;
        }

    }