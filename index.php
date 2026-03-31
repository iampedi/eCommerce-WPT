<?php
/**
 * Main fallback template.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="primary" class="site-main mx-auto max-w-6xl px-4 py-10">
<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/components/content', get_post_type()); ?>
    <?php endwhile; ?>
<?php else : ?>
    <?php get_template_part('template-parts/components/content', 'none'); ?>
<?php endif; ?>
</main>
<?php
get_footer();
