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
        'label-dark' => 'mb-2 block text-sm font-medium text-white',
        'label-dark-compact' => 'text-xs font-medium uppercase tracking-wide text-slate-300',
        'input' => 'block w-full rounded-lg border border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500',
        'input-dark' => 'block w-full rounded-lg border border-slate-700 bg-slate-900 p-2.5 text-sm text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500',
        'input-compact' => 'h-10 w-full rounded-lg border border-slate-300 bg-slate-50 px-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500',
        'input-quantity' => 'h-11 w-16 rounded-lg border border-slate-700 bg-slate-950 px-3 text-sm text-slate-100 focus:border-primary focus:ring-primary/40',
        'input-dark-compact' => 'block w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500',
        'select' => 'block w-full rounded-lg border border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500',
        'select-dark' => 'block w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500',
        'textarea' => 'block w-full rounded-lg border border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500',
        'textarea-dark' => 'block w-full rounded-lg border border-slate-700 bg-slate-900 p-2.5 text-sm text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500',
        'checkbox' => 'h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500',
        'checkbox-dark' => 'h-4 w-4 rounded border-slate-700 bg-slate-900 text-blue-600 focus:ring-2 focus:ring-blue-500',
        'radio' => 'h-4 w-4 border-slate-300 text-blue-600 focus:ring-blue-500',

        'button-default' => 'inline-flex items-center justify-center rounded-lg bg-primary px-5 font-medium text-white transition-colors hover:bg-primary-deep focus:outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',

        'button-size-xs' => 'rounded-lg px-3 py-2 text-xs',
        'button-size-sm' => 'rounded-lg px-4 py-2 text-sm',
        'button-size-md' => 'rounded-lg px-5 h-11 text-[15px]',
        'button-size-lg' => 'rounded-lg px-5 py-3 text-base',

        'button-payment-primary' => 'inline-flex items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-black transition-colors hover:bg-primary-soft focus:outline-none focus:ring-4 focus:ring-primary/40 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',

        'button-payment-secondary' => 'inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-900 px-5 py-2.5 text-sm font-medium text-slate-100 transition-colors hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-700 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
        'button-outline' => 'inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-900 px-5 py-2.5 text-sm font-medium text-slate-100 transition-colors hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-700 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',



        'button-outline-size-xs' => 'inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-medium text-slate-100 transition-colors hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-700 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
        'button-outline-size-sm' => 'inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-900 px-4 py-2 text-sm font-medium text-slate-100 transition-colors hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-700 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
        'button-outline-size-md' => 'inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-900 px-5 py-2.5 text-sm font-medium text-slate-100 transition-colors hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-700 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
        'button-outline-size-lg' => 'inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-900 px-5 py-3 text-base font-medium text-slate-100 transition-colors hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-700 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
        'button-with-icon-size-xs' => 'inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs',
        'button-with-icon-size-sm' => 'inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm',
        'button-with-icon-size-md' => 'inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm',
        'button-with-icon-size-lg' => 'inline-flex items-center gap-2.5 rounded-lg px-5 py-3 text-base',
        'button-icon' => 'inline-flex items-center justify-center rounded-lg text-slate-300 transition-colors hover:bg-slate-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-slate-700 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
        'button-icon-size-xs' => 'h-7 w-7',
        'button-icon-size-sm' => 'h-9 w-9',
        'button-icon-size-md' => 'h-10 w-10',
        'button-icon-size-lg' => 'h-12 w-12',
        'button-loader' => 'me-2 inline h-4 w-4 animate-spin text-current opacity-70',
        'button-disabled' => 'disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
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
        $args['input_class'] = array_merge($args['input_class'] ?? [], pediland_form_class_tokens('select-dark'));
    } elseif ($type === 'textarea') {
        $args['input_class'] = array_merge($args['input_class'] ?? [], pediland_form_class_tokens('textarea-dark'));
    } elseif ($type === 'checkbox') {
        $args['input_class'] = array_merge($args['input_class'] ?? [], pediland_form_class_tokens('checkbox'));
    } elseif ($type === 'radio') {
        $args['input_class'] = array_merge($args['input_class'] ?? [], pediland_form_class_tokens('radio'));
    } else {
        $args['input_class'] = array_merge($args['input_class'] ?? [], pediland_form_class_tokens('input-dark'));
    }

    $args['label_class'] = array_merge($args['label_class'] ?? [], pediland_form_class_tokens('label-dark'));

    return $args;
}
add_filter('woocommerce_form_field_args', 'pediland_woocommerce_form_field_args', 10, 3);
