<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_account_navigation');
?>
<div class="grid gap-8 md:grid-cols-[16rem_minmax(0,1fr)]">
    <aside class="rounded-lg border border-slate-200 p-4">
        <?php do_action('woocommerce_account_navigation'); ?>
    </aside>

    <section class="rounded-lg border border-slate-200 p-6">
        <?php do_action('woocommerce_account_content'); ?>
    </section>
</div>
<?php
do_action('woocommerce_after_account_navigation');
