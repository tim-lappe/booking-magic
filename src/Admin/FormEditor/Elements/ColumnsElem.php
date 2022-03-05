<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\Contracts\FrontendElementInterface;
use TLBM\Admin\FormEditor\ItemSettingsElements\Select;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;


class ColumnsElem extends FormElem implements FrontendElementInterface
{

    /**
     * @var int
     */
    public int $columns = 0;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct($name, $columns)
    {
        $this->localization = MainFactory::get(LocalizationInterface::class);

        parent::__construct($name, $this->localization->__($columns . " Columns", TLBM_TEXT_DOMAIN));
        $this->menu_category = "Layout";
        $this->columns       = $columns;
        $this->type          = "columns";
        $this->only_in_root  = $columns > 3;
        $this->description   = sprintf(
            $this->localization->__("Adds a section in which form fields can be displayed in a %s-column layout", TLBM_TEXT_DOMAIN), $columns
        );

        $settings = [];
        for ($i = 1; $i <= $columns; $i++) {
            $settings[] = new Select(
                "split_" . $i, sprintf($this->localization->__("Size Column %s", TLBM_TEXT_DOMAIN), $i), [
                "1"  => "1",
                "2"  => "2",
                "3"  => "3",
                "4"  => "4",
                "5"  => "5",
                "6"  => "6",
                "7"  => "7",
                "8"  => "8",
                "9"  => "9",
                "10" => "10",
                "11" => "11",
                "12" => "12"
            ],  1, false, false, $this->localization->__("Column Sizes", TLBM_TEXT_DOMAIN)
            );
        }

        $this->addSettings(...$settings);
    }

    /**
     * @param LinkedFormData $linkedFormData
     * @param callable|null $displayChildren
     *
     * @return string
     */
    public function getFrontendContent(LinkedFormData $linkedFormData, callable $displayChildren = null): string
    {
        $lsettings = $linkedFormData->getLinkedSettings();
        $css = ['tlbm-fe-columns'];
        $css[] = $lsettings->getValue("css_classes");

        $html = "<div class='" . implode(" ", $css) . "'>";
        $element = $linkedFormData->getFormElement();

        if($element instanceof ColumnsElem) {
            for($c = 0; $c < $element->getColumns(); $c++) {
                $fgrow = $lsettings->getValue("split_" . ($c + 1));
                $html  .= "<div class='tlbm-fe-column' style='flex-grow: " . $fgrow . "'>";

                $node = $linkedFormData->getFormNode();
                $subChildren = $node['children'][$c]['children'];

                foreach ($subChildren as $childFormNode) {
                    $html .= $displayChildren($childFormNode) ?? "";
                }

                $html .= "</div>";
            }
        }

        $html .= "</div>";

        return $html;
    }

    /**
     * @return int
     */
    public function getColumns(): int
    {
        return $this->columns;
    }

    /**
     * @param int $columns
     */
    public function setColumns(int $columns): void
    {
        $this->columns = $columns;
    }
}
