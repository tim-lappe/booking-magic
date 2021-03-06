<?php


namespace TLBM\Admin\WpForm;

if ( !defined('ABSPATH')) {
    return;
}

class DaysTypeField extends FormFieldBase
{

    public $day_types;

    public function __construct($name, $title, $day_types = false)
    {
        $this->day_types = !$day_types ? array("") : $day_types;

        parent::__construct($name, $title);
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        ?>
        <tr>
            <th scope="row">
                <label for="<?php echo $this->escaping->escAttr($this->name) ?>">
                    <?php echo $this->escaping->escHtml($this->title); ?>
                </label>
            </th>
            <td>
                <?php
                foreach ($this->day_types as $day_type): ?>
                    <p>
                        <label>
                            <input type="checkbox" name="<?php echo $this->escaping->escAttr($this->name) ?>[]">
                            <?php echo $this->escaping->escHtml($day_type); ?>
                        </label>
                    </p>
                <?php
                endforeach; ?>
            </td>
        </tr>
        <?php
    }
}