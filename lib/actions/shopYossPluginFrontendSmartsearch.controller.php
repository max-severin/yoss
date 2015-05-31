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
                    if ( class_exists("shopBrandlogosPluginBrandlogosModel") ) {
                        $brand_logos_model = new shopBrandlogosPluginBrandlogosModel();
                    }                    
                    $brand_feature = $feature_model->getByCode('brand');
                    $brand = '';
                    if ($brand_feature) {
                        $feature_value_model = $feature_model->getValuesModel($brand_feature['type']);
                        $product_brands = $feature_value_model->getProductValues($p['id'], $brand_feature['id']);

                        $brands = array();

                        foreach ($product_brands as $k => $v) {
                            $brand_id = $feature_value_model->getValueId($brand_feature['id'], $v);

                            $brands_logo_info = $brand_logos_model->getByField('brand_value_id', $brand_id);

                            if ($brands_logo_info['logo']) {
                                $brands[] = array(
                                    'id' => $brand_id,
                                    'brand' => '<a href="' . wa()->getRouteUrl('shop/frontend/brand', array('brand' => str_replace('%2F', '/', urlencode($v)))) . '"><img src="/wa-data/public/shop/brandlogos/'.$brands_logo_info['logo'].'" title="'.$v.'" alt="'.$v.'"/></a>',
                                );
                            } else {
                                $brands[$brand_id] = '<a href="' . wa()->getRouteUrl('shop/frontend/brand', array('brand' => str_replace('%2F', '/', urlencode($v)))) . '">' . $v . '</a>';
                            }
                        }   
                    }   
                    $category_model = new shopCategoryModel();
                    $category = $category_model->getById($p['category_id']);
                    $res_category = '';
                    if ($category) {
                        $res_category = '<a href="' . wa()->getRouteUrl('/frontend/category', array('category_url' => $category['full_url'])) . '">' .$category['name'] . '</a>';
                    }              

                    $result['products'][] = array(
                        "name" => $p['name'],
                        "url" => $p['frontend_url'],
                        "image" => ($p['image_id'] ? "<img src='" . shopImage::getUrl(array("product_id" => $p['id'], "id" => $p['image_id'], "ext" => $p['ext']), "48x48") . "' />" : ""),
                        "price" => shop_currency_html($p['price'], true),
                        "brands" => $brands,
                        "category" => $res_category,
                    );
                }

                // Get full data about all product's brands and categories
                $product_model = new shopProductModel();
                $product_count = $collection->count();

                $result['product_count'] = $product_count;

                if ( $product_count > (($page-1)*$product_limit + $product_limit) ) {
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