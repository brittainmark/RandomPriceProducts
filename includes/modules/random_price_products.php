<?php
if (!defined('IS_ADMIN_FLAG')) die('Illegal Access');

$title = zen_get_configuration_key_value('RANDOM_PRICE_PRODUCTS_TITLE');
$limit = (int)zen_get_configuration_key_value('RANDOM_PRICE_PRODUCTS_LIMIT');
$ranges = explode(',', zen_get_configuration_key_value('RANDOM_PRICE_PRODUCTS_RANGES'));

// Handle price range selection
$selected_range = $_GET['price_range'] 
    ?? (defined('RANDOM_PRICE_PRODUCTS_DEFAULT_RANGE') 
        ? RANDOM_PRICE_PRODUCTS_DEFAULT_RANGE 
        : $ranges[0]);

list($min_price, $max_price) = explode('-', $selected_range);

$products_query = "
  SELECT p.products_id, p.products_image, pd.products_name, p.products_price
  FROM " . TABLE_PRODUCTS . " p
  JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON p.products_id = pd.products_id
  WHERE p.products_status = 1
    AND p.products_quantity > 0
    AND pd.language_id = " . (int)$_SESSION['languages_id'] . "
    AND p.products_price >= " . (float)$min_price . "
    AND p.products_price <= " . (float)$max_price . "
  ORDER BY RAND()
  LIMIT " . (int)$limit;

$products = $db->Execute($products_query);
require $template->get_template_dir('tpl_random_price_products.php', DIR_WS_TEMPLATE, $current_page_base, 'templates') . '/tpl_random_price_products.php';
