<?php

namespace Merry\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Channel
 */
class Channel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $recognizervalue;

    /**
     * @var string
     */
    private $program;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Channel
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set recognizervalue
     *
     * @param string $recognizervalue
     * @return Channel
     */
    public function setRecognizervalue($recognizervalue)
    {
        $this->recognizervalue = $recognizervalue;
    
        return $this;
    }

    /**
     * Get recognizervalue
     *
     * @return string 
     */
    public function getRecognizervalue()
    {
        return $this->recognizervalue;
    }

    /**
     * Set program
     *
     * @param string $program
     * @return Channel
     */
    public function setProgram($program)
    {
        $this->program = $program;
    
        return $this;
    }

    /**
     * Get program
     *
     * @return string 
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Channel
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Channel
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Channel
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    /**
     * @var integer
     */
    private $isfavorite;


    /**
     * Set isfavorite
     *
     * @param integer $isfavorite
     * @return Channel
     */
    public function setIsfavorite($isfavorite)
    {
        $this->isfavorite = $isfavorite;
    
        return $this;
    }

    /**
     * Get isfavorite
     *
     * @return integer 
     */
    public function getIsfavorite()
    {
        return $this->isfavorite;
    }
}