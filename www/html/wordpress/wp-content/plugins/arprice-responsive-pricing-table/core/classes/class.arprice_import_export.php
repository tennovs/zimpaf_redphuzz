<?php

class arpricelite_import_export {

	function __construct() {

		add_action( 'wp_ajax_arplite_import_table', array( $this, 'import_table' ) );

		add_action( 'wp_ajax_arplite_get_table_list', array( $this, 'export_table_list' ) );

		add_action( 'init', array( $this, 'arplite_export_pricing_tables' ) );
	}

	function arplite_export_pricing_tables() {

		if ( is_admin() ) {

			if ( isset( $_POST['arplite_export_tables'] ) && ( $_REQUEST['page'] = 'arplite_import_export' || $_REQUEST['page'] = 'arpricelite' ) ) {
				global $wpdb, $arpricelite_import_export,$arplite_pricingtable;

				$check_caps = $arplite_pricingtable->arplite_check_user_cap( 'arplite_import_export_pricingtables', true );

				if ( $check_caps != 'success' ) {
					$check_cap_error = json_decode( $check_caps, true );
					$error_msg       = $check_cap_error[0];
					$import_error    = 'invalid';
					if ( preg_match( '/permission/', $error_msg ) ) {
						$import_error = 'permission';
					} elseif ( preg_match( '/security/', $error_msg ) ) {
						$import_error = 'security';
					}
					echo "<input type='hidden' id='arp_import_file_error' value='" . $import_error . "' />";
					return;
				}

				$arp_db_version = get_option( 'arpricelite_version' );

				$wp_upload_dir       = wp_upload_dir();
				$upload_dir          = $wp_upload_dir['basedir'] . '/arprice-responsive-pricing-table/';
				$upload_dir_url      = $wp_upload_dir['url'];
				$upload_dir_base_url = $wp_upload_dir['baseurl'] . '/arprice-responsive-pricing-table/';
				$charset             = get_option( 'blog_charset' );

				ini_set( 'max_execution_time', 0 );

				if ( ! empty( $_REQUEST['table_to_export'] ) ) {
					$table_ids = implode( ',', $_REQUEST['table_to_export'] );

					$file_name = 'arplite_' . time();

					$filename = $file_name . '.txt';

					$sql_main = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'arplite_arprice WHERE ID in(' . $table_ids . ')' );

					$xml  = '';
					$xml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

					$xml .= "<arplite>\n";

					foreach ( $sql_main as $key => $result ) {

						$xml .= "\t<arplite_table id='" . $result->ID . "'>\n";

						$xml .= "\t\t<site_url><![CDATA[" . site_url() . "]]></site_url>\n";

						$xml .= "\t\t<arp_plugin_version><![CDATA[" . $arp_db_version . "]]></arp_plugin_version>\n";

						$xml .= "\t\t<arp_table_name><![CDATA[" . $result->table_name . "]]></arp_table_name>\n";

						$xml .= "\t\t<status><![CDATA[" . $result->status . "]]></status>\n";

						$xml .= "\t\t<is_template><![CDATA[" . $result->is_template . "]]></is_template>\n";

						$xml .= "\t\t<template_name><![CDATA[" . $result->template_name . "]]></template_name>\n";

						$xml .= "\t\t<is_animated><![CDATA[" . $result->is_animated . "]]></is_animated>\n";

						if ( $arp_db_version > '1.0' ) {
							$arp_db_version1 = '1.0';
						}

						$general_options_new = unserialize( $result->general_options );

						$arp_main_reference_template = $general_options_new['general_settings']['reference_template'];

						$arp_exp_arp_main_reference_template = explode( '_', $arp_main_reference_template );

						$arp_new_arp_main_reference_template = $arp_exp_arp_main_reference_template[1];

						if ( $result->is_template == 1 ) {

							$xml .= "\t\t<arp_template_img><![CDATA[" . ARPLITE_PRICINGTABLE_URL . '/images/arplitetemplate_' . $arp_new_arp_main_reference_template . '_v' . $arp_db_version1 . '.png' . ']]></arp_template_img>';
							$xml .= "\t\t<arp_template_img_big><![CDATA[" . ARPLITE_PRICINGTABLE_URL . '/images/arplitetemplate_' . $arp_new_arp_main_reference_template . '_v' . $arp_db_version1 . '_big.png' . ']]></arp_template_img_big>';
							$xml .= "\t\t<arp_template_img_large><![CDATA[" . ARPLITE_PRICINGTABLE_URL . '/images/arplitetemplate_' . $arp_new_arp_main_reference_template . '_' . $arp_db_version1 . '_large.png' . ']]></arp_template_img_large>';
						} else {
							$xml .= "\t\t<arp_template_img><![CDATA[" . $upload_dir_base_url . 'template_images/arplitetemplate_' . $result->ID . '.png' . ']]></arp_template_img>';
							$xml .= "\t\t<arp_template_img_big><![CDATA[" . $upload_dir_base_url . 'template_images/arplitetemplate_' . $result->ID . '_big.png' . ']]></arp_template_img_big>';
							$xml .= "\t\t<arp_template_img_large><![CDATA[" . $upload_dir_base_url . 'template_images/arplitetemplate_' . $result->ID . '_large.png' . ']]></arp_template_img_large>';
						}

						$xml .= "\t\t<options>\n";

						$xml .= "\t\t\t<general_options>";

						$arp_general_options = unserialize( $result->general_options );

						$arp_gen_opt_new = array();

						$new_general_options = $this->arprice_recursive_array_function( $arp_general_options, 'export' );

						$general_opt = serialize( $new_general_options );

						$xml .= '<![CDATA[' . $general_opt . ']]>';

						$xml .= "</general_options>\n";

						$sql = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'arplite_arprice_options WHERE table_id = %d', $result->ID ) );

						$xml .= "\t\t\t<column_options>";

						$table_opts = unserialize( $sql[0]->table_options );

						$arp_tbl_opt = array();

						$new_array = $this->arprice_recursive_array_function( $table_opts, 'export' );

						$table_opts = serialize( $new_array );

						$xml .= '<![CDATA[' . $table_opts . ']]>';

						$xml .= "</column_options>\n";

						$xml .= "\t\t</options>\n";

						$table_opt = unserialize( $sql[0]->table_options );

						foreach ( $table_opt['columns'] as $c => $res ) {
							$str = isset( $res['arp_header_shortcode'] ) ? $res['arp_header_shortcode'] : '';

							$btn_img = isset( $res['btn_img'] ) ? $res['btn_img'] : '';

							if ( $btn_img != '' ) {
								$btn_img_src   = $btn_img;
								$img_file_name = explode( '/', $btn_img_src );
								$btn_img_file  = $img_file_name[ count( $img_file_name ) - 1 ];

								$arpfileobj = new ARPLiteFileController( $btn_img, true );

								$arpfileobj->check_cap = true;
								$arpfileobj->capabilities = array( 'arplite_import_export_pricingtables' );

								$arpfileobj->check_nonce = true;
								$arpfileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
								$arpfileobj->nonce_action = 'arplite_wp_nonce';

								$arpfileobj->check_only_image = true;

								$destination = $upload_dir . 'temp_' . $btn_img_file;

								$arpfileobj->arplite_process_upload( $destination );

								if ( false != $arpfileobj ) {

									$filename_arry[] = 'temp_' . $btn_img_file;

									$button_img = 'temp_' . $file_name;

									$xml .= "\t\t<" . $c . '_btn_img>' . $btn_img_src . '</' . $c . "_btn_img>\n";
								}
							}

							if ( $str != '' ) {

								$header_img = esc_html( stristr( $str, '<img' ) );

								if ( $header_img != '' ) {
									$img_src = $arprice_import_export->getAttribute( 'src', $str );

									$img_height = $arprice_import_export->getAttribute( 'height', $header_img );

									$img_width = $arprice_import_export->getAttribute( 'width', $header_img );

									$img_class = $arprice_import_export->getAttribute( 'class', $header_img );

									$img_src    = trim( $img_src, '&quot;' );
									$img_src    = trim( $img_src, '"' );
									$img_height = trim( $img_height, '&quot;' );
									$img_height = trim( $img_height, '"' );
									$img_width  = trim( $img_width, '&quot;' );
									$img_width  = trim( $img_width, '"' );
									$img_class  = trim( $img_class, '&quot;' );
									$img_class  = trim( $img_class, '"' );

									$img_height = ( ! empty( $img_height ) ) ? $img_height : '';
									$img_width  = ( ! empty( $img_width ) ) ? $img_width : '';
									$img_class  = ( ! empty( $img_class ) ) ? $img_class : '';
									$img_src    = ( ! empty( $img_src ) ) ? $img_src : '';

									$explodefilename = explode( '/', $img_src );

									$header_img_name = $explodefilename[ count( $explodefilename ) - 1 ];

									$header_img = $header_img_name;

									if ( $header_img != '' ) {
										$newfilename1 = $header_img;

										$arpfileobj = new ARPLiteFileController( $img_src, true );

										$arpfileobj->check_cap = true;
										$arpfileobj->capabilities = array( 'arplite_import_export_pricingtables' );

										$arpfileobj->check_nonce = true;
										$arpfileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
										$arpfileobj->nonce_action = 'arplite_wp_nonce';

										$arpfileobj->check_only_image = true;

										$destination = $upload_dir . 'temp_' . $newfilename1;

										$arpfileobj->arplite_process_upload( $destination );

										if ( false != $arpfileobj ) {

											$filename_arry[] = 'temp_' . $newfilename1;

											$header_img = 'temp_' . $newfilename1;
										}
									}

									if ( file_exists( $upload_dir . 'temp_' . $newfilename1 ) ) {

										$xml .= "\t\t<" . $c . '_img>' . $img_src . '</' . $c . "_img>\n";

										$xml .= "\t\t<" . $c . '_img_width>' . $img_width . '</' . $c . "_img_width>\n";

										$xml .= "\t\t<" . $c . '_img_height>' . $img_height . '</' . $c . "_img_height>\n";

										$xml .= "\t\t<" . $c . '_img_class>' . $img_class . '</' . $c . "_img_class>\n";
									}
								}
							}
						}

						$xml .= "\t</arplite_table>\n\n";
					}

					$xml .= '</arplite>';

					$xml = base64_encode( $xml );

					header( 'Content-type: text/plain' );
					header( 'Content-Disposition: attachment; filename=' . $filename );

					ob_start();
					echo $xml;
					die;
				}
			}
		}
	}

	function Create_zip( $source, $destination, $destindir ) {
		$filename = array();
		$filename = unserialize( $source );

		$zip = new ZipArchive();
		if ( $zip->open( $destination, ZipArchive::CREATE ) === true ) {
			$i = 0;
			foreach ( $filename as $file ) {
				$zip->addFile( $destindir . $file, $file );
				$i++;
			}
			$zip->close();
		}

		foreach ( $filename as $file1 ) {
			unlink( $destindir . $file1 );
		}
	}

	function getAttribute( $att, $tag = '' ) {
		$re = '/' . $att . '=([\'])?((?(1).+?|[^\s>]+))(?(1)\1)/is';

		if ( preg_match( $re, $tag, $match ) ) {
			return urldecode( $match[2] );
		}
		return false;
	}

	function get_table_list() {
		global $wpdb;
		$table = $wpdb->prefix . 'arplite_arprice';

		$res_default_template = $wpdb->get_results( 'SELECT * FROM ' . $table . " WHERE  status = 'published' AND is_template ='1' ORDER BY ID ASC " );
		?>
		<select multiple="multiple" name="arp_table_to_export[]" id="arp_table_to_export">
			<?php
			foreach ( $res_default_template as $r ) {
				?>
				<option value="<?php echo esc_html( $r->ID ); ?>">Template ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $r->table_name; ?>&nbsp;&nbsp;&nbsp;&nbsp;[<?php echo esc_html( $r->ID ); ?>]</option>
				<?php
			}

			$res_new_template = $wpdb->get_results( 'SELECT * FROM ' . $table . " WHERE  status = 'published' AND is_template ='0' ORDER BY ID ASC " );

			foreach ( $res_new_template as $r ) {
				?>
				<option value="<?php echo esc_html( $r->ID ); ?>">Table ::&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $r->table_name; ?>&nbsp;&nbsp;&nbsp;&nbsp;[<?php echo esc_html( $r->ID ); ?>]</option>
				<?php
			}
			?>
		</select>
		<?php
	}

	function export_table_list() {
		global $arpricelite_import_export;
		$arpricelite_import_export->get_table_list();
		die();
	}

	function import_table() {
		$_SESSION['arprice_image_array'] = array();

		WP_Filesystem();

		global $wpdb, $arpricelite_images_css_version,$arplite_pricingtable;

		$sqls         = $wpdb->get_results( $wpdb->prepare( 'SELECT count(ID) AS total FROM ' . $wpdb->prefix . 'arplite_arprice WHERE is_template = %d', 0 ) );
		$total_tables = $sqls[0]->total;
		if ( isset( $sqls[0]->total ) && ( $sqls[0]->total ) >= 4 ) {
			echo 2;
			die();
		}

		$check_caps = $arplite_pricingtable->arplite_check_user_cap( 'arplite_import_export_pricingtables', true );
		if ( $check_caps != 'success' ) {
			$check_cap_error = json_decode( $check_caps, true );
			$error_msg       = $check_cap_error[0];
			if ( preg_match( '/permission/', $error_msg ) ) {
				echo 3;
				die;
			} elseif ( preg_match( '/security/', $error_msg ) ) {
				echo 4;
				die;
			} else {
				echo 4;
				die;
			}
		}

		$arpricelite_images_css_version = '2.0';
		$table                          = $wpdb->prefix . 'arplite_arprice';

		$table_opt = $wpdb->prefix . 'arplite_arprice_options';

		$file_name = sanitize_text_field( $_REQUEST['xml_file'] );

		ini_set( 'max_execution_time', 0 );

		$wp_upload_dir = wp_upload_dir();

		$output_url = $wp_upload_dir['baseurl'] . '/arprice-responsive-pricing-table/';
		$output_dir = $wp_upload_dir['basedir'] . '/arprice-responsive-pricing-table/';

		$upload_dir_path = $wp_upload_dir['basedir'] . '/arprice-responsive-pricing-table/';
		$upload_dir_url  = $wp_upload_dir['baseurl'] . '/arprice-responsive-pricing-table/';

		$xml_file_url     = $output_url . 'import/' . $file_name . '.txt';
		$xml_file_content = wp_remote_get(
			$xml_file_url,
			array(
				'sslverify' => false,
			)
		);
		$xml_content      = $xml_file_content['body'];

		$xml = base64_decode( $xml_content );
		$xml = simplexml_load_string( $xml );

		$ik = 1;

		$xml_file = $output_dir . 'import/' . $file_name . '.txt';

		if ( isset( $xml->arplite_table ) ) {

			$total_tables += count( $xml->children() );

			if ( isset( $total_tables ) && ( $total_tables ) > 4 ) {
				echo 2;
				die();
			}

			foreach ( $xml->children() as $key_main => $val_main ) {

				$attr   = $val_main->attributes();
				$old_id = $attr['id'];

				$status                 = $val_main->status;
				$is_template            = $val_main->is_template;
				$template_name          = $val_main->template_name;
				$is_animated            = $val_main->is_animated;
				$arprice_import_version = $val_main->arp_plugin_version;

				$table_name       = $val_main->arp_table_name;
				$arp_template_css = $val_main->arp_template_css;

				$arp_template_img       = $val_main->arp_template_img;
				$arp_template_img_big   = $val_main->arp_template_img_big;
				$arp_template_img_large = $val_main->arp_template_img_large;

				$date = current_time( 'mysql' );
				foreach ( $val_main->options->children() as $key => $val ) {
					if ( $key == 'general_options' ) {
						$general_options = (string) $val;

						$general_options_new = maybe_unserialize( $general_options );

						if ( isset( $general_options_new['column_animation'] ) ) {
							echo 0;
							die();
							return;
						}
						if ( isset( $general_options_new['tooltip_settings'] ) ) {
							echo 0;
							die();
							return;
						}
						$arp_main_reference_template = $general_options_new['general_settings']['reference_template'];

						$reference_template = $general_options_new['general_settings']['reference_template'];

						$general_options_new = $this->arprice_recursive_array_function( $general_options_new, 'import' );

						$general_options = serialize( $general_options_new );
					} elseif ( $key == 'column_options' ) {

						$column_options = (string) $val;

						$column_opts = unserialize( $column_options );

						$column_opts = $this->arprice_recursive_array_function( $column_opts, 'import' );

						foreach ( $column_opts['columns'] as $c => $columns ) {

							/* -- Caption Column Header Title -- */
							if ( isset( $columns['html_content'] ) ) {
								$html_content                                 = $this->arpricelite_copy_image_from_content( $columns['html_content'] );
                                $html_content                                = $this->update_fa_font_class( $html_content );
								$column_opts['columns'][ $c ]['html_content'] = $html_content;
							}

							/* -- Other Column Header Title -- */
							if ( isset( $columns['package_title'] ) ) {
								$header_content                                = $this->arpricelite_copy_image_from_content( $columns['package_title'] );
                                $header_content                                = $this->update_fa_font_class( $header_content );
								$column_opts['columns'][ $c ]['package_title'] = $header_content;
							}

							/* -- Other Column Price Content -- */
							if ( isset( $columns['price_text'] ) ) {
								$price_text                                 = $this->arpricelite_copy_image_from_content( $columns['price_text'] );
                                $price_text                                 = $this->update_fa_font_class( $price_text );
								$column_opts['columns'][ $c ]['price_text'] = $price_text;
							}

							/* -- Other Column Header Shortcode -- */
							if ( isset( $columns['arp_header_shortcode'] ) ) {
								$arp_header_shortcode                                 = $this->arpricelite_copy_image_from_content( $columns['arp_header_shortcode'] );
                                $arp_header_shortcode                                 = $this->update_fa_font_class( $arp_header_shortcode );
								$column_opts['columns'][ $c ]['arp_header_shortcode'] = $arp_header_shortcode;
							}

							/* -- Other Column Column Description -- */
							if ( isset( $columns['column_description'] ) ) {
								$column_description                                 = $this->arpricelite_copy_image_from_content( $columns['column_description'] );
                                $column_description                                 = $this->update_fa_font_class( $column_description );
								$column_opts['columns'][ $c ]['column_description'] = $column_description;
							}

							/* All Columns Row Changes */
							if ( is_array( $columns['rows'] ) && count( $columns['rows'] ) > 0 ) {
								foreach ( $columns['rows'] as $r => $row ) {
									$row_description = $this->arpricelite_copy_image_from_content( $row['row_description'] );
                                    $row_description = $this->update_fa_font_class( $row_description );
									$column_opts['columns'][ $c ]['rows'][ $r ]['row_description'] = $row_description;
								}
							}

							/* Footer Content */
							$footer_content                                 = $this->arpricelite_copy_image_from_content( $columns['footer_content'] );
                            $footer_content                                 = $this->update_fa_font_class( $footer_content );
							$column_opts['columns'][ $c ]['footer_content'] = $footer_content;

							/* Button Text */
							$button_text                                 = $this->arpricelite_copy_image_from_content( $columns['button_text'] );
                            $button_text                                 = $this->update_fa_font_class( $button_text );
							$column_opts['columns'][ $c ]['button_text'] = $button_text;

							$btn_img = $c . '_btn_img';

							if ( $val_main->$btn_img != '' ) {
								$btn_image  = $c . '_btn_img';
								$button_img = $val_main->$btn_image;
								$image_name = explode( '/', $button_img );
								$image_nm   = $image_name[ count( $image_name ) - 1 ];
								$image_name = 'arp_' . time() . '_' . $image_nm;

								$base_url = trim( $button_img );
								$new_path = $upload_dir_path . $image_name;
								$new_url  = $upload_dir_url . $image_name;
								if ( array_key_exists( $base_url, $_SESSION['arprice_image_array'] ) ) {
									$new_url = $_SESSION['arprice_image_array'][ $base_url ];
								} else {
									
									$arpfileobj = new ARPLiteFileController( $base_url, true );

									$arpfileobj->check_cap = true;
									$arpfileobj->capabilities = array( 'arplite_import_export_pricingtables' );

									$arpfileobj->check_nonce = true;
									$arpfileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
									$arpfileobj->nonce_action = 'arplite_wp_nonce';

									$arpfileobj->check_only_image = true;

									$destination = $new_path;

									$arpfileobj->arplite_process_upload( $destination );

									$_SESSION['arprice_image_array'][ $base_url ] = $new_url;
								}

								$column_opts['columns'][ $c ]['btn_img'] = $new_url;
							}
						}

						$column_options = serialize( $column_opts );
					}
				}

				$table_name      = (string) $table_name;
				$is_animated     = (string) $is_animated;
				$status          = (string) $status;

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $table . ' (table_name,general_options,is_template,is_animated,status,create_date,arp_last_updated_date) VALUES (%s,%s,%s,%s,%s,%s,%s)', $table_name, $general_options, 0, $is_animated, $status, $date, $date ) );

				$new_id = $wpdb->insert_id;

				$ref_id = str_replace( 'arplitetemplate_', '', $reference_template );

				if ( $ref_id >= 20 ) {
					$ref_id             = $ref_id - 3;
					$reference_template = 'arplitetemplate_' . $ref_id;
				}

				$file = ARPLITE_PRICINGTABLE_DIR . '/css/templates/' . $reference_template . '_v' . $arpricelite_images_css_version . '.css';

				$file_url     = ARPLITE_PRICINGTABLE_URL . '/css/templates/' . $reference_template . '_v' . $arpricelite_images_css_version . '.css';
				$file_content = wp_remote_get(
					$file_url,
					array(
						'sslverify' => false,
					)
				);
				$content      = $file_content['body'];

				$css_content = preg_replace( '/arplitetemplate_([\d]+)/', 'arplitetemplate_' . $new_id, $content );

				$css_content = str_replace( '../../images', ARPLITE_PRICINGTABLE_IMAGES_URL, $css_content );

				$css_file_name = 'arplitetemplate_' . $new_id . '.css';

				$template_img_name       = 'arplitetemplate_' . $new_id . '.png';
				$template_img_big_name   = 'arplitetemplate_' . $new_id . '_big.png';
				$template_img_large_name = 'arplitetemplate_' . $new_id . '_large.png';

				$arpfileobj = new ARPLiteFileController( $arp_template_img, true );

				$arpfileobj->check_cap = true;
				$arpfileobj->capabilities = array( 'arplite_import_export_pricingtables' );

				$arpfileobj->check_nonce = true;
				$arpfileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
				$arpfileobj->nonce_action = 'arplite_wp_nonce';

				$arpfileobj->check_only_image = true;

				$destination = $upload_dir_path . 'template_images/' . $template_img_name;

				$arpfileobj->arplite_process_upload( $destination );

				if ( false == $arpfileobj ) {
					$arpfileobj = new ARPLiteFileController( ARPLITE_PRICINGTABLE_DIR . '/images/' . $arp_main_reference_template . '.png', true );

					$arpfileobj->check_cap = true;
					$arpfileobj->capabilities = array( 'arplite_import_export_pricingtables' );

					$arpfileobj->check_nonce = true;
					$arpfileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
					$arpfileobj->nonce_action = 'arplite_wp_nonce';

					$arpfileobj->check_only_image = true;

					$destination = $upload_dir_path . 'template_images/' . $template_img_name;

					$arpfileobj->arplite_process_upload( $destination );
				}

				$arpfileobj = new ARPLiteFileController( $arp_template_img_big, true );

				$arpfileobj->check_cap = true;
				$arpfileobj->capabilities = array( 'arplite_import_export_pricingtables' );

				$arpfileobj->check_nonce = true;
				$arpfileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
				$arpfileobj->nonce_action = 'arplite_wp_nonce';

				$arpfileobj->check_only_image = true;

				$destination = $upload_dir_path . 'template_images/' . $template_img_big_name;

				$arpfileobj->arplite_process_upload( $destination );

				if ( false == $arpfileobj ) {
					$arpfileobj = new ARPLiteFileController( ARPLITE_PRICINGTABLE_DIR . '/images/' . $arp_main_reference_template . '_big.png', true );

					$arpfileobj->check_cap = true;
					$arpfileobj->capabilities = array( 'arplite_import_export_pricingtables' );

					$arpfileobj->check_nonce = true;
					$arpfileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
					$arpfileobj->nonce_action = 'arplite_wp_nonce';

					$arpfileobj->check_only_image = true;

					$destination = $upload_dir_path . 'template_images/' . $template_img_big_name;

					$arpfileobj->arplite_process_upload( $destination );
				}

				$arpfileobj = new ARPLiteFileController( $arp_template_img_large, true );

				$arpfileobj->check_cap = true;
				$arpfileobj->capabilities = array( 'arplite_import_export_pricingtables' );

				$arpfileobj->check_nonce = true;
				$arpfileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
				$arpfileobj->nonce_action = 'arplite_wp_nonce';

				$arpfileobj->check_only_image = true;

				$destination = $upload_dir_path . 'template_images/' . $template_img_large_name;

				$arpfileobj->arplite_process_upload( $destination );

				if ( false == $arpfileobj ) {
					$arpfileobj = new ARPLiteFileController( ARPLITE_PRICINGTABLE_DIR . '/images/' . $arp_main_reference_template . '_large.png', true );

					$arpfileobj->check_cap = true;
					$arpfileobj->capabilities = array( 'arplite_import_export_pricingtables' );

					$arpfileobj->check_nonce = true;
					$arpfileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
					$arpfileobj->nonce_action = 'arplite_wp_nonce';

					$arpfileobj->check_only_image = true;

					$destination = $upload_dir_path . 'template_images/' . $template_img_large_name;

					$arpfileobj->arplite_process_upload( $destination );
				}

				global $wp_filesystem;

				$wp_filesystem->put_contents( ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/' . $css_file_name, $css_content, 0777 );

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $table_opt . ' (table_id,table_options) VALUES (%d,%s)', $new_id, $column_options ) );

			}
			if( file_exists( $wp_upload_dir['basedir'] . '/arprice-responsive-pricing-table/import/' . $file_name . '.zip' ) ){
				unlink( $wp_upload_dir['basedir'] . '/arprice-responsive-pricing-table/import/' . $file_name . '.zip' );
			}

			echo 1;
		} elseif ( ! isset( $xml->arplite_table ) ) {
			echo 0;
		}
		unset( $_SESSION['arprice_image_array'] );
		die();
	}

	function arprice_recursive_array_function( $array = array(), $type = 'export' ) {

		$temp = array();
		if ( is_array( $array ) and ! empty( $array ) ) {
			foreach ( $array as $key => $value ) {
				if ( is_array( $value ) ) {
					$temp[ $key ] = $this->arprice_recursive_array_function( $value, $type );
				} else {
					if ( $type == 'export' ) {
						$temp[ $key ] = str_replace( '&lt;br /&gt;', '[ENTERKEY]', str_replace( '&lt;br/&gt;', '[ENTERKEY]', str_replace( '&lt;br&gt;', '[ENTERKEY]', str_replace( '<br />', '[ENTERKEY]', str_replace( '<br/>', '[ENTERKEY]', str_replace( '<br>', '[ENTERKEY]', trim( preg_replace( '/\s\s+/', ' ', $value ) ) ) ) ) ) ) );
						$temp[ $key ] = str_replace( '&amp;', '[AND]', $temp[ $key ] );
					} elseif ( $type == 'import' ) {
						$temp[ $key ] = str_replace( '[ENTERKEY]', '<br>', $value );
						$temp[ $key ] = str_replace( '[AND]', '&amp;', $temp[ $key ] );
					}
				}
			}
		}

		return $temp;
	}

	function arpricelite_copy_image_from_content( $content = '' ){
		if( empty( $content ) ){
			return $content;
		}

		$pattern = "#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#";
		$matches = array();
		preg_match_all( $pattern, $content, $matches );

		if( !empty( $matches[0] ) && is_array( $matches[0] ) && count( $matches[0] ) > 0 ){
			
			$wp_upload_dir = wp_upload_dir();

			$upload_dir_path = $wp_upload_dir['basedir'] . '/arprice-responsive-pricing-table/';
			$upload_dir_url  = $wp_upload_dir['baseurl'] . '/arprice-responsive-pricing-table/';

			if ( is_ssl() ) {
				$upload_dir_url = str_replace( 'http://', 'https://', $upload_dir_url );
			}

			foreach( $matches[0] as $key => $link ){
				$link_source = trim( $link, '"' );
				$file_name = basename( $link_source );

				$image_name = 'arp_' . time() . '_' . $file_name;

				$base_url = trim( $link_source );
				$new_path = $upload_dir_path . $image_name;
				$new_url  = $upload_dir_url . $image_name;

				if( !empty( $_SESSION['arprice_image_array'] ) && in_array( $base_url, $_SESSION['arprice_image_array'] ) ){
					$new_url   = $_SESSION['arprice_image_array'][ $base_url ];
					$nlinkpart = explode( '/', $new_url );
					$nlastpart = end( $nlinkpart );
					$new_path  = $upload_dir_path . $nlastpart;
				} else {
					$arpfileobj = new ARPLiteFileController( $link_source, true );

					$arpfileobj->check_cap = true;
					$arpfileobj->capabilities = array( 'arplite_import_export_pricingtables' );

					$arpfileobj->check_nonce = true;
					$arpfileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
					$arpfileobj->nonce_action = 'arplite_wp_nonce';

					$arpfileobj->check_only_image = true;

					$arpfileobj->arplite_process_upload( $new_path );

					if ( file_exists( $new_path ) ) {
						$newlink = $new_url;
						$content = str_replace( $link, $newlink, $content );
					} else {
						$content = $content;
					}
				}
			}
		}
		return $content;

	}

	function update_fa_font_class( $value ) {
		$fa_font_arr = array();
		if ( file_exists( ARPLITE_PRICINGTABLE_CLASSES_DIR . '/arpricelite_font_awesome_array_new.php' ) ) {
			include_once ARPLITE_PRICINGTABLE_CLASSES_DIR . '/arpricelite_font_awesome_array_new.php';
			$fa_font_arr = arprice_font_awesome_font_array_new();
		}

		if ( preg_match( '/(arp_fa_icon_(\d+){1,})/', $value ) ) {
			$value = preg_replace( '/(arp_fa_icon_(\d+){1,})/', ' ', $value );
		}

		if ( preg_match( '/\s{2,}/', $value ) ) {
			$value = preg_replace( '/\s{2,}/', ' ', $value );
		}

		$pattern              = '/"fa(\s+)fa-(.*?)"/';
		$is_matched_availabel = preg_match_all( $pattern, $value, $match_arr );

		if ( $is_matched_availabel > 0 ) {
			foreach ( $match_arr[0] as $match_val ) {
				$match_val = preg_replace( '!\s+!', ' ', $match_val );
				$exp       = explode( ' ', $match_val );

				$font_key = trim( str_replace( '"', '', $exp[0] ) . ' ' . str_replace( '"', '', $exp[1] ) );

				$font_key2 = '';

				if ( $exp[1] == 'fa-gears' ) {
					$font_key2 = trim( str_replace( '"', '', $exp[0] ) . ' ' . 'fa-cogs' );
				} elseif ( $exp[1] == 'fa-gear' ) {
					$font_key2 = trim( str_replace( '"', '', $exp[0] ) . ' ' . 'fa-cog' );
				}

				if ( isset( $fa_font_arr[ $font_key ] ) ) {
					$replace_val = $fa_font_arr[ $font_key ]['style'] . ' ' . $fa_font_arr[ $font_key ]['code'];
					$value       = str_replace( $font_key, $replace_val, $value );
				}
				if ( isset( $fa_font_arr[ $font_key2 ] ) ) {
					$replace_val = $fa_font_arr[ $font_key2 ]['style'] . ' ' . $fa_font_arr[ $font_key2 ]['code'];
					$value       = str_replace( $font_key, $replace_val, $value );
				}
			}
		} else {

			$pattern              = "/'fa(\s)fa-(.*?)'/";
			$is_matched_availabel = preg_match_all( $pattern, $value, $match_arr );

			if ( $is_matched_availabel > 0 ) {
				foreach ( $match_arr[0] as $match_val ) {
					$match_val = preg_replace( '!\s+!', ' ', $match_val );
					$exp       = explode( ' ', $match_val );
					$font_key  = trim( str_replace( "'", '', $exp[0] ) . ' ' . str_replace( "'", '', $exp[1] ) );
					$font_key2 = '';

					if ( $exp[1] == 'fa-gears' ) {
						$font_key2 = trim( str_replace( '"', '', $exp[0] ) . ' ' . 'fa-cogs' );
					} elseif ( $exp[1] == 'fa-gear' ) {
						$font_key2 = trim( str_replace( '"', '', $exp[0] ) . ' ' . 'fa-cog' );
					}

					if ( isset( $fa_font_arr[ $font_key ] ) ) {
						$replace_val = $fa_font_arr[ $font_key ]['style'] . ' ' . $fa_font_arr[ $font_key ]['code'];
						$value       = str_replace( $font_key, $replace_val, $value );
					}
					if ( isset( $fa_font_arr[ $font_key2 ] ) ) {
						$replace_val = $fa_font_arr[ $font_key2 ]['style'] . ' ' . $fa_font_arr[ $font_key2 ]['code'];
						$value       = str_replace( $font_key, $replace_val, $value );
					}
				}
			}
		}

		return $this->arplite_update_font_awesome( $value );
	}

	function arplite_update_font_awesome( $convert_string ) {

        $convert_string = preg_replace( '/arpfa(\s+)arpfa\-/','fas$1fa-', $convert_string );

        return $convert_string;
    }
}
?>
