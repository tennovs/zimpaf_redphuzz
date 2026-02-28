<?php
/**
 * @var $gf_item_wrap
 * @var $agent_layout_style
 * @var $custom_agent_image_size
 */
/**
 * ere_before_loop_agent hook.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
do_action('ere_before_loop_agent');
/**
 * ere_loop_agent hook.
 *
 * @hooked ere_loop_agent - 10
 */
do_action('ere_loop_agent', $gf_item_wrap, $agent_layout_style, $custom_agent_image_size);
/**
 * ere_after_loop_agent hook.
 */
do_action('ere_after_loop_agent');