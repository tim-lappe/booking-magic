<?php


namespace TLBM\Admin\WpForm;

if ( !defined('ABSPATH')) {
    return;
}

class RadioField extends FormFieldBase
{

    public array $radios = array();

    /**
     * @param string $name
     * @param string $title
     * @param array $radios
     */
    public function __construct(string $name, string $title, array $radios)
    {
        $this->radios = $radios;
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
            <th scope="row"><?php
                echo $this->title ?></th>
            <td>
                <?php
                foreach ($this->radios as $key => $text): ?>
                    <div class="tlbm-radio-item">
                        <label>
                            <input
                                <?php
                                echo $key == $value && !empty($value) ? "checked='checked'" : "" ?>
                                    id="<?php
                                    echo $this->name ?>-<?php
                                    echo $key ?>"
                                    class="regular-text"
                                    type="radio"
                                    name="<?php
                                    echo $this->name ?>"
                                    value="<?php
                                    echo $key ?>"/>
                            <?php
                            echo $text ?>
                        </label>
                    </div>
                <?php
                endforeach; ?>
            </td>
        </tr>
        <?php
    }
}