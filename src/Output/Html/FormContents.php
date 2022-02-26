<?php


namespace TLBM\Output\Html;


class FormContents
{

    public static function GetTimeHoursSelectOptions($selected = ""): string
    {
        $html = '';
        for ($i = 0; $i < 24; $i++) {
            $html .= '<option ' . ($selected == "'.$i.'" ? 'selected="selected"' : '') . ' value="' . $i . '">' . $i . '</option>';
        }

        return $html;
    }

    public static function GetTimeMinutesSelectOptions($selected = ""): string
    {
        $html = '';
        for ($i = 0; $i <= 59; $i++) {
            $html .= '<option ' . ($selected == "'.$i.'" ? 'selected="selected"' : '') . ' value="' . $i . '">' . $i . '</option>';
        }

        return $html;
    }
}