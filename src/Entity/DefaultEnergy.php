<?php

namespace One\CheckJeHuis\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class DefaultEnergy
{
    protected $id;

    protected $type;

    protected $size;

    protected $maxYear;

    /**
     * @Assert\Type(type="numeric", message = "dit is geen geldige waarde")
     */
    protected $electricity;

    /**
     * @Assert\Type(type="numeric", message = "dit is geen geldige waarde")
     */
    protected $gas;

    /**
     * @Assert\Type(type="numeric", message = "dit is geen geldige waarde")
     */
    protected $electricHeating;

    /**
     * @Assert\Type(type="numeric", message = "dit is geen geldige waarde")
     */
    protected $oil;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param bool $includeElectricHeating
     * @return float
     */
    public function getElectricity($includeElectricHeating = false)
    {
        $heating = 0;
        if ($includeElectricHeating) {
            $heating = $this->getElectricHeating();
        }
        return $this->electricity + $heating;
    }

    /**
     * @param float $electricity
     * @return $this
     */
    public function setElectricity($electricity)
    {
        $this->electricity = $electricity;
        return $this;
    }

    /**
     * @return float
     */
    public function getGas()
    {
        return $this->gas;
    }

    /**
     * @param float $gas
     * @return $this
     */
    public function setGas($gas)
    {
        $this->gas = $gas;
        return $this;
    }

    /**
     * @return float
     */
    public function getElectricHeating()
    {
        return $this->electricHeating;
    }

    /**
     * @param float $electricHeating
     * @return $this
     */
    public function setElectricHeating($electricHeating)
    {
        $this->electricHeating = $electricHeating;
        return $this;
    }

    /**
     * @param bool $convertNull
     * @return string
     */
    public function getMaxYear($convertNull = false)
    {
        if ($convertNull && $this->maxYear == null) {
            return date_format(new \DateTime(), 'Y');
        }
        return $this->maxYear;
    }

    /**
     * @param string $maxYear
     * @return $this
     */
    public function setMaxYear($maxYear)
    {
        $this->maxYear = $maxYear;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOil()
    {
        return $this->oil;
    }

    /**
     * @param mixed $oil
     */
    public function setOil($oil)
    {
        $this->oil = $oil;
    }
}
