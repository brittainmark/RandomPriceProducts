# Random Products by Price Range (Encapsulated Plugin)

Copy content fo zc_plugins to zc_plugins folder and install via Admin → Plugins → Plugin Manager.
Modify the page that you require the random priced products to appear

Add code
```
    if (!empty($randompriceproducts_plugin_presence_indicator) || true) {
        $randomPage = $pageLoader->getTemplatePluginDir('tpl_random_price_products.php', 'templates', 'RandomPriceProducts') . '/tpl_random_price_products.php';
        if ($randomPage) {
            require_once $randomPage;
        }
    }
```

## Features
- Encapsulated in `/zc_plugins/RandomPriceProducts`
- Layout box controlled via Tools → Layout Boxes Controller
- Configurable title, product count, and price ranges
- AJAX filtering, Bootstrap 4 compatible
