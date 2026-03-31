<?php
/**
 * Shop archive template.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

get_header('shop');
?>
<main class="mx-auto max-w-7xl px-4 py-10">
    <?php do_action('woocommerce_before_main_content'); ?>

    <header class="mb-8">
        <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900"><?php woocommerce_page_title(); ?></h1>
        <?php endif; ?>

        <?php do_action('woocommerce_archive_description'); ?>
    </header>

    <?php if (woocommerce_product_loop()) : ?>
        <section class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <?php do_action('woocommerce_before_shop_loop'); ?>
            </div>

            <?php woocommerce_product_loop_start(); ?>

            <?php if (wc_get_loop_prop('total')) : ?>
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            <?php endif; ?>

            <?php woocommerce_product_loop_end(); ?>

            <?php do_action('woocommerce_after_shop_loop'); ?>
        </section>
    <?php else : ?>
        <?php do_action('woocommerce_no_products_found'); ?>
    <?php endif; ?>

    <?php do_action('woocommerce_after_main_content'); ?>
</main>
<?php
get_footer('shop');
