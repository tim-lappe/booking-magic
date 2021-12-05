<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;

class Dashboard {

	/**
	 * @var LastBookingsTile[]
	 */
	private array $tiles;

	public function __construct() {
		$this->tiles = array(
		        array(
		            new DatesTodayTile()
                ),

		        array(
                    new LastBookingsTile(),
                    new BestSellingCalendarsTile()
                )
		);
	}

	public function Print() {
		?>
		<div class="tlbm-dashboard">
			<?php foreach ($this->tiles as $tile_arr): ?>
                <div class="tlbm-dashboard-tile-row">
                    <?php foreach ($tile_arr as $tile): ?>
                        <?php $tile->Print(); ?>
                    <?php endforeach; ?>
                </div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}