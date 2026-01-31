<?php
/**
 * AJAX handler fragment for Random Price Products plugin.
 * Should be included by ajaxHandler.php when act=filterRandomProducts
 */

if (!defined('IS_ADMIN_FLAG')) die('Illegal Access');

if (isset($_GET['rnd_action']) && $_GET['rnd_action'] === 'random_price_products') {
    $randomPricePath = $installedPlugins['RandomPriceProducts']->getAbsolutePath();
    require_once $randomPricePath . 'catalog/' . DIR_WS_MODULES . 'random_price_products.php';
    // get template file
    $randomPricePath = $pageLoader->getTemplateDirectory('tpl_random_price_products.php', $template_dir, $current_page, 'templates');
    require_once $randomPricePath . 'tpl_random_price_products.php';
//-- important exit stops zen cart trying to render a complete page. --//
    exit;
}

  