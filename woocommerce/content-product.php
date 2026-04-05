<?php

/**
 * Product card template.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

global $product;

if (! $product || ! $product->is_visible()) {
    return;
}
?>
<article class="flex h-full flex-col overflow-hidden group border border-transparent hover:border-primary duration-300">
    <a href="<?php the_permalink(); ?>" class="relative bg-black">
        <?php if ($product->is_on_sale()) : ?>
            <span class="onsale absolute left-3 top-3 z-10 rounded-md bg-primary px-2 py-1 text-xs font-semibold text-black"><?php esc_html_e('Sale!', 'woocommerce'); ?></span>
        <?php endif; ?>
        <?php if (! $product->is_in_stock()) : ?>
            <span class="absolute right-3 top-3 z-10 rounded-md border border-slate-700 bg-slate-950 px-2 py-1 text-xs font-medium text-slate-300"><?php esc_html_e('Out of stock', 'woocommerce'); ?></span>
        <?php endif; ?>
        <?php echo woocommerce_get_product_thumbnail('woocommerce_thumbnail', ['class' => 'aspect-square']); ?>
    </a>

    <div class="flex flex-col p-4 pt-0 gap-2 text-center">
        <h2>
            <a href="<?php the_permalink(); ?>" class="transition hover:text-primary text-lg font-light text-slate-500 leading-tight line-clamp-2">
                <?php echo esc_html(pediland_get_composite_product_name($product)); ?>
            </a>
        </h2>

        <?php if (wc_review_ratings_enabled()) : ?>
            <?php $rating_html = wc_get_rating_html($product->get_average_rating()); ?>
            <?php if ($rating_html) : ?>
                <div class="text-sm text-amber-400"><?php echo wp_kses_post($rating_html); ?></div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($price_html = $product->get_price_html()) : ?>
            <p class="text-lg font-semibold text-secondary"><?php echo wp_kses_post($price_html); ?></p>
        <?php endif; ?>
    </div>
</article>
