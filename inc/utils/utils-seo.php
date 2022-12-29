<?php
 function get_seo_data(){
    return array(
        'title_seo' => get_field('title_seo'),
        'meta_description_seo' => get_field('meta_description_seo'),
        'other_data_seo' => get_field('other_data_seo'),
    );
 }