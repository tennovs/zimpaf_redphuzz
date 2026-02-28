<?php

/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

class JoomsportTemplates {
    
    public static function init() {
        //add_filter('single_template', array('JoomsportTemplates','joomsport_single_template'));
        //add_filter('taxonomy_template', array('JoomsportTemplates','joomsport_category_template'));

            add_action( 'parse_request', array('JoomsportTemplates', 'joomsport_parse_request') );

            add_filter( 'the_content', array( 'JoomsportTemplates', 'joomsport_content' ) );

            add_filter('template_include',array( 'JoomsportTemplates', 'override_tax_template'));
            //add_filter('post_thumbnail_html', array( 'JoomsportTemplates', 'joomsport_thumb_modify' ), 99, 5);


    }

    public static function override_tax_template($template){
        // is a specific custom taxonomy being shown?
        $taxonomy_array = array('joomsport_club');
        foreach ($taxonomy_array as $taxonomy_single) {
            if ( is_tax($taxonomy_single) ) {
                if(file_exists(JOOMSPORT_PATH . 'templates' .DIRECTORY_SEPARATOR. 'taxonomy-'.$taxonomy_single.'.php')) {
                    $template = JOOMSPORT_PATH . 'templates' .DIRECTORY_SEPARATOR. 'taxonomy-'.$taxonomy_single.'.php';

                }

                break;
            }
        }

        return $template;
    }

    public static function joomsport_single_template($single){
        
        global $post, $post_type;
        $post_for_check = array(
            'joomsport_season',
            'joomsport_team',
            'joomsport_player',
            'joomsport_match',
            'joomsport_venue',
            'joomsport_person',
            );
        if( in_array($post_type, $post_for_check) ){
            $wp_template_path = get_template_directory();
            if(file_exists($wp_template_path . '/single-' . $post->ID . '.php'))
                    return $wp_template_path . '/single-' . $post->ID . '.php';
            if(file_exists(JOOMSPORT_PATH. 'templates'.DIRECTORY_SEPARATOR.'single.php')){
                return JOOMSPORT_PATH. 'templates'.DIRECTORY_SEPARATOR.'single.php';
            }
        }
	
    }
    public static function joomsport_category_template($template){
        if( get_query_var('joomsport_tournament') || get_query_var('joomsport_matchday') || get_query_var('joomsport_club')){
            $wp_template_path = get_template_directory();

            if(file_exists(JOOMSPORT_PATH. 'templates'.DIRECTORY_SEPARATOR.'single.php')){
                return JOOMSPORT_PATH. 'templates'.DIRECTORY_SEPARATOR.'single.php';
                die();
            }
        }
	
    }
    public static function joomsport_parse_request( &$wp )
        {
            if (isset($_REQUEST['wpjoomsport'])) {
                include JOOMSPORT_PATH. 'templates'.DIRECTORY_SEPARATOR.'single_1.php';
                exit();
            }

            return;
        }
        
    public static function joomsport_content($content){

        if ( !in_the_loop() ) return $content;
        global $controllerSportLeague;
        remove_filter('has_post_thumbnail', "joomsport_filter_has_team_thumb");
        remove_filter('post_thumbnail_html', 'joomsport_filter_pt');
        if(is_singular('joomsport_team')
                || is_singular('joomsport_season')
                || is_singular('joomsport_venue')
                || is_singular('joomsport_match')
                || is_singular('joomsport_player')
                || is_singular('joomsport_person')
            //|| is_tax('joomsport_club')
                //|| is_tax('joomsport_tournament')
                //|| is_tax('joomsport_matchday')
                || isset($_REQUEST['wpjoomsport'])
                ){

            require JOOMSPORT_PATH . 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';

            if ( post_password_required() ) {
                echo get_the_password_form();
                return;
            }
            ob_start();
            $controllerSportLeague->execute();
            return ob_get_clean();
            
        }
        return $content;
    }   
    public static function joomsport_thumb_modify($content){
         /*if(is_singular('joomsport_team')
                || is_singular('joomsport_player')
                ){
             return '';
         }*/
        return $content;
    }
    
}


JoomsportTemplates::init();


add_filter('post_thumbnail_html', 'joomsport_filter_pt',99,5);
function joomsport_filter_pt($html, $post_id, $post_thumbnail_id, $size, $attr) {
    global $post_type;
    if ( !in_the_loop() ){return $html;};
    $width = JoomsportSettings::get('set_emblemhgonmatch', 60);
    if($post_type == 'joomsport_team'){
        $src = wp_get_attachment_image_src(get_post_thumbnail_id(), array($width,'0'));
        $html = '<img src="' . $src['0'] . '" width="'.$width.'" />';

        if(JoomsportSettings::get('enabl_team_featimg', 1) == 0){
            $html = '';
        }
    }
    return $html;
}

add_filter( 'has_post_thumbnail', 'joomsport_filter_has_team_thumb', 10, 3 );
function joomsport_filter_has_team_thumb( $has_thumbnail, $post, $thumbnail_id ){
    global $post_type;
    if ( !in_the_loop() ){return $has_thumbnail;};
    if($post_type == 'joomsport_team'){
        if(JoomsportSettings::get('enabl_team_featimg', 1) == 0){
            return false;
        }
    }

    return $has_thumbnail;
}
