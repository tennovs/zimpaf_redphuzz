<?php
/**
 * @var $custom_property_image_size
 * @var $property_item_class
 * @var $property_image_class
 * @var $property_item_content_class
 */

if (!isset($property_image_class)) {
	$property_image_class = array();
}

if (!isset($property_item_content_class)) {
	$property_item_content_class = array();
}


/**
 * ere_before_loop_property hook.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
do_action( 'ere_before_loop_property' );
/**
 * ere_loop_property hook.
 *
 * @hooked loop_property - 10
 */
do_action( 'ere_loop_property', $property_item_class, $custom_property_image_size, $property_image_class , $property_item_content_class);
/**
 * ere_after_loop_property hook.
 */
do_action( 'ere_after_loop_property' );