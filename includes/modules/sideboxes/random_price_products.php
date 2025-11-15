<?php
/**
 * Sidebox wrapper for Random Price Products plugin.
 * This file is registered as a layout box entry and will render the plugin.
 */

if (!defined('IS_ADMIN_FLAG')) die('Illegal Access');

// Include main module logic which sets up $products, $range_list, $title, $price_filter
require_once(DIR_WS_MODULES . 'random_price_products_main.php');

// Capture template output
ob_start();
require($template->get_template_dir('tpl_random_price_products.php', DIR_WS_TEMPLATE, $current_page_base, 'templates') . '/tpl_random_price_products.php');
$content = ob_get_clean();

// Zen Cart layout boxes expect $bottom_string or $content variables; set $bottom_string
$bottom_string = $content;
?>