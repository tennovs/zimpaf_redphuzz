<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class classExtrafieldSelect
{
    public static function getValue($ef)
    {
        
        $evAr = jsHelperEventsSelvar::getInstance();
        return $evAr[(int) $ef];
    }
}
