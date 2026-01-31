<?php
if (!defined('IS_ADMIN_FLAG')) die('Illegal Access');

$randomProductLimit = (int)zen_get_configuration_key_value('RANDOM_PRICE_PRODUCTS_LIMIT');
$randomProductRanges = explode(',', zen_get_configuration_key_value('RANDOM_PRICE_PRODUCTS_RANGES'));

// Handle price range selection
$randomProductSelectedRange = $_GET['price_range'] 
    ?? (defined('RANDOM_PRICE_PRODUCTS_DEFAULT_RANGE') 
        ? RANDOM_PRICE_PRODUCTS_DEFAULT_RANGE 
        : $randomProductRanges[0]);

list($randomProductMinPrice, $randomProductMaxPrice) = explode('-', $randomProductSelectedRange);

$randomProductsQuery = "
  SELECT p.products_id, p.products_image, pd.products_name, p.products_price
  FROM " . TABLE_PRODUCTS . " p
  JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON p.products_id = pd.products_id
  WHERE p.products_status = 1
    AND p.products_quantity > 0
    AND pd.language_id = " . (int)$_SESSION['languages_id'] . "
    AND p.products_price >= " . (float)$randomProductMinPrice . "
    AND p.products_price <= " . (float)$randomProductMaxPrice . "
  ORDER BY RAND()
  LIMIT " . (int)$randomProductLimit;

$randomProductProducts = $db->Execute($randomProductsQuery);

