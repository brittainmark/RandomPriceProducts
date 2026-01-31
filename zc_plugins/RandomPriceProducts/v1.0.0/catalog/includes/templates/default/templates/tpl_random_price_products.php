<?php
/** Template file for displaying random products by price*/
?>
<div class="centerBoxWrapper text-center">
    <h2 class="h4 mb-3"><?= zen_output_string_protected(zen_get_configuration_key_value('RANDOM_PRICE_PRODUCTS_TITLE')) ?></h2>

    <div class="mb-3">
        <form id="priceRangeForm">
            <label for="price_range" class="form-label">Select Price Range:</label>
            <select name="price_range" class="custom-select w-auto" onchange="updateRandomProducts(this.value)">
<?php
foreach ($randomProductRanges as $randomProductRange) {
    list($randomProductstart, $randomProductEnd) = explode('-', $randomProductRange);
?>
                <option value="<?= $randomProductRange; ?>" <?= ($randomProductRange == $randomProductSelectedRange ? 'selected' : ''); ?>>
                    <?= $currencies->display_price($randomProductstart, 0) . ' - ' . $currencies->display_price($randomProductEnd, 0) ?>
                </option>
<?php
}
?>
            </select>
        </form>
    </div>

    <div id="randomProducts" class="row">
<?php
foreach ($randomProductProducts as $randomProduct) {
?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <a href="<?= zen_href_link(zen_get_info_page($randomProduct['products_id']), 'products_id=' . $randomProduct['products_id']); ?>">
                    <?= zen_image(DIR_WS_IMAGES . $randomProduct['products_image'], $randomProduct['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) ?>
                </a>
                <div class="card-body">
                    <h5 class="card-title"><?= $randomProduct['products_name']; ?></h5>
                    <p class="card-text"><?= zen_get_products_display_price($randomProduct['products_id']); ?></p>
                </div>
            </div>
        </div>
<?php
}
?>
    </div>
</div>


<script>
    function updateRandomProducts(range) {
        $.ajax({
            url: '<?= zen_href_link("ajaxHandler", "", "SSL"); ?>',
            data: {
                rnd_action: 'random_price_products',
                price_range: range
            },
            success: function (data) {
                const html = $(data).find('#randomProducts').html();
                $('#randomProducts').html(html);
            }
        });
    }
</script>
