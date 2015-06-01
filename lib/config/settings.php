<?php

/*
 * @author Max Severin <makc.severin@gmail.com>
 */
return array(
    'status' => array(
        'title'        => 'Статус плагина',
        'value'        => 'off',
        'control_type' => waHtmlControl::SELECT,
        'options'      => array(
            'off' => 'Выключен',
            'on'  => 'Включен',
        ),
    ),
    'product_limit' => array(
        'title'        => 'Количество товаров',
        'description'  => 'Количество товаров в результатах поиска',
        'value'        => '10',
        'placeholder'  => '10',
        'control_type' => waHtmlControl::INPUT,
    ),
    'min_char_count' => array(
        'title'        => 'Количество символов',
        'description'  => 'Количество символов с поисковом запросе, после заполнения которого включается поиск',
        'value'        => '2',
        'placeholder'  => '2',
        'control_type' => waHtmlControl::INPUT,
    ),
    'id_in_html' => array(
        'title'        => 'Селектор поиска',
        'description'  => 'Укажите идентификатор или класс html-элемента,<br />при заполнении которого будет открываться окно с резуьтатами поиска.<br />Должно быть вида «<b>#search</b>» или «<b>.search</b>».',
        'placeholder'  => '#search',
        'value'        => '#search',
        'control_type' => waHtmlControl::INPUT,
    ),
);