<?php
function getCollectionInfo($data){

    $results = 'no result';
    $listproducts = array();
    $mainQuery = new WP_Query(array(
        'post_type' => array('collections'),
        'post_status' => 'publish',
        'post_id' => $data['id']// liste des types de posts que nous cherchons
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if(get_the_ID() == $data['id']){
            $idShootbook = get_field('shootbook_collection');
            $results = array(
                'id' => get_the_ID(),
                'seo'=> get_seo_data(),
                'title' => get_the_title(),
                'image_principale' => get_field('image_principale'),
                'introduction' => get_field('introduction'),
                'description_detaille' => get_field('description_detaille'),
                'categorie_collection_id' => get_field('categorie_collection'),
                'next_collection' => get_field('next_collection'),
                'shootbook_collection_id' => get_field('shootbook_collection'),
                'image_principale_v2' => get_field('image_principale_v2'),
                'productlist' => listProductsCollectionInfo(get_field('categorie_collection')),
                'shootbook_collection' => getShootbookInfoData($idShootbook),
             
                
            );
        }

        //si c'est un post ou page mettre dans le tableau information générale

            
        
    }
    return $results;
}


function getCollectionData($id){

    $results = 'no result';
    $listproducts = array();
    $mainQuery = new WP_Query(array(
        'post_type' => array('collections'),
        'post_status' => 'publish',
        'post_id' => $id// liste des types de posts que nous cherchons
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if(get_the_ID() == $id){
   
            $results = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'image_principale' => get_field('image_principale'),
                'introduction' => get_field('introduction'),
                'description_detaille' => get_field('description_detaille'),
                'categorie_collection_id' => get_field('categorie_collection'),
                'shootbook_collection_id' => get_field('shootbook_collection'),
                'image_principale_v2' => get_field('image_principale_v2'),


                
            );
        }

        //si c'est un post ou page mettre dans le tableau information générale

            
        
    }
    return $results;
}

function getShootbookByID($id) {
    $resultShoot = null;
    $mainQueryShoot = new WP_Query(array(
        'post_type' => array('shootbooks'),
        'post_status' => 'publish',
        'post_id' => $id
    ));

    while($mainQueryShoot->have_posts()) {
        $mainQueryShoot->the_post();
        if(get_the_ID() == $id){
           $resultShoot = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'decription_shootbook'=> get_field('decription_shootbook'),
                'image_1'=> get_field('image_1'),
                'image_2'=> get_field('image_2'),
                'image_3'=> get_field('image_3'),
                'image_4'=> get_field('image_4'),
           );
        }
    }
    return $resultShoot;
}
function getAllCollection($data){
    $results = array();

    $mainQuery = new WP_Query(array(
        'post_type' => array('collections'),
        'post_status' => 'publish',
        //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
    ));

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();
        
        //si c'est un post ou page mettre dans le tableau information générale
        array_push($results, array(
            'id' => get_the_ID(),
            'title' => get_the_title(),
            'titre_accueil' => get_field('titre_accueil'),
            'image_1_accueil' => get_field('image_1_accueil'),
            'image_2_accueil' => get_field('image_2_accueil'),
            'image_3_accueil' => get_field('image_3_accueil'),
        ));
    }
       
    
    return $results;
}
?>