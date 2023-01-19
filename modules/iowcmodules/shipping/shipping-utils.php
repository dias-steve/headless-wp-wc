<?php



function ioBbloomer_get_all_shipping_zones() {
    $data_store = WC_Data_Store::load( 'shipping-zone' );
    $raw_zones = $data_store->get_zones();
    foreach ( $raw_zones as $raw_zone ) {
       $zones[] = new WC_Shipping_Zone( $raw_zone );
    }
    $zones[] = new WC_Shipping_Zone( 0 ); // ADD ZONE "0" MANUALLY
    return $zones;
 }

 function ioBbloomer_get_all_shipping_rates() {
     $results = array();
    foreach ( ioBbloomer_get_all_shipping_zones() as $zone ) {
       array_push($results,
         
        array(
            'zone_shipping_methods' => $zone->get_shipping_methods()[0]->get_method_title()
        )
        );
       $zone_shipping_methods = $zone->get_shipping_methods();
       foreach ( $zone_shipping_methods as $index => $method ) {
          $method_is_taxable = $method->is_taxable();
          $method_is_enabled = $method->is_enabled();
          $method_instance_id = $method->get_instance_id();
          $method_title = $method->get_method_title(); // e.g. "Flat Rate"
          $method_description = $method->get_method_description();
          $method_user_title = $method->get_title(); // e.g. whatever you renamed "Flat Rate" into
          $method_rate_id = $method->get_rate_id(); // e.g. "flat_rate:18"
       }
       print_r( $zone_shipping_methods );   
    }
 }

 function ioGetShippingClasses() {
 
   $shipping_classes = WC()->shipping()->get_shipping_classes();
   $shipping_classes_methode = WC()->shipping()->get_shipping_method_class_names();
  // 
  
  //$cl = WC_Shipping_Flat_Rate::find_shipping_classes(37);
   return  $shipping_classes_methode ;
 }
