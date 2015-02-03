<?php

/*
 * @author Max Severin <makc.severin@gmail.com>
 */
return array(
    'name' => 'Умный поиск',
    'description' => 'Ajax-поиск товаров, брендов и категорий по запросу пользователя',
    'img' => 'img/yoss.png',
    'version' => '1.0',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        'frontend_head' => 'frontendHead',
    ),
);