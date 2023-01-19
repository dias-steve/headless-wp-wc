<?php

    function ioGetAllCoupons(){

        $results = array();
        $mainQuery = new WP_Query(array(
            'post_type' => array('shop_coupon'),
            'post_status' => 'publish',
            'posts_per_page' => -1,
            //s pour serach et on reccupère la valeur du paramètre trem passé dans URL tel que ?term=keyword
        ));
    
        while ($mainQuery->have_posts()) {
            $mainQuery->the_post();
            array_push($results, array(
                'id' =>get_the_ID(),
            
            ));
        }
        return $results;
    }
?>