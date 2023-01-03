<?php
function ioGetAllProductCategoryRaw(){



    $result = get_terms( ['taxonomy' => 'product_cat'] );


    return $result;
}

function ioGetChildProductCategory($idParent){
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

function ioGetProductCategory($id){
    $id_int = (int) $id;
    $categories = get_terms( ['taxonomy' => 'product_cat'] );
    foreach($categories as $category){
        if($category->term_id === $id_int){
            $category -> id =  $category ->term_id;
            $category  ->thumbnail =  ioGetThumailsProductCategory($category ->term_id);
            return $category ;
        }
    }
    return null;
}

function ioGetAllPorductCategoriesWhitTree(){
    
    $rootParents =ioGetChildProductCategory(0);

    
 
    $tree = array();
    foreach($rootParents as $rootParent ){
        $rootParent ->thumbnail = ioGetThumailsProductCategory($rootParent->term_id);
        $rootParent->chidls =ioGetChildTree($rootParent->term_id);
        array_push($tree,  $rootParent );
    }

    $result = array(
        'categorie_flat' =>ioGetFlatCategoryList(),
        'categorie_tree' => $tree,
    );
    return $result;

}

function ioGetRootCategory(){
    return ioGetChildProductCategory(0);
}

function ioGetChildTree($idParent){
    $childs = ioGetChildProductCategory($idParent);
    if (count( $childs) >=1){
        $chidlsResults = array();
        foreach ($childs as $child){
            $child ->thumbnail = ioGetThumailsProductCategory($child->term_id);
            $child->childs = ioGetChildTree($child->term_id);
            array_push($chidlsResults, $child);
        };
        return $chidlsResults;
    }else{
        return [];
    }
}

function ioGetThumailsProductCategory($term_id){
    $thumbnail_id = get_term_meta($term_id, 'thumbnail_id', true);
    $url = wp_get_attachment_url($thumbnail_id);
    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 
    return array(
        'url' => $url,
        'alt' => $alt
    );
}

function ioGetFlatCategoryList(){
    $categories = get_terms( ['taxonomy' => 'product_cat'] );
    $result= array();

    foreach(  $categories as $category ){
        $category  ->thumbnail =  ioGetThumailsProductCategory($category ->term_id);
        $category ->have_childs = count(ioGetChildTree($category ->term_id)) > 0 ? true : false;
        $category ->link = '/productcat/'.$category ->term_id;
        $category -> id =  $category ->term_id;
       // $category -> childs = getChildTree($category ->term_id);
        array_push($result,  $category  );
    }
    return $result;
}

function ioGetFlatCategoryListPublic(){
    $categories = get_terms( ['taxonomy' => 'product_cat'] );
    $result= array();

    foreach(  $categories as $category ){
        if($category ->description !== 'false'){
            $category  ->thumbnail =  ioGetThumailsProductCategory($category ->term_id);
            $category ->have_childs = count(ioGetChildTree($category ->term_id)) > 0 ? true : false;
            $category ->link = '/productcat/'.$category ->term_id;
            $category -> id =  $category ->term_id;
           // $category -> childs = ioGetChildTree($category ->term_id);
            array_push($result,  $category  );
        }

    }
    return $result;
}

function ioGetAllProductCat(){
    return ioGetFlatCategoryListPublic();
    
}

