<?php
/**
 * Checkout payment section.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

if (! wp_doing_ajax()) {
    do_action('woocommerce_review_order_before_payment');
}

$update_totals_classes = pediland_form_class('button-outline', 'mt-3');
$place_order_classes = pediland_form_class('button-primary-full', 'button alt');
?>
<div id="payment" class="woocommerce-checkout-payment mt-6 space-y-4">
    <?php if (WC()->cart && WC()->cart->needs_payment()) : ?>
        <ul class="wc_payment_methods payment_methods methods space-y-3">
            <?php
            if (! empty($available_gateways)) {
                foreach ($available_gateways as $gateway) {
                    wc_get_template('checkout/payment-method.php', ['gateway' => $gateway]);
                }
            } else {
                echo '<li class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">';
                wc_print_notice(apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')), 'notice');
                echo '</li>';
            }
            ?>
        </ul>
    <?php endif; ?>

    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
        <noscript>
            <?php
            printf(
                esc_html__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'),
                '<em>',
                '</em>'
            );
            ?>
            <br /><button type="submit" class="<?php echo esc_attr($update_totals_classes); ?>" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"><?php esc_html_e('Update totals', 'woocommerce'); ?></button>
        </noscript>

        <?php wc_get_template('checkout/terms.php'); ?>

        <?php do_action('woocommerce_review_order_before_submit'); ?>

        <?php echo apply_filters('woocommerce_order_button_html', '<button type="submit" class="' . esc_attr($place_order_classes) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'); ?>

        <?php do_action('woocommerce_review_order_after_submit'); ?>

        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
    </div>
</div>
<?php
if (! wp_doing_ajax()) {
    do_action('woocommerce_review_order_after_payment');
}
