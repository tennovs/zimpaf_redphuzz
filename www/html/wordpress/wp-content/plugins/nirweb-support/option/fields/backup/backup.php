<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSFTICKET_Field_backup' ) ) {
  class CSFTICKET_Field_backup extends CSFTICKET_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unique = $this->unique;
      $nonce  = wp_create_nonce( 'CSFTICKET_backup_nonce' );
      $export = add_query_arg( array( 'action' => 'CSFTICKET-export', 'unique' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

      echo $this->field_before();

      echo '<textarea name="CSFTICKET_import_data" class="CSFTICKET-import-data"></textarea>';
      echo '<button type="submit" class="button button-primary CSFTICKET-confirm CSFTICKET-import" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Import', 'nirweb-support' ) .'</button>';
      echo '<small>( '. esc_html__( 'copy-paste your backup string here', 'nirweb-support' ).' )</small>';

      echo '<hr />';
      echo '<textarea readonly="readonly" class="CSFTICKET-export-data">'. esc_attr( json_encode( get_option( $unique ) ) ) .'</textarea>';
      echo '<a href="'. esc_url( $export ) .'" class="button button-primary CSFTICKET-export" target="_blank">'. esc_html__( 'Export and Download Backup', 'nirweb-support' ) .'</a>';

      echo '<hr />';
      echo '<button type="submit" name="CSFTICKET_transient[reset]" value="reset" class="button CSFTICKET-warning-primary CSFTICKET-confirm CSFTICKET-reset" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Reset All', 'nirweb-support' ) .'</button>';
      echo '<small class="CSFTICKET-text-error">'. esc_html__( 'Please be sure for reset all of options.', 'nirweb-support' ) .'</small>';

      echo $this->field_after();

    }

  }
}
