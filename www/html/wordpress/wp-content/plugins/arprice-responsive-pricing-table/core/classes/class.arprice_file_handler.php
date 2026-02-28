<?php

class ARPLiteFilecontroller{

	var $file;
	var $check_cap;
	var $capabilities;
	var $check_nonce;
	var $nonce_data;
	var $nonce_action;
	var $check_only_image;
	var $check_specific_ext;
	var $allowed_ext;
	var $invalid_ext;
	var $compression_ext;
	var $error_message;
	var $default_error_msg;
	var $import;
	var $image_exts;
	var $file_size;

	function __construct( $file, $import ){

		if( empty( $file ) && ! $import ){
			$this->error_message = esc_html__( "Please select a file to process", "arprice-responsive-pricing-table" );
			return false;
		}

		$this->file = $file;

		$this->import = $import;

		if( !$import ){
			$this->file_size = $file['size'];
		}

		$this->invalid_ext = apply_filters( 'arplite_restricted_file_ext', array( 'php', 'php3', 'php4', 'php5', 'py', 'pl', 'jsp', 'asp', 'cgi', 'ext'  ) );

		$this->compression_ext = apply_filters( 'arplite_exclude_file_check_ext', array( 'tar', 'zip', 'gz', 'gzip', 'rar', '7z' ) );

		$mimes = get_allowed_mime_types();

		$type_img = array();

		foreach( $mimes as $ext => $type ){
			if( preg_match( '/(image\/)/', $type ) ){
				if( preg_match( '/(\|)/', $ext ) ){
					$type_imgs = explode( '|', $ext );
					$type_img = array_merge( $type_img, $type_imgs );
				} else {
					$type_img[] = $ext;
				}
			}
		}

		$this->image_exts = $type_img;

	}

	function arplite_process_upload( $destination ){

		/* Capabilities Check */
		if( $this->check_cap ){
			$capabilities = $this->capabilities;

			if( !empty( $capabilities ) ){
				if( is_array( $capabilities ) ){
					$isFailed = false;
					foreach( $capabilities as $caps ){
						if( !current_user_can( $caps ) ){
							$isFailed = true;
							$this->error_message = esc_html__( "Sorry, you don't have permission to perform this action.", "arprice-responsive-pricing-table" );
							break;
						}
					}

					if( $isFailed ){
						return false;
					}
				} else {
					if( !current_user_can( $capabilities ) ){
						$this->error_message = esc_html__( "Sorry, you don't have permission to perform this action.", "arprice-responsive-pricing-table" );
					}
				}
			} else {
				$this->error_message = esc_html__( "Sorry, you don't have permission to perform this action.", "arprice-responsive-pricing-table" );
				return false;
			}
		}

		/* Nonce Check */
		if( $this->check_nonce ){
			if( empty( $this->nonce_data ) || empty( $this->nonce_action ) ){
				$this->error_message = esc_html__( "Sorry, Your request could not be processed due to security reasons.", "arprice-responsive-pricing-table" );
				return false;
			}

			if( !wp_verify_nonce( $this->nonce_data, $this->nonce_action ) ){
				$this->error_message = esc_html__( "Sorry, Your request could not be processed due to security reasons.", "arprice-responsive-pricing-table" );
				return false;
			}
		}

		if( $this->import ){
			$ext_data = explode( '.', $this->file );
		} else {
			$ext_data = explode( '.', $this->file['name'] );
		}

		$ext = end( $ext_data );
		$ext = strtolower($ext);

		if( in_array( $ext, $this->invalid_ext ) ){
			$this->error_message = esc_html__( "The file could not be uploaded due to security reasons.", "arprice-responsive-pricing-table" );
			return false;
		}

		if( $this->check_only_image ){

			if( !$this->import && !preg_match( '/(image\/)/', $this->file['type'] ) ){
				$this->error_message = esc_html__( "Please select image file only.", "arprice-responsive-pricing-table" );
				if( !empty( $this->default_error_msg ) ){
					$this->error_message = $this->default_error_msg;
				}
				return false;
			}

			if( $this->import ){
				if( ! in_array( $ext, $this->image_exts ) ){
					$this->error_message = esc_html__( "Please select image file only.", "arprice-responsive-pricing-table" );
					if( !empty( $this->default_error_msg ) ){
						$this->error_message = $this->default_error_msg;
					}
					return false;
				}
			}
		}

		if( $this->check_specific_ext ){
			if( empty( $this->allowed_ext ) ){
				$this->error_message = esc_html__( "Please set extensions to validate file.", "arprice-responsive-pricing-table" );
				return false;
			}
			if( !in_array( $ext, $this->allowed_ext ) ){
				$this->error_message = esc_html__( 'Invalid file extension. Please select valid file', 'arprice-responsive-pricing-table' );
				if( !empty( $this->default_error_msg ) ){
					$this->error_message = $this->default_error_msg;
				}

				if( !empty(  $this->field_error_msg ) ){
					$this->error_message = $this->field_error_msg;
				}

				return false;
			}
		}

		if( !function_exists('WP_Filesystem' ) ){
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        WP_Filesystem();
        global $wp_filesystem;

        if( $this->import ){
        	$file_content = $wp_filesystem->get_contents( $this->file );
        } else {
        	$file_content = $wp_filesystem->get_contents( $this->file['tmp_name'] );        	
        }

        $is_valid_file = $this->arplite_read_file( $file_content, $ext );

        if( !$is_valid_file ){
        	return false;
        }

        if( '' == $file_content || ! $wp_filesystem->put_contents( $destination, $file_content, 0777 ) ){
        	$this->error_message = esc_html__( "There is an issue while uploading a file. Please try again", "arprice-responsive-pricing-table");
        	return false;
        }


		return true;
	}

	public static function arplite_get_file_content( $path ){

		if( '' == $path || false == $path || !file_exists( $path ) ){
			return '';
		}

		WP_Filesystem();
        global $wp_filesystem;

        return $wp_filesystem->get_contents( $path );

	}

	function arplite_read_file( $file_content, $ext ){

		if( '' == $file_content ){
			return true;
		}

		if( in_array( $ext, $this->compression_ext ) ){
			return true;
		}

		$file_bytes = $this->file_size;

		$file_size = number_format($file_bytes / 1048576, 2);

		if( $file_size > 10 ){
			return true;
		}

		$arf_valid_pattern = '/(\<\?(php))/';

		if( preg_match( $arf_valid_pattern, $file_content ) ){
			$this->error_message = esc_html__( 'The file could not be uploaded due to security reason as it contains malicious code', 'arprice-responsive-pricing-table' );
            return false;
        }

        return true;

	}

}