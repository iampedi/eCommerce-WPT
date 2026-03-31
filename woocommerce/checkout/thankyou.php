<?php
/**
 * Thankyou page
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;
?>

<section class="woocommerce-order mx-auto max-w-7xl px-4 py-10">
    <?php if ($order) : ?>
        <?php do_action('woocommerce_before_thankyou', $order->get_id()); ?>

        <?php if ($order->has_status('failed')) : ?>
            <div class="rounded-lg border border-red-200 bg-red-50 p-5 text-sm text-red-700">
                <p><?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?></p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="inline-flex h-10 items-center justify-center rounded-lg bg-red-600 px-4 text-sm font-medium text-white hover:bg-red-700"><?php esc_html_e('Pay', 'woocommerce'); ?></a>
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 px-4 text-sm font-medium text-slate-700 hover:bg-slate-100"><?php esc_html_e('My account', 'woocommerce'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else : ?>
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-5 text-sm text-emerald-800">
                <?php wc_get_template('checkout/order-received.php', ['order' => $order]); ?>
            </div>

            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details mt-6 grid gap-3 rounded-lg border border-slate-200 bg-white p-5 text-sm sm:grid-cols-2 lg:grid-cols-5">
                <li class="woocommerce-order-overview__order order">
                    <span class="block text-xs uppercase tracking-wide text-slate-500"><?php esc_html_e('Order number', 'woocommerce'); ?></span>
                    <strong class="text-slate-900"><?php echo esc_html($order->get_order_number()); ?></strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    <span class="block text-xs uppercase tracking-wide text-slate-500"><?php esc_html_e('Date', 'woocommerce'); ?></span>
                    <strong class="text-slate-900"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></strong>
                </li>

                <?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()) : ?>
                    <li class="woocommerce-order-overview__email email sm:col-span-2 lg:col-span-1">
                        <span class="block text-xs uppercase tracking-wide text-slate-500"><?php esc_html_e('Email', 'woocommerce'); ?></span>
                        <strong class="break-all text-slate-900"><?php echo esc_html($order->get_billing_email()); ?></strong>
                    </li>
                <?php endif; ?>

                <li class="woocommerce-order-overview__total total">
                    <span class="block text-xs uppercase tracking-wide text-slate-500"><?php esc_html_e('Total', 'woocommerce'); ?></span>
                    <strong class="text-slate-900"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></strong>
                </li>

                <?php if ($order->get_payment_method_title()) : ?>
                    <li class="woocommerce-order-overview__payment-method method">
                        <span class="block text-xs uppercase tracking-wide text-slate-500"><?php esc_html_e('Payment method', 'woocommerce'); ?></span>
                        <strong class="text-slate-900"><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

        <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
        <?php do_action('woocommerce_thankyou', $order->get_id()); ?>
    <?php else : ?>
        <div class="rounded-lg border border-slate-200 bg-slate-50 p-5 text-sm text-slate-700">
            <?php wc_get_template('checkout/order-received.php', ['order' => false]); ?>
        </div>
    <?php endif; ?>
</section>
