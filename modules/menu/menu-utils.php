<?php 



function createMenuItem($id, $name, $link, $thumbnailUrl, $thumnailAlt, $parent, $haveChildren){
    $menuItem = array(
        "term_id" => $id,
        "name" => $name,
        "parent" => $parent,
        "link" => $link,
        "slug" => $name,
        "description" => "",
        "thumbnail" => array(
            "url" => $thumbnailUrl,
            "alt" => $thumnailAlt,
        ),
        "have_childs" => $haveChildren
        );
     
    return $menuItem;
}

function createMenuItemByPostType($postType, $nameRoot, $root_link ,$thumbnailUrl, $thumnailAlt,$parenRoot,$haveChildren){
    $results = array();

    $mainQuery = new WP_Query(array(
        'post_type' => array($postType),
        'post_status' => 'publish',
    ));
    
   
    $thumbnail_lastChild_url = false;
    $thumbnail_lastChild_alt = false;

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        $thumbnail_id = get_post_thumbnail_id(get_the_ID());
        $thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 
        $thumbnail_url = get_the_post_thumbnail_url();
        $term_id = $postType.'_'.get_the_ID();
        $name = get_the_title();
        $parent = $postType;
        $child_link =$root_link.'/'.get_the_ID();
        $thumbnail_lastChild_url =  $thumbnail_url;
        $thumbnail_lastChild_alt = $thumbnail_alt;

        $child = createMenuItem(
            $term_id,  $name,  $child_link,   $thumbnail_url , $thumbnail_alt,  $parent, false);
        
        array_push($results,$child);
    };

    $parentItem = createMenuItem($postType, $nameRoot,$root_link ,$thumbnail_lastChild_url,$thumbnail_lastChild_alt,0,$haveChildren);
    array_push($results, $parentItem);

    if($haveChildren && count($results)<=1){
        return array();
    }else{
        return $results;
    }



}

function getMenuNav($id_menu){
    $MenuNavItemRaw =wp_get_nav_menu_items($id_menu);
    $menuformated = array();

    foreach( $MenuNavItemRaw as $item){
        $link_item = $item->object === "custom"? $item->url : '/'.$item->object.'/'.$item->object_id;
      
        $new_item =  createMenuItem(
            $item->ID,
            $item->title,
            $link_item,
            false,
            false,
            (int)$item->menu_item_parent,
            haveChildMenuItem($item->ID,$MenuNavItemRaw)
        );
        $new_item['post_type'] = $item->object;
        $new_item['id_post'] =$item->object_id;


        array_push($menuformated, $new_item);
    }
  
  return        $menuformated;

}

function haveChildMenuItem($id_menu_item,$MenuNavItemRaw){

    foreach($MenuNavItemRaw as $item){
        if ($item->menu_item_parent == $id_menu_item){
            return true;
        }
    }
    return false;
}
function getAllMenuKeyDetail(){

    $menu_location = get_nav_menu_locations();
    $list_menu_detail = array();
    while($element = current( $menu_location)) {

        $list_menu_detail[key( $menu_location)] =  getMenuByLocation($menu_location[key( $menu_location)]);
        next( $menu_location);
    }
    return $list_menu_detail;
}

function getMenuByLocation($locationId){
    $menu_list = wp_get_nav_menus();


    foreach( $menu_list  as $menu){
        $menu_name = $menu->name;
        $new_menu_item = array(
            'name'=>  $menu_name,
            'childrens' =>  getMenuNav($menu->term_id)
        );
        if ($menu->term_id === $locationId){
            return $new_menu_item;
        }
       

    }
    return null;
}


function getFooterMenu(){
 
    $menu_list = wp_get_nav_menus();

    $list_menu_footer = array();
    foreach( $menu_list  as $menu){
        $menu_name = $menu->name;
        $new_menu_item = array(
            'name'=>  $menu_name,
            'childrens' =>  getMenuNav($menu->term_id)
        );
        array_push($list_menu_footer, $new_menu_item);

    }
    return $list_menu_footer;
}