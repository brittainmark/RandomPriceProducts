<?php
/**
 * Installer / Uninstaller for Random Products by Price Range (zc_plugins)
 *
 * Zen Cart plugin manager will call this script with "install" or "uninstall".
 */

if (!defined('IS_ADMIN_FLAG')) die('Illegal Access');

$action = $argv[0] ?? 'install';

switch ($action) {
  case 'install':
    // Create configuration group if not exists
    $group = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Random Price Products' LIMIT 1");
    if ($group->EOF) {
      $db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " (configuration_group_title, configuration_group_description, sort_order, visible) VALUES ('Random Price Products', 'Settings for Random Price Products plugin', 99, 1)");
      $group_id = $db->Insert_ID();
    } else {
      $group_id = $group->fields['configuration_group_id'];
    }

    // Add configuration entries
    $configs = [
      ['Section Title', 'RANDOM_PRICE_PRODUCTS_TITLE', 'Random Products', 'Title displayed above the product section.', 1],
      ['Number of Products', 'RANDOM_PRICE_PRODUCTS_LIMIT', '9', 'Number of random products to display.', 2],
      ['Price Ranges', 'RANDOM_PRICE_PRODUCTS_RANGES', '20-50,50-100,100-150,150-9999', 'Comma-separated ranges (min-max) for filtering.', 3]
    ];

    foreach ($configs as $cfg) {
      list($title, $key, $value, $desc, $sort) = $cfg;
      $exists = $db->Execute("SELECT configuration_key FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . zen_db_input($key) . "'");
      if ($exists->EOF) {
        $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . zen_db_input($title) . "', '" . zen_db_input($key) . "', '" . zen_db_input($value) . "', '" . zen_db_input($desc) . "', " . (int)$group_id . ", " . (int)$sort . ", NOW())");
      }
    }

    // Register layout box if not present in layout_boxes (so it appears in Layout Boxes Controller)
    $boxFile = 'random_price_products.php';
    $check = $db->Execute("SELECT layout_box_name FROM " . TABLE_LAYOUT_BOXES . " WHERE layout_box_name = '" . zen_db_input($boxFile) . "'");
    if ($check->EOF) {
      // Default location center, enabled
      $db->Execute("INSERT INTO " . TABLE_LAYOUT_BOXES . " (layout_box_name, layout_box_location, layout_box_status, layout_box_sort_order, layout_box_template) VALUES ('" . zen_db_input($boxFile) . "', 'center', 1, 50, '')");
    }
    break;

  case 'uninstall':
    // Remove configuration keys
    $keys = ['RANDOM_PRICE_PRODUCTS_TITLE', 'RANDOM_PRICE_PRODUCTS_LIMIT', 'RANDOM_PRICE_PRODUCTS_RANGES'];
    foreach ($keys as $key) {
      $db->Execute("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . zen_db_input($key) . "'");
    }

    // Remove config group (if exists)
    $group = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Random Price Products'");
    if (!$group->EOF) {
      $group_id = (int)$group->fields['configuration_group_id'];
      $db->Execute("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_group_id = " . $group_id);
      $db->Execute("DELETE FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_id = " . $group_id);
    }

    // Remove layout box registration
    $db->Execute("DELETE FROM " . TABLE_LAYOUT_BOXES . " WHERE layout_box_name = 'random_price_products.php'");
    break;
}