<?php

/*
 * @author Max Severin <makc.severin@gmail.com>
 */

class shopYossPluginSettingsAction extends waViewAction
{

    public function execute()
    {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(array('shop', 'yoss'));
        $this->view->assign('settings', $settings);
    }

}