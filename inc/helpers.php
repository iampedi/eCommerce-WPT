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
