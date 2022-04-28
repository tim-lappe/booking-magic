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
            <th scope="row"><label for="<?php echo $this->escaping->escAttr($this->name) ?>"><?php echo $this->escaping->escHtml($this->title); ?></label></th>
            <td>
                <textarea id="<?php echo $this->escaping->escAttr($this->name) ?>" style="width: 100%" rows="5" name="<?php echo $this->escaping->escAttr($this->name); ?>"><?php echo $this->escaping->escTextarea($value); ?></textarea>
            </td>
        </tr>
        <?php
    }
}