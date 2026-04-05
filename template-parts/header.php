<?php

/**
 * Shared document head and site header.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-black text-slate-100 antialiased'); ?>>
    <?php wp_body_open(); ?>
    <div id="page-loader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black transition-opacity duration-300">
        <div class="h-10 w-10 animate-spin rounded-full border-4 border-slate-200 border-t-blue-700"></div>
    </div>
    <div id="page" class="min-h-screen">
        <header>
            <div class="container">
                <div class="flex items-center justify-between pt-8 pb-4">
                    <div class="site-branding">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <a class="text-lg font-semibold" href="<?php echo esc_url(home_url('/')); ?>">
                                <?php bloginfo('name'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'menu_class'     => 'flex gap-4 text-sm',
                    ]);
                    ?>
                </div>
            </div>
        </header>