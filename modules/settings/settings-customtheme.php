<?php
function copyrightSection($wp_customize){
    //section
    $wp_customize->add_section(
        'sec_copyright', array(
            'title' => 'Copyright Settings',
            'description' => 'Copyright Section'
        )
    );
            //field 1 
            $wp_customize->add_setting(
                'set_copyright', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'set_copyright', array(
                    'label' => 'Copyright',
                    'description' => 'Please, add your copyright Infirmation here',
                    'section' => 'sec_copyright',
                    'type' => 'text'
                )
            );
}

function multiLangSection($wp_customize){
    //section
    $wp_customize->add_section(
        'sec_multiLang', array(
            'title' => 'Languages supported',
            'description' => ''
        )
    );
            //field 1 
            $wp_customize->add_setting(
                'set_mutil_lang_list', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'set_mutil_lang_list', array(
                    'label' => 'Language supported List',
                    'description' => 'Exemple: FR|EN|ES',
                    'section' => 'sec_multiLang',
                    'type' => 'text'
                )
            );
    
}

function generalSettingInfoSiteSection ($wp_customize){
        //section
        $wp_customize->add_section(
            'sec_website_info', array(
                'title' => 'WebSite Information',
                'description' => 'WebSite Information'
            )
        );

        //logo
        $wp_customize->add_setting(
            'logo_website', array(
                'type'  => 'theme_mod',
                'default' => '',
                'sanitize_callback' => 'theme_slug_sanitize_file'
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Image_Control( 
                $wp_customize, 
                'logo_website', 
                array(
                    'label'      => __( 'Logo', 'theme_slug' ),
                    'section'    => 'sec_website_info'                   
                )
            ) 
        ); 

        //Nom du site

        //field 1 
        $wp_customize->add_setting(
            'name_web_site', array(
                'type'  => 'theme_mod',
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field'
            )
        );

        $wp_customize->add_control(
            'name_web_site', array(
                'label' => 'Name of the website',
                'description' => '',
                'section' => 'sec_website_info',
                'type' => 'text'
            )
        );

        //field 1 
        $wp_customize->add_setting(
            'url_front_website', array(
                'type'  => 'theme_mod',
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field'
            )
        );

        $wp_customize->add_control(
            'url_front_website', array(
                'label' => 'URL of the frontend website',
                'description' => '',
                'section' => 'sec_website_info',
                'type' => 'text'
            )
        );
}

function inMaintenanceSection( $wp_customize) {
    //section
    $wp_customize->add_section(
        'sec_maintenance_mode', array(
            'title' => 'Maintenance Mode',
            'description' => 'Maintenance Mode Setting'
        )
    );

        $wp_customize->add_setting(
            'maintenance_mode_is_activate', array(
                'type'  => 'theme_mod',
                'default' => '',
                'sanitize_callback' => 'theme_slug_sanitize_checkbox'
            )
        );

        $wp_customize->add_control(
            'maintenance_mode_is_activate', array(
                'label' => 'Activate Maintenance Mode',
                'description' => '',
                'section' => 'sec_maintenance_mode',
                'type' => 'checkbox'
            )
        );


           //add setting
        $wp_customize->add_setting( 
            'page_maintenance_mode', array(
            'type'  => 'theme_mod',
            'default' => '',
        ));

        //add control
        $wp_customize->add_control( 
            'page_maintenance_mode', array(
                'label' => 'Select Page to Display While Maintenance Mode Is On',
                'type'  => 'dropdown-pages',
                'section' => 'sec_maintenance_mode',
        ));
    
}


function io_theme_settings_customizer($wp_customize){
    copyrightSection($wp_customize);
    generalSettingInfoSiteSection($wp_customize);
    inMaintenanceSection($wp_customize);
    multiLangSection($wp_customize);

}


add_action('customize_register', 'io_theme_settings_customizer');