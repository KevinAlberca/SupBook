<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 12/05/16
 * Time: 21:48
 */

namespace AppBundle;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ThreadService
{
    protected $_context;
    protected $_em;

    public function __construct(TokenStorage $context, EntityManager $em)
    {
        $this->_context = $context;
        $this->_em = $em;
    }

    public function getPromotionThreads($promotion_year)
    {
        $distinct_promotion = $this->_em->getRepository("AppBundle:User")->getDistinctPromotion();
        $user_promotion = $this->_context->getToken()->getUser()->promotion_year;

        if(in_array($promotion_year, $distinct_promotion) && $promotion_year == $user_promotion){
            return true;
        } else {
            return false;
        }
    }

    public function getOneThreadPerId($id)
    {
        $thread = $this->_em->getRepository("AppBundle:Thread")->getOneThreadById($id);

        if($thread) {
            $replies = $this->_em->getRepository("AppBundle:Reply")->findBy([
                "idThread" => $id,
            ]);

            return [
                "thread" => $thread,
                "replies" => $replies,
                "id" => $id,
            ];
        } else {
            return false;
        }

    }

}