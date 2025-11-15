<?php
/**
 * AJAX handler fragment for Random Price Products plugin.
 * Should be included by ajaxHandler.php when act=filterRandomProducts
 */

if (!defined('IS_ADMIN_FLAG')) die('Illegal Access');

if (isset($_GET['act']) && $_GET['act'] === 'filterRandomProducts') {
  require_once(DIR_WS_MODULES . 'random_price_products_main.php');

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
}
?>