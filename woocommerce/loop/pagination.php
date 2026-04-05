<?php

/**
 * WooCommerce pagination template.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

$total = isset($total) ? (int) $total : wc_get_loop_prop('total_pages');
$current_page = isset($current) ? (int) $current : wc_get_loop_prop('current_page');

if ($total <= 1) {
    return;
}

$base = esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));

$links = paginate_links([
    'base'      => $base,
    'format'    => '',
    'add_args'  => false,
    'current'   => max(1, $current_page),
    'total'     => $total,
    'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
    'next_text' => is_rtl() ? '&larr;' : '&rarr;',
    'type'      => 'array',
    'end_size'  => 1,
    'mid_size'  => 2,
]);

if (empty($links)) {
    return;
}
?>
<nav class="woocommerce-pagination mt-16 w-full flex justify-center" aria-label="<?php esc_attr_e('Product Pagination', 'woocommerce'); ?>">
    <ul class="inline-flex overflow-hidden rounded-lg border border-slate-800 bg-slate-950 mx-auto">
        <?php foreach ($links as $link) : ?>
            <?php $is_current = str_contains($link, 'current'); ?>
            <li class="border-r border-slate-800 last:border-r-0">
                <?php
                $classes = $is_current
                    ? 'page-numbers block bg-primary px-4 py-2 text-sm font-medium text-black'
                    : 'page-numbers block px-4 py-2 text-sm text-slate-300 hover:bg-slate-900';

                echo wp_kses_post((string) preg_replace('/class="([^"]*)"/', 'class="' . $classes . '"', $link, 1));
                ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>