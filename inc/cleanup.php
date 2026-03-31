<?php
/**
 * Disable default frontend styles from WordPress and WooCommerce.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

function pediland_dequeue_default_styles(): void
{
    if (is_admin()) {
        return;
    }

    $style_handles = [
        'wp-block-library',
        'wp-block-library-theme',
        'classic-theme-styles',
        'global-styles',
        'wc-block-style',
        'wc-blocks-style',
        'woocommerce-general',
        'woocommerce-layout',
        'woocommerce-smallscreen',
        'select2',
        'woocommerce_prettyPhoto_css',
    ];

    foreach ($style_handles as $handle) {
        wp_dequeue_style($handle);
        wp_deregister_style($handle);
    }
}
add_action('wp_enqueue_scripts', 'pediland_dequeue_default_styles', 100);
