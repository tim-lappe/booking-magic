<?php


namespace TLBM\Admin\Settings\SingleSettings;


use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\ApiUtils\Contracts\OptionsInterface;
use TLBM\MainFactory;

abstract class SettingsBase
{
    /**
     * @var string
     */
    public string $optionGroup;

    /**
     * @var string
     */
    public string $optionName;

    /**
     * @var mixed
     */
    public $defaultValue;

    /**
     * @var string
     */
    public string $description;

    /**
     * @var string
     */
    public string $title;

    /**
     * @var SettingsManagerInterface
     */
    protected SettingsManagerInterface $settingsManager;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

	/**
	 * @var EscapingInterface
	 */
    protected EscapingInterface $escaping;

    /**
     * @param string $optionGroup
     * @param string $optionName
     * @param string $title
     * @param mixed $defaultValue
     * @param string $description
     */
    public function __construct(
        string $optionGroup,
        string $optionName,
        string $title,
        $defaultValue = "",
        string $description = ""
    ) {
        $this->optionName  = $optionName;
        $this->optionGroup = $optionGroup;
        $this->title       = $title;
        $this->defaultValue = $defaultValue;
        $this->description   = $description;

        $this->localization = MainFactory::get(LocalizationInterface::class);
        $this->escaping = MainFactory::get(EscapingInterface::class);
    }

    public function getSettingsManager(): SettingsManagerInterface
    {
        return $this->settingsManager;
    }

    public function setSettingsManager(SettingsManagerInterface $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        $options     = MainFactory::get(OptionsInterface::class);
        $optionValue = $options->getOption($this->optionName, $this->defaultValue);
        if ($this->isValueValid($optionValue)) {
            return $optionValue;
        }

        return $this->defaultValue;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValueValid($value): bool
    {
        return true;
    }

    public function display()
    {
        ?>
        <label>
            <input type="text" class="regular-text" name="<?php echo $this->escaping->escAttr($this->optionName); ?>" value="<?php echo $this->escaping->escAttr(get_option($this->optionName)); ?>">
        </label>
        <?php
    }
}