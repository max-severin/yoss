<?php

/*
 * @author Max Severin <makc.severin@gmail.com>
 */

return array(
    'name' => 'Умный поиск',
    'description' => 'Ajax-поиск товаров, их брендов и категорий',
    'img' => 'img/yoss.png',
    'version' => '1.0',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        'frontend_head' => 'frontendHead',
    ),
);