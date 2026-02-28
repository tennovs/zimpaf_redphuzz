<?php

class arpriceliteelementcontroller {

    function __construct() {
        add_action( 'plugins_loaded', array( $this, 'arprice_element_widget' ) );
    }

    function arprice_element_widget(){
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }
        require_once(ARPLITE_PRICINGTABLE_CLASSES_DIR . '/arplite_elementor_widget/arpricelite_elementor_element.php');
   }

}

?>