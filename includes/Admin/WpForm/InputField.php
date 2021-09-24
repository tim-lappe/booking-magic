<?php


namespace TL_Booking\Admin\WpForm;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class InputField extends FormFieldBase {

	public $input_type;
	public $title;

	public function __construct( $name, $input_type, $title = "", $value =  "" ) {
		parent::__construct( $name, $title, $value );
		$this->input_type = $input_type;
		$this->title = $title;
	}

	function OutputHtml() {
	    $classes = "";
	    if($this->input_type == "text") {
	        $classes = "regular-text";
        } else if($this->input_type == "number") {
	        $classes = "small-text";
        }

		?>
            <tr>
                <th scope="row"><label for="<?php echo $this->name ?>"><?php echo $this->title ?></label></th>
                <td><input id="<?php echo $this->name ?>" class="<?php echo $classes ?>" type="<?php echo $this->input_type ?>" name="<?php echo $this->name ?>" value="<?php echo $this->value?>"></td>
            </tr>
		<?php
	}
}