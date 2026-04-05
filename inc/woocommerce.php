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

/**
 * Keep breadcrumbs hierarchical by removing pagination crumb (Page N).
 */
function pediland_strip_woocommerce_paged_breadcrumb(array $crumbs): array
{
    $paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
    if ($paged < 2 || empty($crumbs)) {
        return $crumbs;
    }

    $expected = sprintf(__('Page %d', 'woocommerce'), $paged);
    $last_index = array_key_last($crumbs);
    $last_crumb = $crumbs[$last_index] ?? null;

    if (is_array($last_crumb) && isset($last_crumb[0])) {
        $label = wp_strip_all_tags((string) $last_crumb[0]);
        if ($label === $expected) {
            unset($crumbs[$last_index]);
            $crumbs = array_values($crumbs);
        }
    }

    return $crumbs;
}
add_filter('woocommerce_get_breadcrumb', 'pediland_strip_woocommerce_paged_breadcrumb');
