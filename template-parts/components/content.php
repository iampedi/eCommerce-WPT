<?php
/**
 * Default content template.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('rounded border border-slate-200 p-5'); ?>>
    <?php the_title('<h2 class="mb-3 text-xl font-semibold"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
    <div class="content-typography">
        <?php the_excerpt(); ?>
    </div>
</article>

