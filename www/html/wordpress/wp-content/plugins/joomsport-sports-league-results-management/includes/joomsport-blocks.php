<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class JoomsportGutenBlocks {

    public static function init() {

        add_action( 'init', array('JoomsportGutenBlocks','gutenberg_joomsport_register_block') );
    }
  
    public static function gutenberg_joomsport_register_block() {
        wp_register_script(
            'joomsport-guten-block',
            plugins_url( 'assets/js/block.js', __FILE__ ),
            array( 'wp-blocks', 'wp-element' )
        );

        register_block_type( 'gutenberg-joomsport/joomsport_standings', array(
            'editor_script' => 'gutenberg-examples-01',
        ) );



    }
}

JoomsportGutenBlocks::init();