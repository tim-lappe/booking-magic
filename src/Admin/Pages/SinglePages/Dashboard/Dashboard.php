<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use phpDocumentor\Reflection\Types\This;

class Dashboard {

	/**
	 * @var LastBookingsTile[]
	 */
	private array $tiles = array();

	public function __construct() {
		$this->tiles = array(
			new LastBookingsTile()
		);
	}

	public function Print() {
		?>
		<div class="tlbm-dashboard">
			<?php foreach ($this->tiles as $tile): ?>
				<?php $tile->Print(); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}
}