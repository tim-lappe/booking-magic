<?php


namespace TLBM\Admin\FormEditor\ItemSettingsElements;

if ( ! defined('ABSPATH')) {
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
    public string $default_value;

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
    public bool $must_unique = false;

    /**
     * @var array
     */
    public array $forbidden_values = array();

    /**
     * @var string
     */
    public string $category_title = "General";

    /**
     * @var bool
     */
    public bool $expand = false;


    /**
     * SettingsType constructor.
     *
     * @param $name
     * @param $title
     * @param string $default_value
     * @param bool $readonly
     * @param bool $must_unique
     * @param array $forbidden_values
     * @param string $category_title
     */
    public function __construct(
        $name,
        $title,
        string $default_value = "",
        bool $readonly = false,
        bool $must_unique = false,
        array $forbidden_values = array(),
        string $category_title = "General"
    ) {
        $this->name             = $name;
        $this->title            = $title;
        $this->default_value    = $default_value;
        $this->readonly         = $readonly;
        $this->type             = "";
        $this->must_unique      = $must_unique;
        $this->forbidden_values = $forbidden_values;

        if ($category_title == "General") {
            $category_title = __("General", TLBM_TEXT_DOMAIN);
        }

        $this->category_title = $category_title;
    }


    public static function GetForbiddenNameValues(): array
    {
        return array(
            "form"
        );
    }
}