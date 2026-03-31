<?php
/**
 * My Account navigation
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;
?>
<nav class="woocommerce-MyAccount-navigation" aria-label="<?php esc_attr_e('Account pages', 'woocommerce'); ?>">
    <ul class="space-y-1">
        <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
            <?php $is_current = wc_is_current_account_menu_item($endpoint); ?>
            <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--<?php echo esc_attr($endpoint); ?><?php echo $is_current ? ' is-active' : ''; ?>">
                <a
                    class="block rounded px-3 py-2 text-sm <?php echo $is_current ? 'bg-slate-900 text-white hover:bg-slate-900' : 'text-slate-700 hover:bg-slate-100'; ?>"
                    href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"
                    <?php echo $is_current ? 'aria-current="page"' : ''; ?>
                >
                    <?php echo esc_html($label); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
