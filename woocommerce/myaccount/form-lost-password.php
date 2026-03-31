<?php
/**
 * Lost password form
 *
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_lost_password_form');
?>

<div class="mx-auto max-w-xl">
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="mb-3 text-2xl font-semibold text-slate-900"><?php esc_html_e('Lost password', 'woocommerce'); ?></h1>
        <p class="mb-6 text-sm text-slate-600"><?php echo apply_filters('woocommerce_lost_password_message', esc_html__('Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce')); ?></p>

        <form method="post" class="woocommerce-ResetPassword lost_reset_password space-y-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-900" for="user_login"><?php esc_html_e('Username or email', 'woocommerce'); ?>&nbsp;<span class="text-red-600">*</span></label>
                <input class="block w-full rounded-lg border border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500" type="text" name="user_login" id="user_login" autocomplete="username" />
            </div>

            <?php do_action('woocommerce_lostpassword_form'); ?>

            <input type="hidden" name="wc_reset_password" value="true" />
            <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

            <button type="submit" class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300" value="<?php esc_attr_e('Reset password', 'woocommerce'); ?>"><?php esc_html_e('Reset password', 'woocommerce'); ?></button>
        </form>
    </div>
</div>

<?php do_action('woocommerce_after_lost_password_form'); ?>
