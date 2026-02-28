<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class jsHelperImages
{
    public static function getEmblem($img, $type = 0, $class = '', $width = 0, $alt = '')
    {

        if ($width === 0) {
            $width = JoomsportSettings::get('teamlogo_height', 40);
        }
        if(is_array($img)){
            
            $image_arr = wp_get_attachment_image_src($img['id'], array(JoomsportSettings::get('teamlogo_height',40),'0'));
            if (($image_arr[0])) {
                $img = $image_arr[0];
            }else{
                $img = $img['src'];
            }
        }
        $defimg = $type ? JSCONF_TEAM_DEFAULT_IMG : JSCONF_PLAYER_DEFAULT_IMG;

        $html = '';
        $resize_to = 150;

        if ($width < 40) {
            $class .= ' emblpadd3';
        }

        $width = $width + 10;
        if ($img) {
            $html = '<img class="img-thumbnail img-responsive'.$class.'" src="'.$img.'" '.($width?'width="'.$width.'"':"").'  style="max-width: '.$width.'px;"  alt="'.addslashes($alt).'" title="'.addslashes($alt).'" />';
        } else {
            $html = '<img class="img-thumbnail img-responsive'.$class.'" src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.$defimg.'" width="'.$width.'"  style="max-width: '.$width.'px;"  alt="'.addslashes($alt).'" title="'.addslashes($alt).'" />';
        }

        return $html;
    }
    public static function getEmblemBig($img, $type = 1, $class = 'emblInline', $width = '0', $light = true, $alt = '')
    {
        $img_full = '';
        if(is_array($img)){
            $img_full = wp_get_attachment_image_src($img["id"], 'full');
            $img = $img['src'];
        }
        
        $add_styles = '';
        if (!$width) {
            $width = JoomsportSettings::get('set_defimgwidth', 200);
            $add_styles = 'style="width:'.$width.'px;max-width:'.$width.'px;"';
        }
        if($type == '10'){
            $width = '100%';
            $add_styles = '';
        }
        $html = '';
        $resize_to = 300;

        if ($img) {
            if ($light) {
                $html = '<a class="jsLightLink" href="'.(isset($img_full[0])?$img_full[0]:$img).'" data-lightbox="jsteam'.$type.'">';
            }
            $html .= '<img class="img-thumbnail img-responsive" src="'.$img.'" width="'.$width.'"  '.$add_styles.'  alt="'.addslashes($alt).'" title="'.addslashes($alt).'" />';
            if ($light) {
                $html .= '</a>';
            }
        } else {
            $html = '<img class="img-thumbnail img-responsive" src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.JSCONF_PLAYER_DEFAULT_IMG.'" width="'.$width.'"  '.$add_styles.'  alt="'.addslashes($alt).'" title="'.addslashes($alt).'" />';
        }

        return $html;
    }
    public static function getEmblemEvents($img, $type = 0, $class = '', $width = 24, $alt = '')
    {
        $html = '';
        $resize_to = 40;
        if ($width < 40) {
            $class .= ' emblpadd3';
        }
        $imgpath = wp_get_attachment_image_src($img);

        if(isset($imgpath[0]) && $imgpath[0]){
        $html .= '<img class="img-responsive '.$class.'"  src="'.$imgpath[0].'" style="max-width:'.$width.'px;" width="'.$width.'" alt="'.addslashes($alt).'" title="'.addslashes($alt).'" />';
        }
        return $html;
    }
}
