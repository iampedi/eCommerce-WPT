<?php

/**
 * Centralized form classes and helpers.
 *
 * @package PedilandBlank
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Shared class map for form elements.
 */
function pediland_form_class_map(): array
{
    return [
        'label' => 'mb-2 block text-sm font-medium text-slate-900',
        'input' => 'block w-full rounded-lg border border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500',
        'input-compact' => 'h-10 w-full rounded-lg border border-slate-300 bg-slate-50 px-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500',
        'input-quantity' => 'h-10 w-20 rounded-lg border border-slate-300 bg-slate-50 px-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500',
        'select' => 'block w-full rounded-lg border border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500',
        'select-dark' => 'rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-slate-200 focus:border-primary focus:ring-primary',
        'textarea' => 'block w-full rounded-lg border border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500',
        'checkbox' => 'h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500',
        'radio' => 'h-4 w-4 border-slate-300 text-blue-600 focus:ring-blue-500',
        'button-primary-full' => 'w-full rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300',
        'button-dark-full' => 'w-full rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300',
        'button-outline' => 'inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 px-4 text-sm font-medium text-slate-700 hover:bg-slate-100',
        'button-dark-compact' => 'inline-flex h-10 items-center justify-center rounded-lg bg-slate-900 px-5 text-sm font-medium text-white hover:bg-slate-800',
    ];
}

/**
 * Build classes for an element key with optional extra classes.
 */
function pediland_form_class(string $key, string $extra = ''): string
{
    $map = pediland_form_class_map();
    $base = $map[$key] ?? '';
    $full = trim($base . ' ' . $extra);

    return (string) preg_replace('/\s+/', ' ', $full);
}

/**
 * Return class tokens for APIs that expect class arrays.
 */
function pediland_form_class_tokens(string $key, string $extra = ''): array
{
    $classes = pediland_form_class($key, $extra);
    if ($classes === '') {
        return [];
    }

    return array_values(array_filter(preg_split('/\s+/', $classes)));
}

/**
 * Apply unified classes to WooCommerce generated form fields.
 */
function pediland_woocommerce_form_field_args(array $args, string $key, $value): array
{
    $type = $args['type'] ?? 'text';

    if (in_array($type, ['select', 'state', 'country'], true)) {
        $args['input_class'] = array_merge($args['input_class'] ?? [], pediland_form_class_tokens('select'));
    } elseif ($type === 'textarea') {
        $args['input_class'] = array_merge($args['input_class'] ?? [], pediland_form_class_tokens('textarea'));
    } elseif ($type === 'checkbox') {
        $args['input_class'] = array_merge($args['input_class'] ?? [], pediland_form_class_tokens('checkbox'));
    } elseif ($type === 'radio') {
        $args['input_class'] = array_merge($args['input_class'] ?? [], pediland_form_class_tokens('radio'));
    } else {
        $args['input_class'] = array_merge($args['input_class'] ?? [], pediland_form_class_tokens('input'));
    }

    $args['label_class'] = array_merge($args['label_class'] ?? [], pediland_form_class_tokens('label'));

    return $args;
}
add_filter('woocommerce_form_field_args', 'pediland_woocommerce_form_field_args', 10, 3);

/**
 * Keep quantity input styling centralized.
 */
function pediland_woocommerce_quantity_input_classes(array $classes): array
{
    return array_unique(array_merge($classes, pediland_form_class_tokens('input-quantity')));
}
add_filter('woocommerce_quantity_input_classes', 'pediland_woocommerce_quantity_input_classes');
