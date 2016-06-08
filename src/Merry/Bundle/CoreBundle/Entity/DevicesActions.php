<?php

namespace Merry\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DevicesActions
 */
class DevicesActions
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $command;

    /**
     * @var integer
     */
    private $delay;

    /**
     * @var string
     */
    private $args;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \Merry\Bundle\CoreBundle\Entity\Action
     */
    private $action;

    /**
     * @var \Merry\Bundle\CoreBundle\Entity\Device
     */
    private $device;


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
     * Set command
     *
     * @param string $command
     * @return DevicesActions
     */
    public function setCommand($command)
    {
        $this->command = $command;
    
        return $this;
    }

    /**
     * Get command
     *
     * @return string 
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set delay
     *
     * @param integer $delay
     * @return DevicesActions
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
    
        return $this;
    }

    /**
     * Get delay
     *
     * @return integer 
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set args
     *
     * @param string $args
     * @return DevicesActions
     */
    public function setArgs($args)
    {
        $this->args = $args;
    
        return $this;
    }

    /**
     * Get args
     *
     * @return string 
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return DevicesActions
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
     * @return DevicesActions
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
     * Set action
     *
     * @param \Merry\Bundle\CoreBundle\Entity\Action $action
     * @return DevicesActions
     */
    public function setAction(\Merry\Bundle\CoreBundle\Entity\Action $action = null)
    {
        $this->action = $action;
    
        return $this;
    }

    /**
     * Get action
     *
     * @return \Merry\Bundle\CoreBundle\Entity\Action 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set device
     *
     * @param \Merry\Bundle\CoreBundle\Entity\Device $device
     * @return DevicesActions
     */
    public function setDevice(\Merry\Bundle\CoreBundle\Entity\Device $device = null)
    {
        $this->device = $device;
    
        return $this;
    }

    /**
     * Get device
     *
     * @return \Merry\Bundle\CoreBundle\Entity\Device 
     */
    public function getDevice()
    {
        return $this->device;
    }
}
