<?php
/**
 * Proceed to checkout button.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;
?>
<a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-button button alt wc-forward inline-flex h-11 w-full items-center justify-center rounded-lg bg-blue-700 px-5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
    <?php esc_html_e('Proceed to checkout', 'woocommerce'); ?>
</a>
