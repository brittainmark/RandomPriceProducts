<?php

declare(strict_types=1);

/**
 * @author:  brittainmark
 * @link: https://github.com/brittainmark/RandomPriceProducts.git
 * @license https://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version markbrittain 30 Jan 2026
 */
use Zencart\PluginSupport\ScriptedInstaller as ScriptedInstallBase;

class ScriptedInstaller extends ScriptedInstallBase {

    protected string $configPageKey = 'configRandomPriceProducts';
    protected string $configGroupTitle = 'Random Price Products';
    protected int $cgi;
    public string $pluginKey = 'RandomPriceProducts';
    public string $version = '1.0.0';

    /**
     * @return bool
     */
    protected function executeInstall(): bool {

        // ---------------------------------------------
        // Create configuration group if not exists
        // ---------------------------------------------
        $this->cgi = $this->getOrCreateConfigGroupId($this->configGroupTitle, $this->configGroupTitle, null);

        // ---------------------------------------------
        // Add configuration keys
        // ---------------------------------------------
        $configs = [
            ['Section Title', 'RANDOM_PRICE_PRODUCTS_TITLE', 'Random Products', 'Title displayed above the product section.', 1],
            ['Number of Products', 'RANDOM_PRICE_PRODUCTS_LIMIT', '9', 'Number of random products to display.', 2],
            ['Price Ranges', 'RANDOM_PRICE_PRODUCTS_RANGES', '20-50,50-100,100-150,150-9999', 'Comma-separated ranges (min-max) for filtering.', 3]
        ];

        foreach ($configs as $cfg) {
            list($title, $key, $value, $desc, $sort) = $cfg;

            $this->executeInstallerSql("INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
                     (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added)
                     VALUES (
                        '" . zen_db_input($title) . "',
                        '" . zen_db_input($key) . "',
                        '" . zen_db_input($value) . "',
                        '" . zen_db_input($desc) . "',
                        " . (int) $this->cgi . ",
                        " . (int) $sort . ",
                        NOW()
                     )"
                );
        }

        // ---------------------------------------------
        // Register layout box
        // ---------------------------------------------

        if (zen_page_key_exists($this->configPageKey)) {
            zen_deregister_admin_pages([$this->configPageKey]);
        }
        if (!zen_page_key_exists($this->configPageKey)) {
            // -----
            // Register the plugin's configuration page for the admin menus.
            //
            zen_register_admin_page($this->configPageKey, 'BOX_CONFIGURATION_RANDOM_PRICE_PRODUCTS', 'FILENAME_CONFIGURATION', "gID=$this->cgi", 'configuration', 'Y');
        }

        return true;
    }

    /**
     * @param $oldVersion
     * @return bool
     */
    protected function executeUpgrade($oldVersion): bool {
        return true;
    }
    protected function executeUninstall(): bool {
        zen_deregister_admin_pages($this->configPageKey);
        $this->deleteConfigurationGroup($this->configGroupTitle, true);
        return true;
    }
}
