<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getDistinctPromotion()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select("u.promotion_year")
            ->distinct(true)
            ->from("AppBundle:User", "u");

        return array_column($qb->getQuery()->getScalarResult(), "promotion_year");
    }
}
