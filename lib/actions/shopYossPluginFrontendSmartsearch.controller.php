<?php

/*
 * Class shopYossPluginFrontendSmartsearchController
 * @author Max Severin <makc.severin@gmail.com>
 */

class shopYossPluginFrontendSmartsearchController extends waJsonController {
    
    public function execute() {

        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(array('shop', 'yoss'));

        if ( $settings['status'] ) {

            $query = waRequest::post('query', '', waRequest::TYPE_STRING_TRIM);
            $page = waRequest::post('pg', 1, 'int');

            $result = array();
            $result['brands'] = array();
            $result['categories'] = array();
            $result['products'] = array();
            $result['product_count'] = 0;

            $collection = new shopProductsCollection('search/query=' . $query);

            $product_limit = $settings['productLimit'];
            if (!$product_limit) {
                $product_limit = $this->getConfig()->getOption('products_per_page');
            }

            $products = $collection->getProducts('*', ($page-1)*$product_limit, $product_limit);
            
            if ($products) { 

                $brands = array();
                $categories = array();
                $brand_limit = $settings['brandLimit'];
                $category_limit = $settings['categoryLimit'];
                $feature_model = new shopFeatureModel();
                $result['searh_all_url'] = (wa()->getRouteUrl('/frontend/search/query=')) . '?query='.$query;

                foreach ($products as $p) {
                    $brand_feature = $feature_model->getByCode('brand');
                    $brand = '';
                    if ($brand_feature) {
                        $feature_value_model = $feature_model->getValuesModel($brand_feature['type']);
                        $product_brands = $feature_value_model->getProductValues($p['id'], $brand_feature['id']);                        
                        foreach ($product_brands as $k => $v) {
                            $brand_id = $feature_value_model->getValueId($brand_feature['id'], $v);
                            $brands[$brand_id] = $v;
                            if ($brand == '') {
                                $brand = $v;
                            } else {
                                $brand .= ', '.$v;
                            }
                        }   
                    }                 
                    $result['products'][] = array(
                        "name" => $p['name'],
                        "url" => $p['frontend_url'],
                        "image" => ($p['image_id'] ? "<img src='" . shopImage::getUrl(array("product_id" => $p['id'], "id" => $p['image_id'], "ext" => $p['ext']), "48x48") . "' />" : ""),
                        "brand" => $brand,
                    );
                }

                // Get full data about all product's brands and categories
                $product_model = new shopProductModel();
                $all_product_data = $product_model->query("SELECT id, category_id FROM shop_product WHERE name LIKE '%".$query."%' ORDER BY id")->fetchAll();
                foreach ( $all_product_data as $p) {
                    $brand_feature = $feature_model->getByCode('brand');
                    if ($brand_feature) {
                        $feature_value_model = $feature_model->getValuesModel($brand_feature['type']);
                        $product_brands = $feature_value_model->getProductValues($p['id'], $brand_feature['id']);                        
                        foreach ($product_brands as $k => $v) {
                            $brand_id = $feature_value_model->getValueId($brand_feature['id'], $v);
                            $brands[$brand_id] = $v;
                        }   
                    }
                    $categories[] = $p['category_id'];
                }
                if ($brands) {      
                    $brands = array_unique($brands); 
                    if ( class_exists("shopBrandlogosPluginBrandlogosModel") ) {
                        $brand_logos_model = new shopBrandlogosPluginBrandlogosModel();
                    }                  
                    foreach ($brands as $key => $value) {
                        if ($brand_logos_model){
                            $brands_logo_info = $brand_logos_model->getByField('brand_value_id', $key);
                            if ($brands_logo_info['logo']) {
                                $image = "<img src='/wa-data/public/shop/brandlogos/".$brands_logo_info['logo']."' title='".$value."' alt='".$value."' />";
                            } else {
                                $image = "";
                            }
                        } else {
                            $image = "";
                        }
                        $result['brands'][] = array(
                            "name" => $value,
                            "url" => wa()->getRouteUrl('shop/frontend/brand', array('brand' => str_replace('%2F', '/', urlencode($value)))),
                            "image" => $image,
                        );
                    }
                }
                if ( sizeof($result['brands']) > $brand_limit ) {
                    $result['brands'] = array_slice($result['brands'], 0, $brand_limit);
                }

                if ($categories) {
                    $categories = array_unique($categories); 
                    $category_model = new shopCategoryModel();            
                    foreach ($categories as $cat) {
                        $category = $category_model->getById($cat);
                        if ($category) {
                            $result['categories'][] = array(
                                "name" => $category['name'],
                                "url" => wa()->getRouteUrl('/frontend/category', array('category_url' => $category['full_url'])),
                            );
                        }
                    }
                }
                if ( sizeof($result['categories']) > $category_limit ) {
                    $result['categories'] = array_slice($result['categories'], 0, $category_limit);
                }

                $result['product_count'] = sizeof($all_product_data);

                if ( sizeof($all_product_data) > (($page-1)*$product_limit + $product_limit) ) {
                    $result['next_page'] = $page+1;
                } else {
                    $result['next_page'] = false;
                }

            }

            $this->response = $result;

        } else {

            $this->response = false;

        }

    }

}