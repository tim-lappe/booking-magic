<?php


namespace TLBM\Admin\WpForm;


use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;

class BookingStateSelectField extends FormFieldBase {

	public function __construct( $name, $title, $value = "" ) {
		parent::__construct( $name, $title, $value );
	}

	function OutputHtml() {
		$states = BookingStates::GetStates();
		?>
		<tr>
			<th scope="row"><label for="<?php echo $this->name ?>"><?php echo $this->title ?></label></th>
			<td>
				<select name="<?php echo $this->name ?>">
					<?php foreach($states as $state): ?>
						<option <?php selected($state['name'] == $this->value) ?> value="<?php echo $state['name'] ?>"><?php echo $state['title'] ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<?php
	}
}