<?php


namespace TLBM\Admin\WpForm;

use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\MainFactory;

if ( !defined('ABSPATH')) {
    return;
}

class InputField extends FormFieldBase
{

    /**
     * @var string
     */
    public string $input_type;

    /**
     * @var string
     */
    public string $title;

    /**
     * @param string $name
     * @param string $input_type
     * @param string $title
     */
    public function __construct(string $name, string $input_type, string $title = "")
    {
        parent::__construct($name, $title);
        $this->input_type = $input_type;
        $this->title      = $title;
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        $escaping = MainFactory::get(EscapingInterface::class);

        $classes = "";
        if ($this->input_type == "text") {
            $classes = "regular-text";
        } elseif ($this->input_type == "number") {
            $classes = "small-text";
        }

        ?>
        <tr>
            <th scope="row"><label for="<?php echo $escaping->escAttr($this->name) ?>"><?php echo $escaping->escHtml($this->title); ?></label></th>
            <td><input id="<?php
                echo $escaping->escAttr($this->name )?>" class="<?php
                echo $escaping->escAttr($classes) ?>" type="<?php
                echo $escaping->escAttr($this->input_type) ?>" name="<?php
                echo $escaping->escAttr($this->name) ?>" value="<?php
                echo $escaping->escAttr($value) ?>"></td>
        </tr>
        <?php
    }
}