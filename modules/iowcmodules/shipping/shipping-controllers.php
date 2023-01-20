<?php 
function ioGetAllShipmentsData()
{
    global $woocommerce;
    $results = array();

    foreach ( ioBbloomer_get_all_shipping_zones() as $zone ) {
       
        $zone_shipping_methods = $zone->get_shipping_methods();
        $methods = array();
        foreach ( $zone_shipping_methods as $index => $method ) {
           $method_is_taxable = $method->is_taxable();
           $method_is_enabled = $method->is_enabled();
           $method_instance_id = $method->get_instance_id();
           $method_title = $method->get_method_title(); // e.g. "Flat Rate"
           $method_description = $method->get_method_description();
           $method_user_title = $method->get_title(); // e.g. whatever you renamed "Flat Rate" into
           $method_rate_id = $method->get_rate_id(); // e.g. "flat_rate:18"
           array_push($methods, array(
            'method_title' => $method->get_method_title(),
            'method_id' => $method->id,
            'method_rate_id' => $method->get_rate_id(),
            'method_user_title' => $method->get_title(),
            'method_is_enbled'=>$method->is_enabled(),
            'method_cost' => $method->cost,
            'method_description' => $method->get_method_description(),
            'method_instance_id' => $method->get_instance_id(),
            'min_amount' => $method->min_amount,
            'class_shipping' => ""
 
          
            
        ));
        }

        array_push($results, array(
        'zone_id' => $zone->get_id(),
        'zone_name' => $zone->get_zone_name(),
        'zone_order' => $zone->get_zone_order(),
        'zone_locations' => $zone->get_zone_locations(),
        'zone_formatted_location' => $zone->get_formatted_location(),
        'zone_shipping_methods' => $methods
        ));
     }

    return $results;
}