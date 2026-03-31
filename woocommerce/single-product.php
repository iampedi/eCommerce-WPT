<?php
/**
 * WooCommerce single product override.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

get_header('shop');
?>
<main class="mx-auto max-w-7xl px-4 py-10">
    <?php do_action('woocommerce_before_main_content'); ?>

    <?php while (have_posts()) : ?>
        <?php the_post(); ?>
        <?php wc_get_template_part('content', 'single-product'); ?>
    <?php endwhile; ?>

    <?php do_action('woocommerce_after_main_content'); ?>
</main>
<?php
get_footer('shop');
