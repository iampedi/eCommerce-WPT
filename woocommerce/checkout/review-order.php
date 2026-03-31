<?php
/**
 * Review order table
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table w-full text-sm" cellspacing="0">
    <thead>
        <tr>
            <th class="border-b border-slate-200 pb-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500"><?php esc_html_e('Product', 'woocommerce'); ?></th>
            <th class="border-b border-slate-200 pb-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-200">
        <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : ?>
            <?php
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            if (! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                continue;
            }
            ?>
            <tr class="cart_item">
                <td class="py-3 text-slate-700">
                    <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;<strong class="font-semibold text-slate-900">&times;&nbsp;' . esc_html($cart_item['quantity']) . '</strong>'; ?>
                    <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                </td>
                <td class="py-3 text-right font-semibold text-slate-900">
                    <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key)); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot class="divide-y divide-slate-200">
        <tr class="cart-subtotal">
            <th class="py-3 text-left font-medium text-slate-600"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
            <td class="py-3 text-right font-semibold text-slate-900"><?php wc_cart_totals_subtotal_html(); ?></td>
        </tr>

        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
            <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <th class="py-3 text-left font-medium text-slate-600"><?php wc_cart_totals_coupon_label($coupon); ?></th>
                <td class="py-3 text-right font-semibold text-slate-900"><?php wc_cart_totals_coupon_html($coupon); ?></td>
            </tr>
        <?php endforeach; ?>

        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
            <?php do_action('woocommerce_review_order_before_shipping'); ?>
            <?php wc_cart_totals_shipping_html(); ?>
            <?php do_action('woocommerce_review_order_after_shipping'); ?>
        <?php endif; ?>

        <?php foreach (WC()->cart->get_fees() as $fee) : ?>
            <tr class="fee">
                <th class="py-3 text-left font-medium text-slate-600"><?php echo esc_html($fee->name); ?></th>
                <td class="py-3 text-right font-semibold text-slate-900"><?php wc_cart_totals_fee_html($fee); ?></td>
            </tr>
        <?php endforeach; ?>

        <?php if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()) : ?>
            <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                    <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                        <th class="py-3 text-left font-medium text-slate-600"><?php echo esc_html($tax->label); ?></th>
                        <td class="py-3 text-right font-semibold text-slate-900"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr class="tax-total">
                    <th class="py-3 text-left font-medium text-slate-600"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                    <td class="py-3 text-right font-semibold text-slate-900"><?php wc_cart_totals_taxes_total_html(); ?></td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action('woocommerce_review_order_before_order_total'); ?>

        <tr class="order-total">
            <th class="py-4 text-left text-base font-semibold text-slate-900"><?php esc_html_e('Total', 'woocommerce'); ?></th>
            <td class="py-4 text-right text-base font-semibold text-slate-900"><?php wc_cart_totals_order_total_html(); ?></td>
        </tr>

        <?php do_action('woocommerce_review_order_after_order_total'); ?>
    </tfoot>
</table>
