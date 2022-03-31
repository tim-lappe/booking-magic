<?php

namespace TLBM\Admin\FormEditor;

use TLBM\Admin\FormEditor\ItemSettingsElements\ElementSetting;
use TLBM\MainFactory;

class LinkedSettings
{
    /**
     * @var ElementSetting[]
     */
    private array $elementSettings;

    /**
     * @var mixed
     */
    private $formNode;


    /**
     * @param string $name
     *
     * @return string
     */
    public function getValue(string $name): string
    {
        if(isset($this->formNode['formData'][$name])) {
            return trim($this->formNode['formData'][$name]);
        } else {
            $setting = $this->getSettingByName($name);
            if($setting != null) {
                return trim($setting->defaultValue);
            }
        }
        return "";
    }

    /**
     * @param string $name
     *
     * @return ElementSetting|null
     */
    public function getSettingByName(string $name): ?ElementSetting
    {
        foreach ($this->elementSettings as $setting) {
            if($setting->name == $name) {
                return $setting;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getElementSettings(): array
    {
        return $this->elementSettings;
    }

    /**
     * @param ElementSetting[] $elementSettings
     */
    public function setElementSettings(array $elementSettings): void
    {
        $this->elementSettings = $elementSettings;
    }

    /**
     * @return mixed
     */
    public function getFormNode()
    {
        return $this->formNode;
    }

    /**
     * @param mixed $formNode
     */
    public function setFormNode($formNode): void
    {
        $this->formNode = $formNode;
    }

    /**
     * @param mixed $formNode
     * @param array $elementSettings
     *
     * @return LinkedSettings
     */
    public static function createFromData($formNode, array $elementSettings): LinkedSettings
    {
        $linkedSettings = MainFactory::create(LinkedSettings::class);
        $linkedSettings->setFormNode($formNode);
        $linkedSettings->setElementSettings($elementSettings);
        return $linkedSettings;
    }
}