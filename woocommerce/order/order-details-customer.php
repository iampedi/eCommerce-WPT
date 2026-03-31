<?php
/**
 * Order customer details
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<section class="woocommerce-customer-details mt-8 space-y-4">
    <h2 class="text-2xl font-semibold text-slate-900"><?php esc_html_e('Customer details', 'woocommerce'); ?></h2>

    <div class="grid gap-4 <?php echo $show_shipping ? 'md:grid-cols-2' : ''; ?>">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="mb-3 text-lg font-semibold text-slate-900"><?php esc_html_e('Billing address', 'woocommerce'); ?></h3>
            <address class="not-italic text-sm leading-7 text-slate-700">
                <?php echo wp_kses_post($order->get_formatted_billing_address(esc_html__('N/A', 'woocommerce'))); ?>

                <?php if ($order->get_billing_phone()) : ?>
                    <p class="woocommerce-customer-details--phone mt-2"><?php echo esc_html($order->get_billing_phone()); ?></p>
                <?php endif; ?>

                <?php if ($order->get_billing_email()) : ?>
                    <p class="woocommerce-customer-details--email"><?php echo esc_html($order->get_billing_email()); ?></p>
                <?php endif; ?>

                <?php do_action('woocommerce_order_details_after_customer_address', 'billing', $order); ?>
            </address>
        </div>

        <?php if ($show_shipping) : ?>
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-3 text-lg font-semibold text-slate-900"><?php esc_html_e('Shipping address', 'woocommerce'); ?></h3>
                <address class="not-italic text-sm leading-7 text-slate-700">
                    <?php echo wp_kses_post($order->get_formatted_shipping_address(esc_html__('N/A', 'woocommerce'))); ?>

                    <?php if ($order->get_shipping_phone()) : ?>
                        <p class="woocommerce-customer-details--phone mt-2"><?php echo esc_html($order->get_shipping_phone()); ?></p>
                    <?php endif; ?>

                    <?php do_action('woocommerce_order_details_after_customer_address', 'shipping', $order); ?>
                </address>
            </div>
        <?php endif; ?>
    </div>

    <?php do_action('woocommerce_order_details_after_customer_details', $order); ?>
</section>
