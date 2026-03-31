<?php
/**
 * Error notices template.
 *
 * @package PedilandBlank
 */

defined('ABSPATH') || exit;

if (! $notices) {
    return;
}
?>
<ul class="woocommerce-error mb-6 list-none space-y-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
    <?php foreach ($notices as $notice) : ?>
        <li <?php echo wc_get_notice_data_attr($notice); ?>>
            <?php echo wc_kses_notice($notice['notice']); ?>
        </li>
    <?php endforeach; ?>
</ul>
