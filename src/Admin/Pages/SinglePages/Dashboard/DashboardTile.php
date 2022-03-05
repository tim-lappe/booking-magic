<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


abstract class DashboardTile
{
    /**
     * @var string
     */
    public string $title = "";

    /**
     * @var int
     */
    public int $growLevel = 1;

    public function __construct(string $title = "")
    {
        $this->title = $title;
    }

    public function display()
    {
        $css = ["tlbm-admin-page-tile"];

        if($this->growLevel == 2) {
            $css[] = "tlbm-admin-page-tile-grow-2";
        }

        if($this->growLevel == 3) {
            $css[] = "tlbm-admin-page-tile-grow-3";
        }


        ?>
        <div class="<?php echo implode(" ", $css ) ?>">
            <h2><?php echo $this->title ?></h2>
            <?php $this->displayBody(); ?>
        </div>
        <?php
    }

    abstract public function displayBody(): void;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getGrowLevel(): int
    {
        return $this->growLevel;
    }

    /**
     * @param int $growLevel
     */
    public function setGrowLevel(int $growLevel): void
    {
        $this->growLevel = $growLevel;
    }
}