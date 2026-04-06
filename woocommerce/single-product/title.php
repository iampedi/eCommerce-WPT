<?php

/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

global $product;

$title = get_the_title();
if ($product instanceof WC_Product && function_exists('pediland_get_composite_product_name')) {
	$composite_title = pediland_get_composite_product_name($product);
	if ($composite_title !== '') {
		$title = $composite_title;
	}
}
?>
<h1 class="_product_title text-3xl text-slate-100"><?php echo esc_html($title); ?></h1>