<?php


namespace TLBM\Admin\WpForm;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class RadioField extends FormFieldBase {

	public $radios = array();

	public function __construct( $name, $title, $radios, $value = "" ) {
		$this->radios = $radios;
		parent::__construct( $name, $title, $value );
	}

	function OutputHtml() {
		?>
		<tr>
			<th scope="row"><?php echo $this->title ?></th>
			<td>
                <?php foreach($this->radios as $key => $text): ?>
                    <label>
                        <div class="tlbm-radio-item">
                            <input <?php echo $key == $this->value && !empty($this->value) ? "checked='checked'" : "" ?> id="<?php echo $this->name ?>-<?php echo $key ?>" class="regular-text" type="radio" name="<?php echo $this->name ?>" value="<?php echo $key ?>" />
                            <?php echo $text ?>
                        </div>
                    </label>
                <?php endforeach; ?>
			</td>
		</tr>
		<?php
	}
}