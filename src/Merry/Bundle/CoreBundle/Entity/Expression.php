<?php

namespace Merry\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Expression
 */
class Expression
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $sentence;

    /**
     * @var string
     */
    private $sentenceType;

    /**
     * @var string
     */
    private $provider;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    private $actions;

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
     * Set sentence
     *
     * @param string $sentence
     * @return Expression
     */
    public function setSentence($sentence)
    {
        $this->sentence = $sentence;
    
        return $this;
    }

    /**
     * Get sentence
     *
     * @return string 
     */
    public function getSentence()
    {
        return $this->sentence;
    }

    /**
     * Set sentenceType
     *
     * @param string $sentenceType
     * @return Expression
     */
    public function setSentenceType($sentenceType)
    {
        $this->sentenceType = $sentenceType;
    
        return $this;
    }

    /**
     * Get sentenceType
     *
     * @return string 
     */
    public function getSentenceType()
    {
        return $this->sentenceType;
    }

    /**
     * Set provider
     *
     * @param string $provider
     * @return Expression
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    
        return $this;
    }

    /**
     * Get provider
     *
     * @return string 
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Expression
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
     * @return Expression
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
    
    public function getActions() {
        return $this->actions;
    }

    public function setActions($actions) {
        $this->actions = $actions;
    }


}
