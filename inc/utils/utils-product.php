<?php
function getListVarariationKey($childrens)
{
    if($childrens[0]['variation_name']) {
        $keys = array_keys($childrens[0]['variation_name']);	
        return $keys;
    }
    return null;
}

function inStockConverterToBoolean($value) {
    if($value ==='instock'){
        return true;
    }else{
        return false;
    }
}

function getAvailablesTermesByVariationKey($variation_name, $childrens){
    $listStockStatus = array();
    $listAvailableTermes = array();
    $listTermesInStock = array();
    foreach($childrens as $child) {
        $terme = $child['variation_name'][$variation_name];
        $inStock = $child['stock_status'];
        $termeStockState = array(
           $terme => inStockConverterToBoolean($inStock),
        );
       
        if (!array_key_exists($terme, $listStockStatus)){
           $listStockStatus[$terme] = inStockConverterToBoolean($inStock);
        }else{
            if(inStockConverterToBoolean($inStock)){
                $listStockStatus[$terme] = true;
            }
        }
        if (!in_array($terme, $listAvailableTermes)){
            array_push( $listAvailableTermes, $terme);
        }
        
       
    };
    foreach($listAvailableTermes as $terme){
        if($listStockStatus[$terme]){
            array_push(  $listTermesInStock, $terme);
        }
    }
    return array(
        'termes_stock_status' => $listStockStatus,
        'termes_names' => $listAvailableTermes,
        'termes_in_stock' =>$listTermesInStock,
    );
}

function getListVariationAvailble($childrens){
    $listVariationkey = getListVarariationKey($childrens);
    $listVariationAvailable = array();
    foreach($listVariationkey as $variationKey ){
        $termes = getAvailablesTermesByVariationKey($variationKey, $childrens);
        $variationName = getNameVariationByVariationKey($variationKey);
        
        array_push($listVariationAvailable, array(
            'variation_name' => $variationName,
            'variation_key' => $variationKey,
            'termes' => $termes,

        ));
    };
    return $listVariationAvailable;
}

function getNameVariationByVariationKey($variation_key){
    $variation_name_raw = array_pop(explode("_",$variation_key));
    $variation_name = str_replace("-", " ",$variation_name_raw);
    return $variation_name;
}

function haveVariations ($listVariation){
    if( count($listVariation) > 0){
        return true;
    }else{
        return false;
    }
}


function isValidPrice($price){
    if($price === "" || $price === null){
        return false;
    }else{
        if(floatval($price)){
            return true;
        }
       
    }
}
function productIsInStock($inStockStatusProductParent, $listVariation, $priceProductParent, $childrens){
    $variationInStock = false;
    if(haveVariations($listVariation)){
        if(!checkSanityProductChild($childrens)){
            return false;
        }
        foreach($listVariation as $variation){
           if (count($variation["termes"]["termes_in_stock"]) > 0){
            $variationInStock = true;
           }
        }

        return  $variationInStock;
    }else{
        if(isValidPrice($priceProductParent)){
            return inStockConverterToBoolean($inStockStatusProductParent);
        }else{
            return false;
        }
        
    }
}

function checkSanityProductChild($childrens){

    foreach($childrens as $child){
        if(!isValidPrice($child['price']) ){
            return false;
        }

    }
    return true;
}

function priceValidFilter($price){
    if(isValidPrice($price)){
        return $price;
    }else{
        return"";
    }
}

