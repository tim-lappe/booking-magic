<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


abstract class DashboardTile
{

    public string $title = "";

    public function __construct(string $title = "")
    {
        $this->title = $title;
    }

    public function display()
    {
        ?>
        <div class="tlbm-dashboard-tile">
            <span class="tlbm-dashboard-tile-heading"><?php
                echo $this->title ?></span>
            <?php
            $this->displayBody(); ?>
        </div>
        <?php
    }

    abstract public function displayBody(): void;
}