<?php 


function aProposData() {
    $results = 'aucun';
    
    $mainQuery = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => array('page'),
        'post_status' => 'publish',
        'pagename' => 'A propos'
        
    ));

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        $results= array (
            'seo'=> get_seo_data(),
            'id' => get_the_ID(),
            'title' => get_field('title_page'),
            'content' => get_field('content_page')
        );
        
    }
    return $results;  
}


