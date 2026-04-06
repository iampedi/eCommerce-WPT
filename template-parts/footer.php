<?php

/**
 * Shared site footer and document end.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<footer class="mt-12">
    <div class="container">
        <div class="text-center text-sm text-slate-500 py-6">
            &copy; <?php echo esc_html(wp_date('Y')); ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-400 transition-colors hover:text-slate-200">
                <?php bloginfo('name'); ?>
            </a>.
            <?php esc_html_e('All rights reserved.', 'pediland'); ?>
        </div>
    </div>
</footer>
</div>
<?php wp_footer(); ?>
</body>

</html>
