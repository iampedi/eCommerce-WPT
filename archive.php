<?php
/**
 * Archive template.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="primary" class="site-main mx-auto max-w-6xl px-4 py-10">
    <header class="mb-8">
        <?php the_archive_title('<h1 class="text-3xl font-semibold">', '</h1>'); ?>
        <?php the_archive_description('<div class="mt-2 text-sm text-gray-600">', '</div>'); ?>
    </header>

<?php if (have_posts()) : ?>
    <div class="grid gap-6 md:grid-cols-2">
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/components/content', get_post_type()); ?>
    <?php endwhile; ?>
    </div>
    <?php the_posts_navigation(); ?>
<?php else : ?>
    <?php get_template_part('template-parts/components/content', 'none'); ?>
<?php endif; ?>
</main>
<?php
get_footer();
