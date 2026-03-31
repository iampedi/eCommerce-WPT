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
    <footer class="mt-12 border-t border-slate-200">
        <div class="mx-auto max-w-6xl px-4 py-6 text-sm text-slate-500">
            <?php bloginfo('name'); ?>
        </div>
    </footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
