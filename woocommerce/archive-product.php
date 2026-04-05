<?php

/**
 * Shop archive template override.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

get_header('shop');
?>
<main class="_archive_product py-8">
    <div class="container mx-auto max-w-7xl px-4">
        <?php do_action('pediland_archive_before_content'); ?>

        <?php woocommerce_output_all_notices(); ?>

        <div class="flex items-center justify-between mb-6">
            <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                <h1 class="text-3xl light tracking-tight text-slate-100"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>

            <?php
            woocommerce_breadcrumb([
                'delimiter'   => '<span class="mx-2 text-slate-500">/</span>',
                'wrap_before' => '<nav class="text-sm text-slate-400" aria-label="' . esc_attr__('Breadcrumb', 'pediland') . '">',
                'wrap_after'  => '</nav>',
                'before'      => '<span>',
                'after'       => '</span>',
            ]);
            ?>

            <?php do_action('woocommerce_archive_description'); ?>
        </div>

        <?php if (woocommerce_product_loop()) : ?>
            <section class="space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-lg border border-slate-800 bg-slate-950 p-4">
                    <div class="text-sm text-slate-300">
                        <?php woocommerce_result_count(); ?>
                    </div>
                    <div>
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                </div>

                <?php woocommerce_product_loop_start(); ?>

                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php woocommerce_pagination(); ?>
            </section>
        <?php else : ?>
            <?php wc_get_template('loop/no-products-found.php'); ?>
        <?php endif; ?>

        <?php do_action('pediland_archive_after_content'); ?>
    </div>
</main>

<?php
get_footer('shop');
