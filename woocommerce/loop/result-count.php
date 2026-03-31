<?php
/**
 * Product result count.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;
?>
<p class="woocommerce-result-count text-sm text-slate-600" <?php if (1 === $total) : ?>role="alert"<?php endif; ?>>
    <?php
    if (1 === $total) {
        esc_html_e('Showing the single result', 'woocommerce');
    } elseif ($total <= $per_page || -1 === $per_page) {
        /* translators: %d: total results */
        printf(esc_html(_n('Showing all %d result', 'Showing all %d results', $total, 'woocommerce')), esc_html($total));
    } else {
        $first = ($per_page * $current) - $per_page + 1;
        $last  = min($total, $per_page * $current);
        /* translators: 1: first result 2: last result 3: total results */
        printf(esc_html__('Showing %1$d-%2$d of %3$d results', 'woocommerce'), esc_html($first), esc_html($last), esc_html($total));
    }
    ?>
</p>
