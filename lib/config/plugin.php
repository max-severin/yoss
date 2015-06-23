<?php

/*
 * @author Max Severin <makc.severin@gmail.com>
 */
return array(
    'name' => /*_wp*/('Smart search'),
    'description' => /*_wp*/('Product ajax-search'),
    'img' => 'img/yoss.png',
    'vendor' => 1020720,
    'version' => '1.0',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        'frontend_head' => 'frontendHead',
    ),
);