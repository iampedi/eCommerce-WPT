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
<li <?php wc_product_class('group'); ?>>
    <article class="h-full overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
        <a href="<?php the_permalink(); ?>" class="relative block overflow-hidden">
            <?php if ($product->is_on_sale()) : ?>
                <span class="onsale absolute left-3 top-3 z-10 rounded bg-slate-900 px-2 py-1 text-xs font-semibold text-white"><?php esc_html_e('Sale!', 'woocommerce'); ?></span>
            <?php endif; ?>
            <?php echo woocommerce_get_product_thumbnail('woocommerce_thumbnail', ['class' => 'h-56 w-full object-cover object-center']); ?>
        </a>

        <div class="space-y-3 p-4">
            <h2 class="text-base font-semibold text-slate-900">
                <a href="<?php the_permalink(); ?>" class="hover:text-blue-700"><?php the_title(); ?></a>
            </h2>

            <?php if ($price_html = $product->get_price_html()) : ?>
                <p class="text-lg font-semibold text-slate-900"><?php echo wp_kses_post($price_html); ?></p>
            <?php endif; ?>

            <?php
            woocommerce_template_loop_add_to_cart([
                'class' => 'button mt-2 inline-flex w-full items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300',
            ]);
            ?>
        </div>
    </article>
</li>
