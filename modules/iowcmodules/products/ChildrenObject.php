<?php
class ChildrenProduct
{
    public $reformater_children_list;
    public $shipping_cost_unit;
    public $is_free_shippement;
    public $product;
    public $child_id_list;



    public function __construct($product, $shipping_cost_unit,  $is_free_shippement)
    {

        $this->shipping_cost_unit = $shipping_cost_unit;
        $this->is_free_shippement = $is_free_shippement;
        $this->product = $product;
        $this->reformater_children_list = $this->getChildrensProduct();


        $this->child_id_list = $this->product->get_children();
    }

    public function getMaxPrice()
    {
        $max = null;
        if ($this->reformater_children_list) {
            foreach ($this->reformater_children_list as $child) {
                if ($max  == null) {
                    $max = (float)$child['price'];
                }
                if ($max < (float)$child['price']) {
                    $max = (float)$child['price'];
                }
            }
        }

        return $max;
    }

    public function getMinPrice()
    {

        $min = null;
        if ($this->reformater_children_list) {
            foreach ($this->reformater_children_list as $child) {
                if ($min  == null) {
                    $min  = (float)$child['price'];
                }
                if ($min > (float)$child['price']) {
                    $min = (float)$child['price'];
                }
            }
        }
        return $min;
    }

    public function isMultiPrice()
    {
        $formerPrice = null;
        foreach ($this->reformater_children_list as $child) {
            if ($formerPrice == null) {
                $formerPrice = (float)$child['price'];
            }

            if ((float)$child['price'] != $formerPrice) {
                return true;
            }
        }

        return false;
    }

    public function haveOnSaleChild()
    {
        $onSale = false;
        foreach ($this->reformater_children_list as $child) {
            if ($child['on_sale'] == true) {
                $onSale = $child['on_sale'];
            }
        }

        return  $onSale;
    }


    private function getChildrensProduct()
    {
        $children_ids = $this->product->get_children();
        $children_data = array();


        foreach ($children_ids as $child_id) {
            $childData = wc_get_product($child_id);
            $thumbnail_id_child = get_post_thumbnail_id($child_id);
            $alt_child = get_post_meta($thumbnail_id_child, '_wp_attachment_image_alt', true);
            $thumbnail_url_child = get_the_post_thumbnail_url($child_id);
            $on_sale = false;



            if (($childData->get_regular_price() !== "") && ($childData->get_regular_price() !== $childData->get_price())) {
                $on_sale = true;
            }




            array_push($children_data, array(
                'id_parent' =>  get_the_ID(),
                'id' => $child_id,
                'name' => $childData->get_name(),
                'price' => ioPriceValidFilter($childData->get_price()),
                'regular_price' => $childData->get_regular_price(),
                'stock_status' => $childData->get_stock_status(),
                'variation_name' => $childData->get_variation_attributes(),
                'ship_class' => $childData->get_shipping_class(),
                'free_shippement' =>  $this->is_free_shippement,
                'on_sale' => $on_sale,
                'shipping_cost_unit' => $this->shipping_cost_unit,
                'thumnail' => array(
                    'url' => $thumbnail_url_child ? $thumbnail_url_child : 'parent',
                    'alt' =>  $thumbnail_url_child ? $alt_child : 'alt_parent'
                ),
                'images_gallery' =>   $this->getGalleryChild($childData->get_variation_attributes()),
                'sold_individualy' => $this->product->is_sold_individually(),
                'product_is_in_stock' => (($childData->get_stock_status() ==='instock') && (ioIsValidPrice($childData->get_price()))) ? true : false

            
            ));
        }

        return $children_data;
    }


    private function getGalleryChild($variation_attributes){
        $parentGallery = ioGetImagesGalleryProduct($this->product);
        $result = $parentGallery;
        if(get_field('alt_gallery_is_actived')){
            $galleryList = get_field('alt_gallery');
            foreach( $galleryList as $gallery){
                $convertedKey= "attribute_pa_".$gallery["key_variation"];
                $valueVariationChild = $variation_attributes[$convertedKey];
                if($gallery["value_variation"] ===  $valueVariationChild){
                    return $gallery["gallery"];
                }
            }
        }
        return $result;
    }

    private function getAvailablesTermesByVariationKey($variation_name, $childrens)
    {
        $listStockStatus = array();
        $listAvailableTermes = array();
        $listTermesInStock = array();
        foreach ($childrens as $child) {
            $terme = $child['variation_name'][$variation_name];
            $inStock = $child['stock_status'];
            $termeStockState = array(
                $terme => $this->inStockConverterToBoolean($inStock),
            );

            if (!array_key_exists($terme, $listStockStatus)) {
                $listStockStatus[$terme] = $this->inStockConverterToBoolean($inStock);
            } else {
                if ($this->inStockConverterToBoolean($inStock)) {
                    $listStockStatus[$terme] = true;
                }
            }
            if (!in_array($terme, $listAvailableTermes)) {
                array_push($listAvailableTermes, $terme);
            }
        };
        foreach ($listAvailableTermes as $terme) {
            if ($listStockStatus[$terme]) {
                array_push($listTermesInStock, $terme);
            }
        }
        return array(
            'termes_stock_status' => $listStockStatus,
            'termes_names' => $listAvailableTermes,
            'termes_in_stock' => $listTermesInStock,
        );
    }


    private function getListVarariationKey($childrens)
    {
        if ($childrens[0]['variation_name']) {
            $keys = array_keys($childrens[0]['variation_name']);
            return $keys;
        }
        return null;
    }

    private function inStockConverterToBoolean($value)
    {
        if ($value === 'instock') {
            return true;
        } else {
            return false;
        }
    }

    private function getNameVariationByVariationKey($variation_key){
        $variation_name_raw = array_pop(explode("_",$variation_key));
        $variation_name = str_replace("-", " ",$variation_name_raw);
        return $variation_name;
    }

    public function getListVariationAvailble(){
        $childrens = $this->reformater_children_list;
        $listVariationkey = $this->getListVarariationKey($childrens);
        $listVariationAvailable = array();
        foreach($listVariationkey as $variationKey ){
            $termes = $this->getAvailablesTermesByVariationKey($variationKey, $childrens);
            $variationName = $this->getNameVariationByVariationKey($variationKey);
            
            array_push($listVariationAvailable, array(
                'variation_name' => $variationName,
                'variation_key' => $variationKey,
                'termes' => $termes,
    
            ));
        };
        return $listVariationAvailable;
    }

    public function haveVariations (){
        $listVariation = $this->getListVariationAvailble();
        if( count($listVariation) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function productIsInStock(){
        $inStockStatusProductParent=$this->product->get_stock_status();
        $listVariation = $this->getListVariationAvailble();
        $priceProductParent=$this->product->get_price();
        $childrens = $this->reformater_children_list;
        
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


    public function getGalleryImage(){
        $images_id = $this->product->get_gallery_attachment_ids();
        $images = array();
    
        foreach ($images_id as $attachment_id) {
            $image_link = wp_get_attachment_url($attachment_id);
            array_push($images, array(
                'attachement_id' => $attachment_id,
                'url' => $image_link,
                'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true) 
            ));
        }
    
        return $images;
    }

    public function getChildrensMatrix() {

  
       
        return null;
    }


}
