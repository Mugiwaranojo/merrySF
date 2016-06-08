<?php

namespace Merry\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExpressionActions
 */
class ExpressionActions
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \Merry\Bundle\CoreBundle\Entity\Expression
     */
    private $expression;

    /**
     * @var \Merry\Bundle\CoreBundle\Entity\Area
     */
    private $area;

    /**
     * @var \Merry\Bundle\CoreBundle\Entity\Action
     */
    private $action;


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
     * Set created
     *
     * @param \DateTime $created
     * @return ExpressionActions
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
     * @return ExpressionActions
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
     * Set expression
     *
     * @param \Merry\Bundle\CoreBundle\Entity\Expression $expression
     * @return ExpressionActions
     */
    public function setExpression(\Merry\Bundle\CoreBundle\Entity\Expression $expression = null)
    {
        $this->expression = $expression;
    
        return $this;
    }

    /**
     * Get expression
     *
     * @return \Merry\Bundle\CoreBundle\Entity\Expression 
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Set area
     *
     * @param \Merry\Bundle\CoreBundle\Entity\Area $area
     * @return ExpressionActions
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
     * Set action
     *
     * @param \Merry\Bundle\CoreBundle\Entity\Action $action
     * @return ExpressionActions
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
}
