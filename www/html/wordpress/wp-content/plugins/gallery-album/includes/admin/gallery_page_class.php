<?php

class wpda_gall_gallery_page{
	
	public $notification;
	
	public $image_manipulatioan;
	
	private $uploaded_image_html='';
	
	function __construct(){
		$this->notification=array();
		$this->image_manipulatioan = new wpda_gall_image_manupulation();
	}

	/*###################### Controller function ##################*/	
	
	public function controller(){	
		$this->save_album_images_name_description();	
		$this->insert_gallery_into_database();
		$this->insert_album_into_database();
		$this->update_gallery_name_in_database();
		$this->update_album_name_in_database();
		$this->delete_album();
		$this->delete_gallery();
		$this->update_image_info();
		$this->remove_image();
		$this->remove_images();	
		$this->insert_image_from_media_library();
		$this->insert_image_from_standart_uploader();
		$this->insert_image_from_youtube_url();
		$this->image_rotate_form();
		$this->page_view();					
	}

	/*############  Function for generating notifications ################*/
		
	private function genererete_notification(){ 
		if(count($this->notification)>1){
		?>
        <div id="setting-error-settings_updated" class="<?php echo $this->notification['type'] ?> settings-error notice is-dismissible"> 
			<p><strong><?php echo $this->notification['text']; ?></strong></p>
         	<button type="button" class="notice-dismiss">
            	<span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
		<?php
		}
	}

	/*###################### Page view function ##################*/	
	
	private function page_view(){
		?>												
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">       
        <div class="wpda_gall_load_trash"></div>
        <div id="wpdevart_gallery_page_content">
			<div class="wpdevart_page_head">
    			<div class="wpdevart_plugins_header div-for-clear">
				<div class="wpdevart_plugins_get_pro div-for-clear">
					<div class="wpdevart_plugins_get_pro_info">
						<h3>WpDevArt Gallery Premium</h3>
						<p>Powerful and Customizable Gallery</p>
					</div>
						<a target="blank" href="https://wpdevart.com/wordpress-gallery-plugin/" class="wpdevart_upgrade">Upgrade</a>
				</div>
				<a target="blank" href="<?php echo wpdevart_gallery_support_url; ?>" class="wpdevart_support">Have any Questions? Get a quick support!</a>
			</div>
                <?php $this->genererete_notification(); ?>
    		</div>
            <div class="uploader_and_image_editor" id="uploader_and_image_editor">
           		<div id="wpda_gall_welcome_tab" class="tab active">
					<center>
               			<h1>Create WpDevArt Gallery (Instruction)</h1>					
               		</center>
               		 <ol>
                        <li class="wpda_gall_descriptin">Here is short user manual for creating galleries(with Draggable Navigation Tree)</li>                
                        <li class="wpda_gall_descriptin">1.How to create Gallery(group of Albums):</li>
                        <li class="wpda_gall_descriptin" style="padding-left:10px;">Click "Create Gallery" (in the navigation panel), then type the Gallery name and click on "add new gallery" button on the right side.
                            The new gallery will be added to the navigation tree.</li>
                        <li class="wpda_gall_descriptin">2.How to create Album(group of Images/Videos):</li>
                        <li class="wpda_gall_descriptin" style="padding-left:10px;">Click the "+" button on the left side of created gallery, then you will see "Create Album" option. Click on "Create Album" and type the new Album name, then press "add new album" button on the right side.
                            The new Album will be added to the navigation tree.</li>
                        <li class="wpda_gall_descriptin">3.Uploading Images/videos:</li>
                        <li class="wpda_gall_descriptin" style="padding-left:10px;">Click the "+" button on the left side of the created album (in the navigation tree). After that you will see "New" option. Click on "New" button, then on "Wpdevart Uploaders" (in right side navigation panel), you will see the upload options in the uploader panel. You can use the Wpdevart uploaders for uploading new images/videos respectively. After uploading, images/videos will be added to this album (in the navigation tree) and the Wpdevart media library. You can add images to this album from the WordPress media library and images/videos from the Wpdevart media library</li>	
                        <li class="wpda_gall_descriptin">4.Delete, Moving, Rearrange Images, Albums, Galleries using drag and drop feature.</li>									
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;">a. If you want to change image place, then(in the navigation tree) drag the image(click on it and then move) and drop it on other image. Images change places.</b></li>
           				<li class="wpda_gall_descriptin"><b style="margin-left:10px;">b. If you want to change Album place, then(in the navigation tree) drag the Album(click on it and then move) and drop it on other Album. Albums change places.</b></li>
          			    <li class="wpda_gall_descriptin"><b style="margin-left:10px;">c. If you want to change Gallery place, then(in the navigation tree) drag the Gallery(click on it and then move) and drop it on other Gallery. Galleries change places.</b></li>                        
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;">d. If you want to delete the image, then(in the navigation tree) drag it and drop it into basket(Recycle Bin).</b></li>
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;">e. If you want to move image (in the navigation tree)  to other Album, then drag it and drop into other album or her album editing panel.</b></li> 
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;">f. You can change image places from album editing panel by dragging and dropping them.</b></li>
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;">g. If you want to move image from her image editing panel to other Album (in the navigation tree), then drag it and drop into other Album folder(icon).</b></li>
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;">i. If you want to delete the image from her image editing panel, then drag it and drop it into basket(Recycle Bin).</b></li> 
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;">j. If you want to move all images from one Album(in the navigation tree) to another Album, then drag the Album folder(icon) and drop into other Album (in the navigatiom tree) or her album editing panel.</b></li>
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;">k. If you want to delete the Album(with all images), then(in the navigation tree) drag it and drop it into basket(Recycle Bin).</b></li>
  						<li class="wpda_gall_descriptin"><b style="margin-left:10px;">l. If you want to delete the Gallery(with all Albums and images), then(in the navigation tree) drag it and drop it into basket.</b></li> 
                        <li class="wpda_gall_descriptin">5.Edit Galleries and Albums names, Edit Image details, Removing Images.</b></li>
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;"></b>a.You can edit Galleries or Albums names easily, just click on Gallery or Album name and you will see the edit field, type the new name and save it by clicking on save button from right side.</li>
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;"></b>b.Click on the album of navigation tree and in her album editing panel you can edit the images of this album.</li>
                        <li class="wpda_gall_descriptin"><b style="margin-left:10px;"></b>c.Click on the image in navigation tree or in album editing panel you can edit this image(in her image editing tab).</li>                     
					</ol>
                </div>
            	<div id="wpda_gall_add_new_image_tab" class="tab">
               		<div class="wpda_gall_add_image_tab_links">
						<ul>
							<li  class="active">Wpdevart Uploaders</li>
							<li>WordPress media Library</li>
							<li>Wpdevart media Library</li>						
						</ul>
                    </div>
                    <div class="wpda_gall_add_image_tab_contents">
						<div class="standart_uploader active" title="uploader panel">
                       		<form id="wpdevart_standart_uploader" action="" method="post" enctype="multipart/form-data">
                       			<input type="hidden" id="gallery_name" name="gallery_name" value="">
                       			<input type="hidden" id="album_name" name="album_name" value="">                                         			
                                <div style="display:inline-block;padding:5px;border:solid rgb(153, 153, 153) 2px;margin-left:15px;margin-top:15px;">
                                    <bold style="font-size:16px">Video Uploader :</bold> 
                                    <input type="text" name="youtube_url" placeholder="Enter Youtube or Vimeo Video URL" style="width:320px"/>
                                    <input name="insert_from_youtube_url" type="submit"  style="right:200px;" value="Insert to album" onclick="alert('If you want to use this feature upgrade to Pro Version'); return false;">
                                    <span id="tagavor"></span>
                                </div>
                                <input type="file" name="fileToUpload[]" id="wpda_gall_stadndart_files_upload" multiple style="display: inline;">
                       			<?php echo $this->uploaded_image_html; ?>
                       			<input id="insert_from_standat_uploader" name="insert_from_standat_uploader" type="submit" class="button button-primary wpda_gall_isnert_images" value="Insert to album">
                                <input type="button" id="cancel" class="button button-primary wpda_gall_isnert_images" value="Cancel upload" >
                       		</form>
                            <div class="wpda_gall_standart_uploaded_image_status">
                                <div id="wpda_gall_load_list_no_images"></div>
                                <div id="wpda_gall_load_list_images"></div> 
                            </div>                           
                       	</div>
                        <div class="media_library_uploader centrr">
							<form id="from_media_library" method="post" action="">
								<input type="hidden" name="gallery_name" value="">
                       			<input type="hidden" name="album_name" value="">
                                <div class="wpda_gall_isnert_images" style="display:inline-block">
                                    <input name="remove_from_upload_images" type="submit" class="button button-primary" value="Remove images"> 
                                    <input name="insert_from_upload_images" type="submit" class="button button-primary" value='Insert to album'>
                                </div> 
                       			<input name="insert_from_meia_library" type="submit" class="button button-primary wpda_gall_isnert_images" value="Insert to album">
							</form>
						</div>
                    </div>
                </div>
                <div id="wpda_gall_edit_album_images_tab" class="tab centrr">			
					<form id="wpda_gall_edit_album_form" method="post" action="" title="album edit panel">
						<h2>Edit album "<span></span>" 
                        	<div class="wpda_gall_float_right">
                            <b id="wpda_gall_album_caunt"></b>
                                <a href="javascript:wpda_gallery_edit_upload.redact_album_list_view();"><i class="fa wpda_gall_font_size_24">&#xf03a;</i></a> 
                                <a href="javascript:wpda_gallery_edit_upload.redact_album_square_view();"><i class="fa wpda_gall_font_size_24">&#xf00a;</i></a>
                            </div>
                        </h2>                                                      
						<input type="hidden" name="gallery_name" value="">
						<input type="hidden" name="album_name" value="">
                        <input name="save_album_images_name_description" type="submit" class="button button-primary wpda_gall_album_save" value="Save Changes">  
						<input name="remov_album_images" type="submit" class="button button-primary wpda_gall_isnert_from_media1" value="Remove images">
					</form>
                </div>
                <div id="wpda_gall_edit_image_tab" class="tab">
                	<form id="wpda_gall_edit_image_form" method="post" action="" title="image edit panel">
						<div class="show_image_conteiner">
							<img id="edited_image" class="wpda_gall_edited_image" title="loading image please wait">
						</div>
                   		<div class="image_info_conteiner">
                   			<h2>Image Information</h2>
							<div class="image_not_editable_information">
								<div  class="wpda_gall_image_filname"><span>File name:</span><span>File Name</span></div>
								<div  class="wpda_gall_image_filesize"><span>File size:</span><span>File size</span></div>
								<div  class="wpda_gall_image_filedemisions"><span>File Dimensions:</span><span>File Dimensions</span></div>
								<div  class="wpda_gall_image_filetype"><span>File type:</span><span>File type</span></div>
								<div  class="wpda_gall_image_uploadeddate"><span>Updated date:</span><span>Updated date</span></div>
								
							</div>
							<hr>
							<table>
								<tr class="wpda_edit_image_line">
									<td class="edit_image_label">Image url:</td><td class="edit_image_input"><input name="wpda_gall_edit_image_url" type="text" class="regular-text" value="text value" readonly></td>
								</tr>
								<tr class="wpda_edit_image_line">
									<td class="edit_image_label">Image name:</td><td class="edit_image_input"><input name="wpda_gall_edit_image_name" type="text" class="regular-text" value="text value"></td>
								</tr>
								<tr class="wpda_edit_image_line">
									<td class="edit_image_label">Image description:</td>
									<td class="edit_image_input"><textarea name="wpda_gall_edit_image_description" class="regular-text" rows="5" name="text"></textarea></td>
								</tr>
								<tr class="wpda_edit_image_line">
									<td class="edit_image_label"><a id="wpda_gall_attachment_image_page" target="_blank">Attachment image page</a></td>      <!--19-01-2019-->
								</tr>                                 
							</table>
							<input type="hidden" name="wpda_gall_image_id" value="">
							<input name="delete_image" type="submit" class="button wpda_gall_remove_image" value=""></input> 
							<input name="edit_image" type="submit" class="button button-primary wpda_gall_isnert_from_media" value="Save Changes">
                            <div id="rot_right_left">
                                <input id="rot_right" name="right" type="submit" class="button button-primary wpda_gall_rot_right" value="&#8635;" style=" font-size: 20px;" title="rotate right">
                                <input id="rot_left" name="left" type="submit" class="button button-primary wpda_gall_rot_left" value="&#8634;" style=" font-size: 20px;" title="rotate left">
                            </div>
                            <a href="javascript:{localStorage.setItem('wpda_gall_gallery_active_tab_index','3');wpda_gallery_edit_upload.restart_tree();}" class="button button-primary wpda_gall_go_to_album" title="go back">
                            	<i class="fa wpda_gall_font_size_24">&#xf112;</i>
                            </a>                            
                            
						</div>
                    </form>
                </div>
            </div>
            <?php wp_nonce_field('wpda_gall_gallery_page_nonce_action','wpda_gall_gallery_page_nonce_name'); ?>
		</div>              
        <?php
		$this->php_to_js_variable();
	}

	/*###################### Function for PHP to JS variable ##################*/
	
	private function php_to_js_variable(){
		global $wpdb;
		$table_gallery = wpdevart_gallery_databese::$table_names['gallery'];
		$table_images = wpdevart_gallery_databese::$table_names['images'];
		$tree=array();	
		$gallerys = $wpdb->get_col( "SELECT DISTINCT `gallery` FROM ".$table_gallery." WHERE `album` IS NULL ORDER BY `order_id` ASC ;");
		$galler_counter=0;
		$album_counter=0;
		foreach($gallerys as $key=>$value){
			$tree[$galler_counter]['name']=$value;
			$curent_gallery_albums=$wpdb->get_col( "SELECT DISTINCT `album` FROM ".$table_gallery." WHERE `gallery` = '$value' And `album` IS NOT NULL And `image_name` IS NULL ORDER BY `order_id` ASC ;" );		
				foreach($curent_gallery_albums as $alb_key=>$alb_value){				
					$images = $wpdb->get_results( "SELECT ".$table_gallery.".id,".$table_images.".image_w, ".$table_images.".image_h, ".$table_images.".url,".$table_images.".image_type,".$table_images.".image_size, ".$table_gallery.".image_name, ".$table_gallery.".time, ".$table_gallery.".image_description,".$table_gallery.".order_id,".$table_gallery.".published FROM ".$table_gallery." INNER JOIN ".$table_images." ON ".$table_gallery.".img_id = ".$table_images.".id WHERE ".$table_gallery.".gallery = '$value' And ".$table_gallery.".album = '$alb_value' And ".$table_gallery.".image_name IS NOT NULL ORDER BY ".$table_gallery.".order_id ASC ;" ,ARRAY_A);
					$tree[$galler_counter][$album_counter]=$images;
					$tree[$galler_counter][$album_counter]['name']=$alb_value;
					$album_counter++;
				}
			$galler_counter++;
			$album_counter=0;
            //Wpdevart media library
			$upload_images = $wpdb->get_results( "SELECT id, url FROM ".$table_images." ;");//20-05-2017
		}
		?><script> 
			var wpda_gall_admin_ajax_url="<?php  echo admin_url( 'admin-ajax.php' ); ?>";
			var wpda_gall_content_url="<?php  echo   $this->image_manipulatioan->urls['original']; ?>";
			var wpda_gall_content_url_width ="<?php  echo   $this->image_manipulatioan->urls['width']; ?>";
			var wpda_gall_content_url_height ="<?php  echo   $this->image_manipulatioan->urls['height']; ?>";
			var wpda_gall_content_url_main ="<?php  echo   $this->image_manipulatioan->urls['main']; ?>";
			var wpda_gall_admin_url ="<?php  echo   wpdevart_gallery_plugin_url; ?>";
		<?php		
		$output_text = "wpda_gallery_tree['tree_info'] = " . json_encode($tree ,JSON_FORCE_OBJECT). ";";

		//get WordPress media library pictures information
		$query_images_args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_status'    => 'inherit',
			'posts_per_page' => - 1,
		);
		$query_images = new WP_Query( $query_images_args );
		$counter=0;
		$abstract_media_library_images=array();
		foreach($query_images->posts as $key=>$value){
			$abstract_media_library_images[$counter]['id']=$value->ID;
			$abstract_media_library_images[$counter]['thumb_url']=wp_get_attachment_thumb_url( $value->ID );
			$counter++;
			
		}
		$counter=0;
		$upload_media_library_images=array();
		if(!isset($upload_images)){
			$upload_images=array();
		}
		foreach($upload_images as $key=>$value){
			$upload_media_library_images[$counter]['id']=$value->id;
			$upload_media_library_images[$counter]['thumb_url']=$value->url ;
			$counter++;
			
		}		
		$output_text .= "wpda_gallery_edit_upload['medi_attachments'] = " . json_encode($abstract_media_library_images ,JSON_FORCE_OBJECT). ";";
		$output_text .= "wpda_gallery_edit_upload['upload_images'] = " . json_encode($upload_media_library_images ,JSON_FORCE_OBJECT). ";";
		$output_text .= "wpda_gall_load.k = " . time(). ";";
		echo $output_text;
		?>
		</script>		
		<?php
	}


/*########################################### SAVE AND UPDATE DATABASE ###########################################*/
/*########################################### SAVE AND UPDATE DATABASE ###########################################*/
/*########################################### SAVE AND UPDATE DATABASE ###########################################*/
	private function insert_gallery_into_database(){
		global $wpdb;	
		if(isset($_POST['wpda_gall_tree_gallery_form_name_input']) && isset($_POST['wpda_gall_tree_gallery_form_submit_button'])){
			$name=$_POST['wpda_gall_tree_gallery_form_name_input'];
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){
				if($name!=''){
					$exsisting_name=$wpdb->get_var($wpdb->prepare("SELECT `gallery` FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE `gallery` = %s ORDER BY `id` ASC;",$name));				
					if($exsisting_name!=$name){
						$max_ord=$wpdb->get_var( "SELECT MAX(order_id) FROM ".wpdevart_gallery_databese::$table_names['gallery'].";" )+1;
						$wpdb->insert(
							wpdevart_gallery_databese::$table_names['gallery'], 
							array( 								
								'gallery'           => $name,
								'time'              => current_time( 'mysql' ),
								'order_id'           	=> $max_ord
							) ,
							array('%s','%s','%d')
						);	
						$this->notification['type']='updated';
						$this->notification['text']='Gallery "'.$name.'" successfully created';
						
					}else{
						//error this gallery name exsists
						$this->notification['type']='error';
						$this->notification['text']='Gallery Name must by unique. "'.$name.'" name already exist';					
					}
				}else{
					//error name not null	
					$this->notification['type']='error';
					$this->notification['text']='Gallery Name is Not null. Please type any text for gallery name';				
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}		
	}
	
	/*###################### Function for inserting album into database ##################*/	
	
	private function insert_album_into_database(){
		global $wpdb;
		if(isset($_POST['wpda_gall_tree_gallery_form_name_input']) && isset($_POST['wpda_gall_tree_album_form_name_input']) && isset($_POST['wpda_gall_tree_album_form_submit_button'])){
			$name_gallery=$_POST['wpda_gall_tree_gallery_form_name_input'];
			$name_album=$_POST['wpda_gall_tree_album_form_name_input'];
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){
				if($name_gallery!='' && $name_album!=''){
					$exsisting_name=$wpdb->get_var($wpdb->prepare("SELECT `album` FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE `gallery` =%s and album=%s ORDER BY `id` ASC ;",$name_gallery,$name_album));
					if($exsisting_name!=$name_album){
						$wpdb->insert(
							wpdevart_gallery_databese::$table_names['gallery'], 
							array( 								 
								'gallery'           => $name_gallery,
								'album'           	=> $name_album,
								'time'              => current_time( 'mysql' )
							) ,
							array('%s','%s','%s')
						);	
						$this->notification['type']='updated';
						$this->notification['text']='Album "'.$name_album.'" successfully created';
					}else{
						//error this gallery name exsists
						$this->notification['type']='error';
						$this->notification['text']='Album Name must by unique. "'.$name_album.'" name already exist';					
					}
				}else{
					//error name not null	
					$this->notification['type']='error';
					$this->notification['text']='Album Name is Not null. Please type any text for album name';				
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}
	}
	
	/*###################### Function for updating Gallery name in the database ##################*/	
	
	private function update_gallery_name_in_database(){
		global $wpdb;	
		if(isset($_POST['wpda_gall_tree_gallery_form_name_input']) && isset($_POST['wpda_gall_old_name'])  && isset($_POST['wpda_gall_update_gallery_name'])){			
			$gallery_old_name=$_POST['wpda_gall_old_name'];
			$gallery_new_name=$_POST['wpda_gall_tree_gallery_form_name_input'];
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){
				if($gallery_new_name!='' && $gallery_old_name!=''){
					$exsisting_name=$wpdb->get_var($wpdb->prepare("SELECT `gallery` FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE `gallery` = %s ORDER BY `id` ASC;",$gallery_new_name));				
					if($exsisting_name!=$gallery_new_name){
						$wpdb->update(
							wpdevart_gallery_databese::$table_names['gallery'], 
							array( 								
								'gallery'           => $gallery_new_name,
								'time'              => current_time( 'mysql' )
							) ,
							array( 'gallery'  =>$gallery_old_name, ),
							array('%s','%s'),
							array('%s')
						);	
						$this->notification['type']='updated';
						$this->notification['text']='Gallery "'.$gallery_old_name.'" successfully updated to "'.$gallery_new_name.'"';
						
					}else{
						//error - this gallery name exists
						$this->notification['type']='error';
						$this->notification['text']='Gallery Name must by unique. "'.$gallery_new_name.'" name already exist';					
					}
				}else{
					//error name not null	
					$this->notification['type']='error';
					$this->notification['text']='Gallery Name is Not null. Please type any text for gallery name';				
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}		
	}
	
	/*###################### Function for updating Album name in the database ##################*/	
	
	private function update_album_name_in_database(){
		global $wpdb;	
		if(isset($_POST['wpda_gall_tree_album_form_name_input']) && isset($_POST['wpda_gall_update_album_name'])  && isset($_POST['wpda_gall_gallery_old_name'])  && isset($_POST['wpda_gall_album_old_name'])){			
			$gallery_name=$_POST['wpda_gall_gallery_old_name'];
			$album_old_name=$_POST['wpda_gall_album_old_name'];
			$album_new_name=$_POST['wpda_gall_tree_album_form_name_input'];
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){
				if($gallery_name!='' && $album_old_name!='' && $album_new_name!=''){
					$exsisting_name=$wpdb->get_var($wpdb->prepare("SELECT `album` FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE `gallery` = %s and `album`=%s ORDER BY `id` ASC;",$gallery_name,$album_new_name));				
					if($exsisting_name!=$album_new_name){
						$wpdb->update(
							wpdevart_gallery_databese::$table_names['gallery'], 
							array( 								
								'album'           	=> $album_new_name,
								'time'              => current_time( 'mysql' )
							) ,
							array( 'gallery'  =>$gallery_name,'album'  =>$album_old_name, ),
							array('%s','%s'),
							array('%s')
						);	
						$this->notification['type']='updated';
						$this->notification['text']='Album "'.$album_old_name.'" successfully updated to "'.$album_new_name.'"';
						
					}else{
						//error this gallery name exsists
						$this->notification['type']='error';
						$this->notification['text']='Album Name must by unique. "'.$album_new_name.'" name already exist';					
					}
				}else{
					//error name not null	
					$this->notification['type']='error';
					$this->notification['text']='Album Name is Not null. Please type any text for album name';				
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}
	}

	/*###################### Function for deleting the Gallery ##################*/	
	
	private function delete_gallery(){
		global $wpdb;	
		if(isset($_POST['wpda_gall_tree_gallery_form_name_input']) && isset($_POST['wpda_gall_old_name'])  && isset($_POST['wpda_gall_delete_gallery'])){			
			$gallery_old_name=$_POST['wpda_gall_old_name'];
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){									
				$file_name=$wpdb->get_col( "SELECT `url` FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE `gallery` = '$gallery_old_name' ;" );				
				$file_name_length = count($file_name);					
				for ($x = 0; $x < $file_name_length; $x++) {						
					$this->image_manipulatioan->remove_image_from_url($file_name[$x]);						
				}
				$wpdb->delete( wpdevart_gallery_databese::$table_names['gallery'], array( 'gallery' => $gallery_old_name ),array('%s') );	
				$this->notification['type']='updated';
				$this->notification['text']='Gallery "'.$gallery_old_name.'" successfully deleted';				
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}
	}
	
	/*###################### Function for deleting the album ##################*/		
	
	private function delete_album(){
		global $wpdb;	
		if(isset($_POST['wpda_gall_tree_album_form_name_input']) && isset($_POST['wpda_gall_delete_album'])  && isset($_POST['wpda_gall_gallery_old_name'])  && isset($_POST['wpda_gall_album_old_name'])){			
			$album_name=$_POST['wpda_gall_album_old_name'];
			$gallery_name=$_POST['wpda_gall_gallery_old_name'];
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){	

				$file_name=$wpdb->get_col( "SELECT `url` FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE `gallery` = '$gallery_name' And `album` = '$album_name' ;" );
				
				$file_name_length = count($file_name);					
				for ($x = 0; $x < $file_name_length; $x++) {						
					$this->image_manipulatioan->remove_image_from_url($file_name[$x]);						
				}
				$wpdb->delete( wpdevart_gallery_databese::$table_names['gallery'], array( 'gallery' => $gallery_name,'album' =>$album_name ));
				$this->notification['type']='updated';
				$this->notification['text']='Album "'.$album_name.'" successfully deleted';				
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}		
	}




	//20-05-2017
	private function insert_image_from_media_library(){
		//from WordPress media library	
		if(isset($_POST["insert_from_meia_library"])) {
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){	
				if(isset($_POST['medi_library_attachment'])) {
					if(isset($_POST['gallery_name']) && $_POST['gallery_name'] && isset($_POST['album_name']) && $_POST['album_name']){
						$gallery_name	=$_POST['gallery_name'];
						$album_name		=$_POST['album_name'];
						if((integer) wpdevart_gallery_start_mem < 128){ini_set('memory_limit', '128M');}                                               //01-11-2017						
						foreach ($_POST['medi_library_attachment'] as $value) {
							$this->image_manipulatioan->insert_from_media_library($value,$gallery_name,$album_name);
						}
						ini_set('memory_limit', wpdevart_gallery_start_mem);                                                                             //01-11-2017
					}else{
						$this->notification['type']='error';
						$this->notification['text']='"album name" or "gallery name" not detected';
					}
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}		
		//from WpDevArt media library
		if(isset($_POST["insert_from_upload_images"])) {
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){	
				if(isset($_POST['medi_library_attachment'])) {
					if(isset($_POST['gallery_name']) && $_POST['gallery_name'] && isset($_POST['album_name']) && $_POST['album_name']){
						global $wpdb;
						$table_gallery = wpdevart_gallery_databese::$table_names['gallery'];
						$table_images = wpdevart_gallery_databese::$table_names['images'];						
						$gallery_name	=$_POST['gallery_name'];
						$album_name		=$_POST['album_name'];
				
						$upload_images = $wpdb->get_results( "SELECT id, url FROM ".$table_images." ;");//20-05-2017																
						$xxxxx=array();
						foreach($upload_images as $key=>$value){
							$xxxxx[$value->id]['image_name']= substr($value->url,0,strrpos($value->url,"."));														
							if(substr($xxxxx[$value->id]['image_name'],0,8) == 'vimeo___'){                                             //20-09-2017							
								$xxxxx[$value->id]['image_name'] = '&#9654;'. str_replace("vimeo___","",$xxxxx[$value->id]['image_name']);
							}							
							if(substr($xxxxx[$value->id]['image_name'],0,8) == 'youtube_'){                                             //20-09-2017							
								$xxxxx[$value->id]['image_name'] = '&#9654;'. str_replace("youtube_","",$xxxxx[$value->id]['image_name']);
							}																							
						}						

						$max_ord=$wpdb->get_var( "SELECT MAX(order_id) FROM ".wpdevart_gallery_databese::$table_names['gallery'].";" ) + 1;						
						foreach ($_POST['medi_library_attachment'] as $value) {   
							$wpdb->insert( 
								$table_gallery, 
								array( 
									'time'              => current_time( 'mysql' ), 
									'gallery'           => $gallery_name, 
									'album'             => $album_name, 
									'image_description' => '',
									'image_name' 		=> $xxxxx[$value]['image_name'],
									'order_id'       	=> $max_ord,
									'img_id' 		    => $value,
									'published'         => 1,                    
								),array(
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%d',
									'%d',
									'%d'
								)
							);																		
							$max_ord++;	
						}
					}else{
						$this->notification['type']='error';
						$this->notification['text']='"album name" or "gallery name" not detected';
					}
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}
		// 22-05-2017       remove from WpDevArt media library
		if(isset($_POST["remove_from_upload_images"])) {
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){	
				if(isset($_POST['medi_library_attachment'])) {					
					global $wpdb;
					$yyyy = $_POST['medi_library_attachment'];
					$table_gallery = wpdevart_gallery_databese::$table_names['gallery'];
					$table_images = wpdevart_gallery_databese::$table_names['images'];						
					$upload_images = $wpdb->get_results( "SELECT id, url FROM ".$table_images." ;");																
					$xxxxx = array();
					foreach($upload_images as $key=>$value){
						$xxxxx[$value->id]['url'] = $value->url;							
					}											
					foreach ($yyyy as $value) {   							
						$this->image_manipulatioan->remove_image_from_url($xxxxx[$value]['url']);
						$wpdb->query("DELETE FROM $table_images WHERE id='$value'");								
					}
					$wpdb->query("DELETE FROM $table_gallery WHERE img_id IN (".implode(',',$yyyy).");");
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}		
		
	}	

	/*###################### Function for inserting image from the standard uploader ##################*/	
	
	private function insert_image_from_standart_uploader(){	
		if(isset($_POST['insert_from_standat_uploader'])) {
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){	
				if(isset($_POST['gallery_name']) && $_POST['gallery_name'] && isset($_POST['album_name']) && $_POST['album_name']){
					if(isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['name'][0]){
					}else{
						$this->notification['type']='error';
						$this->notification['text']='Files not attached. Please attach one or more files.';
					}
					$gallery_name	=$_POST['gallery_name'];
					$album_name		=$_POST['album_name'];						
					$this->uploaded_image_html=$this->image_manipulatioan->insert_from_standart_uploader($_FILES['fileToUpload'],$gallery_name,$album_name);
				}else{
					$this->notification['type']='error';
					$this->notification['text']='"album name" or "gallery name" not detected';
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}					
		}
	}
		
// insert from YouTube image URL	01-09-2017
	private function insert_image_from_youtube_url(){	
		if(isset($_POST['insert_from_youtube_url'])) {
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){	
				if(isset($_POST['gallery_name']) && $_POST['gallery_name'] && isset($_POST['album_name']) && $_POST['album_name'] && isset($_POST['youtube_url']) && $_POST['youtube_url']){

					$youtube_url    =$_POST['youtube_url'];
					$gallery_name	=$_POST['gallery_name'];
					$album_name		=$_POST['album_name'];						
					$this->uploaded_image_html=$this->image_manipulatioan->insert_from_youtube_url($youtube_url,$gallery_name,$album_name);
				}else{
					$this->notification['type']='error';
					$this->notification['text']='"album name" or "gallery name" not detected';
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}					
		}
	}		
	
    /*###################### Function for removing the image ##################*/	
		
	private function remove_image(){
		if(isset($_POST['delete_image'])){	
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){	
				if(isset($_POST['wpda_gall_image_id']) && $_POST['wpda_gall_image_id']){
					$image_id=(int)$_POST['wpda_gall_image_id'];
					$this->image_manipulatioan->remove_images($image_id);
					$this->notification['type']='updated';
					$this->notification['text']='iamge successfully deleted';
				}else{
					$this->notification['type']='error';
					$this->notification['text']='"image id" not detected';
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}
	}
	
    /*###################### Function for updating the image info ##################*/	
	
	private function update_image_info(){
		if(isset($_POST['edit_image'])){
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){	
				if(isset($_POST['wpda_gall_image_id']) && $_POST['wpda_gall_image_id']){
					$image_id=(int)$_POST['wpda_gall_image_id'];
					$image_name=$_POST['wpda_gall_edit_image_name'];
					$image_description=$_POST['wpda_gall_edit_image_description'];
					$this->image_manipulatioan->update_image_info($image_id,$image_name,$image_description);
					$this->notification['type']='updated';
					$this->notification['text']='iamge successfully updated';
				}else{
					$this->notification['type']='error';
					$this->notification['text']='"image id" not detected';
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}
	}
	
	/*###################### Function for removing the images ##################*/		
	
	private function remove_images(){
		if(isset($_POST['remov_album_images'])){	
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){
				if(isset($_POST['album_thumbnail']) && $_POST['album_thumbnail']){
					$image_ids=$_POST['album_thumbnail'];
					$this->image_manipulatioan->remove_images($image_ids);
					$this->notification['type']='updated';
					$this->notification['text']='iamges successfully deleted';
				}else{
					$this->notification['type']='error';
					$this->notification['text']='please Select images';
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}
	}

	/*###################### Album details function ##################*/	
	
	private function save_album_images_name_description(){
		if(isset($_POST['save_album_images_name_description'])){	
			if(isset($_POST['wpda_gall_gallery_page_nonce_name']) && wp_verify_nonce($_POST['wpda_gall_gallery_page_nonce_name'],'wpda_gall_gallery_page_nonce_action') ){
				if(isset($_POST['album_thumbnail']) && $_POST['album_thumbnail']){
					$image_ids=$_POST['album_thumbnail'];
					$album_name=$_POST['album_name'];
					$album_description=$_POST['album_description'];
					foreach($image_ids as $x => $id){
						$album_name[$x] = str_replace(array("\'", "\\", "&", "^", "%", "$", "#", "@", "!", "~", "+"), '', $album_name[$x]);  //14-11-2018			
						$album_description[$x] = str_replace(array("\'", "\\", "&", "^", "%", "$", "#", "@", "!", "~", "+"), '', $album_description[$x]);				
						$this->image_manipulatioan->update_image_info($id,$album_name[$x],$album_description[$x]);
					}
					
					$this->notification['type']='updated';
					$this->notification['text']='iamge successfully updated';
				}else{
					$this->notification['type']='error';
					$this->notification['text']='"image id" not detected';
				}
			}else{
				//error authentication
				$this->notification['type']='error';
				$this->notification['text']='Authentication Error please try again';	
			}
		}
	}
	
	//0 form image rotate
	public function image_rotate_form() {
		if(isset($_POST['wpda_gall_image_id']) && isset($_POST['right'])){
			$img_id =$_POST['wpda_gall_image_id'];
			$degree =-90;
			$this->image_manipulatioan->create_image_duplicates_rotate_form($img_id,$degree);
		}
		
		if(isset($_POST['wpda_gall_image_id']) && isset($_POST['left'])){
			$img_id =$_POST['wpda_gall_image_id'];
			$degree =90;			
			$this->image_manipulatioan->create_image_duplicates_rotate_form($img_id,$degree);
		}		
	}		
	
	
}

class wpda_gall_image_manupulation{
	public $directory='wpdevart_gallery';
	public $directorys=array();
	public $urls=array();
	private $thumb_width='200';
	private $thumb_height='40';//'200';
	private $max_width='1920';
	private $max_height='1080';
	//
	function __construct(){
		$destination=$this->create_directory();
		$this->directorys=$destination[0];
		$this->urls=$destination[1];
		// geting croping option for croping settings
		$this->thumb_width=get_option('wpda_gall_croping_imagae_thumbnail_width',$this->thumb_width);
		$this->thumb_height='40';//get_option('wpda_gall_croping_imagae_thumbnail_height',$this->thumb_height);
		$this->max_width=get_option('wpda_gall_croping_imagae_max_width',$this->max_width);
		$this->max_height=get_option('wpda_gall_croping_imagae_max_height',$this->max_height);
		
		add_action( 'wp_ajax_wpda_gall_drag_img_drop_img_inversia', array($this,'drag_img_drop_img_inversia') ); 
		add_action( 'wp_ajax_wpda_gall_drag_album_drop_album', array($this,'drag_album_drop_album') );
		add_action( 'wp_ajax_wpda_gall_drag_album_drop_album_inversia', array($this,'drag_album_drop_album_inversia') ); 
		add_action( 'wp_ajax_wpda_gall_drag_img_drop_album', array($this,'drag_img_drop_album') ); 
		add_action( 'wp_ajax_wpda_gall_drag_img_drop_delet', array($this,'drag_img_drop_delet') ); 
		add_action( 'wp_ajax_wpda_gall_drag_album_drop_delet', array($this,'drag_album_drop_delet') ); 		
		add_action( 'wp_ajax_wpda_gall_drag_gallery_drop_delet', array($this,'drag_gallery_drop_delet') );
		add_action( 'wp_ajax_wpda_gall_drag_gallery_drop_gallery_inversia', array($this,'drag_gallery_drop_gallery_inversia') );
		add_action( 'wp_ajax_wpda_gall_form_album_rename', array($this,'form_album_rename') );
		add_action( 'wp_ajax_wpda_gall_form_album_delete', array($this,'form_album_delete') );
		add_action( 'wp_ajax_wpda_gall_form_gallery_rename', array($this,'form_gallery_rename') );
		add_action( 'wp_ajax_wpda_gall_form_gallery_delete', array($this,'form_gallery_delete') );
		add_action( 'wp_ajax_wpda_gall_form_create_gallery', array($this,'form_create_gallery') );
		add_action( 'wp_ajax_wpda_gall_form_create_album', array($this,'form_create_album') );
		add_action( 'wp_ajax_wpda_gall_form_edit_image', array($this,'form_edit_image') );
		add_action( 'wp_ajax_wpda_gall_visibility', array($this,'visibility') );
		add_action( 'wp_ajax_wpda_gall_recrop_image', array($this,'recrop_image') );
		add_action( 'wp_ajax_wpda_gall_upload_image', array($this,'upload_image') );
		add_action( 'wp_ajax_wpda_gall_image_rotate', array($this,'image_rotate') );		
	}
	
	/*###################### Directory function ##################*/		
	
	private function create_directory(){
		$directory=array();
		if(!file_exists(WP_CONTENT_DIR . "/" . $this->directory . "/")) {
			mkdir(WP_CONTENT_DIR . "/" . $this->directory . "/");
		}
		if(!file_exists(WP_CONTENT_DIR . "/" . $this->directory . "/main/")) {
			mkdir(WP_CONTENT_DIR . "/" . $this->directory . "/main/");			
		}
		$urls['main']=WP_CONTENT_URL . "/" . $this->directory . "/main/";
		$directory['main']=WP_CONTENT_DIR . "/" . $this->directory . "/main/";
		if(!file_exists(WP_CONTENT_DIR . "/" . $this->directory . "/width/")) {
			mkdir(WP_CONTENT_DIR . "/" . $this->directory . "/width/");			
		}	
		$urls['width']=WP_CONTENT_URL . "/" . $this->directory . "/width/";
		$directory['width']=WP_CONTENT_DIR . "/" . $this->directory . "/width/";
		if(!file_exists(WP_CONTENT_DIR . "/" .$this->directory . "/height/")) {
			mkdir(WP_CONTENT_DIR . "/" . $this->directory . "/height/");			
		}
		$urls['height']=WP_CONTENT_URL . "/" . $this->directory . "/height/";
		$directory['height']=WP_CONTENT_DIR . "/" . $this->directory . "/height/";
		if(!file_exists(WP_CONTENT_DIR . "/" . $this->directory . "/original/")) {
			mkdir(WP_CONTENT_DIR . "/" . $this->directory . "/original/");			
		}
		$urls['original']=WP_CONTENT_URL . "/" . $this->directory . "/original/";
		$directory['original']=WP_CONTENT_DIR . "/" . $this->directory . "/original/";
		if(!file_exists(WP_CONTENT_DIR . "/" . $this->directory . "/attachment/")) {
			mkdir(WP_CONTENT_DIR . "/" . $this->directory . "/attachment/");			
		}
		$urls['attachment']=WP_CONTENT_URL . "/" . $this->directory . "/attachment/";
		$directory['attachment']=WP_CONTENT_DIR . "/" . $this->directory . "/attachment/";		
		return array($directory,$urls);
	}
	
	// 19-01-2019     wpda_gall_image_manupulation::create_attachment_page($image_w,$image_h,$image_name);	
	public function create_attachment_page($image_w,$image_h,$image_name){
		$image_title_ = pathinfo($image_name , PATHINFO_FILENAME);		
		if($this->max_width >= $image_w && $this->max_height >= $image_h){
			$main_width = $image_w;
			$main_height = $image_h;			
		} else {
			if($this->max_height / $this->max_width >= $image_h / $image_w){
				$main_width = min($image_w, $this->max_width);
				$main_height = floor($main_width * $image_h / $image_w);																		
			} else {
				$main_height = min($image_h, $this->max_height);
				$main_width = floor($main_height * $image_w / $image_h);			
			}
		}							
		// 
		if($this->thumb_width >= $image_w){
			$t_width = $image_w;
			$t_height = $image_h;				
		} else {
			$t_width = $this->thumb_width;
			$t_height = floor($this->thumb_width * $image_h / $image_w);			
		}
		$file2 =$this->directorys['attachment'] . $image_title_ .".html";
		if(file_exists($file2)){
			unlink($file2);
		}  
		$myfile = fopen($this->directorys['attachment'] . $image_title_ .".html", "w");
		$txt = '<!DOCTYPE html>';
		$txt .= '<html lang="en-US" class="no-js">';
		$txt .= '<head>';
		$txt .= '<title>' . $image_title_ . '</title>';
		$txt .= '<meta charset="UTF-8">';
		$txt .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
		$txt .= '</head>';
		$txt .= '<body>';
		$txt .= '<article>';
		$txt .= '<p class="entry-title">' . $image_name.'</p>';
		$txt .= '<p class="entry-title">size: ' . $image_w.' x '.$image_h.'</p>';
		$txt .= '<p>Posted on: '.current_time( 'mysql' ).'</p>';
		$txt .= '<a href="'.$this->urls['original'] . $image_name.'" target="_blank"><img width="'.$image_w.'" height="'.$image_h.'" src="'.$this->urls['original'] . $image_name.'" alt=""></a>'; 
		$txt .= '<p class="entry-title">' . $image_name.'</p>';
		$txt .= '<p class="entry-title">size: ' . $main_width.' x '.$main_height.'</p>';
		$txt .= '<a href="'.$this->urls['main'] . $image_name.'" target="_blank"><img width="'.$main_width.'" height="'.$main_height.'" src="'.$this->urls['main'] . $image_name.'" alt=""></a>'; 
		$txt .= '<p class="entry-title">' . $image_name.'</p>';
		$txt .= '<p class="entry-title">size: ' . $t_width.' x '.$t_height.'</p>';
		$txt .= '<a href="'.$this->urls['width'] . $image_name.'" target="_blank"><img width="'.$t_width.'" height="'.$t_height.'" src="'.$this->urls['width'] . $image_name.'" alt=""></a>'; 
		$txt .= '</article>';
		$txt .= '</body>';
		$txt .= '</html>';
		fwrite($myfile, $txt);
		fclose($myfile);		
	}	

	/*###################### Function for creating the images duplicates ##################*/		
	
	private function create_image_duplicates($main_image_path){
		$image= new wpda_gall_image();
		$image1= new wpda_gall_image();
		$image2= new wpda_gall_image();
		
		$image_name=basename($main_image_path);
		
		$image->load($main_image_path);
		$image1->load($main_image_path);
		$image2->load($main_image_path);
			
		// Resize to main.	05-11-2017
		if($this->max_width >= $image->getWidth() && $this->max_height >= $image->getHeight()){
			copy($main_image_path, $this->directorys['main'] . $image_name);
		} else {
			if($this->max_height / $this->max_width >= $image->getHeight() / $image->getWidth()){
				$resize_max_width = min($image->getWidth(), $this->max_width);
				$image->resizeToWidth($resize_max_width);                                                                                  
				$image->save($this->directorys['main'].$image_name);					
			} else {
				$resize_max_height = min($image->getHeight(), $this->max_height);
				$image->resizeToHeight($resize_max_height);                                                                                  
				$image->save($this->directorys['main'].$image_name);
			}
		}			
						
		// Resize to width.
		if($this->thumb_width >= $image1->getWidth()){
			copy($main_image_path, $this->directorys['width'] . $image_name);
		} else {
			$image1->resizeToWidth($this->thumb_width);
			$image1->save($this->directorys['width'].$image_name);
		}						
		
		// Resize to height.		
		if($this->thumb_height >= $image2->getHeight()){
			copy($main_image_path, $this->directorys['height'] . $image_name);
		} else {
			$image2->resizeToHeight($this->thumb_height);
			$image2->save($this->directorys['height'].$image_name);
		}			
	}
	//
	//19-01-2019
	private function create_image_duplicates_recrop($main_image_path){
		$image= new wpda_gall_image();
		$image1= new wpda_gall_image();
		$image2= new wpda_gall_image();
		
		$image_name=basename($main_image_path);
		
		$image->load($main_image_path);
		$image1->load($main_image_path);
		$image2->load($main_image_path);
			
		// Resize to main.	05-11-2017		
		$image_w = $image->getWidth();
		$image_h = $image->getHeight();
		if($this->max_width >= $image->getWidth() && $this->max_height >= $image->getHeight()){
			copy($main_image_path, $this->directorys['main'] . $image_name);									
		} else {
			if($this->max_height / $this->max_width >= $image->getHeight() / $image->getWidth()){
				$resize_max_width = min($image->getWidth(), $this->max_width);
				$image->resizeToWidth($resize_max_width);                                                                                  
				$image->save($this->directorys['main'].$image_name);																
			} else {
				$resize_max_height = min($image->getHeight(), $this->max_height);
				$image->resizeToHeight($resize_max_height);                                                                                  
				$image->save($this->directorys['main'].$image_name);							
			}
		}			
						
		// Resize to width.
		if($this->thumb_width >= $image1->getWidth()){
			copy($main_image_path, $this->directorys['width'] . $image_name);					
		} else {
			$image1->resizeToWidth($this->thumb_width);
			$image1->save($this->directorys['width'].$image_name);						
		}
		// Resize to height.		
		if($this->thumb_height >= $image2->getHeight()){
			copy($main_image_path, $this->directorys['height'] . $image_name);
		} else {
			$image2->resizeToHeight($this->thumb_height);
			$image2->save($this->directorys['height'].$image_name);
		}
		//		
		wpda_gall_image_manupulation::create_attachment_page($image_w,$image_h,$image_name);											
	}

	/*###################### Function for insterting the images from the media gallery ##################*/		
	
	public function insert_from_media_library($image_id,$gallery_name,$album_name){
		if ($image_id) {
			$image_post=get_post($image_id);
			$target_dir_upload= get_attached_file( $image_id );
			$image_title=$image_post->post_title;
			$image_description=$image_post->post_content;
			$image_name = basename( wp_get_attachment_url( $image_id ) );
			$image_size = filesize($target_dir_upload);
			$image_demisions = getimagesize($target_dir_upload);
			$image_w = $image_demisions[0];
			$image_h = $image_demisions[1];	
			$imageFileType = substr(strrchr($image_name, "."), 1);				
			$image_name_whitouth_info = pathinfo($image_name , PATHINFO_FILENAME);
			$dublicate_counter = '';
			$j = 0;		
			while(file_exists($this->directorys['original'] . $image_name_whitouth_info . $dublicate_counter . '.' . $imageFileType)){											
				$dublicate_counter = '-'.$j;	
				$j++;	
			}										
			$image_name = $image_name_whitouth_info  . $dublicate_counter . '.' . $imageFileType;						
			copy($target_dir_upload, $this->directorys['original'] . $image_name);	
			$this->create_image_duplicates($this->directorys['original'] . $image_name);
			////////	
			global $wpdb;
			$max_ord=$wpdb->get_var( "SELECT MAX(order_id) FROM ".wpdevart_gallery_databese::$table_names['gallery'].";" )+1;

			$wpdb->insert( 
				wpdevart_gallery_databese::$table_names['images'], 
 				array(  
					'image_h'           => $image_h,
					'image_w'           => $image_w,
					'image_size'        => $image_size,
					'image_type'        => $imageFileType,
					'url'               => $image_name,
				),array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				)
			);                
            $lastid = $wpdb->insert_id;     
                                
			$wpdb->insert( 
				wpdevart_gallery_databese::$table_names['gallery'], 
				array( 
					'time'              => current_time( 'mysql' ), 
					'gallery'           => $gallery_name, 
					'album'             => $album_name, 
					'image_description' => $image_description,
					'image_name' 		=> $image_title,
					'order_id'       	=> $max_ord,
                    'img_id' 		    => $lastid,
					'published'         => 1,                    
				),array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%d'
				)
			);
						
			// 18-01-2019			
			wpda_gall_image_manupulation::create_attachment_page($image_w,$image_h,$image_name);																				
		}else{
			return 'Not selected image';
		}
	}
	////////////////////////////////////////////                   31-08-2017     //////////////////////////////////////////////////////////////
	

	//15-09-2017
	private function getUrlContent($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$data = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return ($httpcode>=200 && $httpcode<300) ? $data : false;
		}


	// insert from youtube url
	public function insert_from_youtube_url($youtube_url,$gallery_name,$album_name){
	
		global $wpdb;

		//15-09-2017
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_url, $match)) {
			$video_id = $match[1];
			$parse_video_link = array("video" => "youtube", "videoID" => $video_id);
		}
		$regexstr = '~
		 # Match Vimeo link and embed code
		(?:<iframe [^>]*src=")? 	# If iframe match up to first quote of src
		(?:				# Group vimeo url
			https?:\/\/		# Either http or https
			(?:[\w]+\.)*		# Optional subdomains
			vimeo\.com		# Match vimeo.com
			(?:[\/\w]*\/videos?)?	# Optional video sub directory this handles groups links also
			\/			# Slash before Id
			([0-9]+)		# $1: VIDEO_ID is numeric
			[^\s]*			# Not a space
		)				# End group
		"?				# Match end quote if part of src
		(?:[^>]*></iframe>)?		# Match the end of the iframe
		(?:<p>.*</p>)?		        # Match any title information stuff
		~ix';
		if(preg_match($regexstr, $youtube_url, $match)){
			$video_id = $match[1];
			$parse_video_link = array("video" => "vimeo", "videoID" => $video_id);
		}
		//return false;
		if (isset($parse_video_link)) {
			$image_title = $parse_video_link["videoID"];
			$video_type = $parse_video_link["video"];		
			/////////////////////		
			if($video_type ==  "vimeo"){		
				$url = 'vimeo.com/api/v2/video/' . $image_title .'.php';;
				$contents = wpda_gall_image_manupulation::getUrlContent($url);
				$thumb = @unserialize(trim($contents));
				$image_url = $thumb[0]['thumbnail_large'] ?  $thumb[0]['thumbnail_large']:  $thumb[0]['thumbnail_medium'];
				$res = $thumb[0]['thumbnail_large'] ?  '(thumbnail_large)':  '(thumbnail_medium)';
				$check = getimagesize($image_url);								
			}else{
				$image_url = "https://i.ytimg.com/vi/" . $image_title . "/maxresdefault.jpg";  
				$image_name = 'youtube_' . $image_title . '.jpg';
				$res = '(maxresdefault)';
				// Check if image file is a actual image or fake image		
				$check = getimagesize($image_url);
				if($check === false) {
					$image_url = "https://i.ytimg.com/vi/" . $image_title . "/sddefault.jpg";
					$check = getimagesize($image_url);
					$res = '(sddefault)';
				}
				if($check === false) {
					$image_url = "https://i.ytimg.com/vi/" . $image_title . "/0.jpg";
					$check = getimagesize($image_url);
					$res = '(hqdefault)';
				}						
			
			}
		}else{
			return "<script>document.getElementById('tagavor').innerHTML='The URL not exist,Please type only Youtube or Vimeo Video URL :<br>- Supported YouTube URL formats: <br>- http://youtu.be/My2FRPA3Gf8 <br>  - http://www.youtube.com/watch?v=My2FRPA3Gf8<br>- http://www.youtube.com/embed/dQw4w9WgXcQ <br>- http://www.youtube.com/v/dQw4w9WgXcQ<br>- http://www.youtube.com/e/dQw4w9WgXcQ<br>- http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ<br>- http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ<br>- http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ<br>- http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ<br>- // It also works on the youtube-nocookie.com URL with the same above options.<br>- Supported Vimeo URL formats:<br>   - http://vimeo.com/25451551<br>   - http://player.vimeo.com/video/25451551';</script>";
	
		}
		///////////////////
		
		$imageFileType = "jpg";

		if($check !== false) {
			$image_width = $check[0];
			$image_height = $check[1];					
			
			if($video_type ==  "vimeo"){		
				$image_name_whitouth_info = "vimeo___" . $image_title . "vimeo___";
			}else{
				$image_name_whitouth_info = "youtube_" . $image_title . "youtube_";
			}
			$dublicate_counter = '';
			$j = 0;		
			while(file_exists($this->directorys['original'] . $image_name_whitouth_info . $dublicate_counter . '.' . $imageFileType)){											
				$dublicate_counter = '-'.$j;	
				$j++;	
			}										
			$image_name = $image_name_whitouth_info  . $dublicate_counter . '.' . $imageFileType;
							
			$target_file = $this->directorys['original'] . $image_name;			
			if (file_put_contents($target_file,file_get_contents($image_url))) {
								
				$image_size = filesize($target_file);	
				$this->create_image_duplicates($this->directorys['original'] . $image_name);
				$max_ord = $wpdb->get_var( "SELECT MAX(order_id) FROM ".wpdevart_gallery_databese::$table_names['gallery'] . ";" ) + 1;
	
				$wpdb->insert( 
					wpdevart_gallery_databese::$table_names['images'], 
					array(  
						'image_h'           => $image_height,
						'image_w'           => $image_width,
						'image_size'        => $image_size,
						'image_type'        => $imageFileType,
						'url'               => $image_name,
					),array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					)
				);                
				$lastid = $wpdb->insert_id;     
									
				$wpdb->insert( 
					wpdevart_gallery_databese::$table_names['gallery'], 
					array( 
						'time'              => current_time( 'mysql' ), 
						'gallery'           => $gallery_name, 
						'album'             => $album_name, 
						'image_description' => '',
						'image_name' 		=> '&#9654;'.$image_title,
						'order_id'       	=> $max_ord,
						'img_id' 		    => $lastid,
						'published'         => 1,                    
					),array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d'
					)
				);						
			}
			
			// 18-01-2019
			$image_w = $image_width;
			$image_h = $image_height;
			wpda_gall_image_manupulation::create_attachment_page($image_w,$image_h,$image_name);			
			
			return "<script>document.getElementById('tagavor').innerHTML='Video $youtube_url has been uploaded " . $res . "';</script>";
		}else{
			return "<script>document.getElementById('tagavor').innerHTML='The URL not exist,Please type only Youtube or Vimeo Video URL';</script>";
		} 
	}		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	


	///////////////////////////////////////////////////////////////      insert image from media_library
	public function insert_from_upload_images($image_id,$gallery_name,$album_name){
		if ($image_id) {
			$image_post=get_post($image_id);
			$target_dir_upload= get_attached_file( $image_id );
			$image_title=$image_post->post_title;
			$image_description=$image_post->post_content;

			////////	
			global $wpdb;
			$max_ord=$wpdb->get_var( "SELECT MAX(order_id) FROM ".wpdevart_gallery_databese::$table_names['gallery'].";" ) + 1;
                    
			$wpdb->insert( 
				wpdevart_gallery_databese::$table_names['gallery'], 
				array( 
					'time'              => current_time( 'mysql' ), 
					'gallery'           => $gallery_name, 
					'album'             => $album_name, 
					'image_description' => $xxxxx[$image_id]['image_name'],
					'image_name' 		=> $xxxxx[$image_id]['image_name'],
					'order_id'       	=> $max_ord,
                    'img_id' 		    => $image_post->id,
					'published'         => 1,                    
				),array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%d'
				)
			);																			
		}else{
			return 'Not selected image';
		}
	}
	
	
	// insert from standart uploader
	public function insert_from_standart_uploader($files,$gallery_name,$album_name){
		$output_html='';
		global $wpdb;
		if(!empty($files["name"][0])){	  
			$length = count($files["name"]);
			
			$output_html= '<div id="wpda_gall_standart_uploaded_image_status1" class="wpda_gall_standart_uploaded_image_status">';
			if((integer) wpdevart_gallery_start_mem < 128){ini_set('memory_limit', '128M');}                                               //01-11-2017
			for($i = 0; $i < $length; $i++){
				$local_output_html='';
				$image_name = basename($files["name"][$i]);
				$image_title = pathinfo($image_name , PATHINFO_FILENAME);				
				$image_name = preg_replace('/[^A-Za-z0-9\/. -]/', '', preg_replace('/\\s+/', '-',trim($image_name)));	
				$target_file = $this->directorys['original'] . $image_name;	
				$image_uploaded = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				
				// Check if image file is a actual image or fake image
				
				$check = getimagesize($files["tmp_name"][$i]);
				if($check !== false) {			
					$image_size = $files["size"][$i];	                
					$image_width = $check[0];
					$image_height = $check[1];	
					$local_output_html .= "File is an image - " . $check["mime"] . " ";						
					$image_uploaded = 1;
				} else {
					$local_output_html .= "File is not an image. ";
					$image_uploaded = 0;
				}
	
				$image_name_whitouth_info = pathinfo($image_name , PATHINFO_FILENAME);
				$dublicate_counter = '';
				$j = 0;		
				while(file_exists($this->directorys['original'] . $image_name_whitouth_info . $dublicate_counter . '.' . $imageFileType)){											
					$dublicate_counter = '-'.$j;	
					$j++;	
				}										
				$image_name = $image_name_whitouth_info  . $dublicate_counter . '.' . $imageFileType;	
				// Check file size
				if ($files["size"][$i] > wp_max_upload_size()) {
					$local_output_html .= "Sorry, your file is too large.";
					$image_uploaded = 0;
				}
				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
					$local_output_html .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
					$image_uploaded = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($image_uploaded == 0) {
					$local_output_html .= "Sorry, your file was not uploaded. <br>";
				// if everything is ok, try to upload file
				} else {				
					if (move_uploaded_file($files["tmp_name"][$i], $this->directorys['original'] . $image_name)) {
						$this->create_image_duplicates($this->directorys['original'] . $image_name);
						$max_ord=$wpdb->get_var( "SELECT MAX(order_id) FROM ".wpdevart_gallery_databese::$table_names['gallery'].";" )+1;

						$wpdb->insert( 
							wpdevart_gallery_databese::$table_names['images'], 
							array(  
								'image_h'           => $image_height,
								'image_w'           => $image_width,
								'image_size'        => $image_size,
								'image_type'        => $imageFileType,
								'url'               => $image_name,
							),array(
								'%s',
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);                
						$lastid = $wpdb->insert_id;     
											
						$wpdb->insert( 
							wpdevart_gallery_databese::$table_names['gallery'], 
							array( 
								'time'              => current_time( 'mysql' ), 
								'gallery'           => $gallery_name, 
								'album'             => $album_name, 
								'image_description' => '',
								'image_name' 		=> $image_title,
								'order_id'       	=> $max_ord,
								'img_id' 		    => $lastid,
								'published'         => 1,                    
							),array(
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%d',
								'%d',
								'%d'
							)
						);						
									
						$local_output_html .= "The file ". basename( $files["name"][$i]). " has been uploaded. <br>";
					} else {
						$local_output_html .= "Sorry, there was an error uploading your file.<br>";
					}
				}				
				$local_output_html = '<div class="wpda_gall_standart_uploaded_image_info"><span><img src="' . $this->urls['width'].$image_name . '" style="width:60px;height:60px;margin-right:10px;"></span><span>'.$local_output_html.'</span></div>';
				$local_output_html .= '<hr>';
				$output_html .=$local_output_html;
			}
			$output_html .= '</div>';
			ini_set('memory_limit', wpdevart_gallery_start_mem);                                              //01-11-2017
		}
		return $output_html;
	}		
	// insert from standart uploader ajax
/////////////////////////////////////////////////////////////  21-01-2018
	public function insert_from_standart_uploader_ajax($files,$gallery_name,$album_name){
		$echo='';
		global $wpdb;
		$table_gallery = wpdevart_gallery_databese::$table_names['gallery'];
		$table_images = wpdevart_gallery_databese::$table_names['images'];		
		
		if(!empty($files["name"])){	  
			if((integer) wpdevart_gallery_start_mem < 128){ini_set('memory_limit', '128M');}                                         
			$echo='';
			$image_name = basename($files["name"]);				
			$image_name = preg_replace('/[^A-Za-z0-9\/. -]/', '', preg_replace('/\\s+/', '-',trim($image_name)));
			$image_title = pathinfo($image_name , PATHINFO_FILENAME);				
			$target_file = $this->directorys['original'] . $image_name;	
			$image_uploaded = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			$imageFileType = strtolower($imageFileType);//20-01-2019			
			
			// Check if image file is a actual image or fake image
			
			$check = getimagesize($files["tmp_name"]);
			if($check !== false) {			
				$image_size = $files["size"];	                
				$image_width = $check[0];
				$image_height = $check[1];	
				$echo .= "File is an image - " . $check["mime"] . " ";						
				$image_uploaded = 1;
			} else {
				$echo .= "File is not an image. ";
				$image_uploaded = 0;
			}

			$image_name_whitouth_info = pathinfo($image_name , PATHINFO_FILENAME);
			$dublicate_counter = '';
			$j = 0;		
			while(file_exists($this->directorys['original'] . $image_name_whitouth_info . $dublicate_counter . '.' . $imageFileType)){											
				$dublicate_counter = '-'.$j;	
				$j++;	
			}										
			$image_name = $image_name_whitouth_info  . $dublicate_counter . '.' . $imageFileType;	
			// Check file size
			if ($files["size"] > wp_max_upload_size()) {
				$echo .= "Sorry, your file is too large.";
				$image_uploaded = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				$echo .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
				$image_uploaded = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($image_uploaded == 0) {
				$echo .= "Sorry, your file was not uploaded";
			// if everything is ok, try to upload file
			} else {				
				if (move_uploaded_file($files["tmp_name"], $this->directorys['original'] . $image_name)) {
					$this->create_image_duplicates($this->directorys['original'] . $image_name);
					$max_ord=$wpdb->get_var( "SELECT MAX(order_id) FROM ".wpdevart_gallery_databese::$table_names['gallery'].";" )+1;

					$wpdb->insert( 
						wpdevart_gallery_databese::$table_names['images'], 
						array(  
							'image_h'           => $image_height,
							'image_w'           => $image_width,
							'image_size'        => $image_size,
							'image_type'        => $imageFileType,
							'url'               => $image_name,
						),array(
							'%s',
							'%s',
							'%s',
							'%s',
							'%s'
						)
					);                
					$lastid = $wpdb->insert_id;     
										
					$wpdb->insert( 
						wpdevart_gallery_databese::$table_names['gallery'], 
						array( 
							'time'              => current_time( 'mysql' ), 
							'gallery'           => $gallery_name, 
							'album'             => $album_name, 
							'image_description' => '',
							'image_name' 		=> $image_title,
							'order_id'       	=> $max_ord,
							'img_id' 		    => $lastid,
							'published'         => 1,                    
						),array(
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%d',
							'%d',
							'%d'
						)
					);						
								
					$echo .= "The file ". basename( $files["name"]). " has been uploaded.";
					
					// 18-01-2019
					$image_w = $image_width;
					$image_h = $image_height;
					wpda_gall_image_manupulation::create_attachment_page($image_w,$image_h,$image_name);					
					
				} else {
					$echo .= "Sorry, there was an error uploading your file.";
				}
			}				
			ini_set('memory_limit', wpdevart_gallery_start_mem);                                        
		}
		$id =$wpdb->get_var( "SELECT id FROM $table_gallery WHERE order_id='$max_ord'");
	
		$image_obect='{"id":"'.$id.'","image_w":"'.$image_width.'","image_h":"'.$image_height.'","url":"'.$image_name.'","image_type":"'.$imageFileType.'","image_size":"'.$image_size.'","image_name":"'.$image_title.'","time":"'.current_time( 'mysql' ).'","image_description":"","order_id":"'.$max_ord.'","published":"1"}';			
		$image_thumb_url='{"id":'.$lastid.',"thumb_url":"'.$image_name.'"}';
		$gal_alb_names='{"gallery_name":"'.$gallery_name.'","album_name":"'.$album_name.'"}';	
		echo '{"0":'.$image_uploaded.',"1":"'.$echo.'","2":'.$image_obect.',"3":'.$image_thumb_url.',"4":'.$gal_alb_names.'}';
		exit;
	}
	// remove image
	public function remove_images($image_ids){
		global $wpdb;
		if(!is_array($image_ids)){
			$wpdb->delete( wpdevart_gallery_databese::$table_names['gallery'], array( 'id' => $image_ids ), array( '%d' ) );
		}else{
			foreach($image_ids as $id){
				if(is_array($id)){
					return;
				}else{
					$this->remove_images($id);
				}
			}
		}
	}
	//19-01-2019
	public function remove_image_from_url($file_name){
		foreach($this->directorys as $value){
			if($file_name != ''){
				if($value != $this->directorys['attachment']){
					unlink($value.$file_name);
				}else{			
					$image_title_ = pathinfo($file_name , PATHINFO_FILENAME);					
					$file2 = $value . $image_title_ .".html";
					if(file_exists($file2)){
						unlink($file2);
					}										
				}
			}
		}
	}
	
	public function remove_crop_image_from_url($file_name){   
		foreach($this->directorys as $value){
			if($value != $this->directorys['original'] && $value != $this->directorys['attachment'] && $file_name != ''){unlink($value.$file_name);}//18-01-2019
		}
	}	
	
	public function update_image_info($image_id,$image_name,$image_description){
		global $wpdb;
		$wpdb->update( 
			wpdevart_gallery_databese::$table_names['gallery'], 
			array( 
				'image_name' 		 => $image_name,	
				'image_description' => $image_description,
				'time'              => current_time( 'mysql' ),
			), 
			array( 'id' => $image_id ), 
			array( 
				'%s',	
				'%s'	
			), 
			array( '%d' ) 
		);		
	}	

	///////////////// drag-drop functions  8 ///////////////////////////////////////////////
	///////////////// drag-drop functions  8 ///////////////////////////////////////////////
	
	public function drag_img_drop_img_inversia() {
		if(isset($_GET['drop_g']) && isset($_GET['drop_a']) && isset($_GET['drop_i']) && isset($_GET['drop_ord']) && isset($_GET['drag_g']) && isset($_GET['drag_a']) && isset($_GET['drag_i']) && isset($_GET['drag_ord'])){
			global $wpdb;						 					
			$table_name = wpdevart_gallery_databese::$table_names['gallery'];
			
			$drop_g =$_GET['drop_g'];
			$drop_a =$_GET['drop_a'];
			$drop_i =$_GET['drop_i'];
			$drop_ord =$_GET['drop_ord'];
			$drag_g =$_GET['drag_g'];
			$drag_a =$_GET['drag_a'];
			$drag_i =$_GET['drag_i'];
			$drag_ord =$_GET['drag_ord'];	
			
			//11-05-2017
			$wpdb->update( $table_name, array('order_id' => $drop_ord, 'gallery' => $drop_g, 'album' => $drop_a), array('id' => $drag_i) );
			$wpdb->update( $table_name, array('order_id' => $drag_ord, 'gallery' => $drag_g, 'album' => $drag_a), array('id' => $drop_i) );		
			exit;
		}
	}	
	
		
	public function drag_album_drop_album() {
		if(isset($_GET['drop_g']) && isset($_GET['drop_a']) && isset($_GET['drag_g']) && isset($_GET['drag_a'])){
			global $wpdb;						 					
			$table_name = wpdevart_gallery_databese::$table_names['gallery'];
			
			$drop_g =$_GET['drop_g'];
			$drop_a =$_GET['drop_a'];
			$drag_g =$_GET['drag_g'];
			$drag_a =$_GET['drag_a'];
	
			$wpdb->query("UPDATE $table_name SET gallery='$drop_g', album='$drop_a' WHERE gallery='$drag_g' AND album='$drag_a' AND image_name IS NOT NULL");
			exit;
		}
	}	
	
	public function drag_album_drop_album_inversia() {
		if(isset($_GET['drop_g']) && isset($_GET['drop_a']) && isset($_GET['drag_g']) && isset($_GET['drag_a'])){
			global $wpdb;						 					
			$table_name = wpdevart_gallery_databese::$table_names['gallery'];
			
			$drop_g =$_GET['drop_g'];
			$drop_a =$_GET['drop_a'];
			$drag_g =$_GET['drag_g'];
			$drag_a =$_GET['drag_a'];
			$drag_i =$wpdb->get_var( "SELECT id FROM $table_name WHERE gallery='$drag_g' AND album='$drag_a' AND image_name IS NULL" );
			$drop_i =$wpdb->get_var( "SELECT id FROM $table_name WHERE gallery='$drop_g' AND album='$drop_a' AND image_name IS NULL" );
	
			$drag_ord =$wpdb->get_var( "SELECT order_id FROM $table_name WHERE id='$drag_i'" );
			$drop_ord =$wpdb->get_var( "SELECT order_id FROM $table_name WHERE id='$drop_i'" );
		
			if($drag_g != $drop_g){			
				$drag_gallery_albums=$wpdb->get_col( "SELECT DISTINCT `album` FROM ".$table_name." WHERE `gallery` = '$drag_g' And `album` IS NOT NULL And `image_name` IS NULL ORDER BY `order_id` ASC ;" );
				$drop_gallery_albums=$wpdb->get_col( "SELECT DISTINCT `album` FROM ".$table_name." WHERE `gallery` = '$drop_g' And `album` IS NOT NULL And `image_name` IS NULL ORDER BY `order_id` ASC ;" );
				
				$drag_gallery_albums = array_map('strtolower', $drag_gallery_albums);
				$drop_gallery_albums = array_map('strtolower', $drop_gallery_albums);
				
	
				$dublicate_counter = '';
				$j = 0;
		
				while(in_array(strtolower($drag_a.$dublicate_counter), $drop_gallery_albums)){											
					$dublicate_counter = $j;	
					$j++;	
				}										
				$drag_a_dublicat = $drag_a . $dublicate_counter;
				
				$dublicate_counter = '';
				$j = 0;
		
				while(in_array(strtolower($drop_a.$dublicate_counter), $drag_gallery_albums)){												
					$dublicate_counter = $j;	
					$j++;	
				}										
				$drop_a_dublicat = $drop_a  . $dublicate_counter;			
							
				$wpdb->query("UPDATE $table_name SET gallery='$drop_g', album='$drag_a_dublicat' WHERE gallery='$drag_g' AND album='$drag_a'");
				$wpdb->query("UPDATE $table_name SET gallery='$drag_g', album='$drop_a_dublicat' WHERE gallery='$drop_g' AND album='$drop_a'");
				
				$wpdb->query("UPDATE $table_name SET order_id='$drop_ord' WHERE id='$drag_i'");
				$wpdb->query("UPDATE $table_name SET order_id='$drag_ord' WHERE id='$drop_i'");
				
				echo '{"0":"' . $drag_a_dublicat . '", "1":"' . $drop_a_dublicat . '"}';
					
			}else{
				$wpdb->query("UPDATE $table_name SET order_id='$drop_ord' WHERE id='$drag_i'");
				$wpdb->query("UPDATE $table_name SET order_id='$drag_ord' WHERE id='$drop_i'");
				echo '{"0":"' . $drag_a . '", "1":"' . $drop_a . '"}';			
			}
		
			exit;
		}
	}	
	
	public function drag_img_drop_album() {
		if(isset($_GET['drop_g']) && isset($_GET['drop_a']) && isset($_GET['drag_g']) && isset($_GET['drag_a']) && isset($_GET['drag_i'])){
			global $wpdb;						 					
			$table_name = wpdevart_gallery_databese::$table_names['gallery'];
			
			$drop_g =$_GET['drop_g'];
			$drop_a =$_GET['drop_a'];
	
			$drag_g =$_GET['drag_g'];
			$drag_a =$_GET['drag_a'];
			$drag_i =$_GET['drag_i'];	
	
			$wpdb->update( $table_name, array('gallery' => $drop_g, 'album' => $drop_a), array('id' => $drag_i) );
			exit;
		}
	}	

    /*###################### Delete image function ##################*/	
	
	public function drag_img_drop_delet() {
		if(isset($_GET['drag_i'])){
			global $wpdb;						 					
			$table_name = wpdevart_gallery_databese::$table_names['gallery'];
	
			$drag_i =(int)$_GET['drag_i'];
				
			//$this->remove_images($drag_i);		
			$wpdb->delete( wpdevart_gallery_databese::$table_names['gallery'], array( 'id' => $drag_i ), array( '%d' ) );//19-05-2017
	
			exit;
		}
	}		
	public function drag_album_drop_delet() {
		if(isset($_GET['drag_g']) && isset($_GET['drag_a'])){
			global $wpdb;						 					
			$table_name = wpdevart_gallery_databese::$table_names['gallery'];
	
			$drag_g =$_GET['drag_g'];
			$drag_a =$_GET['drag_a'];
	
			//$wpdb->query("DELETE FROM $table_name WHERE gallery='$drag_g' AND image_type IS NOT NULL AND album='$drag_a'");// delet only album images
			$wpdb->query("DELETE FROM $table_name WHERE gallery='$drag_g' AND album='$drag_a'");//global delet album with name
			exit;
		}
	}
	
    /*###################### Delete gallery function ##################*/		
	
	public function drag_gallery_drop_delet() {
		if(isset($_GET['drag_g'])){
			global $wpdb;						 					
			$table_name = wpdevart_gallery_databese::$table_names['gallery'];
	
			$drag_g =$_GET['drag_g'];
			
			//$wpdb->query("DELETE FROM $table_name WHERE gallery='$drag_g' AND album IS NOT NULL");// delet only albums and images from gallery
			$wpdb->query("DELETE FROM $table_name WHERE gallery='$drag_g'");//global delet gallery with name
			exit;
		}
	}

	public function drag_gallery_drop_gallery_inversia() {
		if(isset($_GET['drop_g']) && isset($_GET['drag_g'])){
			global $wpdb;						 					
			$table_name = wpdevart_gallery_databese::$table_names['gallery'];
			
			$drop_g =$_GET['drop_g'];
			$drag_g =$_GET['drag_g'];
	
			$drag_i =$wpdb->get_var( "SELECT id FROM $table_name WHERE gallery='$drag_g' AND album IS NULL" );
			$drop_i =$wpdb->get_var( "SELECT id FROM $table_name WHERE gallery='$drop_g' AND album IS NULL" );
			
			$drag_ord =$wpdb->get_var( "SELECT order_id FROM $table_name WHERE gallery='$drag_g' AND album IS NULL" );
			$drop_ord =$wpdb->get_var( "SELECT order_id FROM $table_name WHERE gallery='$drop_g' AND album IS NULL" );
			
			$wpdb->query("UPDATE $table_name SET order_id='$drop_ord' WHERE id='$drag_i'");
			$wpdb->query("UPDATE $table_name SET order_id='$drag_ord' WHERE id='$drop_i'");			
			exit;
		}
	}	
	/////////////////end drag-drop functions  ///////////////////////////////////////////////
	/////////////////end drag-drop functions  ///////////////////////////////////////////////
		
		
	public function form_album_rename() {
		global $wpdb;						 					
        $table_name = wpdevart_gallery_databese::$table_names['gallery'];
		
		$form_album_index_name =$_GET['form_album_index_name'];
		$form_gallery_index_name =$_GET['form_gallery_index_name'];
		$form_album_index_old_name =$_GET['form_album_index_old_name'];

		$wpdb->query("UPDATE $table_name SET gallery='$form_gallery_index_name', album='$form_album_index_name' WHERE gallery='$form_gallery_index_name' AND album='$form_album_index_old_name'");
		exit;
	}
	
    /*###################### Delete album function ##################*/	
	
	public function form_album_delete() {
		global $wpdb;						 					
       // $table_name = wpdevart_gallery_databese::$table_names['gallery'];
		$table_gallery = wpdevart_gallery_databese::$table_names['gallery'];//19-05-2017
		$table_images = wpdevart_gallery_databese::$table_names['images'];//19-05-2017

		$form_gallery_index_name =$_GET['form_gallery_index_name'];
		$form_album_index_old_name =$_GET['form_album_index_old_name'];
		
		//$wpdb->query("DELETE FROM $table_name WHERE gallery='$form_gallery_index_name' AND album='$form_album_index_old_name'");//global delet album with name
		$wpdb->query("DELETE FROM $table_gallery WHERE gallery='$form_gallery_index_name' AND album='$form_album_index_old_name'");//19-05-2017

		exit; 
	}		

    /*###################### Rename gallery function ##################*/
	
	public function form_gallery_rename() {
		global $wpdb;						 					
        $table_name = wpdevart_gallery_databese::$table_names['gallery'];
		
		$form_gallery_index_name =$_GET['form_gallery_index_name'];
		$form_gallery_index_old_name =$_GET['form_gallery_index_old_name'];

		$wpdb->query("UPDATE $table_name SET gallery='$form_gallery_index_name' WHERE gallery='$form_gallery_index_old_name'");
		exit;
	}	
	public function form_gallery_delete() {
		global $wpdb;						 					
       // $table_name = wpdevart_gallery_databese::$table_names['gallery'];
		$table_gallery = wpdevart_gallery_databese::$table_names['gallery'];//19-05-2017
		$table_images = wpdevart_gallery_databese::$table_names['images'];//19-05-2017		

		$form_gallery_index_old_name =$_GET['form_gallery_index_old_name'];
	
		//$wpdb->query("DELETE FROM $table_name WHERE gallery='$form_gallery_index_old_name'");//global delet album with name
		$wpdb->query("DELETE FROM $table_gallery WHERE gallery='$form_gallery_index_old_name'");//19-05-2017
		exit; 
	}	
	
    /*###################### Create gallery function ##################*/	
	
	public function form_create_gallery() {
		global $wpdb;						 					
        $table_name = wpdevart_gallery_databese::$table_names['gallery'];
		$max_ord=$wpdb->get_var( "SELECT MAX(order_id) FROM ".wpdevart_gallery_databese::$table_names['gallery'].";" ) + 1;
		$drag_g =$_GET['drag_g'];
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'time'              => current_time( 'mysql' ), 
				'gallery'           => $drag_g, 
				'album'             => NULL, 
				'image_description' => NULL,
				'image_name' 		=> NULL,
				'order_id'       	=> $max_ord,
				'published'       	=> 1,
			),array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d'
			)
		);		
	
		exit; 
	}
	public function form_create_album() {
		global $wpdb;						 					
        $table_name = wpdevart_gallery_databese::$table_names['gallery'];
		$max_ord=$wpdb->get_var( "SELECT MAX(order_id) FROM ".wpdevart_gallery_databese::$table_names['gallery'].";" ) + 1;
		$drag_g =$_GET['drag_g'];
		$drag_a =$_GET['drag_a'];
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'time'              => current_time( 'mysql' ), 
				'gallery'           => $drag_g, 
				'album'             => $drag_a, 
				'image_description' => NULL,
				'image_name' 		=> NULL,
				'order_id'       	=> $max_ord,
				'published'       	=> 1,
			),array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d'
			)
		);				
		exit; 
	}
	public function form_edit_image() {
		global $wpdb;						 					
        $table_name = wpdevart_gallery_databese::$table_names['gallery'];
		

		$form_img_index_id =$_GET['form_img_index_id'];
		$form_img_index_name =$_GET['form_img_index_name'];
		$form_img_index_description =$_GET['form_img_index_description'];

		$wpdb->query("UPDATE $table_name SET image_name='$form_img_index_name', image_description='$form_img_index_description' WHERE id='$form_img_index_id'");
		exit;
	}
	

	//18-05-2017
	public function visibility() {
		global $wpdb;						 					
        $table_name = wpdevart_gallery_databese::$table_names['gallery'];
		
		$visibility_id =$_GET['visibility_id'];
		$visibility_value =$_GET['visibility_value'];
		$visibility_value = ($visibility_value =='visibility')?0:1;

		$wpdb->query("UPDATE $table_name SET published='$visibility_value' WHERE id='$visibility_id'");
		exit;
	}
	

	/////////////////////////////15-10-2017    create_image_duplicates_from_original_directory
	public function recrop_image(){
		$recrop_image=$_GET["recrop_image"];
		$file_name = scandir($this->directorys['original']);
		$count_file_name=count($file_name) - 2;
		$this->remove_crop_image_from_url($file_name[$recrop_image+2]);
		if((integer) wpdevart_gallery_start_mem < 128){ini_set('memory_limit', '128M');}                                               //01-11-2017
		//$this->create_image_duplicates($this->directorys['original'] . $file_name[$recrop_image+2]);
		$this->create_image_duplicates_recrop($this->directorys['original'] . $file_name[$recrop_image+2]);//18-01-2019
		ini_set('memory_limit', wpdevart_gallery_start_mem);                                                                             //01-11-2017
		echo $count_file_name;			
		exit;
	}
	//////////////////////  21-01-2018
	
	
	///////////////// ajax upload image, rotate image   /////////////////////////////////////////	
	///////////////// ajax upload image, rotate image   /////////////////////////////////////////	
	///////////////// ajax upload image, rotate image   /////////////////////////////////////////
	
	//1  ajax upload image
	public function upload_image(){	
		if(isset($_FILES['input_files']) && isset($_POST['gallery_name']) && isset($_POST['album_name'])){
			$gallery_name	=$_POST['gallery_name'];
			$album_name		=$_POST['album_name'];						
			$this->insert_from_standart_uploader_ajax($_FILES['input_files'],$gallery_name,$album_name);
		}
	}


	//2 ajax image rotate
	public function image_rotate() {
		if(isset($_POST['image_name']) && isset($_POST['gallery_name']) && isset($_POST['album_name']) && isset($_POST['degree'])){
	
			$gallery_name =$_POST['gallery_name'];
			$album_name =$_POST['album_name'];
			$image_name =$_POST['image_name'];
			$degree =$_POST['degree'];
			$main_image_path=$this->directorys['original'].$image_name;
			
			$this->create_image_duplicates_rotate($main_image_path,$gallery_name,$album_name,$degree);
		}
	}
		
    //3 dublicates original image for ajax rotate
	private function create_image_duplicates_rotate($main_image_path,$gallery_name,$album_name,$degree){
		global $wpdb;
        $table_name = wpdevart_gallery_databese::$table_names['gallery'];
		$table_images = wpdevart_gallery_databese::$table_names['images'];
		
		//rotate image original and save
		$this->rotate($main_image_path, $degree);
		
		//greate duplicates from original image
		$this->create_image_duplicates($main_image_path);
		
		//replace image width and height in table		
		$image_name = basename($main_image_path );
		$id =$wpdb->get_var( "SELECT id FROM $table_images WHERE url='$image_name'");
		$img_w =$wpdb->get_var( "SELECT image_w FROM $table_images WHERE id='$id'");
		$img_h =$wpdb->get_var( "SELECT image_h FROM $table_images WHERE id='$id'");		
		$wpdb->query("UPDATE $table_images SET image_w='$img_h', image_h='$img_w' WHERE id='$id'");
		//19-01-2019		
		$image_w = $img_h;
		$image_h = $img_w;
		wpda_gall_image_manupulation::create_attachment_page($image_w,$image_h,$image_name);		
		
		echo $image_name;
		exit;		
	}
	//4 dublicates original image for form rotate
	public function create_image_duplicates_rotate_form($img_id,$degree){
		global $wpdb;
        $table_name = wpdevart_gallery_databese::$table_names['gallery'];
		$table_images = wpdevart_gallery_databese::$table_names['images'];
		$id =$wpdb->get_var( "SELECT img_id FROM $table_name WHERE id='$img_id'");
		$image_name =$wpdb->get_var( "SELECT url FROM $table_images WHERE id='$id'");
		$main_image_path=$this->directorys['original'].$image_name;
		
		//rotate image original and save
		$this->rotate($main_image_path, $degree);
		
		//greate duplicates from original image
		$this->create_image_duplicates($main_image_path);
				
		//replace image width and height in table
		$img_w =$wpdb->get_var( "SELECT image_w FROM $table_images WHERE id='$id'");
		$img_h =$wpdb->get_var( "SELECT image_h FROM $table_images WHERE id='$id'");		
		$wpdb->query("UPDATE $table_images SET image_w='$img_h', image_h='$img_w' WHERE id='$id'");
		
		//19-01-2019	
		$image_w = $img_h;
		$image_h = $img_w;
		wpda_gall_image_manupulation::create_attachment_page($image_w,$image_h,$image_name);			
	}		
		
	
	//5 rotate original image	
	private function rotate($filename, $degrees){	
		list($width, $height, $type, $attr) = getimagesize($filename);
		$size = getimagesize($filename);
					  
		switch($size['mime']){
			case 'image/jpeg':
				 $source = imagecreatefromjpeg($filename);
				break;
			case 'image/png':
				 $source = imagecreatefrompng($filename);
				break;
			case 'image/gif':
				 $source = imagecreatefromgif($filename);
				break;
		}			  
	
		$bgColor=imageColorAllocateAlpha($source, 0, 0, 0, 0);
		$rotation = imagerotate($source,$degrees,$bgColor);
		imagealphablending($rotation, false);
		imagesavealpha($rotation, true);
		imagecreate($width,$height);
		$image_name=basename($filename);

		foreach($this->directorys as $value){
			if($value != $this->directorys['attachment']){
				unlink($value.$image_name);
			}
		}		
		
				  
		switch($size['mime']){
			case 'image/jpeg':
				imagejpeg($rotation,$filename);
				break;
			case 'image/png':
				imagepng($rotation,$filename);
				break;
			case 'image/gif':
				imagegif($rotation,$filename);
				break;
		}
		chmod($filename, 0777);
	
	}	
	/////////////////end ajax upload image, rotate image   /////////////////////////////////////////	
	/////////////////end ajax upload image, rotate image   /////////////////////////////////////////	
	/////////////////end ajax upload image, rotate image   /////////////////////////////////////////		
									
}

$aaaaaa=new wpda_gall_image_manupulation();


class wpda_gall_image{
	var $image;
	var $image_type;
	var $image_w;   
	var $image_h;
	var $image_size;   	   
	function load($filename) {
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		$this->image_w = $image_info[0];
		$this->image_h = $image_info[1];
		$this->image_size = $image_info[3];	  
		if( $this->image_type == IMAGETYPE_JPEG ) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == IMAGETYPE_GIF ) {
			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == IMAGETYPE_PNG ) {
			$this->image = imagecreatefrompng($filename);
		}
	}
	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=100, $permissions=null) {
		if( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image,$filename,$compression);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			imagegif($this->image,$filename);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			imagepng($this->image,$filename);
		}
		if( $permissions != null) {
			chmod($filename,$permissions);
		}
	}
	function output($image_type=IMAGETYPE_JPEG) {
		if( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			imagegif($this->image);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			imagepng($this->image);
		}
	}
	function getWidth() {
		return imagesx($this->image);
	}
	function getHeight() {
		return imagesy($this->image);
	}
	function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}
	function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width,$height);
	}
	function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->resize($width,$height);
	}
	function resize($width,$height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}	
}
/////                 
?>