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
            $result['products'] = array();

            $collection = new shopProductsCollection('search/query=' . $query);

            $product_limit = $settings['productLimit'];
            if (!$product_limit) {
                $product_limit = $this->getConfig()->getOption('products_per_page');
            }
            $brand_limit = $settings['brandLimit'];
            $category_limit = $settings['categoryLimit'];

            $products = $collection->getProducts('*', 0, $product_limit);
            if ($products) {
                foreach ($products as $p) {
                    $result['products'][] = array(
                        "name" => $p['name'],
                        "url" => $p['frontend_url'],
                        "image" => ($p['image_id'] ? "<img src='" . shopImage::getUrl(array("product_id" => $p['id'], "id" => $p['image_id'], "ext" => $p['ext']), "48x48") . "' />" : ""),
                        "price" => ($p['compare_price'] ? "<span class='compare-at-price'>".shop_currency($p['compare_price'], $p['currency'])."</span>" : "")."<span class='price'>".shop_currency($p['price'], $p['currency'])."</span>",
                    );
                }
            }

            $this->response = $result;

        } else {

            $this->response = false;

        }

    }   
}