<?php

namespace TLBM\Localization\Contracts;

interface ScriptLocalizationInterface
{

    public function getLabels(): array;

    public function getLabelCollections(): array;

    public function getLabelKeys(): array;
}