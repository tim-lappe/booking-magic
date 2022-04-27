<?php


namespace TLBM\Admin\WpForm;

use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\MainFactory;

if ( !defined('ABSPATH')) {
    return;
}

class SelectField extends FormFieldBase
{

    /**
     * @var array
     */
    public array $options = array();

    /**
     * @var bool
     */
    private bool $wide;

    /**
     * @param string $name
     * @param string $title
     * @param array $options
     * @param bool $wide
     */
    public function __construct(string $name, string $title, array $options, bool $wide = false)
    {
        $this->options = $options;
        $this->wide = $wide;
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
            <th scope="row"><label for="<?php echo $escaping->escAttr($this->name) ?>"><?php echo $escaping->escHtml($this->title); ?></label></th>
            <td>
                <label>
                    <select <?php echo $this->wide ? 'class="tlbm-select-wide"' : '' ?> name="<?php echo $escaping->escAttr($this->name) ?>">
                        <?php
                        foreach ($this->options as $key => $option): ?>
                            <option <?php echo $value == $key ? "selected='selected'" : "" ?> value="<?php echo $escaping->escAttr($key) ?>">
                                <?php echo $escaping->escHtml($option) ?>
                            </option>
                        <?php
                        endforeach; ?>
                    </select>
                </label>
            </td>
        </tr>
        <?php
    }
}