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
                <label class="<?php echo esc_attr(pediland_form_class('label')); ?>" for="user_login"><?php esc_html_e('Username or email', 'woocommerce'); ?>&nbsp;<span class="text-red-600">*</span></label>
                <input class="<?php echo esc_attr(pediland_form_class('input')); ?>" type="text" name="user_login" id="user_login" autocomplete="username" />
            </div>

            <?php do_action('woocommerce_lostpassword_form'); ?>

            <input type="hidden" name="wc_reset_password" value="true" />
            <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

            <button type="submit" class="<?php echo esc_attr(pediland_form_class('button-default', pediland_form_class('button-size-md'))); ?>" value="<?php esc_attr_e('Reset password', 'woocommerce'); ?>"><?php esc_html_e('Reset password', 'woocommerce'); ?></button>
        </form>
    </div>
</div>

<?php do_action('woocommerce_after_lost_password_form'); ?>
