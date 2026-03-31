<?php
/**
 * Order details template.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

$order = wc_get_order($order_id);

if (! $order) {
    return;
}

$order_items        = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
$show_purchase_note = $order->has_status(apply_filters('woocommerce_purchase_note_order_statuses', ['completed', 'processing']));
$downloads          = $order->get_downloadable_items();
$actions            = array_filter(wc_get_account_orders_actions($order), static fn($key) => 'view' !== $key, ARRAY_FILTER_USE_KEY);
$show_customer_details = $order->get_user_id() === get_current_user_id();

if ($show_downloads) {
    wc_get_template('order/order-downloads.php', ['downloads' => $downloads, 'show_title' => true]);
}
?>
<section class="woocommerce-order-details mt-8 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    <?php do_action('woocommerce_order_details_before_order_table', $order); ?>

    <h2 class="woocommerce-order-details__title mb-5 text-2xl font-semibold text-slate-900"><?php esc_html_e('Order details', 'woocommerce'); ?></h2>

    <table class="woocommerce-table woocommerce-table--order-details shop_table order_details w-full text-sm">
        <thead>
            <tr>
                <th class="border-b border-slate-200 pb-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                <th class="border-b border-slate-200 pb-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500"><?php esc_html_e('Total', 'woocommerce'); ?></th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">
            <?php
            do_action('woocommerce_order_details_before_order_table_items', $order);

            foreach ($order_items as $item_id => $item) {
                $product = $item->get_product();

                wc_get_template('order/order-details-item.php', [
                    'order'              => $order,
                    'item_id'            => $item_id,
                    'item'               => $item,
                    'show_purchase_note' => $show_purchase_note,
                    'purchase_note'      => $product ? $product->get_purchase_note() : '',
                    'product'            => $product,
                ]);
            }

            do_action('woocommerce_order_details_after_order_table_items', $order);
            ?>
        </tbody>

        <tfoot class="divide-y divide-slate-200">
            <?php foreach ($order->get_order_item_totals() as $key => $total) : ?>
                <tr>
                    <th scope="row" class="py-3 text-left font-medium text-slate-600"><?php echo esc_html($total['label']); ?></th>
                    <td class="py-3 text-right font-semibold text-slate-900"><?php echo wp_kses_post($total['value']); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ($order->get_customer_note()) : ?>
                <tr>
                    <th class="py-3 text-left font-medium text-slate-600"><?php esc_html_e('Note:', 'woocommerce'); ?></th>
                    <td class="py-3 text-right text-slate-700"><?php echo wp_kses(nl2br(wc_wptexturize_order_note($order->get_customer_note())), ['br' => []]); ?></td>
                </tr>
            <?php endif; ?>

            <?php if (! empty($actions)) : ?>
                <tr>
                    <th class="py-3 text-left font-medium text-slate-600"><?php esc_html_e('Actions', 'woocommerce'); ?></th>
                    <td class="py-3 text-right">
                        <div class="flex flex-wrap justify-end gap-2">
                            <?php foreach ($actions as $key => $action) : ?>
                                <a href="<?php echo esc_url($action['url']); ?>" class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 px-4 text-sm font-medium text-slate-700 hover:bg-slate-100 <?php echo esc_attr(sanitize_html_class($key)); ?>"><?php echo esc_html($action['name']); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tfoot>
    </table>

    <?php do_action('woocommerce_order_details_after_order_table', $order); ?>
</section>

<?php do_action('woocommerce_after_order_details', $order); ?>

<?php if ($show_customer_details) {
    wc_get_template('order/order-details-customer.php', ['order' => $order]);
} ?>
