<?php

namespace TLBM\Admin\Pages\SinglePages\Dashboard\Contracts;

use TLBM\Admin\Pages\SinglePages\Dashboard\DashboardTile;

interface DashboardInterface
{
    /**
     * @param int $row
     * @param DashboardTile $dashboardTile
     *
     * @return bool
     */
    public function registerTile(int $row, DashboardTile $dashboardTile): bool;

    /**
     * @return DashboardTile[]
     */
    public function getAllTiles(): array;

    /**
     * @return void
     */
    public function display(): void;
}