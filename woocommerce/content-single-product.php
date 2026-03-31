<?php
/**
 * Single product content template.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

global $product;

if (! $product) {
    return;
}

do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}
?>
<article id="product-<?php the_ID(); ?>" <?php wc_product_class('single-product-card space-y-10', $product); ?>>
    <div class="grid gap-10 lg:grid-cols-2">
        <section class="single-product-gallery rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <?php do_action('woocommerce_before_single_product_summary'); ?>
        </section>

        <section class="single-product-summary rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <?php do_action('woocommerce_single_product_summary'); ?>
        </section>
    </div>

    <section class="single-product-details rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <?php do_action('woocommerce_after_single_product_summary'); ?>
    </section>
</article>
<?php do_action('woocommerce_after_single_product'); ?>
