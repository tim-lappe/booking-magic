<?php


namespace TLBM\Admin\WpForm;


use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;

class BookingStateSelectField extends FormFieldBase
{

    /**
     * @param string $name
     * @param string $title
     */
    public function __construct(string $name, string $title)
    {
        parent::__construct($name, $title);
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        $states = BookingStates::getStates();
        ?>
        <tr>
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <select name="<?php
                echo $this->name ?>">
                    <?php
                    foreach ($states as $state): ?>
                        <option <?php
                        selected($state['name'] == $this->value) ?> value="<?php
                        echo $state['name'] ?>"><?php
                            echo $state['title'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </td>
        </tr>
        <?php
    }
}