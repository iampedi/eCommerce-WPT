<?php
/**
 * Theme asset enqueueing.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

function pediland_enqueue_assets(): void
{
    wp_enqueue_style(
        'pediland-font-google-sans-flex',
        'https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@8..144,100..700&display=swap',
        [],
        null
    );

    $css_path = get_theme_file_path('/assets/css/main.css');
    $css_uri = get_theme_file_uri('/assets/css/main.css');
    $css_version = file_exists($css_path) ? (string) filemtime($css_path) : pediland_theme_version();

    wp_enqueue_style('pediland-main', $css_uri, ['pediland-font-google-sans-flex'], $css_version);
    wp_add_inline_style('pediland-main', "body{font-family:'Google Sans Flex',sans-serif;}");

    $main_dependencies = [];
    $flowbite_path = get_theme_file_path('/assets/js/vendor/flowbite.min.js');
    if (file_exists($flowbite_path)) {
        wp_enqueue_script(
            'pediland-flowbite',
            get_theme_file_uri('/assets/js/vendor/flowbite.min.js'),
            [],
            (string) filemtime($flowbite_path),
            true
        );
        $main_dependencies[] = 'pediland-flowbite';
    }

    $js_path = get_theme_file_path('/assets/js/main.js');
    if (file_exists($js_path)) {
        wp_enqueue_script(
            'pediland-main',
            get_theme_file_uri('/assets/js/main.js'),
            $main_dependencies,
            (string) filemtime($js_path),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'pediland_enqueue_assets');
