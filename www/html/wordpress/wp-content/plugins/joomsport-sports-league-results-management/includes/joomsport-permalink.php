<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */



add_filter('the_title', 'jоomsport_filter_seasontitle', 999, 2);
function jоomsport_filter_seasontitle($title, $id) {
    global $wpdb, $post_type, $post, $pagenow;
    /*if( is_admin() || !in_the_loop() ){
        return $title;
    }*/
    if($pagenow == 'nav-menus.php'){
        $tpost  = get_post($id);
        if($tpost->post_type == 'joomsport_season'){
            $terms = wp_get_object_terms( $id, 'joomsport_tournament' );
            $post_name = '';
            if( $terms ){

                $post_name .= $terms[0]->name;
            }
            $post_name .= " ".$title;
            //remove_filter( 'the_title', 'jоomsport_filter_seasontitle' );
            return $post_name;
        }
    }
    if(!$post){
        return $title;
    }

    if ( !in_the_loop() ) return $title;

    if($title != $post->post_title){
        return $title;
    }
    if($id != $post->ID){
        return $title;
    }
    if($post_type == 'joomsport_season'){
        $terms = wp_get_object_terms( $post->ID, 'joomsport_tournament' );
        $post_name = '';
        if( $terms ){

            $post_name .= $terms[0]->name;
        }
        $post_name .= " ".$title;
        //remove_filter( 'the_title', 'jоomsport_filter_seasontitle' );
        return $post_name;
    }/*elseif($post_type == 'joomsport_match'){
        $m_date = get_post_meta($post->ID,'_joomsport_match_date',true);
        if($m_date){
            $m_date_str = explode("-", $m_date);
            if(count($m_date_str)){
                $m_date = $m_date_str[2].".".$m_date_str[1].".".$m_date_str[0];
            }
        }
        $post_name = $m_date." ".$title;
        return $post_name;
    }*/
    return $title;
}
add_filter( 'document_title_parts', function( $title_parts_array ) {
    global $wpdb, $post_type, $post;

    if(!$post){
        return $title_parts_array;
    }
    if($post_type == 'joomsport_season'){
        $terms = wp_get_object_terms( $post->ID, 'joomsport_tournament' );
        $post_name = '';
        if( $terms ){

            $post_name .= $terms[0]->name;
        }
        //$post_name .= " ".$title;
        $title_parts_array['title'] =  $post_name ." ".$title_parts_array['title'];
    }

    return $title_parts_array;
} );
add_filter( 'pre_get_document_title', function( $title )
  {
    global $wpdb, $post_type, $post;
    if(!$title){
        return '';
    }
    if(!$post){
        return $title;
    }
    if($post_type == 'joomsport_season'){
        $terms = wp_get_object_terms( $post->ID, 'joomsport_tournament' );
        $post_name = '';
        if( $terms ){

            $post_name .= $terms[0]->name;
        }
        $title =  $post_name ." ".$title;
    }
    /*elseif($post_type == 'joomsport_match'){
        $m_date = get_post_meta($post->ID,'_joomsport_match_date',true);
        if($m_date){
            $m_date_str = explode("-", $m_date);
            if(count($m_date_str)){
                $m_date = $m_date_str[2].".".$m_date_str[1].".".$m_date_str[0];
            }
        }
        $post_name = $m_date." ".$title;
        return $post_name;
    }*/

    return $title;
  }, 999, 1 );
/*add_filter( 'aioseop_title', 'allinone_jsport_wordpress_seo_title' );

function allinone_jsport_wordpress_seo_title( $title ){
    global $wpdb, $post_type, $post;

    if($post_type == 'joomsport_match'){
        $m_date = get_post_meta($post->ID,'_joomsport_match_date',true);
        if($m_date){
            $m_date_str = explode("-", $m_date);
            if(count($m_date_str)){
                $m_date = $m_date_str[2].".".$m_date_str[1].".".$m_date_str[0];

                $title = str_replace("%match_date%", $m_date, $title);
            }
        }
        $title = str_replace("%match_date%", "", $title);

    }
    return $title;
}*/