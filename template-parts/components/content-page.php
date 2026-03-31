<?php
/**
 * Page content template.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php the_title('<h1 class="mb-4 text-3xl font-semibold">', '</h1>'); ?>
    <div class="content-typography">
        <?php the_content(); ?>
    </div>
</article>

