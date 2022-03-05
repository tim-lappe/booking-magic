<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;

use TLBM\Admin\Pages\SinglePages\Dashboard\Contracts\DashboardInterface;

class Dashboard implements DashboardInterface
{

    /**
     * @var DashboardTile[][]
     */
    private array $tiles = array();

    public function __construct()
    {

    }

    /**
     * @param int $row
     * @param DashboardTile $dashboardTile
     *
     * @return bool
     */
    public function registerTile(int $row, DashboardTile $dashboardTile): bool
    {
        if ( !isset($this->tiles[$row][get_class($dashboardTile)])) {
            $this->tiles[$row][get_class($dashboardTile)] = $dashboardTile;

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAllTiles(): array
    {
        return $this->tiles;
    }

    /**
     * @return void
     */
    public function display(): void
    {
        ?>
        <div class="tlbm-admin-page">
            <div class="tlbm-dashboard">
                <?php
                foreach ($this->tiles as $tile_arr): ?>
                    <div class="tlbm-admin-page-tile-row">
                        <?php
                        foreach ($tile_arr as $tile): ?>
                            <?php
                            $tile->display(); ?>
                        <?php
                        endforeach; ?>
                    </div>
                <?php
                endforeach; ?>
            </div>
        </div>
        <?php
    }
}