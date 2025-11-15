<?php
/**
 * Installer for Random Price Products (Zen Cart 2.0.1)
 */

if (!defined('IS_ADMIN_FLAG')) {
    return;
}

if (!isset($_SESSION['admin_id'])) {
    return; // only run when logged into admin
}

global $db;
$version = '1.0.0';

/**
 * 1. Create configuration group
 */
$module_constant = 'RANDOM_PRICE_PRODUCTS'; // This should be a UNIQUE name followed by _VERSION for convention
$module_installer_directory = DIR_FS_ADMIN . 'includes/installers/min_max_order'; // This is the directory your installer is in, usually this is lower case
$module_name = 'Random Price Products'; // This should be a plain English or Other in a user friendly way
$admin_page = '';
$module_file_for_version_check = ''; // File to check for new version so it doesn't check on every page
$zencart_com_plugin_id = 0; // from zencart.com plugins - Leave Zero not to check
// Just change the stuff above... Nothing down here should need to change

$configuration_group_id = '';
if (defined($module_constant . '_VERSION')) {
    $current_version = constant($module_constant . '_VERSION');
} else {
    $current_version = '0.0.0';
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " (configuration_group_title, configuration_group_description, sort_order, visible) VALUES ('" . $module_name . "', 'Set " . $module_name . " Options', '1', '1')");
    $configuration_group_id = $db->Insert_ID();

    $db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET sort_order = " . $configuration_group_id . " WHERE configuration_group_id = " . $configuration_group_id);

    $db->Execute("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES
                ('Version', '" . $module_constant . "_VERSION', '0.0.0', 'Version installed:', " . $configuration_group_id . ", 0, NOW(), NOW(), NULL, \"zen_cfg_select_option(array('0.0.0'),\")");
}
if ($configuration_group_id == '') {
    $config = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION . " WHERE configuration_key= '" . $module_constant . "_VERSION'");
    $configuration_group_id = $config->fields ['configuration_group_id'];
}

/**
 * Initial install
 */
if ($current_version === '0.0.0') {
    $configs = [
        ['Version', 'RANDOM_PRICE_PRODUCTS_VERSION', $version , 'Random Price Products Version', 0],
        ['Section Title', 'RANDOM_PRICE_PRODUCTS_TITLE', 'Random Products', 'Displayed heading', 1],
        ['Number of Products', 'RANDOM_PRICE_PRODUCTS_LIMIT', '9', 'How many random products to display', 2],
        ['Price Ranges', 'RANDOM_PRICE_PRODUCTS_RANGES', '0-20,20-50,50-100,100-200,200-9999', 'Comma-separated min-max price ranges', 3],
        ['Default Price Range', 'RANDOM_PRICE_PRODUCTS_DEFAULT_RANGE', '50-100', 'Select which price range is chosen by default.', 4],
    ];

    foreach ($configs as $cfg) {
        list($title, $key, $value, $desc, $sort) = $cfg;

        $exists = $db->Execute("
            SELECT configuration_key 
            FROM " . TABLE_CONFIGURATION . "
            WHERE configuration_key = '" . zen_db_input($key) . "'
            LIMIT 1
        ");

        if ($exists->EOF) {
            $db->Execute("
                INSERT INTO " . TABLE_CONFIGURATION . "
                (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added)
                VALUES (
                    '" . zen_db_input($title) . "',
                    '" . zen_db_input($key) . "',
                    '" . zen_db_input($value) . "',
                    '" . zen_db_input($desc) . "',
                    " . (int)$configuration_group_id . ",
                    " . (int)$sort . ",
                    NOW()
                )
            ");
        }
    }

    /**
     * 3. Add layout box to Layout Boxes Controller
     */
    $boxFile = 'random_price_products.php';

    $check = $db->Execute("
        SELECT layout_box_name 
        FROM " . TABLE_LAYOUT_BOXES . "
        WHERE layout_box_name = '" . zen_db_input($boxFile) . "'
        LIMIT 1
    ");

    if ($check->EOF) {

        // Insert as CENTERBOX (appears in centerboxes)
        $db->Execute("
            INSERT INTO " . TABLE_LAYOUT_BOXES . "
            (layout_box_name, layout_box_location, layout_box_status, layout_box_sort_order)
            VALUES (
                '" . zen_db_input($boxFile) . "',
                'center',
                1,
                60
            )
        ");
    }

/**
 * 4. register the admin page
 */
    if (function_exists('zen_page_key_exists')) {
      if (!zen_page_key_exists('configRandomPriceProducts')) {
        zen_register_admin_page(
          'configRandomPriceProducts',                     // page key
          'BOX_CONFIGURATION_RANDOM_PRICE_PRODUCTS',       // language define
          'FILENAME_CONFIGURATION',                        // filename
          'gID='. $configuration_group_id,                                // parameters (replace 100 with your actual group ID)
          'configuration',                                 // menu category
          'Y'                                              // menu visibility
        );
      }
    }
}
if ( $current_version != $version){
    // -----
    // Now, update the current configuration version for the plugin.
    //
    $db->Execute("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . $version . "' WHERE configuration_key = 'RANDOM_PRICE_PRODUCTS_VERSION' LIMIT 1");
    $messageStack->add('Installed ' . $module_name . ' v' . $version, 'success');
}
