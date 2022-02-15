<?php


namespace TLBM\Admin\WpForm;

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
    private bool $wide = false;

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
        ?>
        <tr>
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <select <?php echo $this->wide ? 'class="tlbm-select-wide"' : '' ?> name="<?php echo $this->name ?>">
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