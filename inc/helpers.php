<?php
/**
 * Theme helper functions.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

function pediland_theme_version(): string
{
    $theme = wp_get_theme();
    $version = $theme->get('Version');

    return $version ?: '1.0.0';
}

/**
 * Get first non-empty attribute value by trying multiple taxonomy keys.
 */
function pediland_get_product_attribute_value(WC_Product $product, array $keys): string
{
    foreach ($keys as $key) {
        $value = trim((string) $product->get_attribute($key));
        if ($value !== '') {
            return $value;
        }
    }

    return '';
}

/**
 * Build combined archive product name:
 * COLOR + SHAPE + MATRIX + Persian Turquoise
 */
function pediland_get_composite_product_name(WC_Product $product): string
{
    $color = pediland_get_product_attribute_value($product, ['pa_color', 'color']);
    $cut = pediland_get_product_attribute_value($product, ['pa_cut', 'cut', 'pa_shape', 'shape']);
    $matrix = pediland_get_product_attribute_value($product, ['pa_matrix', 'matrix']);

    $title = trim('Natural ' . $color . ' Persian Turquoise');
    $details = trim($cut . ' Cut' . ($matrix !== '' ? ', ' . $matrix : ''));

    if ($details === '') {
        return $title;
    }

    return $title . ' – ' . $details;
}
