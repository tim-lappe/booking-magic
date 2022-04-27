<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\MainFactory;

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

	/**
	 * @var EscapingInterface
	 */
    private EscapingInterface $escaping;

	/**
	 * @param string $title
	 */
    public function __construct(string $title = "")
    {
        $this->escaping = MainFactory::get(EscapingInterface::class);
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
        <div class="<?php echo $this->escaping->escAttr(implode(" ", $css )) ?>">
            <h2><?php echo $this->escaping->escHtml($this->title) ?></h2>
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