<?php
/**
 * Success notices template.
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
        <div class="woocommerce-message mb-4 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700" role="alert" <?php echo wc_get_notice_data_attr($notice); ?>>
            <?php echo wc_kses_notice($notice['notice']); ?>
        </div>
    <?php endforeach; ?>
</div>
