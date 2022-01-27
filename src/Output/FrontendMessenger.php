<?php


namespace TLBM\Output;


if ( !defined('ABSPATH')) {
    return;
}

class FrontendMessenger
{

    private static array $frontend_msgs = array();

    public static function AddFrontendMsg($html)
    {
        self::$frontend_msgs[] = $html;
    }

    public static function GetMessangesPrint(): string
    {
        if (sizeof(self::$frontend_msgs) > 0) {
            $html = '<div class="tlbm-messages">';
            foreach (self::$frontend_msgs as $msg) {
                $html .= "$msg<br>";
            }
            $html .= "</div>";

            return $html;
        }

        return "";
    }
}