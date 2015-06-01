<?php

/*
 * Class shopYossPluginSettingsAction
 * @author Max Severin <makc.severin@gmail.com>
 */

class shopYossPluginSettingsAction extends shopPluginsSettingsAction {

    public function execute() {
    	$_GET['id'] = 'yoss';

        parent::execute();
    }

}