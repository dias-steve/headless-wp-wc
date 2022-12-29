<?php


function fancy_lab_customizer( $wp_customize){

    //section
    $wp_customize->add_section(
        'sec_copyright', array(
            'title' => 'Copyright Settings',
            'description' => 'Copyright Secion'
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

    // section 2 
    $wp_customize->add_section(
        'sec_maintenance_mode', array(
            'title' => 'Mode Maintenance',
            'description' => 'Mettre le site en mode maintenance'
        )
    );

            //field 1 

            $wp_customize->add_setting(
                'set_maintenance_mode', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'theme_slug_sanitize_checkbox'
                )
            );

            $wp_customize->add_control(
                'set_maintenance_mode', array(
                    'label' => 'Mode maintenance',
                    'description' => 'Mettre le mode maintenance',
                    'section' => 'sec_maintenance_mode',
                    'type' => 'checkbox'
                )
            );

            //field 2

            $wp_customize->add_setting(
                'set_maintenance_message', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'set_maintenance_message', array(
                    'label' => 'message',
                    'description' => 'Entrez le message ici svp',
                    'section' => 'sec_maintenance_mode',
                    'type' => 'text'
                )
            );

                //field 2

                $wp_customize->add_setting(
                    'set_maintenance_seo_desc', array(
                        'type'  => 'theme_mod',
                        'default' => '',
                        'sanitize_callback' => 'sanitize_text_field'
                    )
                );
    
                $wp_customize->add_control(
                    'set_maintenance_seo_desc', array(
                        'label' => 'SEO Meta description',
                        'description' => 'Entrez la meta description ici',
                        'section' => 'sec_maintenance_mode',
                        'type' => 'textarea'
                    )
                );

            //field 3

            $wp_customize->add_setting(
                'set_maintenance_image', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'theme_slug_sanitize_file'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Image_Control( 
                    $wp_customize, 
                    'set_maintenance_image', 
                    array(
                        'label'      => __( 'Image principale', 'theme_slug' ),
                        'section'    => 'sec_maintenance_mode'                   
                    )
                ) 
            );  
            
            //field 4


            $wp_customize->add_setting(
                'set_maintenance_image_alt', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'set_maintenance_image_alt', array(
                    'label' => 'image alt',
                    'section' => 'sec_maintenance_mode',
                    'type' => 'text'
                )
            );

            //field 5

            $wp_customize->add_setting(
                'set_maintenance_image_logo', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'theme_slug_sanitize_file'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Image_Control( 
                    $wp_customize, 
                    'set_maintenance_image_logo', 
                    array(
                        'label'      => __( 'Image logo', 'theme_slug' ),
                        'section'    => 'sec_maintenance_mode'                   
                    )
                ) 
            );

                // section 2 
    $wp_customize->add_section(
        'sec_contact', array(
            'title' => 'Apparence page Contact',
            'description' => 'Réglages des la page contact'
        )
    );

            //field 1 

            $wp_customize->add_setting(
                'sec_contact_message', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'sec_contact_message', array(
                    'label' => 'message',
                    'description' => 'Entrez le message ici svp',
                    'section' => 'sec_contact',
                    'type' => 'text'
                )
            );


        
        $wp_customize->add_section(
            'sec_guide', array(
                'title' => 'Guide des tailles',
                'description' => 'Réglages des guides des tailles'
            )
        );

            //feild
            $wp_customize->add_setting(
                'sec_message_guide', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'sec_message_guide', array(
                    'label' => 'instruction de meusure',
                    'description' => 'instruction de meusure',
                    'section' => 'sec_guide',
                    'type' => 'text'
                )
            );


            //fleild image Tab
            $wp_customize->add_setting(
                'sec_guide_image_tab', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'theme_slug_sanitize_file'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Image_Control( 
                    $wp_customize, 
                    'sec_guide_image_tab', 
                    array(
                        'label'      => __( 'Tableau de tailles', 'theme_slug' ),
                        'section'    => 'sec_guide'                   
                    )
                ) 
            );  

            //fleild image mesuer
            $wp_customize->add_setting(
                'sec_guide_image_mesure', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'theme_slug_sanitize_file'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Image_Control( 
                    $wp_customize, 
                    'sec_guide_image_mesure', 
                    array(
                        'label'      => __( 'illustration de mesure', 'theme_slug' ),
                        'section'    => 'sec_guide'                   
                    )
                )
            );

        // section 3
        $wp_customize->add_section(
            'sec_payment', array(
                'title' => 'Section paiement',
                'description' => 'Paramètrage de la section paiement'
            )
        );

            //field 1 

            $wp_customize->add_setting(
                'payment_title', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'payment_title', array(
                    'label' => 'Titre de la section paiement',
                    'description' => 'Entrez le titre de la section paiement',
                    'section' => 'sec_payment',
                    'type' => 'text'
                )
            );
            //fleild image mesuer
            $wp_customize->add_setting(
                'image_payement', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'theme_slug_sanitize_file'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Image_Control( 
                    $wp_customize, 
                    'image_payement', 
                    array(
                        'label'      => __( 'logo des paiements acceptés', 'theme_slug' ),
                        'section'    => 'sec_payment'                   
                    )
                )
            );

            //field 1 

            $wp_customize->add_setting(
                'payment_description', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'payment_description', array(
                    'label' => 'description',
                    'description' => 'Entrez la description des payments aceptés',
                    'section' => 'sec_payment',
                    'type' => 'textarea'
                )
            );

            //field 1 

            $wp_customize->add_setting(
                'link_learn_more_payment', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'link_learn_more_payment', array(
                    'label' => 'Lien vers la page des paiements',
                    'description' => 'Entrez le lien en respectant le schémas: /page/[id de page]',
                    'section' => 'sec_payment',
                    'type' => 'text'
                )
            );
        // section shipment
        $wp_customize->add_section(
            'sec_shipment', array(
                'title' => 'Section Livraison',
                'description' => 'Paramètrage de la section paiement'
            )
        );

            //field 1 

            $wp_customize->add_setting(
                'shipment_title', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'shipment_title', array(
                    'label' => 'Titre de la section Livraison',
                    'description' => 'Entrez le titre de la section Livraison',
                    'section' => 'sec_shipment',
                    'type' => 'text'
                )
            );


            //field 1 

            $wp_customize->add_setting(
                'shipment_description', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'shipment_description', array(
                    'label' => 'description',
                    'description' => 'Entrez la description de la livraison',
                    'section' => 'sec_shipment',
                    'type' => 'textarea'
                )
            );

            //field 1 

            $wp_customize->add_setting(
                'link_learn_more_shipment', array(
                    'type'  => 'theme_mod',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'link_learn_more_shipment', array(
                    'label' => 'Lien vers la page des Livraisons',
                    'description' => 'Entrez le lien en respectant le schémas: /page/[id de page]',
                    'section' => 'sec_shipment',
                    'type' => 'text'
                )
            );

    
    

    //Section RGPD
    $wp_customize->add_section(
        'sec_rgpd', array(
            'title' => 'RGPD',
            'description' => 'Paramètrage de la pop-up RGPD'
        )
    );

        //title field
        $wp_customize->add_setting(
            'rgpd_title', array(
                'type'  => 'theme_mod',
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field'
            )
        );

        $wp_customize->add_control(
            'rgpd_title', array(
                'label' => 'titre de la pop-up RGPD',
                'description' => 'Entrez le titre de la pop-up de la politique des cookies',
                'section' => 'sec_rgpd',
                'type' => 'text'
            )
        );

        //description field
        $wp_customize->add_setting(
            'rgpd_description', array(
                'type'  => 'theme_mod',
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field'
            )
        );

        $wp_customize->add_control(
            'rgpd_description', array(
                'label' => 'description',
                'description' => 'Entrez la description de la politique des cookies',
                'section' => 'sec_rgpd',
                'type' => 'textarea'
            )
        );

        //link to politique cookies field
        $wp_customize->add_setting(
        'rgpd_link', array(
            'type'  => 'theme_mod',
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field'
        )
    );

    $wp_customize->add_control(
        'rgpd_link', array(
            'label' => 'le lien vers la page politique de cookies',
            'description' => 'suivre ce schéma: /page/<id page politique de cookies>',
            'section' => 'sec_rgpd',
            'type' => 'text'
        )
    );


}

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

add_action('customize_register', 'fancy_lab_customizer');