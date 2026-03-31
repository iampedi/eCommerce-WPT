<?php
/**
 * Login page template for slug: login
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

if (is_user_logged_in()) {
    wp_safe_redirect(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/'));
    exit;
}

get_header();
?>
<main id="primary" class="site-main">
    <?php
    if (function_exists('wc_print_notices')) {
        wc_print_notices();
    }

    if (function_exists('wc_get_template')) {
        wc_get_template('myaccount/form-login.php');
    } else {
        ?>
        <section class="mx-auto max-w-xl px-4 py-10">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h1 class="mb-6 text-2xl font-semibold text-slate-900"><?php esc_html_e('Login', 'pediland'); ?></h1>
                <?php wp_login_form(); ?>
            </div>
        </section>
        <?php
    }
    ?>
</main>
<?php
get_footer();
