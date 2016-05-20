<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocationRepository")
 */
class Location
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    public $type;

    /**
     * @var string
     *
     * @ORM\Column(name="variety", type="string", length=255)
     */
    public $variety;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_variety", type="string", length=255, nullable=true)
     */
    public $subVariety;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Location
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set variety
     *
     * @param string $variety
     *
     * @return Location
     */
    public function setVariety($variety)
    {
        $this->variety = $variety;

        return $this;
    }

    /**
     * Get variety
     *
     * @return string
     */
    public function getVariety()
    {
        return $this->variety;
    }

    /**
     * Set subVariety
     *
     * @param string $subVariety
     *
     * @return Location
     */
    public function setSubVariety($subVariety)
    {
        $this->subVariety = $subVariety;

        return $this;
    }

    /**
     * Get subVariety
     *
     * @return string
     */
    public function getSubVariety()
    {
        return $this->subVariety;
    }
}

