<?php

namespace TLBM\Admin\RuleActionsEditor\Actions\Traits;

use TLBM\Admin\RuleActionsEditor\SettingsFields\ActionSettingsField;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;

trait CapacityFieldTrait
{
    /**
     * @param string $name
     * @param string $title
     *
     * @return array[]
     */
    public function createCapacityFields(string $name, string $title = ""): array
    {
        $localization = MainFactory::get(LocalizationInterface::class);

        $selectField = MainFactory::create(ActionSettingsField::class);
        $selectField->setName($name . "_mode");
        $selectField->setType("select");
        $selectField->setOptions(["set" => $localization->getText("Set", TLBM_TEXT_DOMAIN),
                                     "add" => $localization->getText("Add", TLBM_TEXT_DOMAIN),
                                     "subtract" => $localization->getText("Subtract", TLBM_TEXT_DOMAIN)
                                 ]);

        $numberField = MainFactory::create(ActionSettingsField::class);
        $numberField->setName($name . "_amount");
        $numberField->setType("number");


        return [$title => [$selectField,
            $numberField
        ]
        ];
    }
}