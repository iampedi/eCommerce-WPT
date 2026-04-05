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
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'menu_class'     => 'flex gap-10',
                    ]);
                    ?>

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
                    $account_url = home_url('/my-account/');
                    $cart_url = home_url('/cart/');
                    $cart_count = 0;

                    if (class_exists('WooCommerce')) {
                        $wc_account_url = wc_get_page_permalink('myaccount');
                        if (! empty($wc_account_url)) {
                            $account_url = $wc_account_url;
                        }

                        $wc_cart_url = wc_get_cart_url();
                        if (! empty($wc_cart_url)) {
                            $cart_url = $wc_cart_url;
                        }

                        if (function_exists('WC') && WC() && WC()->cart) {
                            $cart_count = (int) WC()->cart->get_cart_contents_count();
                        }
                    }

                    $cart_badge = $cart_count > 99 ? '99+' : (string) $cart_count;
                    ?>
                    <div class="icons flex items-center gap-2">
                        <a
                            href="<?php echo esc_url($account_url); ?>"
                            class="group inline-flex size-12 items-center justify-center rounded-full border-2 border-slate-700 transition-colors hover:border-slate-500 hover:bg-slate-900"
                            aria-label="<?php esc_attr_e('My account', 'pediland'); ?>">
                            <i class="ph-duotone ph-lock-simple text-2xl text-slate-400 group-hover:text-slate-100 transition-colors" aria-hidden="true"></i>
                        </a>

                        <a
                            href="<?php echo esc_url($cart_url); ?>"
                            class="relative igroup inline-flex size-12 items-center justify-center rounded-full border-2 border-slate-700 transition-colors hover:border-slate-500 hover:bg-slate-900"
                            aria-label="<?php esc_attr_e('Cart', 'pediland'); ?>">
                            <i class="ph ph-shopping-bag text-2xl text-slate-400 group-hover:text-slate-100 transition-colors" aria-hidden="true"></i>
                            <span class="absolute -right-2 -top-2 inline-flex min-w-5 items-center justify-center rounded-full bg-primary px-1.5 text-[11px] leading-5 text-black">
                                <?php echo esc_html($cart_badge); ?>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </header>