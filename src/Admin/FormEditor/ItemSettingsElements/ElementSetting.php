<?php


namespace TLBM\Admin\FormEditor\ItemSettingsElements;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;

if ( !defined('ABSPATH')) {
    return;
}


abstract class ElementSetting
{

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $title;

    /**
     * @var string
     */
    public string $defaultValue;

    /**
     * @var bool
     */
    public bool $readonly;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var bool
     */
    public bool $mustUnique = false;

    /**
     * @var array
     */
    public array $forbiddenValues = [];

    /**
     * @var string
     */
    public string $categoryTitle = "General";

    /**
     * @var bool
     */
    public bool $expand = false;

    /**
     * @var string|null
     */
    public ?string $dataSourceProvier = null;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    /**
     * SettingsType constructor.
     *
     * @param string $name
     * @param string $title
     * @param string $defaultValue
     * @param bool $readonly
     * @param bool $mustUnique
     * @param array $forbiddenValues
     * @param string $categoryTitle
     */
    public function __construct(
        string $name,
        string $title,
        string $defaultValue = "",
        bool $readonly = false,
        bool $mustUnique = false,
        array $forbiddenValues = array(),
        string $categoryTitle = "General"
    ) {
        $this->name            = $name;
        $this->title           = $title;
        $this->defaultValue    = $defaultValue;
        $this->readonly        = $readonly;
        $this->type            = "";
        $this->mustUnique      = $mustUnique;
        $this->forbiddenValues = $forbiddenValues;

        $this->localization = MainFactory::get(LocalizationInterface::class);

        if ($categoryTitle == "General") {
            $categoryTitle = $this->localization->getText("General", TLBM_TEXT_DOMAIN);
        }

        $this->categoryTitle = $categoryTitle;
    }

    public static function GetForbiddenNameValues(): array
    {
        return ["form",
            "calendar",
            "_wp_nonce",
            "_wp_http_referer"
        ];
    }
}