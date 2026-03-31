<?php
/**
 * Checkout form
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>
<section class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="mb-8 text-3xl font-semibold tracking-tight text-slate-900"><?php esc_html_e('Checkout', 'woocommerce'); ?></h1>

    <form name="checkout" method="post" class="checkout woocommerce-checkout grid gap-6 lg:grid-cols-[1fr_24rem]" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__('Checkout', 'woocommerce'); ?>">
        <div class="space-y-6">
            <?php if ($checkout->get_checkout_fields()) : ?>
                <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                <div id="customer_details" class="space-y-6">
                    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <?php do_action('woocommerce_checkout_billing'); ?>
                    </section>

                    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <?php do_action('woocommerce_checkout_shipping'); ?>
                    </section>
                </div>

                <?php do_action('woocommerce_checkout_after_customer_details'); ?>
            <?php endif; ?>
        </div>

        <aside id="order_review" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h3 id="order_review_heading" class="mb-5 text-xl font-semibold text-slate-900"><?php esc_html_e('Your order', 'woocommerce'); ?></h3>
            <?php do_action('woocommerce_checkout_order_review'); ?>
        </aside>
    </form>
</section>
<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
