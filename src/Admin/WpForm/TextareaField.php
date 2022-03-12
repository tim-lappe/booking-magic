<?php

namespace TLBM\Admin\WpForm;

class TextareaField extends FormFieldBase
{


    public function __construct(string $name, string $title = "")
    {
        parent::__construct($name, $title);
    }

    /**
     * @inheritDoc
     */
    public function displayContent($value): void
    {
        ?>
        <tr>
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <textarea id="<?php
                echo $this->name ?>" style="width: 100%" rows="5" name="<?php
                echo $this->name ?>"><?php
                    echo $value ?></textarea>
            </td>
        </tr>
        <?php
    }
}