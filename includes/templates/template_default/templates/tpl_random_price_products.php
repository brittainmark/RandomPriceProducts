<?php
/** @var queryFactoryResult $products */
?>
<div class="centerBoxWrapper text-center">
  <h2 class="h4 mb-3"><?php echo zen_output_string_protected($title); ?></h2>

  <div class="mb-3">
    <form id="priceRangeForm">
      <select name="price_range" class="custom-select w-auto" onchange="updateRandomProducts(this.value)">
<?php foreach ($ranges as $range): 
    list($start, $end) = explode('-',$range);
?>
          <option value="<?= $range; ?>" <?= ($range == $selected_range ? 'selected' : ''); ?>>
            <?= $currencies->display_price($start,0) . ' - ' . $currencies->display_price($end,0) ?>
          </option>
<?php endforeach; ?>
      </select>
    </form>
  </div>

  <div id="randomProducts" class="row">
<?php foreach ($products as $p): ?>
    <div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm">
      <a href="<?= zen_href_link(zen_get_info_page($p['products_id']), 'products_id=' . $p['products_id']); ?>">
          <?= zen_image(DIR_WS_IMAGES . $p['products_image'], $p['products_name']) ?>
      </a>
      <div class="card-body">
        <h5 class="card-title"><?= $p['products_name']; ?></h5>
        <p class="card-text"><?= zen_get_products_display_price($p['products_id']); ?></p>
      </div>
    </div>
    </div>
<?php endforeach; ?>
  </div>
</div>

<script>
function updateRandomProducts(range) {
  $.ajax({
    url: '<?= zen_href_link("index", "", "SSL"); ?>',
    data: { main_page: 'index', price_range: range, ajax: 'true' },
    success: function(data) {
      const html = $(data).find('#randomProducts').html();
      $('#randomProducts').html(html);
    }
  });
}
</script>
