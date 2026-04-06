<?php

/**
 * Single product content template.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

global $product;

if (! $product) {
    return;
}

$format_upper = static function (string $value): string {
    if ($value === '') {
        return '';
    }

    return function_exists('mb_strtoupper') ? mb_strtoupper($value, 'UTF-8') : strtoupper($value);
};

$read_attribute = static function (array $keys) use ($product): string {
    foreach ($keys as $key) {
        $value = trim((string) $product->get_attribute($key));
        if ($value !== '') {
            return $value;
        }
    }

    return '';
};

$origin_terms = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
$origin_value = '';
if (! is_wp_error($origin_terms) && ! empty($origin_terms)) {
    $origin_value = implode(', ', array_map('trim', $origin_terms));
}

$weight_value = '';
if ($product->has_weight()) {
    $weight_value = wc_format_weight($product->get_weight());
}

$format_dimension_value = static function (string $raw_value): string {
    if ($raw_value === '') {
        return '';
    }

    $unit = (string) get_option('woocommerce_dimension_unit');
    $formatted = wc_format_localized_decimal($raw_value);

    return trim($formatted . ' ' . $unit);
};

$height_value = $format_dimension_value((string) $product->get_height());
$width_value = $format_dimension_value((string) $product->get_width());

$dimensions_value = '';
if ($height_value !== '' || $width_value !== '') {
    $dimension_parts = [];

    if ($height_value !== '') {
        $dimension_parts[] = sprintf(
            '<span class="inline-flex items-center gap-1">%s<i class="ph ph-arrows-out-line-vertical text-slate-300" aria-hidden="true"></i></span>',
            esc_html($height_value)
        );
    }

    if ($width_value !== '') {
        $dimension_parts[] = sprintf(
            '<span class="inline-flex items-center gap-1">%s<i class="ph ph-arrows-out-line-horizontal text-slate-300" aria-hidden="true"></i></span>',
            esc_html($width_value)
        );
    }

    $dimensions_value = sprintf(
        '<div class="flex items-center gap-5">%s</div>',
        implode('', $dimension_parts)
    );
}

$additional_rows = [
    ['label' => __('Origin', 'pediland'), 'value' => $origin_value],
    ['label' => __('Color', 'pediland'), 'value' => $read_attribute(['pa_color', 'color'])],
    ['label' => __('Shape', 'pediland'), 'value' => $read_attribute(['pa_shape', 'shape'])],
    ['label' => __('Dimensions', 'pediland'), 'value' => $dimensions_value, 'is_html' => true],
    ['label' => __('Weight', 'pediland'), 'value' => $weight_value],
    ['label' => __('Matrix', 'pediland'), 'value' => $read_attribute(['pa_matrix', 'matrix'])],
    ['label' => __('Cut', 'pediland'), 'value' => $read_attribute(['pa_cut', 'cut'])],
    ['label' => __('SKU', 'pediland'), 'value' => $format_upper((string) $product->get_sku())],
];

$additional_rows = array_values(array_filter(
    $additional_rows,
    static fn(array $row): bool => trim((string) $row['value']) !== ''
));

do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
?>
<article id="product-<?php the_ID(); ?>" <?php wc_product_class('_single_product_card space-y-8', $product); ?>>
    <div class="single-product-main-grid grid gap-10 lg:grid-cols-2">
        <section class="single-product-gallery rounded-3xl border-2 border-slate-800 bg-black">
            <?php do_action('woocommerce_before_single_product_summary'); ?>
        </section>

        <section class="_single_product_summary p-5">
            <?php do_action('woocommerce_single_product_summary'); ?>

            <?php if (! empty($additional_rows)) : ?>
                <section class="single-product-additional-info mt-16">
                    <table class="shop_attributes w-full border-collapse border-b border-slate-800 border-dashed">
                        <tbody>
                            <?php foreach ($additional_rows as $row) : ?>
                                <tr>
                                    <th class="w-1/3 border-t border-dashed text-slate-400 border-slate-800 px-4 py-2.5 text-left align-top font-medium"><?php echo esc_html($row['label']); ?></th>
                                    <td class="border-t border-dashed border-slate-800 py-2.5 align-top text-slate-100 font-light">
                                        <?php if (! empty($row['is_html'])) : ?>
                                            <?php echo wp_kses($row['value'], ['div' => ['class' => []], 'span' => ['class' => []], 'i' => ['class' => [], 'aria-hidden' => []]]); ?>
                                        <?php else : ?>
                                            <?php echo esc_html($row['value']); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            <?php endif; ?>

            <div class="mt-8">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>
        </section>
    </div>

    <section class="_single_product_related pt-12">
        <?php woocommerce_output_related_products(); ?>
    </section>
</article>
<?php do_action('woocommerce_after_single_product'); ?>