<?php


namespace TLBM\Admin\WpForm;

if ( !defined('ABSPATH')) {
    return;
}

class SelectField extends FormFieldBase
{

    public array $options = array();

    /**
     * @param string $name
     * @param string $title
     * @param array $options
     */
    public function __construct(string $name, string $title, array $options)
    {
        $this->options = $options;
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
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <select name="<?php
                echo $this->name ?>">
                    <?php
                    foreach ($this->options as $key => $option): ?>
                        <option <?php
                        echo $value == $key ? "selected='selected'" : "" ?> value="<?php
                        echo $key ?>">
                            <?php
                            echo $option ?>
                        </option>
                    <?php
                    endforeach; ?>
                </select>
            </td>
        </tr>
        <?php
    }
}