<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class JoomsportLogosliderwp {
    public $seasonID;
    public $categoryID;

    public function __construct($post_id)
    {
        $post = get_post($post_id);
        if($post->post_type != 'joomsport_season'){
            die("Error");
        }
        $this->seasonID = $post_id;
    }

    public function addSlider(){
        $this->addCategory();
        $teams = JoomSportHelperObjects::getParticipiants($this->seasonID);
        if(count($teams)){
            foreach ($teams as $team){
                $this->addLogo($team);
            }
        }

    }

    public function addCategory(){
        $seasonName = '';
        $term_list = get_the_terms($this->seasonID, 'joomsport_tournament');
        if(count($term_list)) {
            $term_meta = get_option("taxonomy_" . $term_list[0]->term_id . "_metas");
            $season_name = esc_attr(get_the_title($this->seasonID));
            $seasonName = esc_attr($term_list[0]->name) . ' ' . $season_name;
        }

        $res = wp_insert_term(
            esc_attr($seasonName),   // the term
            'logosliderwpcat', // the taxonomy
            array(
                'description' => $seasonName,
                'slug'        => esc_attr($seasonName),
                'parent'      => 0,
            )
        );
        if( is_wp_error( $res ) ) {
            //echo "<p class='notice notice-error'>".$res->get_error_message()."</p>";
            return $res;
        }
        if(isset($res['term_id']) && $res['term_id']){
            add_term_meta( $res['term_id'], "meta_key_seasonID", $this->seasonID, true );
        }
        $this->categoryID = intval($res['term_id']);
    }

    public function checkCategory(){
        $terms = get_terms( array(
            'taxonomy' => 'logosliderwpcat',
            'hide_empty' => false,
            'meta_query' => array(
                [
                    'key' => 'meta_key_seasonID',
                    'value' => $this->seasonID
                ]
            ),

        ) );

        return $terms;
    }

    public function addLogo($teamID){
        $arr = array(
            'post_type' => "logosliderwp",
            'post_title' => wp_strip_all_tags( get_the_title($teamID) ),
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => get_current_user_id()
        );

        $post_id = wp_insert_post( $arr );
        if($post_id){
            $saveableData = array();

            $link = get_permalink($teamID);
            if($this->seasonID){
                $link = add_query_arg( 'sid', $this->seasonID, $link );
            }

            $saveableData['company_url']  = esc_url( $link );
            $saveableData['company_name']  = sanitize_text_field( get_the_title($teamID) );
            $saveableData['company_desc']  = sanitize_textarea_field( get_the_title($teamID) );

            update_post_meta( $post_id, '_logosliderwpmeta', $saveableData );

            $thumb = get_post_thumbnail_id($teamID);
            if($thumb){
                $success = set_post_thumbnail( $post_id, $thumb );
            }else{
                $defaultLogo = plugin_dir_url( __FILE__ ).'../sportleague'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR."teams_st.png";

                $imgA = explode("/", $defaultLogo);
                if(count($imgA) > 1){
                    $image_name = $imgA[count($imgA) - 1];
                }else{
                    return 0;
                }
                $upload_dir       = wp_upload_dir(); // Set upload folder

                $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
                $filename         = basename( $unique_file_name ); // Create image file name

                // Check folder permission and define file location
                if( wp_mkdir_p( $upload_dir['path'] ) ) {
                    $file = $upload_dir['path'] . '/' . $filename;
                } else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                }
                if(copy($defaultLogo, $file)) {

                    $wp_filetype = wp_check_filetype($defaultLogo, null);

                    // Set attachment data
                    $attachment = array(
                        'guid' => $upload_dir['url'] . '/' . basename( $filename ),
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => sanitize_file_name($filename),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    // Create the attachment
                    $attach_id = wp_insert_attachment($attachment, $file, 0);

                    // Include image.php
                    require_once(ABSPATH . 'wp-admin/includes/image.php');

                    // Define attachment metadata
                    $attach_data = wp_generate_attachment_metadata($attach_id, $file);

                    // Assign metadata to attachment
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    $success = set_post_thumbnail($post_id, $attach_id);
                }

            }

            wp_set_post_terms( $post_id, array((int)$this->categoryID), 'logosliderwpcat');

        }

        return $post_id;

    }
}