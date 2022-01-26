<?php


namespace TLBM\Admin\WpForm;

if ( ! defined('ABSPATH')) {
    return;
}

class DaysTypeField extends FormFieldBase
{

    public $day_types;

    public function __construct($name, $title, $day_types = false, $value = "")
    {
        $this->day_types = ! $day_types ? array("") : $day_types;

        parent::__construct($name, $title, $value);
    }

    public function displayContent(): void
    {
        ?>
        <tr>
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <?php
                foreach ($this->day_types as $day_type): ?>
                    <p><label><input type="checkbox" name="<?php
                            echo $this->name ?>[]"> <?php
                            echo $day_type ?> </label></p>
                <?php
                endforeach; ?>
            </td>
        </tr>
        <?php
    }
}