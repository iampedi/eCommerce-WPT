<?php
/**
 * Payment method item.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr($gateway->id); ?> rounded-lg border border-slate-200 bg-white p-4">
    <input id="payment_method_<?php echo esc_attr($gateway->id); ?>" type="radio" class="<?php echo esc_attr(pediland_form_class('radio', 'input-radio')); ?>" name="payment_method" value="<?php echo esc_attr($gateway->id); ?>" <?php checked($gateway->chosen, true); ?> data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>" />
    <label for="payment_method_<?php echo esc_attr($gateway->id); ?>" class="ml-2 inline-flex items-center gap-2 text-sm font-medium text-slate-900">
        <?php echo wp_kses_post($gateway->get_title()); ?>
        <?php echo wp_kses_post($gateway->get_icon()); ?>
    </label>

    <?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
        <div class="payment_box payment_method_<?php echo esc_attr($gateway->id); ?> mt-3 rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700" <?php if (! $gateway->chosen) : ?>style="display:none;"<?php endif; ?>>
            <?php $gateway->payment_fields(); ?>
        </div>
    <?php endif; ?>
</li>
