<?php

/**
 * My Account login form
 *
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if (! defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_customer_login_form');

$registration_enabled = 'yes' === get_option('woocommerce_enable_myaccount_registration');
$username_required = 'no' === get_option('woocommerce_registration_generate_username');
$password_required = 'no' === get_option('woocommerce_registration_generate_password');
?>

<div class="mx-auto max-w-6xl">
    <div class="grid gap-6 <?php echo $registration_enabled ? 'md:grid-cols-2' : ''; ?>">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="mb-6 text-2xl font-semibold text-slate-900"><?php esc_html_e('Login', 'woocommerce'); ?></h1>

            <form class="woocommerce-form woocommerce-form-login login space-y-5" method="post" novalidate>
                <?php do_action('woocommerce_login_form_start'); ?>

                <div>
                    <label class="<?php echo esc_attr(pediland_form_class('label')); ?>" for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span class="text-red-600">*</span></label>
                    <input
                        type="text"
                        class="<?php echo esc_attr(pediland_form_class('input')); ?>"
                        name="username"
                        id="username"
                        autocomplete="username"
                        value="<?php echo isset($_POST['username']) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
                </div>

                <div>
                    <label class="<?php echo esc_attr(pediland_form_class('label')); ?>" for="password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="text-red-600">*</span></label>
                    <input
                        class="<?php echo esc_attr(pediland_form_class('input')); ?>"
                        type="password"
                        name="password"
                        id="password"
                        autocomplete="current-password" />
                </div>

                <?php do_action('woocommerce_login_form'); ?>

                <div class="flex flex-wrap items-center justify-between gap-3">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                        <input class="<?php echo esc_attr(pediland_form_class('checkbox')); ?>" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                        <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
                    </label>

                    <a class="text-sm text-blue-600 hover:underline" href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
                </div>

                <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                <button type="submit" class="<?php echo esc_attr(pediland_form_class('button-primary-full')); ?>" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>

                <?php do_action('woocommerce_login_form_end'); ?>
            </form>
        </div>

        <?php if ($registration_enabled) : ?>
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-6 text-2xl font-semibold text-slate-900"><?php esc_html_e('Register', 'woocommerce'); ?></h2>

                <form method="post" class="woocommerce-form woocommerce-form-register register space-y-5" <?php do_action('woocommerce_register_form_tag'); ?>>
                    <?php do_action('woocommerce_register_form_start'); ?>

                    <?php if ($username_required) : ?>
                        <div>
                            <label class="<?php echo esc_attr(pediland_form_class('label')); ?>" for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="text-red-600">*</span></label>
                            <input
                                type="text"
                                class="<?php echo esc_attr(pediland_form_class('input')); ?>"
                                name="username"
                                id="reg_username"
                                autocomplete="username"
                                value="<?php echo isset($_POST['username']) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="<?php echo esc_attr(pediland_form_class('label')); ?>" for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="text-red-600">*</span></label>
                        <input
                            type="email"
                            class="<?php echo esc_attr(pediland_form_class('input')); ?>"
                            name="email"
                            id="reg_email"
                            autocomplete="email"
                            value="<?php echo isset($_POST['email']) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
                    </div>

                    <?php if ($password_required) : ?>
                        <div>
                            <label class="<?php echo esc_attr(pediland_form_class('label')); ?>" for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="text-red-600">*</span></label>
                            <input
                                type="password"
                                class="<?php echo esc_attr(pediland_form_class('input')); ?>"
                                name="password"
                                id="reg_password"
                                autocomplete="new-password" />
                        </div>
                    <?php else : ?>
                        <p class="text-sm text-slate-600"><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>
                    <?php endif; ?>

                    <?php do_action('woocommerce_register_form'); ?>

                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    <button type="submit" class="<?php echo esc_attr(pediland_form_class('button-dark-full')); ?>" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>

                    <?php do_action('woocommerce_register_form_end'); ?>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
