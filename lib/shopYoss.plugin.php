<?php

/*
 * Class shopYossPlugin
 * @author Max Severin <makc.severin@gmail.com>
 */

class shopYossPlugin extends shopPlugin {

    /** Handler for frontend_head event: return plugin content in frontend. */
    public function frontendHead() {

        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(array('shop', 'yoss'));

        $view = wa()->getView();
        $view->assign('search_url', wa()->getRouteUrl('shop/frontend/smartsearch'));
        $view->assign('yoss_settings', $settings);
        $html = $view->fetch($this->path.'/templates/Frontend.html');

        return $html;

    }

}