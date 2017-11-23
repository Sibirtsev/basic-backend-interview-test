<?php

namespace NeoBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\Groups;

/**
 * @MongoDB\Document(repositoryClass="NeoBundle\Repository\NeoRepository")
 */
class Neo
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="date")
     * @Groups({"neo"})
     */
    protected $date;

    /**
     * @MongoDB\Field(type="int")
     * @Groups({"neo"})
     */
    protected $reference;

    /**
     * @MongoDB\Field(type="string")
     * @Groups({"neo"})
     */
    protected $name;

    /**
     * @MongoDB\Field(type="float")
     * @Groups({"neo"})
     */
    protected $speed;

    /**
     * @MongoDB\Field(type="boolean")
     * @Groups({"neo"})
     */
    protected $isHazardous;

    /**
     * Get id
     *
     * @return \MongoId $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set reference
     *
     * @param int $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * Get reference
     *
     * @return int $reference
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set speed
     *
     * @param float $speed
     * @return $this
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;
        return $this;
    }

    /**
     * Get speed
     *
     * @return float $speed
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Set isHazardous
     *
     * @param boolean $isHazardous
     * @return $this
     */
    public function setIsHazardous($isHazardous)
    {
        $this->isHazardous = $isHazardous;
        return $this;
    }

    /**
     * Get isHazardous
     *
     * @return boolean $isHazardous
     */
    public function getIsHazardous()
    {
        return $this->isHazardous;
    }
}
