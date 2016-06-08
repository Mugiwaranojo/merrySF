<?php

namespace Merry\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 */
class Device
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $deviceType;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var boolean
     */
    private $favorite;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \Merry\Bundle\CoreBundle\Entity\Area
     */
    private $area;

    /**
     * @var \Merry\Bundle\CoreBundle\Entity\Device
     */
    private $parent;


    private $devicesChilds;
    
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
     * Set identifier
     *
     * @param string $identifier
     * @return Device
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    
        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Device
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
     * Set status
     *
     * @param string $status
     * @return Device
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set deviceType
     *
     * @param string $deviceType
     * @return Device
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;
    
        return $this;
    }

    /**
     * Get deviceType
     *
     * @return string 
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Device
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set favorite
     *
     * @param boolean $favorite
     * @return Device
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;
    
        return $this;
    }

    /**
     * Get favorite
     *
     * @return boolean 
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Device
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
     * @return Device
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
     * Set area
     *
     * @param \Merry\Bundle\CoreBundle\Entity\Area $area
     * @return Device
     */
    public function setArea(\Merry\Bundle\CoreBundle\Entity\Area $area = null)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return \Merry\Bundle\CoreBundle\Entity\Area 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set parent
     *
     * @param \Merry\Bundle\CoreBundle\Entity\Device $parent
     * @return Device
     */
    public function setParent(\Merry\Bundle\CoreBundle\Entity\Device $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Merry\Bundle\CoreBundle\Entity\Device 
     */
    public function getParent()
    {
        return $this->parent;
    }
   
    public function getDevicesChilds() {
        return $this->devicesChilds;
    }

    public function setDevicesChilds($devicesChilds) {
        $this->devicesChilds = $devicesChilds;
    }


}
