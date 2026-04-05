<?php

/**
 * Theme bootstrap file.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

$pediland_includes = [
    '/inc/helpers.php',
    '/inc/form-classes.php',
    '/inc/setup.php',
    '/inc/assets.php',
    '/inc/cleanup.php',
    '/inc/woocommerce.php',
];

foreach ($pediland_includes as $pediland_file) {
    require_once get_theme_file_path($pediland_file);
}
