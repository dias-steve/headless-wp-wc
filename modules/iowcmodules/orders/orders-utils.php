<?php

function ioGetProductPriceList($productIdList){

    $result = null;
    $all_product_is_found = true;

    $productlistResult = array();
    if(is_array($productIdList)){
      foreach( $productIdList as $productData ) {
            $mainQuery = new WP_Query(array(
                'post_type' => array('product'),
                'post_id' => $productData['id_parent']
            ));

            while ($mainQuery->have_posts()) {
                $mainQuery->the_post();
                if (get_the_ID() === (Int) $productData['id_parent']){
                $cost_shipping= get_field('shippement_cost_unit');
                $product = wc_get_product($productData['id']);
                if($product){
                  
                
                array_push($productlistResult, array(
                    'id' =>  $productData['id'],
                    'title' => $product->get_name(),
                    'free_shippement' =>get_field('free_shippement'),
                    'shippement_cost_unit'=> $cost_shipping,
                    'price' => ioPriceValidFilter($product->get_price()),
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_sale_price(),
                    'quantity' =>$productData['quantity']
                ));
                }else {
                    $all_product_is_found = false;
                }
            }
            }
  
        };
    }

    if(!$all_product_is_found){
        return [];
    }

    return $productlistResult;

}

function ioConvertStringRequestTotalOrder($request){

    $productList = explode('!', $request->get_param('products'));
    $productmap= [];
 
    $productmap = array_map(
        function($productString){
            $productAttList= explode(',', $productString);

            $productObject=array_reduce($productAttList, function($object,$productAtt ){
                $productAttExp =  explode('=',$productAtt);
                $object[$productAttExp[0]] = $productAttExp[1];
                return $object;
            }, array());

            return $productObject;
        }
        ,  $productList);
    return  array(
        "items" => $productmap,
        "method_rate_id" => $request->get_param('method_rate_id')
    );
}


function ioGetShipmentsMethodByIdForOrder($method_rate_id_request)
{
    global $woocommerce;
    $results = null;

    foreach ( ioBbloomer_get_all_shipping_zones() as $zone ) {
       
        $zone_shipping_methods = $zone->get_shipping_methods();
      
        foreach ( $zone_shipping_methods as $index => $method ) {
           $method_rate_id = $method->get_rate_id(); // e.g. "flat_rate:18"
            if ($method_rate_id_request === $method_rate_id){
                $results = array(
                    'method_title' => $method->get_method_title(),
                    'method_id' => $method->id,
                    'method_rate_id' => $method->get_rate_id(),
                    'method_user_title' => $method->get_title(),
                    'method_is_enbled'=>$method->is_enabled(),
                    'method_cost' => $method->cost ? $method->cost : 0,
                    'method_description' => $method->get_method_description(),
                    'method_instance_id' => $method->get_instance_id(),
                    'min_amount' => $method->min_amount,
                    'class_shipping' => ""
                );
            }
        }
     }

    return $results;
}


function ioGetTotalData($orderObject){
    return ioGetTotalPriceOrder(
        ioGetProductPriceList($orderObject['items']),
        ioGetShipmentsMethodByIdForOrder($orderObject['method_rate_id'])
    );
}
function ioGetTotalPriceOrder($productIdList, $shippingMethod){

    $shippingCost = (float)$shippingMethod['method_cost'];
    if (!is_array($productIdList)){
        return null;
    }

    $totalInitial = array(
        'trust_result' => array(
            'is_trust' =>true,
            'message' => '',
        ),
        'detail' => array(),
        'shipping_method' => $shippingMethod,
        'shippingCost' => $shippingCost,
        'sub_total_product' => 0,
        'sub_total_shipping'=> 0,
        'is_all_free_shipping' => true,
        'total' => 0
    
    );



    $totalResult = array_reduce($productIdList,
     function($total, $productItem){
        $shippingCost = functionCalculCostShippment($total['shippingCost'],$productItem['shippement_cost_unit'], $productItem['quantity']);

        $productPrice = (float)$productItem['price']* (int)$productItem['quantity'];

        if(!$productItem['free_shippement']){
            $total['is_all_free_shipping'] = false;
        }
        $total['sub_total_shipping_cost_sup'] = $total['sub_total_shipping_cost_sup'] + $shippingCost;
        $total['sub_total_product'] = $total['sub_total_product'] + $productPrice;

        array_push( $total['detail'], array(
            'id' => $productItem['id'],
            'price' => $productItem['price'],
            'quantity' =>$productItem['quantity'],
            'shipping_cost_unit'=> $productItem['shippement_cost_unit'],
            'free_shippement' =>  $productItem['free_shippement'],
            "sub-total" => $shippingCost+$productPrice
        ));
        return $total;

    }
    ,$totalInitial);

    if (  $totalResult['is_all_free_shipping'] ){
        $totalResult['total'] =  number_format((float) $totalResult['sub_total_product'],2,',', '');
    }else{
        $totalfloat = (float) $totalResult['sub_total_shipping_cost_sup'] + $totalResult['sub_total_product'] + $totalResult['shippingCost'];
        $totalResult['total'] = number_format($totalfloat,2,',', '');
    }


    if(!$shippingMethod){


        return array(
            'trust_result' => array(
                'is_trust' =>false,
                'message' => 'No shipping method found',
            ),
            'detail' => array(),
            'shipping_method' => $shippingMethod,
            'shippingCost' => $shippingCost,
            'sub_total_product' => 0,
            'sub_total_shipping_cost_sup'=> 0,
            'total' => -1
        
        );
    }

    if(($shippingMethod['min_amount'] ? $shippingMethod['min_amount'] : 0) > $totalResult['sub_total_product'] ){

     return array(
            'trust_result' =>  array(
                'is_trust' =>false,
                'message' => 'the total cart is not > of min amount of the shipping method',
            ),
            'detail' => array(),
            'shipping_method' => $shippingMethod,
            'shippingCost' => $shippingCost,
            'sub_total_product' => 0,
            'sub_total_shipping_cost_sup'=> 0,
            'total' => -1
        
        );
    }


    if(!count($productIdList) > 0){
        return array(
            'trust_result' => array(
                'is_trust' =>false,
                'message' => 'Product not found',
            ),
            'detail' => array(),
            'shipping_method' => $shippingMethod,
            'shippingCost' => $shippingCost,
            'sub_total_product' => 0,
            'sub_total_shipping_cost_sup'=> 0,
            'total' => -1
        );
    }
    return $totalResult;


}

function functionCalculCostShippment($shippemntCost, $itemCostunit,$quantityItem){

    $result = ($shippemntCost? (float)$shippemntCost: 0 )*
        ( $itemCostunit ? (float)$itemCostunit: 0 )*
        (float)$quantityItem;
    return number_format($result, 2,',', '');
}