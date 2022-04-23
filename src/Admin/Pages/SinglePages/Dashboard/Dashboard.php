<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;

use TLBM\Admin\Pages\SinglePages\Dashboard\Contracts\DashboardInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class Dashboard implements DashboardInterface
{

    /**
     * @var DashboardTile[][]
     */
    private array $tiles = array();

    private LocalizationInterface $localization;

    public function __construct(LocalizationInterface $localization)
    {
        $this->localization = $localization;
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
        <div class="tlbm-admin-page tlbm-admin-page-dashboard">
            <div class="tlbm-dashboard-container">
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
                <?php
                if(defined("TLBM_NEWS_FEED_URL")) {
                    $newscontent = @file_get_contents(TLBM_NEWS_FEED_URL);
                    if(!empty($newscontent)) {
                        ?>
                        <div class="tlbm-news-sidebar tlbm-admin-page-tile">
                            <h2><?php $this->localization->echoText("News", TLBM_TEXT_DOMAIN) ?></h2>
                            <?php echo $newscontent; ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
}