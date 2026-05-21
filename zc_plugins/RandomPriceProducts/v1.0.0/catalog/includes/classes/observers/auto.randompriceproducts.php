<?php

/**
 * Observer class used to indicate the plugin is active
 */
class zcObserverRandompriceproducts extends base
{
    public function __construct()
    {
        // Declare the global variable and set it to true
        global $randompriceproducts_plugin_presence_indicator;
        $randompriceproducts_plugin_presence_indicator = true;
        $this->attach(
            $this,
            [
                'NOTIFY_HTML_HEAD_END',
            ]);
    }
    protected function updateNotifyHtmlHeadEnd(&$class, string $e): void
    {
        global $current_page_base, $randomProductProducts;
        if ( $current_page_base === 'index' &&
            empty($_GET['cPath']) &&
            empty($_GET['products_id']) &&
            empty($_GET['manufacturers_id']) &&
            empty($_GET['keyword'])) {
            // Home page so add random products structured data
            $list_pos = 1;
            foreach ($randomProductProducts as $randomProduct) {
                $listing_schema[] = [
                    '@type' => 'ListItem',
                    'position' => $list_pos,
                    'url' => htmlspecialchars_decode(zen_href_link(zen_get_info_page($randomProduct['products_id']), 'products_id=' . $randomProduct['products_id'])),
                    'name' => $this->sdata_prepare_string($randomProduct['products_name']),
                    'image' => (!empty( $randomProduct['products_image'])) ? HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES .  $randomProduct['products_image'] : '',
                ];
                $list_pos++;
            }
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'name' => 'Random Products',
                'itemListElement' => []
            ];

            foreach ($listing_schema as $element) {
                // Each $element is already a valid ListItem array
                $schema['itemListElement'][] = $element;
            }
            // Remove empty items from schema
            $schema = $this->rproduct_clean_schema($schema);

?>
<script title="Structured Data: Random Products List" type="application/ld+json">
<?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL; ?>
</script>
<?php
        }
    }
    protected function rproduct_clean_schema($value):mixed
    {
        if (is_array($value)) {
            $value = array_map([$this, 'rproduct_clean_schema'], $value);
            $value = array_filter($value, fn($v) => $v !== '' && $v !== [] && $v !== null);
        }
        return $value;
    }
    protected function  sdata_prepare_string($string): string
    {
        $string = html_entity_decode(trim($string), ENT_COMPAT, CHARSET);//convert HTML entities to characters
        $string = str_replace('</p>', '</p> ', $string); // add a space to separate text when tags are removed
        $string = str_replace('<br>', '<br> ', $string); // add a space to separate text when tags are removed
        $string = strip_tags($string);//remove html tags
        $string = str_replace(["\r\n", "\n", "\r"], '', $string); // remove LF, CR
        return preg_replace('/\s+/', ' ', $string); // remove multiple spaces
    }
}

