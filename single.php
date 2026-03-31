<?php
/**
 * Single post template.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="primary" class="site-main mx-auto max-w-4xl px-4 py-10">
<?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/components/content', 'single'); ?>
<?php endwhile; ?>
</main>
<?php
get_footer();
