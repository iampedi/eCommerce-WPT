<?php
/**
 * WooCommerce archive behavior overrides.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Remove default wrappers/sidebar so archive layout is fully theme-controlled.
 */
function pediland_customize_woocommerce_hooks(): void
{
    if (! class_exists('WooCommerce')) {
        return;
    }

    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
}
add_action('wp', 'pediland_customize_woocommerce_hooks');
