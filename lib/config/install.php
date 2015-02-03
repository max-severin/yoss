<?php

/*
 * @author Max Severin <makc.severin@gmail.com>
 */
$plugin_id = array('shop', 'yoss');
$app_settings_model = new waAppSettingsModel();
$app_settings_model->set($plugin_id, 'status', 1);
$app_settings_model->set($plugin_id, 'productLimit', 10);
$app_settings_model->set($plugin_id, 'brandLimit', 10);
$app_settings_model->set($plugin_id, 'categoryLimit', 10);
$app_settings_model->set($plugin_id, 'minCharCount', 1);
$app_settings_model->set($plugin_id, 'idHtml', "#search");