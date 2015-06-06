<?php

/*
 * @author Max Severin <makc.severin@gmail.com>
 */
return array(
    'status' => array(
        'title'        => _wp('Status'),
        'value'        => 'off',
        'control_type' => waHtmlControl::SELECT,
        'options'      => array(
            'off' => _wp('Off'),
            'on'  => _wp('On'),
        ),
    ),
    'id_in_html' => array(
        'title'        => _wp('Search input selector'),
        'description'  => _wp('Specify the ID or class of the html element,<br />when filling which will open a search results.<br />Must be like «<b>#search</b>» or «<b>.search</b>».'),
        'placeholder'  => '#search',
        'value'        => '#search',
        'control_type' => waHtmlControl::INPUT,
    ),
    'product_limit' => array(
        'title'        => _wp('Products limit'),
        'description'  => _wp('Number of items in the search results.'),
        'value'        => '10',
        'placeholder'  => '10',
        'control_type' => waHtmlControl::INPUT,
    ),
    'min_char_count' => array(
        'title'        => _wp('Characters limit'),
        'description'  => _wp('Number of characters in the search query, after filling that triggered search.'),
        'value'        => '2',
        'placeholder'  => '2',
        'control_type' => waHtmlControl::INPUT,
    ),
    'lazy_loading' => array(
        'title'        => _wp('Lazy loading'),
        'description'  => _wp('To enable lazy loading of items in search results.'),
        'value'        => 'off',
        'control_type' => waHtmlControl::SELECT,
        'options'      => array(
            'off' => _wp('Off'),
            'on'  => _wp('On'),
        ),
    ),
    'result_max_height' => array(
        'title'        => _wp('Max height (px)'),
        'description'  => _wp('Max height of search result block.'),
        'value'        => '400',
        'placeholder'  => '400',
        'control_type' => waHtmlControl::INPUT,
    ),
    'result_width' => array(
        'title'        => _wp('Width (px)'),
        'description'  => _wp('Width of search result block.'),
        'value'        => 'auto',
        'placeholder'  => 'auto',
        'control_type' => waHtmlControl::INPUT,
    ),
);