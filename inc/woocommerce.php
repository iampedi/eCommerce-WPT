<?php

/**
 * WooCommerce archive behavior overrides.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
   exit;
}

/**
 * Remove default wrappers/sidebar so archive layout is fully theme-controlled.
 */
function pediland_customize_woocommerce_hooks(): void
{
   if (! class_exists('WooCommerce')) {
      return;
   }

   remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
   remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
   remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

   remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
   remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
}
add_action('wp', 'pediland_customize_woocommerce_hooks');

/**
 * Keep breadcrumbs hierarchical by removing pagination crumb (Page N).
 */
function pediland_strip_woocommerce_paged_breadcrumb(array $crumbs): array
{
   $paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
   if ($paged < 2 || empty($crumbs)) {
      return $crumbs;
   }

   $expected = sprintf(__('Page %d', 'woocommerce'), $paged);
   $last_index = array_key_last($crumbs);
   $last_crumb = $crumbs[$last_index] ?? null;

   if (is_array($last_crumb) && isset($last_crumb[0])) {
      $label = wp_strip_all_tags((string) $last_crumb[0]);
      if ($label === $expected) {
         unset($crumbs[$last_index]);
         $crumbs = array_values($crumbs);
      }
   }

   return $crumbs;
}
add_filter('woocommerce_get_breadcrumb', 'pediland_strip_woocommerce_paged_breadcrumb');

/**
 * Get normalized archive filter request values.
 */
function pediland_get_archive_filter_request(): array
{
   $read = static function (string $key): string {
      if (! isset($_GET[$key])) {
         return '';
      }

      return sanitize_text_field(wp_unslash((string) $_GET[$key]));
   };

   $to_float_or_null = static function (string $value): ?float {
      if ($value === '') {
         return null;
      }

      return is_numeric($value) ? (float) $value : null;
   };

   $read_alias = static function (array $keys) use ($read): string {
      foreach ($keys as $key) {
         $value = $read($key);
         if ($value !== '') {
            return $value;
         }
      }

      return '';
   };

   $filters = [
      'color'      => $read_alias(['pf_color', 'filter_color']),
      'cut'        => $read_alias(['pf_cut', 'filter_cut']),
      'matrix'     => $read_alias(['pf_matrix', 'filter_matrix']),
      'shape'      => $read_alias(['pf_shape', 'filter_shape']),
      'min_price'  => $to_float_or_null($read_alias(['pf_min_price', 'min_price'])),
      'max_price'  => $to_float_or_null($read_alias(['pf_max_price', 'max_price'])),
      'min_width'  => $to_float_or_null($read_alias(['pf_min_width', 'min_width'])),
      'max_width'  => $to_float_or_null($read_alias(['pf_max_width', 'max_width'])),
      'min_height' => $to_float_or_null($read_alias(['pf_min_height', 'min_height'])),
      'max_height' => $to_float_or_null($read_alias(['pf_max_height', 'max_height'])),
      'price_active' => $read_alias(['pf_price_active', 'price_active']) === '1',
      'width_active' => $read_alias(['pf_width_active', 'width_active']) === '1',
      'height_active' => $read_alias(['pf_height_active', 'height_active']) === '1',
      'available'  => $read_alias(['pf_available', 'available']) === '1',
   ];

   foreach (pediland_get_archive_range_filter_configs() as $range_key => $config) {
      $active_key = $config['request_active'];
      $active_param_present = isset($_GET[$config['input_active']]) || isset($_GET[$config['request_active']]);
      $legacy_requested = $read_alias([$config['input_min'], $config['request_min']]) !== '' || $read_alias([$config['input_max'], $config['request_max']]) !== '';

      // Backward compatibility for old URLs that contain min/max but no explicit active flag.
      // For current form submissions, active flag is always present and authoritative.
      if (! $active_param_present && ! $filters[$active_key] && $legacy_requested) {
         $filters[$active_key] = true;
      }
   }

   foreach (['price', 'width', 'height'] as $range_key) {
      $min_key = 'min_' . $range_key;
      $max_key = 'max_' . $range_key;
      $active_key = $range_key . '_active';
      if (! $filters[$active_key]) {
         $filters[$min_key] = null;
         $filters[$max_key] = null;
         continue;
      }

      if (null !== $filters[$min_key] && null !== $filters[$max_key] && $filters[$min_key] > $filters[$max_key]) {
         [$filters[$min_key], $filters[$max_key]] = [$filters[$max_key], $filters[$min_key]];
      }

      if (null === $filters[$min_key] && null === $filters[$max_key]) {
         $filters[$active_key] = false;
      }
   }

   return $filters;
}

/**
 * Resolve real taxonomies used for archive filters.
 */
function pediland_get_archive_filter_taxonomies(): array
{
   $product_taxonomies = get_object_taxonomies('product');
   if (empty($product_taxonomies)) {
      return [
         'color'  => '',
         'cut'    => '',
         'matrix' => '',
         'shape'  => '',
      ];
   }

   $resolve = static function (array $candidates) use ($product_taxonomies): string {
      foreach ($candidates as $taxonomy) {
         if (taxonomy_exists($taxonomy) && in_array($taxonomy, $product_taxonomies, true)) {
            return $taxonomy;
         }
      }

      foreach ($product_taxonomies as $taxonomy) {
         foreach ($candidates as $candidate) {
            if (strpos($taxonomy, $candidate) !== false) {
               return $taxonomy;
            }
         }
      }

      return '';
   };

   return [
      'color'  => $resolve(['pa_color', 'pa_colors', 'color', 'colors']),
      'cut'    => $resolve(['pa_cut', 'pa_cuts', 'cut', 'cuts']),
      'matrix' => $resolve(['pa_matrix', 'pa_matrices', 'matrix', 'matrices']),
      'shape'  => $resolve(['pa_shape', 'pa_shapes', 'shape', 'shapes']),
   ];
}

/**
 * Get base taxonomy constraints from current archive context.
 */
function pediland_get_archive_base_tax_query(): array
{
   if (! is_product_taxonomy()) {
      return [];
   }

   $term = get_queried_object();
   if (! ($term instanceof WP_Term)) {
      return [];
   }

   return [[
      'taxonomy' => $term->taxonomy,
      'field'    => 'term_id',
      'terms'    => [(int) $term->term_id],
   ]];
}

/**
 * Build taxonomy query for archive filters, optionally excluding one filter key.
 */
function pediland_build_archive_filter_tax_query(array $filters, array $tax_map, string $exclude_filter = '', bool $include_archive_context = true): array
{
   $tax_query = $include_archive_context ? pediland_get_archive_base_tax_query() : [];
   $product_taxonomies = get_object_taxonomies('product');
   $resolve_taxonomy_for_slug = static function (string $preferred_taxonomy, string $term_slug) use ($product_taxonomies): string {
      if ($preferred_taxonomy !== '' && taxonomy_exists($preferred_taxonomy)) {
         $preferred_term = get_term_by('slug', $term_slug, $preferred_taxonomy);
         if ($preferred_term instanceof WP_Term) {
            return $preferred_taxonomy;
         }
      }

      foreach ((array) $product_taxonomies as $taxonomy) {
         if (! taxonomy_exists($taxonomy)) {
            continue;
         }

         $term = get_term_by('slug', $term_slug, $taxonomy);
         if ($term instanceof WP_Term) {
            return (string) $taxonomy;
         }
      }

      return '';
   };

   foreach ($tax_map as $filter_key => $taxonomy) {
      if (($exclude_filter !== '' && $filter_key === $exclude_filter) || $filters[$filter_key] === '') {
         continue;
      }

      $term_slug = sanitize_title($filters[$filter_key]);
      if ($term_slug === '') {
         continue;
      }

      $resolved_taxonomy = $resolve_taxonomy_for_slug((string) $taxonomy, $term_slug);
      if ($resolved_taxonomy === '') {
         continue;
      }

      $tax_query[] = [
         'taxonomy' => $resolved_taxonomy,
         'field'    => 'slug',
         'terms'    => [$term_slug],
      ];
   }

   if (count($tax_query) > 1 && ! isset($tax_query['relation'])) {
      $tax_query['relation'] = 'AND';
   }

   return $tax_query;
}

/**
 * Build meta query for archive filters.
 */
function pediland_build_archive_filter_meta_query(array $filters, string $exclude_range = ''): array
{
   $meta_query = [];
   if ('price' !== $exclude_range && (! array_key_exists('price_active', $filters) || $filters['price_active'])) {
      $meta_query = pediland_add_numeric_range_meta_query($meta_query, '_price', $filters['min_price'], $filters['max_price']);
   }

   if ('width' !== $exclude_range && (! array_key_exists('width_active', $filters) || $filters['width_active'])) {
      $meta_query = pediland_add_numeric_range_meta_query($meta_query, '_width', $filters['min_width'], $filters['max_width']);
   }

   if ('height' !== $exclude_range && (! array_key_exists('height_active', $filters) || $filters['height_active'])) {
      $meta_query = pediland_add_numeric_range_meta_query($meta_query, '_height', $filters['min_height'], $filters['max_height']);
   }

   if ($filters['available']) {
      $meta_query[] = [
         'key'   => '_stock_status',
         'value' => 'instock',
      ];
   }

   if (count($meta_query) > 1 && ! isset($meta_query['relation'])) {
      $meta_query['relation'] = 'AND';
   }

   return $meta_query;
}

/**
 * Get product IDs matching active archive filters, optionally excluding one taxonomy filter.
 */
function pediland_get_archive_filtered_product_ids(array $filters, array $tax_map, string $exclude_filter = '', string $exclude_range = ''): array
{
   static $cache = [];

   $queried_object = get_queried_object();
   $cache_key = md5(wp_json_encode([
      'filters'        => $filters,
      'tax_map'        => $tax_map,
      'exclude_filter' => $exclude_filter,
      'exclude_range'  => $exclude_range,
      'archive_term'   => $queried_object instanceof WP_Term ? [$queried_object->taxonomy, $queried_object->term_id] : null,
   ]));

   if (isset($cache[$cache_key])) {
      return $cache[$cache_key];
   }

   $query_args = [
      'post_type'           => 'product',
      'post_status'         => 'publish',
      'fields'              => 'ids',
      'posts_per_page'      => -1,
      'no_found_rows'       => true,
      'ignore_sticky_posts' => true,
   ];

   $tax_query = pediland_build_archive_filter_tax_query($filters, $tax_map, $exclude_filter);
   if (! empty($tax_query)) {
      $query_args['tax_query'] = $tax_query;
   }

   $meta_query = pediland_build_archive_filter_meta_query($filters, $exclude_range);
   if (! empty($meta_query)) {
      $query_args['meta_query'] = $meta_query;
   }

   $products_query = new WP_Query($query_args);
   $product_ids = array_map('intval', (array) $products_query->posts);

   $cache[$cache_key] = $product_ids;

   return $product_ids;
}

/**
 * Check if "Available" filter is meaningful for the current result context.
 */
function pediland_is_available_filter_relevant(array $filters, array $tax_map): bool
{
   static $cache = [];

   $filters_without_available = $filters;
   $filters_without_available['available'] = false;

   $queried_object = get_queried_object();
   $cache_key = md5(wp_json_encode([
      'filters'      => $filters_without_available,
      'tax_map'      => $tax_map,
      'archive_term' => $queried_object instanceof WP_Term ? [$queried_object->taxonomy, $queried_object->term_id] : null,
   ]));

   if (isset($cache[$cache_key])) {
      return $cache[$cache_key];
   }

   $candidate_ids = pediland_get_archive_filtered_product_ids($filters_without_available, $tax_map);
   if (empty($candidate_ids)) {
      $cache[$cache_key] = false;
      return false;
   }

   foreach ($candidate_ids as $product_id) {
      $stock_status = (string) get_post_meta((int) $product_id, '_stock_status', true);
      if ($stock_status !== '' && $stock_status !== 'instock') {
         $cache[$cache_key] = true;
         return true;
      }
   }

   $cache[$cache_key] = false;
   return false;
}

/**
 * Format numeric values for slider labels.
 */
function pediland_format_archive_range_value(float $value, int $decimals = 2): string
{
   return number_format($value, $decimals, '.', '');
}

/**
 * Format numeric values for slider labels/chips with optional unit suffix.
 */
function pediland_format_archive_range_display(float $value, int $decimals = 2, string $unit = ''): string
{
   $formatted = pediland_format_archive_range_value($value, $decimals);

   if ($unit === '') {
      return $formatted;
   }

   return $formatted . ' ' . $unit;
}

/**
 * Parse numeric meta values safely (supports commas and values with unit suffixes).
 */
function pediland_parse_archive_numeric_value($raw_value): ?float
{
   if (is_int($raw_value) || is_float($raw_value)) {
      return (float) $raw_value;
   }

   if (! is_string($raw_value)) {
      return null;
   }

   $normalized = trim(str_replace(',', '.', $raw_value));
   if ($normalized === '') {
      return null;
   }

   if (is_numeric($normalized)) {
      return (float) $normalized;
   }

   if (preg_match('/-?\d+(?:\.\d+)?/', $normalized, $matches) === 1) {
      return (float) $matches[0];
   }

   return null;
}

/**
 * Shared config for numeric archive range filters.
 */
function pediland_get_archive_range_filter_configs(): array
{
   return [
      'price' => [
         'label'            => __('Price', 'pediland'),
         'meta_key'         => '_price',
         'input_min'        => 'pf_min_price',
         'input_max'        => 'pf_max_price',
         'input_active'     => 'pf_price_active',
         'request_min'      => 'min_price',
         'request_max'      => 'max_price',
         'request_active'   => 'price_active',
         'decimals'         => 0,
         'step'             => 5.0,
         'unit'             => html_entity_decode('&euro;', ENT_QUOTES, 'UTF-8'),
         'min_label'        => __('Min price', 'pediland'),
         'max_label'        => __('Max price', 'pediland'),
         'min_remove_args'  => ['pf_min_price', 'min_price'],
         'max_remove_args'  => ['pf_max_price', 'max_price'],
      ],
      'height' => [
         'label'            => __('Height', 'pediland'),
         'meta_key'         => '_height',
         'input_min'        => 'pf_min_height',
         'input_max'        => 'pf_max_height',
         'input_active'     => 'pf_height_active',
         'request_min'      => 'min_height',
         'request_max'      => 'max_height',
         'request_active'   => 'height_active',
         'decimals'         => 1,
         'step'             => 0.1,
         'unit'             => 'cm',
         'min_label'        => __('Min height', 'pediland'),
         'max_label'        => __('Max height', 'pediland'),
         'min_remove_args'  => ['pf_min_height', 'min_height'],
         'max_remove_args'  => ['pf_max_height', 'max_height'],
      ],
      'width' => [
         'label'            => __('Width', 'pediland'),
         'meta_key'         => '_width',
         'input_min'        => 'pf_min_width',
         'input_max'        => 'pf_max_width',
         'input_active'     => 'pf_width_active',
         'request_min'      => 'min_width',
         'request_max'      => 'max_width',
         'request_active'   => 'width_active',
         'decimals'         => 1,
         'step'             => 0.1,
         'unit'             => 'cm',
         'min_label'        => __('Min width', 'pediland'),
         'max_label'        => __('Max width', 'pediland'),
         'min_remove_args'  => ['pf_min_width', 'min_width'],
         'max_remove_args'  => ['pf_max_width', 'max_width'],
      ],
   ];
}

/**
 * Normalize numeric range filters so default bounds are treated as inactive.
 */
function pediland_normalize_archive_numeric_filters(array $filters, array $tax_map): array
{
   $range_configs = pediland_get_archive_range_filter_configs();
   $max_passes = max(1, count($range_configs));

   // Multi-pass normalization removes order dependency between sliders.
   for ($pass = 0; $pass < $max_passes; $pass++) {
      $changed = false;

      foreach ($range_configs as $range_key => $config) {
         $active_key = $config['request_active'];
         $min_key = $config['request_min'];
         $max_key = $config['request_max'];

         if (array_key_exists($active_key, $filters) && ! $filters[$active_key]) {
            if (null !== $filters[$min_key] || null !== $filters[$max_key]) {
               $changed = true;
            }

            $filters[$min_key] = null;
            $filters[$max_key] = null;
            continue;
         }

         $range_state = pediland_get_archive_numeric_slider_state($filters, $tax_map, $range_key, $config['meta_key']);
         if (null === $range_state) {
            if (null !== $filters[$min_key] || null !== $filters[$max_key] || (array_key_exists($active_key, $filters) && $filters[$active_key])) {
               $changed = true;
            }

            $filters[$min_key] = null;
            $filters[$max_key] = null;
            if (array_key_exists($active_key, $filters)) {
               $filters[$active_key] = false;
            }
            continue;
         }

         $step = (float) $config['step'];
         $real_bounds_min = (float) $range_state['bounds_min'];
         $real_bounds_max = (float) $range_state['bounds_max'];
         $display_bounds_min = $real_bounds_min;
         $display_bounds_max = $real_bounds_max;

         if ($step > 0) {
            $display_bounds_min = floor($real_bounds_min / $step) * $step;
            $display_bounds_max = ceil($real_bounds_max / $step) * $step;
         }

         $min_request_value = $filters[$min_key];
         $max_request_value = $filters[$max_key];
         $edge_tolerance = $step > 0 ? ($step / 2) : 0.00001;

         // Treat values at/over current edges as unbounded so ranges deactivate correctly
         // when other filters shrink the available bounds.
         $min_is_unbounded = null === $min_request_value || (float) $min_request_value <= ($display_bounds_min + $edge_tolerance);
         $max_is_unbounded = null === $max_request_value || (float) $max_request_value >= ($display_bounds_max - $edge_tolerance);

         $selected_min = $min_is_unbounded ? $display_bounds_min : (float) $min_request_value;
         $selected_max = $max_is_unbounded ? $display_bounds_max : (float) $max_request_value;

         if ($step > 0) {
            $selected_min = floor($selected_min / $step) * $step;
            $selected_max = ceil($selected_max / $step) * $step;
         }

         // Clamp to real bounds for querying (prevents empty results on synthetic rounded edges).
         $selected_min = max($real_bounds_min, min($selected_min, $real_bounds_max));
         $selected_max = max($real_bounds_min, min($selected_max, $real_bounds_max));
         if ($selected_min > $selected_max) {
            $selected_max = $selected_min;
         }

         $min_is_at_edge = $selected_min <= ($display_bounds_min + $edge_tolerance);
         $max_is_at_edge = $selected_max >= ($display_bounds_max - $edge_tolerance);
         $next_min = ($min_is_unbounded || $min_is_at_edge) ? null : $selected_min;
         $next_max = ($max_is_unbounded || $max_is_at_edge) ? null : $selected_max;
         $next_active = null !== $next_min || null !== $next_max;

         if ($filters[$min_key] !== $next_min || $filters[$max_key] !== $next_max || (array_key_exists($active_key, $filters) && $filters[$active_key] !== $next_active)) {
            $changed = true;
         }

         $filters[$min_key] = $next_min;
         $filters[$max_key] = $next_max;

         if (array_key_exists($active_key, $filters)) {
            $filters[$active_key] = $next_active;
         }
      }

      if (! $changed) {
         break;
      }
   }

   return $filters;
}

/**
 * Get min/max bounds for a numeric product meta key within a product ID set.
 */
function pediland_get_archive_numeric_bounds(array $product_ids, string $meta_key): ?array
{
   static $cache = [];

   sort($product_ids);
   $cache_key = md5($meta_key . '|' . implode(',', $product_ids));
   if (array_key_exists($cache_key, $cache)) {
      return $cache[$cache_key];
   }

   $min = null;
   $max = null;

   foreach ($product_ids as $product_id) {
      $raw_value = get_post_meta((int) $product_id, $meta_key, true);
      $parsed_value = pediland_parse_archive_numeric_value($raw_value);
      if (null === $parsed_value) {
         continue;
      }

      $value = $parsed_value;
      $min = null === $min ? $value : min($min, $value);
      $max = null === $max ? $value : max($max, $value);
   }

   if (null === $min || null === $max) {
      $cache[$cache_key] = null;
      return null;
   }

   $cache[$cache_key] = [
      'min' => $min,
      'max' => $max,
   ];

   return $cache[$cache_key];
}

/**
 * Get sorted numeric meta values for a product ID set.
 */
function pediland_get_archive_numeric_values(array $product_ids, string $meta_key): array
{
   static $cache = [];

   sort($product_ids);
   $cache_key = md5($meta_key . '|' . implode(',', $product_ids));
   if (array_key_exists($cache_key, $cache)) {
      return $cache[$cache_key];
   }

   $values = [];
   foreach ($product_ids as $product_id) {
      $raw_value = get_post_meta((int) $product_id, $meta_key, true);
      $parsed_value = pediland_parse_archive_numeric_value($raw_value);
      if (null === $parsed_value) {
         continue;
      }

      $values[] = $parsed_value;
   }

   sort($values, SORT_NUMERIC);
   $cache[$cache_key] = $values;

   return $cache[$cache_key];
}

/**
 * Resolve slider state (bounds and active values) for a numeric archive filter.
 */
function pediland_get_archive_numeric_slider_state(array $filters, array $tax_map, string $range_key, string $meta_key): ?array
{
   $candidate_ids = pediland_get_archive_filtered_product_ids($filters, $tax_map, '', $range_key);
   if (empty($candidate_ids)) {
      return null;
   }

   $bounds = pediland_get_archive_numeric_bounds($candidate_ids, $meta_key);
   if (null === $bounds) {
      return null;
   }

   $min_key = 'min_' . $range_key;
   $max_key = 'max_' . $range_key;

   $current_min = null !== $filters[$min_key] ? (float) $filters[$min_key] : (float) $bounds['min'];
   $current_max = null !== $filters[$max_key] ? (float) $filters[$max_key] : (float) $bounds['max'];

   $current_min = max((float) $bounds['min'], min($current_min, (float) $bounds['max']));
   $current_max = max((float) $bounds['min'], min($current_max, (float) $bounds['max']));
   if ($current_min > $current_max) {
      $current_max = $current_min;
   }

   return [
      'bounds_min'  => (float) $bounds['min'],
      'bounds_max'  => (float) $bounds['max'],
      'current_min' => $current_min,
      'current_max' => $current_max,
      'candidate_ids' => array_map('intval', $candidate_ids),
   ];
}

/**
 * Resolve archive filter form action URL to first page of current archive.
 */
function pediland_get_archive_filter_action_url(): string
{
   if (is_shop()) {
      $shop_url = wc_get_page_permalink('shop');
      if (! empty($shop_url)) {
         return (string) $shop_url;
      }
   }

   if (is_product_taxonomy()) {
      $term = get_queried_object();
      if ($term instanceof WP_Term) {
         $term_url = get_term_link($term);
         if (! is_wp_error($term_url) && ! empty($term_url)) {
            return (string) $term_url;
         }
      }
   }

   $url = get_pagenum_link(1);

   if (is_wp_error($url) || empty($url)) {
      $url = remove_query_arg(['paged', 'product-page']);
   }

   return (string) remove_query_arg([
      'pf_color',
      'pf_cut',
      'pf_matrix',
      'pf_shape',
      'pf_min_price',
      'pf_max_price',
      'pf_min_width',
      'pf_max_width',
      'pf_min_height',
      'pf_max_height',
      'pf_price_active',
      'pf_width_active',
      'pf_height_active',
      'pf_available',
      'filter_color',
      'filter_cut',
      'filter_matrix',
      'filter_shape',
      'min_price',
      'max_price',
      'min_width',
      'max_width',
      'min_height',
      'max_height',
      'price_active',
      'width_active',
      'height_active',
      'available',
      'paged',
      'product-page',
      'submit',
   ], (string) $url);
}

/**
 * Render horizontal filters for product archive.
 */
function pediland_render_archive_filters(): void
{
   if (! (is_shop() || is_product_taxonomy())) {
      return;
   }

   $resolved_taxonomies = pediland_get_archive_filter_taxonomies();
   $filters = pediland_normalize_archive_numeric_filters(pediland_get_archive_filter_request(), $resolved_taxonomies);
   $taxonomies = [
      'color'  => ['input' => 'pf_color', 'taxonomy' => $resolved_taxonomies['color'], 'label' => __('Color', 'pediland')],
      'shape'  => ['input' => 'pf_shape', 'taxonomy' => $resolved_taxonomies['shape'], 'label' => __('Shape', 'pediland')],
      'matrix' => ['input' => 'pf_matrix', 'taxonomy' => $resolved_taxonomies['matrix'], 'label' => __('Matrix', 'pediland')],
      'cut'    => ['input' => 'pf_cut', 'taxonomy' => $resolved_taxonomies['cut'], 'label' => __('Cut', 'pediland')],
   ];
   $exclude_query_args = [
      'pf_color',
      'pf_cut',
      'pf_matrix',
      'pf_shape',
      'pf_min_price',
      'pf_max_price',
      'pf_min_width',
      'pf_max_width',
      'pf_min_height',
      'pf_max_height',
      'pf_price_active',
      'pf_width_active',
      'pf_height_active',
      'pf_available',
      'filter_color',
      'filter_cut',
      'filter_matrix',
      'filter_shape',
      'min_price',
      'max_price',
      'min_width',
      'max_width',
      'min_height',
      'max_height',
      'price_active',
      'width_active',
      'height_active',
      'available',
      'paged',
      'product-page',
      'submit',
   ];
   $has_active_filters = pediland_has_active_archive_filters($filters);
   $available_filter_relevant = pediland_is_available_filter_relevant($filters, $resolved_taxonomies);
   if (! $available_filter_relevant) {
      $filters['available'] = false;
   }
   $action_url = pediland_get_archive_filter_action_url();
?>
   <div id="archive-filters-panel" class="archive-filters-panel" data-archive-filter-panel data-has-active-filters="<?php echo esc_attr($has_active_filters ? '1' : '0'); ?>" aria-hidden="true">
      <form class="rounded-lg border border-slate-800 bg-slate-950 p-4" method="get" action="<?php echo esc_url($action_url); ?>" data-auto-submit="1" data-form-style="manual" data-archive-filter-form data-reset-url="<?php echo esc_url($action_url); ?>">
         <div class="flex items-start gap-3 overflow-x-auto">
            <?php foreach ($taxonomies as $filter_key => $config) : ?>
               <?php
               $terms = [];
               if (! empty($config['taxonomy'])) {
                  // On active filters (or taxonomy archives), only show terms that still have matching products.
                  if ($has_active_filters || is_product_taxonomy()) {
                     $candidate_ids = pediland_get_archive_filtered_product_ids($filters, $resolved_taxonomies, $filter_key);
                     if (! empty($candidate_ids)) {
                        $terms = get_terms([
                           'taxonomy'   => $config['taxonomy'],
                           'hide_empty' => true,
                           'object_ids' => $candidate_ids,
                           'orderby'    => 'name',
                           'order'      => 'ASC',
                        ]);
                     }
                  } else {
                     $terms = get_terms([
                        'taxonomy'   => $config['taxonomy'],
                        'hide_empty' => true,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                     ]);
                  }
               }
               ?>
               <div class="w-36 shrink-0 flex flex-col">
                  <label class="<?php echo esc_attr(pediland_form_class('label-dark-compact', 'fb-form-styled')); ?>">
                     <?php echo esc_html($config['label']); ?>
                  </label>
                  <select name="<?php echo esc_attr($config['input']); ?>" class="<?php echo esc_attr(pediland_form_class('select-dark', 'mt-1')); ?>" <?php disabled(empty($config['taxonomy'])); ?>>
                     <option value=""><?php esc_html_e('All', 'pediland'); ?></option>
                     <?php if (! is_wp_error($terms)) : ?>
                        <?php foreach ($terms as $term) : ?>
                           <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($filters[$filter_key], $term->slug); ?>>
                              <?php echo esc_html($term->name); ?>
                           </option>
                        <?php endforeach; ?>
                     <?php endif; ?>
                  </select>
               </div>
            <?php endforeach; ?>

            <?php
            $range_filters = pediland_get_archive_range_filter_configs();
            ?>
            <?php foreach ($range_filters as $range_key => $range_config) : ?>
               <?php
               $range_state = pediland_get_archive_numeric_slider_state($filters, $resolved_taxonomies, $range_key, $range_config['meta_key']);
               if (null === $range_state) {
                  continue;
               }

               $step_value = (float) $range_config['step'];
               $bounds_min = (float) $range_state['bounds_min'];
               $bounds_max = (float) $range_state['bounds_max'];
               $current_min = (float) $range_state['current_min'];
               $current_max = (float) $range_state['current_max'];

               if ($step_value > 0) {
                  $bounds_min = floor($bounds_min / $step_value) * $step_value;
                  $bounds_max = ceil($bounds_max / $step_value) * $step_value;
                  $current_min = floor($current_min / $step_value) * $step_value;
                  $current_max = ceil($current_max / $step_value) * $step_value;
               }

               $current_min = max($bounds_min, min($current_min, $bounds_max));
               $current_max = max($bounds_min, min($current_max, $bounds_max));
               if ($current_min > $current_max) {
                  $current_max = $current_min;
               }

               $candidate_ids = isset($range_state['candidate_ids']) && is_array($range_state['candidate_ids']) ? $range_state['candidate_ids'] : [];
               $candidate_values = pediland_get_archive_numeric_values($candidate_ids, $range_config['meta_key']);
               $epsilon = max(0.00001, $step_value > 0 ? $step_value / 1000 : 0.00001);
               $allowed_max_for_min = $current_min;
               $allowed_min_for_max = $current_max;

               if (! empty($candidate_values)) {
                  $max_for_min = null;
                  foreach ($candidate_values as $candidate_value) {
                     if ($candidate_value <= ($current_max + $epsilon)) {
                        $max_for_min = $candidate_value;
                        continue;
                     }

                     break;
                  }

                  if (null !== $max_for_min) {
                     $allowed_max_for_min = $step_value > 0 ? floor($max_for_min / $step_value) * $step_value : $max_for_min;
                  }

                  foreach ($candidate_values as $candidate_value) {
                     if ($candidate_value + $epsilon >= $current_min) {
                        $allowed_min_for_max = $step_value > 0 ? ceil($candidate_value / $step_value) * $step_value : $candidate_value;
                        break;
                     }
                  }
               }

               $allowed_max_for_min = max($bounds_min, min($allowed_max_for_min, $bounds_max));
               $allowed_min_for_max = max($bounds_min, min($allowed_min_for_max, $bounds_max));
               if ($allowed_max_for_min < $current_min) {
                  $allowed_max_for_min = $current_min;
               }
               if ($allowed_min_for_max > $current_max) {
                  $allowed_min_for_max = $current_max;
               }

               $min_attr = number_format($bounds_min, $range_config['decimals'], '.', '');
               $max_attr = number_format($bounds_max, $range_config['decimals'], '.', '');
               $current_min_attr = number_format($current_min, $range_config['decimals'], '.', '');
               $current_max_attr = number_format($current_max, $range_config['decimals'], '.', '');
               $allowed_max_for_min_attr = number_format($allowed_max_for_min, $range_config['decimals'], '.', '');
               $allowed_min_for_max_attr = number_format($allowed_min_for_max, $range_config['decimals'], '.', '');
               $valid_steps = [];
               if (! empty($candidate_values)) {
                  foreach ($candidate_values as $candidate_value) {
                     $valid_steps[] = number_format((float) $candidate_value, $range_config['decimals'], '.', '');
                  }
               }

               $valid_steps = array_values(array_unique($valid_steps));
               usort($valid_steps, static function (string $left, string $right): int {
                  return (float) $left <=> (float) $right;
               });
               $range_values_attr = implode(',', $valid_steps);
               ?>
               <div class="min-w-40 flex-1 shrink-0 flex flex-col" data-range-filter data-range-decimals="<?php echo esc_attr((string) $range_config['decimals']); ?>" data-range-unit="<?php echo esc_attr($range_config['unit']); ?>" data-range-values="<?php echo esc_attr($range_values_attr); ?>">
                  <label class="<?php echo esc_attr(pediland_form_class('label-dark-compact', 'fb-form-styled flex items-center justify-between gap-2')); ?>">
                     <span><?php echo esc_html($range_config['label']); ?></span>
                     <?php if ($range_key === 'height') : ?>
                        <i class="ph ph-arrows-out-line-vertical text-slate-300" aria-hidden="true"></i>
                     <?php elseif ($range_key === 'width') : ?>
                        <i class="ph ph-arrows-out-line-horizontal text-slate-300" aria-hidden="true"></i>
                     <?php endif; ?>
                  </label>
                  <div class="mt-1 flex items-center justify-between text-xs text-slate-300">
                     <span data-range-min-label><?php echo esc_html(pediland_format_archive_range_display($current_min, $range_config['decimals'], $range_config['unit'])); ?></span>
                     <span data-range-max-label><?php echo esc_html(pediland_format_archive_range_display($current_max, $range_config['decimals'], $range_config['unit'])); ?></span>
                  </div>
                  <div class="pf-range-slider" data-range-slider-wrap>
                     <div class="pf-range-slider-track"></div>
                     <div class="pf-range-slider-active" data-range-active-track></div>
                     <input
                        type="range"
                        min="<?php echo esc_attr($min_attr); ?>"
                        max="<?php echo esc_attr($max_attr); ?>"
                        step="<?php echo esc_attr($range_config['step']); ?>"
                        value="<?php echo esc_attr($current_min_attr); ?>"
                        data-range-role="min"
                        data-range-limit-max="<?php echo esc_attr($allowed_max_for_min_attr); ?>"
                        class="pf-range-input"
                        aria-label="<?php echo esc_attr(sprintf(__('%s minimum', 'pediland'), $range_config['label'])); ?>" />
                     <input
                        type="range"
                        min="<?php echo esc_attr($min_attr); ?>"
                        max="<?php echo esc_attr($max_attr); ?>"
                        step="<?php echo esc_attr($range_config['step']); ?>"
                        value="<?php echo esc_attr($current_max_attr); ?>"
                        data-range-role="max"
                        data-range-limit-min="<?php echo esc_attr($allowed_min_for_max_attr); ?>"
                        class="pf-range-input"
                        aria-label="<?php echo esc_attr(sprintf(__('%s maximum', 'pediland'), $range_config['label'])); ?>" />
                  </div>
                  <input type="hidden" name="<?php echo esc_attr($range_config['input_min']); ?>" value="<?php echo esc_attr($current_min_attr); ?>" data-range-hidden="min" />
                  <input type="hidden" name="<?php echo esc_attr($range_config['input_max']); ?>" value="<?php echo esc_attr($current_max_attr); ?>" data-range-hidden="max" />
                  <input type="hidden" name="<?php echo esc_attr($range_config['input_active']); ?>" value="<?php echo esc_attr((null !== $filters[$range_config['request_min']] || null !== $filters[$range_config['request_max']]) ? '1' : '0'); ?>" data-range-hidden="active" />
               </div>
            <?php endforeach; ?>

            <?php if ($available_filter_relevant) : ?>
               <div class="flex shrink-0 self-stretch flex-col">
                  <label class="<?php echo esc_attr(pediland_form_class('label-dark-compact', 'fb-form-styled')); ?>">
                     <?php esc_html_e('In stock', 'pediland'); ?>
                  </label>
                  <div class="mt-1 flex flex-1 items-end justify-center">
                     <label class="inline-flex cursor-pointer items-center text-slate-200">
                        <input type="checkbox" name="pf_available" value="1" class="sr-only peer" <?php checked($filters['available']); ?> />
                        <div class="relative h-6 w-11 rounded-full border border-gray-600 bg-gray-700 transition-colors peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-800 peer-checked:bg-blue-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-600 after:bg-white after:transition-all after:content-[''] peer-checked:after:border-white"></div>
                     </label>
                  </div>
               </div>
            <?php endif; ?>
         </div>

         <div class="mt-4">
            <?php pediland_render_archive_filter_summary(true); ?>
         </div>

         <input type="hidden" name="paged" value="1" />
         <?php wc_query_string_form_fields(null, $exclude_query_args); ?>
      </form>
   </div>
<?php
}

/**
 * Render result count and active filter chips for archive feedback.
 */
function pediland_render_archive_filter_summary(bool $inside_filters = false): void
{
   if (! (is_shop() || is_product_taxonomy())) {
      return;
   }

   $taxonomies = pediland_get_archive_filter_taxonomies();
   $filters = pediland_normalize_archive_numeric_filters(pediland_get_archive_filter_request(), $taxonomies);
   $action_url = pediland_get_archive_filter_action_url();
   $available_filter_relevant = pediland_is_available_filter_relevant($filters, $taxonomies);
   if (! $available_filter_relevant) {
      $filters['available'] = false;
   }
   $chips = [];
   $current_query_args = [];
   foreach ($_GET as $key => $value) {
      if (is_array($value)) {
         continue;
      }

      $current_query_args[sanitize_key((string) $key)] = sanitize_text_field(wp_unslash((string) $value));
   }
   $current_url = (string) add_query_arg($current_query_args, $action_url);

   $build_remove_url = static function (array $keys) use ($current_url): string {
      $remove_keys = array_merge($keys, ['paged', 'product-page']);

      return (string) remove_query_arg($remove_keys, $current_url);
   };

   $taxonomy_remove_args = [
      'color'  => ['pf_color', 'filter_color'],
      'cut'    => ['pf_cut', 'filter_cut'],
      'matrix' => ['pf_matrix', 'filter_matrix'],
      'shape'  => ['pf_shape', 'filter_shape'],
   ];

   foreach ($taxonomies as $param => $taxonomy) {
      if (empty($taxonomy) || $filters[$param] === '') {
         continue;
      }

      $term = get_term_by('slug', $filters[$param], $taxonomy);
      if ($term && ! is_wp_error($term)) {
         $chips[] = [
            'label'      => $term->name,
            'remove_url' => $build_remove_url($taxonomy_remove_args[$param] ?? []),
         ];
      }
   }

   foreach (pediland_get_archive_range_filter_configs() as $config) {
      $min_key = $config['request_min'];
      $max_key = $config['request_max'];

      if (null !== $filters[$min_key]) {
         $chips[] = [
            'label'      => $config['min_label'] . ': ' . pediland_format_archive_range_display((float) $filters[$min_key], (int) $config['decimals'], (string) $config['unit']),
            'remove_url' => $build_remove_url($config['min_remove_args']),
         ];
      }

      if (null !== $filters[$max_key]) {
         $chips[] = [
            'label'      => $config['max_label'] . ': ' . pediland_format_archive_range_display((float) $filters[$max_key], (int) $config['decimals'], (string) $config['unit']),
            'remove_url' => $build_remove_url($config['max_remove_args']),
         ];
      }
   }

   if ($filters['available'] && $available_filter_relevant) {
      $chips[] = [
         'label'      => __('Available', 'pediland'),
         'remove_url' => $build_remove_url(['pf_available', 'available']),
      ];
   }
?>
   <div class="flex flex-wrap items-center justify-between gap-3 border-slate-800 <?php echo esc_attr($inside_filters ? 'border-t pt-4' : ' rounded-lg border bg-slate-950 px-4 py-3'); ?>">
      <div class="text-sm text-slate-200">
         <?php woocommerce_result_count(); ?>
      </div>
      <div class="flex flex-wrap items-center justify-end gap-2">
         <?php foreach ($chips as $chip) : ?>
            <a href="<?php echo esc_url($chip['remove_url']); ?>" class="inline-flex items-center gap-2 rounded-full border border-slate-700 bg-slate-900 pl-3 pr-2 h-7 text-xs font-medium text-slate-200 transition-colors hover:border-slate-600 hover:bg-slate-800">
               <span><?php echo esc_html($chip['label']); ?></span>
               <i class="ph ph-x text-slate-400" aria-hidden="true"></i>
               <span class="sr-only"><?php esc_html_e('Remove filter', 'pediland'); ?></span>
            </a>
         <?php endforeach; ?>
         <a href="<?php echo esc_url($action_url); ?>" class="<?php echo esc_attr(pediland_form_class('button-outline-size-xs')); ?>">
            <?php esc_html_e('Reset', 'pediland'); ?>
         </a>
         <?php if ($inside_filters) : ?>
            <button type="button" data-filter-close data-reset-url="<?php echo esc_url($action_url); ?>" class="<?php echo esc_attr(pediland_form_class('button-outline-size-xs')); ?>">
               <?php esc_html_e('Close', 'pediland'); ?>
            </button>
         <?php endif; ?>
      </div>
   </div>
<?php
}

/**
 * Add range comparisons to meta query.
 */
function pediland_add_numeric_range_meta_query(array $meta_query, string $key, ?float $min, ?float $max): array
{
   if (null === $min && null === $max) {
      return $meta_query;
   }

   if (null !== $min && null !== $max) {
      $meta_query[] = [
         'key'     => $key,
         'value'   => [$min, $max],
         'compare' => 'BETWEEN',
         'type'    => 'NUMERIC',
      ];
      return $meta_query;
   }

   $meta_query[] = [
      'key'     => $key,
      'value'   => null !== $min ? $min : $max,
      'compare' => null !== $min ? '>=' : '<=',
      'type'    => 'NUMERIC',
   ];

   return $meta_query;
}

/**
 * Check whether any archive filter is active.
 */
function pediland_has_active_archive_filters(array $filters): bool
{
   foreach (['color', 'cut', 'matrix', 'shape'] as $key) {
      if ($filters[$key] !== '') {
         return true;
      }
   }

   foreach (['min_price', 'max_price', 'min_width', 'max_width', 'min_height', 'max_height'] as $key) {
      if (null !== $filters[$key]) {
         return true;
      }
   }

   return $filters['available'];
}

/**
 * Detect whether query is the main WooCommerce product archive/taxonomy query.
 */
function pediland_is_product_archive_query(WP_Query $query): bool
{
   if (! $query->is_main_query()) {
      return false;
   }

   $post_type = $query->get('post_type');
   if ($post_type === 'product' || (is_array($post_type) && in_array('product', $post_type, true))) {
      return true;
   }

   if ($query->is_post_type_archive('product')) {
      return true;
   }

   $product_taxonomies = get_object_taxonomies('product');
   if (empty($product_taxonomies)) {
      return false;
   }

   $taxonomy_var = (string) $query->get('taxonomy');
   if ($taxonomy_var !== '' && in_array($taxonomy_var, $product_taxonomies, true)) {
      return true;
   }

   return $query->is_tax($product_taxonomies);
}

/**
 * Apply custom archive filters to the main product archive query.
 */
function pediland_apply_archive_filters_to_query(WP_Query $query): void
{
   if ((bool) $query->get('pediland_filters_applied')) {
      return;
   }

   $query->set('pediland_filters_applied', true);

   $tax_map = pediland_get_archive_filter_taxonomies();
   $filters = pediland_normalize_archive_numeric_filters(pediland_get_archive_filter_request(), $tax_map);
   if (! pediland_has_active_archive_filters($filters)) {
      return;
   }

   if ($filters['available'] && ! pediland_is_available_filter_relevant($filters, $tax_map)) {
      $filters['available'] = false;
   }
   $tax_query = (array) $query->get('tax_query');
   $filter_tax_query = pediland_build_archive_filter_tax_query($filters, $tax_map, '', false);
   if (! empty($filter_tax_query)) {
      foreach ($filter_tax_query as $key => $clause) {
         if ($key === 'relation') {
            continue;
         }

         $tax_query[] = $clause;
      }
   }
   if (count($tax_query) > 1 && ! isset($tax_query['relation'])) {
      $tax_query['relation'] = 'AND';
   }
   $query->set('tax_query', $tax_query);

   $meta_query = (array) $query->get('meta_query');
   $filter_meta_query = pediland_build_archive_filter_meta_query($filters);
   if (! empty($filter_meta_query)) {
      foreach ($filter_meta_query as $key => $clause) {
         if ($key === 'relation') {
            continue;
         }

         $meta_query[] = $clause;
      }
   }
   if (count($meta_query) > 1 && ! isset($meta_query['relation'])) {
      $meta_query['relation'] = 'AND';
   }
   $query->set('meta_query', $meta_query);
}

/**
 * Apply archive filters on WP main product archive query.
 */
function pediland_apply_archive_filters_to_main_query(WP_Query $query): void
{
   if (is_admin() || ! pediland_is_product_archive_query($query)) {
      return;
   }

   pediland_apply_archive_filters_to_query($query);
}
add_action('pre_get_posts', 'pediland_apply_archive_filters_to_main_query', 30);

/**
 * Apply archive filters on WooCommerce product query hook (best-practice fallback).
 */
function pediland_apply_archive_filters_to_wc_query(WP_Query $query): void
{
   if (is_admin() || ! pediland_is_product_archive_query($query)) {
      return;
   }

   pediland_apply_archive_filters_to_query($query);
}
add_action('woocommerce_product_query', 'pediland_apply_archive_filters_to_wc_query', 30);
