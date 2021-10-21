<?php


namespace TLBM\Admin\WpForm;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class SelectField extends FormFieldBase {

	public $options = array();

	public function __construct( $name, $title, $options, $value = "" ) {
		$this->options = $options;
		parent::__construct( $name, $title, $value );
	}

	function OutputHtml() {
		?>
		<tr>
			<th scope="row"><label for="<?php echo $this->name ?>"><?php echo $this->title ?></label></th>
			<td>
				<select name="<?php echo $this->name ?>">
					<?php foreach($this->options as $key => $option): ?>
						<option <?php echo $this->value == $key ? "selected='selected'" : "" ?> value="<?php echo $key ?>"><?php echo $option ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<?php
	}
}