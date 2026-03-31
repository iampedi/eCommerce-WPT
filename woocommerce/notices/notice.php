<?php
/**
 * Notice template.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

if (! $notices) {
    return;
}
?>
<div class="woocommerce-notices-wrapper mb-6">
    <?php foreach ($notices as $notice) : ?>
        <?php
        $notice_type = isset($notice['notice_type']) ? $notice['notice_type'] : 'notice';
        $notice_text = isset($notice['notice']) ? $notice['notice'] : '';
        ?>
        <div class="woocommerce-<?php echo esc_attr($notice_type); ?> mb-4 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700" <?php echo wc_get_notice_data_attr($notice); ?>>
            <?php echo wc_kses_notice($notice_text); ?>
        </div>
    <?php endforeach; ?>
</div>
