<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use TLBM\Admin\Tables\BookingListTable;

abstract class DashboardTile {

	public string $title = "";

	public function __construct(string $title = "") {
		$this->title = $title;
	}

	public function Print() {
		?>
		<div class="tlbm-dashboard-tile">
			<span class="tlbm-dashboard-tile-heading"><?php echo $this->title ?></span>
			<?php $this->PrintBody(); ?>
		</div>
		<?php
	}

	public abstract function PrintBody(): void;
}