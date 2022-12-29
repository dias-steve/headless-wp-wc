<?php

function getHomeData(){
    $mainQuery = new WP_Query(array(
        'post_type' => array('page'),
        'post_status' => 'publish',// liste des types de posts que nous cherchons
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    $results = null;

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        //si c'est un post ou page mettre dans le tableau information générale 
        if(get_post_type() == 'page') {
            if(get_the_title() == 'accueil' or get_the_title() == 'Accueil' ){
                $collection1_id=get_field('collection_1');
                $collection2_id=get_field('collection_2');
                $collection3_id=get_field('collection_3');
                $shootbook_id = get_field('shootbook_1');
                
                $results = array(
                    'title' => get_the_title(),
                    'id' => get_the_ID(),
                    'seo'=> get_seo_data(),
                    'phrase_intermediaire' => get_field('phrase_intermediaire'),
                    'image_category_collection' => get_field('image_categorie_collection'),
                    'image_category_shootbook' => get_field('image_categorie_shootbook'),
                    'id_video_youtube' => get_field('id_video_youtube'),
                    'collection_1' => getCollectiondataAccueil($collection1_id),
                    'collection_2' => getCollectiondataAccueil($collection2_id),
                    'collection_3' => getCollectiondataAccueil($collection3_id),
                    'shootbook_1' => getShootbookInfoData($shootbook_id)

                );
            }
        } 
    }
    return $results;
}

//home
function getCollectiondataAccueil($collectionID){
    $mainQuery = new WP_Query(array(
         'post_type' => array('collections'),
         'post_id' => $collectionID,
         'post_status' => 'publish',
     ));
     $results = null;
     while($mainQuery->have_posts()) {
         $mainQuery->the_post();
         if($collectionID === get_the_ID()){
             $results = array(
                 'id' => get_the_ID(),
                 'title' => get_the_title(),
                 'titre_accueil' => get_field('titre_accueil'),
                 'image_1_accueil' => get_field('image_1_accueil'),
                 'short_description' => get_field('short_description')
             );
         }
     }
     wp_reset_query();
     return $results;
 
 }

