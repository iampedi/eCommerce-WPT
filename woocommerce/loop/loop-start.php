<?php

/**
 * Product loop start.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

$default_columns = function_exists('wc_get_default_products_per_row') ? (int) wc_get_default_products_per_row() : 4;
$columns = (int) wc_get_loop_prop('columns', $default_columns);
$columns = max(1, $columns);
$columns_class = 'columns-' . $columns;
$grid_style = '--pediland-products-desktop-cols:' . $columns . ';';
?>
<div class="products pediland-products-grid <?php echo esc_attr($columns_class); ?> grid list-none gap-4 p-0 sm:grid-cols-2" style="<?php echo esc_attr($grid_style); ?>">