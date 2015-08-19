# yoss

## Description
Smart search plugin for Shop-Script 6

## Features
Product search with ajax-loading of the results.

Dynamically displays a list of products, their categories and brands.

You can enable lazy-loading scrolling of products in the search results. Or you can customize the number of displayed products in the settings.

## Installing
### Auto
Install plugin from webasyst store: [SmartSearch-en](https://www.webasyst.com/store/plugin/shop/yoss/) or [SmartSearch-ru](https://www.webasyst.ru/store/plugin/shop/yoss/).  
Or you can install plugin from Installer app in backend.

### Manual
1. Get the code into your web server's folder /PATH_TO_WEBASYST/wa-apps/shop/plugins

2. Add the following line into the /PATH_TO_WEBASYST/wa-config/apps/shop/plugins.php file (this file lists all installed shop plugins):

		'yoss' => true,

3. Done. Configure the plugin in the plugins settings tab of shop backend.

## Specificity
For the correct operation of the plugin in the current design theme needs to be connected **frontend_head** hook