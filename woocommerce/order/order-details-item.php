<?php
/**
 * Order details item template.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! apply_filters('woocommerce_order_item_visible', true, $item)) {
    return;
}
?>
<tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order)); ?>">
    <td class="woocommerce-table__product-name product-name py-3 text-slate-700">
        <?php
        $is_visible        = $product && $product->is_visible();
        $product_permalink = apply_filters('woocommerce_order_item_permalink', $is_visible ? $product->get_permalink($item) : '', $item, $order);

        echo wp_kses_post(apply_filters('woocommerce_order_item_name', $product_permalink ? sprintf('<a class="font-medium text-slate-900 hover:text-blue-700" href="%s">%s</a>', esc_url($product_permalink), $item->get_name()) : $item->get_name(), $item, $is_visible));

        $qty          = $item->get_quantity();
        $refunded_qty = $order->get_qty_refunded_for_item($item_id);

        if ($refunded_qty) {
            $qty_display = '<del>' . esc_html($qty) . '</del> <ins>' . esc_html($qty - ($refunded_qty * -1)) . '</ins>';
        } else {
            $qty_display = esc_html($qty);
        }

        echo wp_kses_post(apply_filters('woocommerce_order_item_quantity_html', ' <strong class="product-quantity font-semibold text-slate-900">&times;&nbsp;' . $qty_display . '</strong>', $item));

        do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false);

        wc_display_item_meta($item);

        do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false);
        ?>
    </td>

    <td class="woocommerce-table__product-total product-total py-3 text-right font-semibold text-slate-900">
        <?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?>
    </td>
</tr>

<?php if ($show_purchase_note && $purchase_note) : ?>
    <tr class="woocommerce-table__product-purchase-note product-purchase-note">
        <td colspan="2" class="pb-4 text-sm text-slate-600"><?php echo wp_kses_post(wpautop(do_shortcode($purchase_note))); ?></td>
    </tr>
<?php endif; ?>
