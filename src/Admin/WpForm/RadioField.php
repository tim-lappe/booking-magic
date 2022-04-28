<?php


namespace TLBM\Admin\WpForm;

use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\MainFactory;

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
        $escaping = MainFactory::get(EscapingInterface::class);

        ?>
        <tr>
            <th scope="row"><?php echo $escaping->escHtml($this->title) ?></th>
            <td>
                <?php
                foreach ($this->radios as $key => $text): ?>
                    <div class="tlbm-radio-item">
                        <label>
                            <input
                                <?php echo $key == $value && !empty($value) ? "checked='checked'" : "" ?>
                                    id="<?php echo $escaping->escAttr($this->name) ?>-<?php echo $escaping->escAttr($key) ?>"
                                    class="regular-text"
                                    type="radio"
                                    name="<?php echo $escaping->escAttr($this->name) ?>"
                                    value="<?php echo $escaping->escAttr($key) ?>"/>
                            <?php echo $escaping->escHtml($text); ?>
                        </label>
                    </div>
                <?php
                endforeach; ?>
            </td>
        </tr>
        <?php
    }
}