# Random Products by Price Range (Encapsulated Plugin)

Place the `RandomPriceProducts` folder in `/zc_plugins/` and install via Admin → Plugins → Plugin Manager.
Then place this code where you want it to appear.
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
