<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class classExtrafieldLink
{
    public static function getValue($ef)
    {
        $html = '';
        if ($ef) {
            $html = "<a target='_blank' href='".((substr($ef, 0, 7) == 'http://' || substr($ef, 0, 8) == 'https://') ? $ef : 'http://'.$ef)."'>".$ef.'</a>';
        }

        return $html;
    }
}
