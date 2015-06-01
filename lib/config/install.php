<?php

/*
 * @author Max Severin <makc.severin@gmail.com>
 */

$plugin_id = array('shop', 'yoss');
$app_settings_model = new waAppSettingsModel();
$app_settings_model->set($plugin_id, 'status', 1);
$app_settings_model->set($plugin_id, 'product_limit', 10);
$app_settings_model->set($plugin_id, 'min_char_count', 2);
$app_settings_model->set($plugin_id, 'id_in_html', "#search");