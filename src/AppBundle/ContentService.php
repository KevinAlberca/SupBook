<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 12/05/16
 * Time: 21:48
 */

namespace AppBundle;

use AppBundle\Entity\Thread;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ContentService
{
    protected $_context;
    protected $_em;
    private $_user;

    public function __construct(TokenStorage $context, EntityManager $em)
    {
        $this->_context = $context;
        $this->_em = $em;
        $this->_user = $this->_context->getToken()->getUser();
    }

    public function getPromotionThreads($promotion_year)
    {
        $distinct_promotion = $this->_em->getRepository("AppBundle:User")->getDistinctPromotion();
        $user_promotion = $this->_user->promotion_year;

        $promotion_threads = $this->_em->getRepository("AppBundle:Thread");
        var_dump($promotion_threads->findBy(["idLocation" => "16"]));

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

    public function listAllThreads($thread_form, $reply_form)
    {
        $threads = $this->_em->getRepository("AppBundle:Thread")->getAllThreads();
        $replies = $this->_em->getRepository("AppBundle:Reply")->findAll();
        $allThreads = [];


        foreach ($threads as $thread) {
            $thread["reply_form"] = $reply_form->createView();
            $allThreads[] = $thread;
        }

        return [
            "threads" => $allThreads,
            "thread_form" => $thread_form->createView(),
            "replies" => $replies,
        ];
    }

    public function getThreadsLocationForUser()
    {
        $loc = $this->_em->getRepository("AppBundle:Location");
        $possible_location = [
            "classe" => $loc->findBy(["type" => "classe", "variety" => $this->_user->id_bachelor, "subVariety" => $this->_user->promotion_year]),
            "bachelor" => $loc->findBy(["type" => "bachelor", "variety" => $this->_user->id_bachelor]),
            "promotion" => $loc->findBy(["type" => "promotion", "variety" => $this->_user->promotion_year]),
        ];
        return $possible_location;
    }

}