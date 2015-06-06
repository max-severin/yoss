<?php

/*
 * Class shopYossPlugin
 * @author Max Severin <makc.severin@gmail.com>
 */

class shopYossPlugin extends shopPlugin {

    /** Handler for frontend_head event: return plugin content in frontend. */
    public function frontendHead() {   
        $settings = $this->getSettings();

        foreach ($settings as $id => $setting) {
            $settings[$id] = addslashes(htmlspecialchars($setting));

            $settings['result_max_height'] = (int)$settings['result_max_height'] . 'px';
            
            if ($settings['result_width'] != 'auto') { 
                $settings['result_width'] = (int)$settings['result_width'] . 'px';
            }
        }

        $view = wa()->getView();
        $view->assign('yoss_settings', $settings);
        $view->assign('search_url', wa()->getRouteUrl('shop/frontend/smartsearch'));
        $html = $view->fetch($this->path.'/templates/Frontend.html');

        return $html;

    }

}