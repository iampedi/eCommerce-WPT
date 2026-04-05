<?php
/**
 * No products found loop template override.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;
?>
<section class="rounded-xl border border-slate-800 bg-slate-950 p-6 text-center">
    <h2 class="text-xl font-semibold text-slate-100"><?php esc_html_e('No products were found.', 'woocommerce'); ?></h2>
    <p class="mt-2 text-sm text-slate-300"><?php esc_html_e('Try changing category, filters, or search keywords.', 'pediland'); ?></p>
</section>
