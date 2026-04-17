<?php
/**
 * Init script for Random Price Products (zc_plugins)
 *
 * Loads the module and AJAX handler when appropriate.
 */

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

// Only run on storefront (not admin)
if (IS_ADMIN_FLAG === false) {

    // Load main module
    

    switch ($current_page_base) {
        case 'ajaxHandler':
            $randomPricePages = $pageLoader->listModulePagesFiles('header_php_random_price_products', '.php', 'catalog');
            foreach ($randomPricePages as $file) {
                require_once $file;
            }
            break;
        case 'index':
            if (empty($cpath)) {
                $randomPricePath = $installedPlugins['RandomPriceProducts']->getAbsolutePath();
                require_once $randomPricePath . 'catalog/' . DIR_WS_MODULES . 'random_price_products.php';
/*
 * or move
                $randomPricePages = $pageLoader->listModulePagesFiles('random_price_products', '.php', 'catalog');
                foreach ($randomPricePages as $file) {
                    require_once $file;
                }
 */
            }
            break;
        default :
    }
}