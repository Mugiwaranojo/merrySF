<?php

namespace Merry\Bundle\CoreBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ExpressionRepository extends EntityRepository{
    
    private $expressionActionsRepository;
    
    public function __construct($em, $class) {
        parent::__construct($em, $class);
        $this->expressionActionsRepository = $this->getEntityManager()
                                               ->getRepository('MerryCoreBundle:ExpressionActions');
    }
    
    public function findBySentence($sentence){
        $expressions = $this->findBy(array("sentence"=>$sentence));
        if($expressions){
            foreach ($expressions as $expression){
                $actions = $this->expressionActionsRepository->findBy(array("expression"=>$expression));
                $expression->setActions($actions);
            }
        }
        return $expressions;
    }
}
