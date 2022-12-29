<?php

    function getAuthAccessFront($data) {
        $results = array(
            'is_auth' => false,
            'token' => null
        );
        $user_id = $data['user'];
            $mdp = $data['mdp'];
    
        if(is_authenticated($user_id, $mdp)){
            $results['is_auth'] = true;
            $results['token'] = '7263HSK89SJKS0033';
        }
        return $results;
    }

    function is_authenticated($user_id, $mdp){
        
        $mainQuery = new WP_Query(array(
            'post_type' => array('frontaccess'),
            'post_status' => 'publish',
        ));
        while($mainQuery->have_posts()) {
            $mainQuery->the_post();
            if($user_id ===  get_field('user_id') &&  $mdp ===  get_field('mdp')){
               return  true;
            }
        }

        return false;

    }
?>