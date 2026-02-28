<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class classJsportRequest
{
    public static function get($var, $request = 'request', $type = 'string')
    {
        switch ($request) {
            case 'post':
                $return = isset($_POST[$var]) ? sanitize_text_field($_POST[$var]) : '';

                break;
            case 'get':
                $return = isset($_GET[$var]) ? sanitize_text_field($_GET[$var]) : '';

                break;
            default:
                $return = isset($_REQUEST[$var]) ? sanitize_text_field($_REQUEST[$var]) : '';
                break;
        }

        switch ($type) {
            case 'int':
                $return = intval($return);

                break;
            case 'float':
                $return = floatval($return);

                break;
            default:
                break;
        }

        return $return;
    }
}
