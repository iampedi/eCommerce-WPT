<?php
/**
 * Product sorting.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;
?>
<form class="woocommerce-ordering flex items-center gap-3" method="get">
    <label class="text-sm font-medium text-slate-700" for="orderby"><?php esc_html_e('Sort:', 'pediland'); ?></label>
    <select name="orderby" class="orderby rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500" aria-label="<?php esc_attr_e('Shop order', 'woocommerce'); ?>">
        <?php foreach ($catalog_orderby_options as $id => $name) : ?>
            <option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="hidden" name="paged" value="1" />
    <?php wc_query_string_form_fields(null, ['orderby', 'submit', 'paged', 'product-page']); ?>
</form>
