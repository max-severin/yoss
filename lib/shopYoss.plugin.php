<?php

/*
 * @author Max Severin <makc.severin@gmail.com>
 */

class shopYossPlugin extends shopPlugin
{

    public function frontendHead()
    {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(array('shop', 'yoss'));

        $view = wa()->getView();
        $view->assign('search_url', wa()->getRouteUrl('shop/frontend/smartsearch'));
        $view->assign('yoss_settings', $settings);
        $html = $view->fetch($this->path.'/templates/Frontend.html');

        return $html;
    }

}