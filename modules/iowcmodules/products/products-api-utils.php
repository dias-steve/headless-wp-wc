<?php

/**
 * @param 
 * PAGE
 * -limit = int,
 * -page= int,
 * CATEGORIES
 * -categories= array
 * PRODUCT
 * -numericfilter = [meta_value][OPERATOR][value]
 * -sort = ['-' or null ][meta_value] //'_price'
 */


 /**
  *     'posts_per_page' => 10,
        'post_type' => $post_type,
        'paged'=> 1,
        'orderby'=>"meta_value",
        'order' => 'ASC',
        'meta_key' => '_price',
        'post_status' => 'publish'
  */
function ioConvertToQuery($post_type,$request){



    $mainQuery =array(
        'post_type' => $post_type,
        // 'tax_query' =>$tax_query,
        // 'meta_query' =>$meta_query
     );


    /**SORTING */
    if($request->get_param('sort') !== null){
        $meta_key_raw = $request->get_param('sort');
        $order = 'ASC';
        $meta_key=  $request->get_param('sort');
        if(str_split($meta_key_raw)[0]=== '-'){
            $order='DESC';
            $meta_key=  str_replace("-","", $meta_key_raw);
        }
        $mainQuery['orderby'] = "meta_value";
        $mainQuery['order'] =   $order;
        $mainQuery['meta_key'] = $meta_key;
    }

    /**CATGORIE */
    if($request->get_param('taxinomy')){
        
        $tax_query=array();

        $tax_query_list = explode('!', $request->get_param('taxinomy'));

        foreach ($tax_query_list as $tax){
            $taxinomy_array = explode('=', $tax);

            if ( count($taxinomy_array) > 1){

                
                $taxinomy = $taxinomy_array[0];
                $list_id_taxinomy =  explode(',',$taxinomy_array[1]);

               array_push( $tax_query , array(
                    'taxonomy' =>  $taxinomy ,
                    'field'    => 'term_id',
                    'terms'    =>  $list_id_taxinomy
                ));
            }
        }

        if(count($tax_query) > 0){
            $mainQuery['tax_query'] = $tax_query;
        }


    }

    /*page*/
    if($request->get_param('page')){

        $mainQuery['paged'] = $request->get_param('page');
    }

    if($request->get_param('limit')){

        $mainQuery['posts_per_page'] = $request->get_param('limit');
    }

    $meta_query = array();
    $tax_query = array();
    $cat_product= array();

    array_push ($tax_query,
    array('taxonomy' => 'action_cat',
            'field'    => 'term_id',
            'terms'    =>   $cat_product)
);

    //TODO: numerical filter


    return  $mainQuery;
}
