<?php

function AllProductCategoryRaw(){



    $result = get_terms( ['taxonomy' => 'product_cat'] );


    return $result;
}

function getChildProductCategory($idParent){
    $categories = get_terms( ['taxonomy' => 'product_cat'] );
     $result= array_filter($categories, function($category) use ($idParent){
        if($category->parent === $idParent){
            return true;
        }else{
            return false;
        }
        return false;
    });
    return $result;
}

function getProductCategory($id){
    $id_int = (int) $id;
    $categories = get_terms( ['taxonomy' => 'product_cat'] );
    foreach($categories as $category){
        if($category->term_id === $id_int){
            $category -> id =  $category ->term_id;
            $category  ->thumbnail =  getThumailsProductCategory($category ->term_id);
            return $category ;
        }
    }
    return null;
}

function AllPorductCategoriesWhitTree(){
    
    $rootParents = getChildProductCategory(0);

    
 
    $tree = array();
    foreach($rootParents as $rootParent ){
        $rootParent ->thumbnail =  getThumailsProductCategory($rootParent->term_id);
        $rootParent->chidls = getChildTree($rootParent->term_id);
        array_push($tree,  $rootParent );
    }

    $result = array(
        'categorie_flat' => getFlatCategoryList(),
        'categorie_tree' => $tree,
    );
    return $result;

}

function getRootCategory(){
    return getChildProductCategory(0);
}

function getChildTree($idParent){
    $childs = getChildProductCategory($idParent);
    if (count( $childs) >=1){
        $chidlsResults = array();
        foreach ($childs as $child){
            $child ->thumbnail = getThumailsProductCategory($child->term_id);
            $child->childs = getChildTree($child->term_id);
            array_push($chidlsResults, $child);
        };
        return $chidlsResults;
    }else{
        return [];
    }
}

function getThumailsProductCategory($term_id){
    $thumbnail_id = get_term_meta($term_id, 'thumbnail_id', true);
    $url = wp_get_attachment_url($thumbnail_id);
    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 
    return array(
        'url' => $url,
        'alt' => $alt
    );
}

function getFlatCategoryList(){
    $categories = get_terms( ['taxonomy' => 'product_cat'] );
    $result= array();

    foreach(  $categories as $category ){
        $category  ->thumbnail =  getThumailsProductCategory($category ->term_id);
        $category ->have_childs = count(getChildTree($category ->term_id)) > 0 ? true : false;
        $category ->link = '/productcat/'.$category ->term_id;
        $category -> id =  $category ->term_id;
       // $category -> childs = getChildTree($category ->term_id);
        array_push($result,  $category  );
    }
    return $result;
}

function getFlatCategoryListPublic(){
    $categories = get_terms( ['taxonomy' => 'product_cat'] );
    $result= array();

    foreach(  $categories as $category ){
        if($category ->description !== 'false'){
            $category  ->thumbnail =  getThumailsProductCategory($category ->term_id);
            $category ->have_childs = count(getChildTree($category ->term_id)) > 0 ? true : false;
            $category ->link = '/productcat/'.$category ->term_id;
            $category -> id =  $category ->term_id;
           // $category -> childs = getChildTree($category ->term_id);
            array_push($result,  $category  );
        }

    }
    return $result;
}

function getAllProductCat(){
    return getFlatCategoryListPublic();
    
}

function getProductCat($data){
    $id = $data['id'];
    $result = array(
        "id"=> $id,
        "product_cat_info"=> getProductCategory($id) ,
        "product_list" =>listProductsCollectionInfo($id),

    );

    return $result;
}
//filter

