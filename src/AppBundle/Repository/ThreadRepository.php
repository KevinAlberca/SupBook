<?php

/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 05/03/16
 * Time: 17:41
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ThreadRepository extends EntityRepository
{

    public function getAllThreads()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select("t.id, t.content, u.lastname, u.firstname, t.postDate, u.promotion_year")
            ->from("AppBundle:Thread", "t")
            ->innerJoin("AppBundle:User", "u", "WITH", "u.id = t.idAuthor")
            ->addOrderBy("t.id", "DESC");

        return $qb->getQuery()->getResult();
    }

    public function getOneThreadById($id)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select("t.id, t.content, u.lastname, u.firstname, t.postDate, u.promotion_year")
            ->from("AppBundle:Thread", "t")
            ->innerJoin("AppBundle:User", "u", "WITH", "u.id = t.idAuthor")
            ->where("t.id = ".$id)
            ->addOrderBy("t.id", "DESC");

        return $qb->getQuery()->getResult()[0];
    }
}