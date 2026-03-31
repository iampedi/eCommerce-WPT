<?php
/**
 * Archive description template.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

if (is_product_taxonomy() && 0 !== absint(get_query_var('paged'))) {
    return;
}

if (is_search()) {
    return;
}

$description = get_the_archive_description();
if (! $description) {
    return;
}
?>
<div class="woocommerce-products-header__description mt-2 text-sm text-slate-600">
    <?php echo wp_kses_post($description); ?>
</div>
