<?php

namespace One\CheckJeHuis\Calculator;

use One\CheckJeHuis\Entity\Config;
use One\CheckJeHuis\Entity\ConfigCategory;

/**
 * Calculates the different between the default configs and the current configs
 *
 * @package One\CheckJeHuis
 */
class CurrentEnergyCalculator extends EnergyCalculator
{
    /**
     * @var Config[]|array
     */
    protected $defaultConfigs = array();

    /**
     * @return array|Config[]
     */
    public function getDefaultConfigs()
    {
        return $this->defaultConfigs;
    }

    /**
     * @param array|Config[] $defaultConfigs
     * @return $this
     */
    public function setDefaultConfigs($defaultConfigs)
    {
        $this->defaultConfigs = $defaultConfigs;
        return $this;
    }

    /**
     * @param State $state
     */
    public function calculate(State $state)
    {
        $this->state = $state;

        if ($this->calculated || $this->house->hasCustomEnergy()) {
            if ($this->house->hasCustomEnergy()) {
                $this->log->add('energie manueel ingegeven');
            }
//            return;
        }

        $configs = [];
        $defaultRoofConfig = null;

        foreach ($this->getDefaultConfigs() as $defaultConfig) {
            // only allow 1 type of heating, elec or gas
            if ($this->house->hasElectricHeating() && $defaultConfig->isCategory(ConfigCategory::CAT_HEATING)) {
                continue;
            }
            if (!$this->house->hasElectricHeating() && $defaultConfig->isCategory(ConfigCategory::CAT_HEATING_ELEC)) {
                continue;
            }
            if ($defaultConfig->isCategory(ConfigCategory::CAT_ROOF)) {
                $defaultRoofConfig = $defaultConfig;
            }

            $chosenConfig = $this->house->getConfig($defaultConfig->getCategory());
            if ($defaultConfig !== $chosenConfig) {
                $configs[$defaultConfig->getCategory()->getSlug()][100] = [ $defaultConfig, $chosenConfig ];
            }
        }

        $configs = $this->splitTheRoof($configs, $defaultRoofConfig, $this->house->getExtraConfigRoof());

        $this->diff($configs, $this->house->getRenewables());

        $this->calculated = true;
    }
}
