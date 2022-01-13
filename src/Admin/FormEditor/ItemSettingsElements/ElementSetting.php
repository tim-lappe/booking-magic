<?php


namespace TLBM\Admin\FormEditor\ItemSettingsElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


abstract class ElementSetting {

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
	 * SettingsType constructor.
	 *
	 * @param $name
	 * @param $title
	 * @param string $default_value
	 * @param bool $readonly
	 */
	public function __construct($name, $title, string $default_value = "", bool $readonly = false, bool $must_unique = false) {
		$this->name = $name;
		$this->title = $title;
		$this->default_value = $default_value;
		$this->readonly = $readonly;
        $this->type = "";
        $this->must_unique = $must_unique;
	}
}