<?php
/*
 * @author Max Severin <makc.severin@gmail.com>
 */
class shopYossPluginFrontendSmartsearchController extends waJsonController
{
    public function execute()
    {

        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(array('shop', 'yoss'));

        if ( $settings['status'] ){

            $query = waRequest::post('query', '', waRequest::TYPE_STRING_TRIM);
            $result = array();
            $result['brands'] = array();
            $result['categories'] = array();
            $result['products'] = array();

            $collection = new shopProductsCollection('search/query=' . $query);

            $product_limit = $settings['productLimit'];
            if (!$product_limit) {
                $product_limit = $this->getConfig()->getOption('products_per_page');
            }

            $products = $collection->getProducts('*', 0, $product_limit);
            
            if ($products) {                
                $brands = array();
                $categories = array();
                $brand_limit = $settings['brandLimit'];
                $category_limit = $settings['categoryLimit'];
                $feature_model = new shopFeatureModel();
                $result['products_count'] = sizeof($products);
                $result['searh_all_url'] = (wa()->getRouteUrl('/frontend/search/query=')) . '?query='.$query;
                foreach ($products as $p) {
                    $brand_feature = $feature_model->getByCode('brand');
                    $brand = '';
                    if ($brand_feature) {
                        $feature_value_model = $feature_model->getValuesModel($brand_feature['type']);
                        $product_brands = $feature_value_model->getProductValues($p['id'], $brand_feature['id']);                        
                        foreach ($product_brands as $k => $v) {
                            $brands[$k] = $v;
                            if ($brand == '') {
                                $brand = $v;
                            } else {
                                $brand .= ', '.$v;
                            }
                        }   
                    }                 
                    $categories[] = $p['category_id'];
                    $result['products'][] = array(
                        "name" => $p['name'],
                        "url" => $p['frontend_url'],
                        "image" => ($p['image_id'] ? "<img src='" . shopImage::getUrl(array("product_id" => $p['id'], "id" => $p['image_id'], "ext" => $p['ext']), "48x48") . "' />" : ""),
                        "brand" => $brand,
                    );
                }
                if ($brands) {      
                    $brands = array_unique($brands); 
                    foreach ($brands as $key => $value) {
                        $result['brands'][] = array(
                            "name" => $value,
                            "url" => wa()->getRouteUrl('shop/frontend/brand', array('brand' => str_replace('%2F', '/', urlencode($value)))),
                        );
                    }
                }
                if ($categories) {
                    $categories = array_unique($categories);
                    $category_model = new shopCategoryModel();            
                    foreach ($categories as $key => $value) {
                        $category = $category_model->getById($value);
                        if ($category) {
                            $result['categories'][] = array(
                                "name" => $category['name'],
                                "url" => wa()->getRouteUrl('/frontend/category', array('category_url' => $category['full_url'])),
                            );
                        }
                    }
                }
            }

            $this->response = $result;

        } else {

            $this->response = false;

        }

    }   
}