<?php
/**
 * AJAX endpoint for Random Price Products module.
 * Call with: includes/modules/random_price_products_ajax.php?price_filter=20-50
 * Outputs only the inner product grid HTML.
 */

// petite bootstrap to access zen cart environment
require_once('../../includes/application_top.php');

// Sanitize input
$price_filter = isset($_GET['price_filter']) ? preg_replace('/[^0-9\-]/', '', $_GET['price_filter']) : null;
if (!$price_filter) {
  exit;
}

// Load config
$limit = (int)zen_get_configuration_key_value('RANDOM_PRICE_PRODUCTS_LIMIT');
$ranges_raw = zen_get_configuration_key_value('RANDOM_PRICE_PRODUCTS_RANGES');
$ranges = array_filter(array_map('trim', explode(',', $ranges_raw)));

list($min_price, $max_price) = explode('-', $price_filter);

$price_sql = "AND p.products_price >= " . (float)$min_price;
if ((int)$max_price < 9999) $price_sql .= " AND p.products_price <= " . (float)$max_price;

$products_query = "SELECT p.products_id, pd.products_name, p.products_image, p.products_price
  FROM " . TABLE_PRODUCTS . " p
  JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON p.products_id = pd.products_id
  WHERE p.products_status = 1
  AND p.products_quantity > 0
  AND pd.language_id = " . (int)$_SESSION['languages_id'] . "
  " . $price_sql . "
  ORDER BY RAND()
  LIMIT " . (int)$limit;

$products = $db->Execute($products_query);

if ($products->RecordCount() > 0) {
  while (!$products->EOF) {
    echo '<div class="col-6 col-sm-4 col-md-3 mb-4">' .
           '<div class="card h-100 shadow-sm">' .
             '<a href="' . zen_href_link(zen_get_info_page($products->fields['products_id']), 'products_id=' . $products->fields['products_id']) . '" class="text-decoration-none">' .
               '<img src="' . DIR_WS_IMAGES . $products->fields['products_image'] . '" class="card-img-top img-fluid" alt="' . htmlspecialchars($products->fields['products_name']) . '">' .
               '<div class="card-body text-center">' .
                 '<h6 class="card-title text-truncate">' . htmlspecialchars($products->fields['products_name']) . '</h6>' .
                 '<p class="card-text font-weight-bold">' . zen_get_products_display_price($products->fields['products_id']) . '</p>' .
               '</div>' .
             '</a>' .
           '</div>' .
         '</div>';
    $products->MoveNext();
  }
} else {
  echo '<div class="col-12 text-center"><p>No products found in this price range.</p></div>';
}
exit;
