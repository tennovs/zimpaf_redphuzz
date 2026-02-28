<?php 

 if( ! defined('ABSPATH' ) ){
	exit;
}

function wpfm_cpt_register_post_type() {
	
	$custom_post_types_meta = wpfm_array_cpt_meta();
	
	foreach( $custom_post_types_meta as $type => $cpt ) {
		
		register_post_type ( $type, $cpt );
		
	}
	
}


function wpfm_user_file_model( $file ) { ?>
	<?php if ( $file->node_type == 'dir' ){ ?>
		<a class='view-icon button button-primary' href="#dir_modal_<?php echo esc_attr($file->id); ?>" data-target="dir_modal_<?php echo esc_attr($file->id); ?>" id="dir<?php echo esc_attr($file->id); ?>">
			<span class="detail-icon dashicons dashicons-search"></span>
		</a>
		<?php //wpfm_dir_model($file); ?>
	<?php
	 }else { ?>
		
		<a class='view-icon btn button button-primary' href="#file_detail_box_<?php echo esc_attr($file->id); ?>" data-target="file_detail_box_<?php echo esc_attr($file->id); ?>" id="modal<?php echo esc_attr($file->id); ?>">
			<span class="detail-icon dashicons dashicons-visibility"></span>
		</a>
	<?php //echo $file->file_detail_html; ?>
	<?php  } ?>
	
<?php  
}

function wpfm_dir_model($file){ ?>

	
	<div id="dir_modal_<?php echo esc_attr($file->id); ?>">
	    <div class="close-modal-btn close-dir_modal_<?php echo esc_attr($file->id); ?>">
	    	<img class="close-btn" src="<?php echo WPFM_URL ?>/images/closebt.svg">
	    </div>
	        <div class="wpfm-modal-content">
	        	<h2 class="modal-title"><?php _e($file->title, "wpfm"); ?></h2>
	            <hr>
				<?php 
					if (!empty($file->children)) { 
						
						wpfm_get_dir_detail($file->children); 
					} else {
						echo 'No Nested found';
					}
				?>
			</div>
	    </div>
	</div>
<?php } 

function wpfm_get_dir_detail($files) { ?>

	<div class="row">
	<?php 
	foreach ($files as $file) { ?>
			<div class="col-md-2">
				<?php if ($file->node_type == 'file'){ ?>
					<a class='view-icon modal<?php echo esc_attr($file->id); ?> close-dir_modal_<?php echo esc_attr($file->file_parent); ?>' href="#file_detail_box_<?php echo esc_attr($file->id); ?>" data-target="file_detail_box_<?php echo esc_attr($file->id); ?>" data-modal_id="modal<?php echo esc_attr($file->id); ?>">
					
					<?php 
						echo wp_kses($file->thumb_image, wpfm_get_allowed_html());
						echo '<h4 class="title">'.esc_attr($file->title).'</h4>';

					 ?>
					</a>
				<?php }else { ?>
					<a class='view-icon dir<?php echo esc_attr($file->id); ?> close-dir_modal_<?php echo esc_attr($file->file_parent); ?>' href="#dir_modal_<?php echo esc_attr($file->id); ?>" data-target="dir_modal_<?php echo esc_attr($file->id); ?>" data-modal_id="dir<?php echo esc_attr($file->id); ?>">
			
					<?php 
						echo wp_kses($file->thumb_image, wpfm_get_allowed_html());
						echo '<h4 class="title">'.esc_attr($file->title).'</h4>';

					 ?>
					</a>
				<?php } ?>
			</div>
	<?php } ?>
		</div>
		<?php 
}

function wpfm_cpt_cloumns(){
	
	//@Fayaz: not properly loclized
	$columns = array(
		'cb' 		=> '<input type="checkbox" />',
		'thumb' 	=> __( 'Thumbnail' ),
		'title' 	=> __( 'Tilte' ),
		'author' 	=> __( 'Author' ),
		'downloads' => __( 'Downloads' ),
		'file_type' => __( 'Type' ),
		'location'	=> __( 'Location' ),
		'file_size' => __( 'size' ),
		'detail'	=> __('Detail'),
		'upload_date'		=> __('Date'),
	);
	
	if(!empty($file->file_meta)){
		$columns['meta'] = __("Meta");
	}
	
	if( wpfm_is_pro_installed() ){
		$columns['taxonomy-file_groups'] = __( 'File Groups' );
	}

	if( wpfm_digital_download_addon_installed() ){
		$columns['price'] = __( 'Price' );
	}

	return apply_filters( 'wpfm_cpt_cloumns', $columns);
}

function wpfm_cpt_columns_data( $column, $post_id ) {
	
	$file = new WPFM_File($post_id);
	switch( $column ) {

		case 'thumb' :

			echo wp_kses($file->thumb_image, wpfm_get_allowed_html());
			
			break;

		case 'author' :
				$post_author = get_post_meta( $post_id, 'wpfm_file_author', true );
				echo esc_attr($post_author);
			break;

		case 'downloads':
				echo esc_attr($file->total_downloads);
			break;

		case 'file_type':
			$file_type = wp_check_filetype($file->name);
			
			if ( !$file_type['ext'] ) {
				echo "Directory";	
			}else {
				echo esc_attr($file_type['ext']);
			};
			break;

		case 'location':
				echo esc_attr($file->location);
			break;
		

		case 'file_size':
				echo esc_attr($file->size);
			break;

		case 'detail':
			
			wpfm_user_file_model($file);
			echo $file->download_button;
			break;
		
		case 'upload_date':
			echo get_the_date( '', $post_id );
			break;
		case 'price':

				echo $file->price_html;
			break;
	}
}

function wpfm_cpt_columns_sorted($columns) {

	$columns['downloads'] 	= 'downloads';
	$columns['file_size'] 	= 'file_size';
	$columns['author'] 		= 'author';
	$columns['upload_date'] = 'upload_date';

	
	// var_dump($columns);
	

	return $columns;
}