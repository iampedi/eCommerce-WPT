<?php

/**
 * Theme setup configuration.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

function pediland_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus([
        'primary' => __('Primary Menu', 'pediland'),
    ]);
}
add_action('after_setup_theme', 'pediland_setup');

function pediland_filter_custom_logo(string $html): string
{
    if ($html === '') {
        return $html;
    }

    $html = preg_replace(
        '/class="custom-logo-link"/',
        'class="custom-logo-link inline-flex items-center"',
        $html,
        1
    );

    return (string) preg_replace(
        '/class="custom-logo"/',
        'class="custom-logo h-16 w-auto"',
        $html,
        1
    );
}
add_filter('get_custom_logo', 'pediland_filter_custom_logo');
