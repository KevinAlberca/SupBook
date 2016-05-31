<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 12/05/16
 * Time: 21:48
 */

namespace AppBundle;

use AppBundle\Entity\Reply;
use AppBundle\Entity\Thread;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\DependencyInjection\Container;


class ContentService
{
    protected $_context;
    protected $_em;
    private $_user;
    protected $_container;

    public function __construct(TokenStorage $context, EntityManager $em, Container $container)
    {
        $this->_context = $context;
        $this->_em = $em;
        $this->_user = $this->_context->getToken()->getUser();
        $this->_container = $container;
    }

    public function listAllThreads()
    {
        $location = $this->getThreadsLocationForUser();

        $allThreads = [
            "classe" => $this->getThreadWithReplyForm($this->_em->getRepository("AppBundle:Thread")->getThreadsWithLocation($location["classe"][0]->id)),
            "promotion" => $this->getThreadWithReplyForm($this->_em->getRepository("AppBundle:Thread")->getThreadsWithLocation($location["promotion"][0]->id)),
            "bachelor" => $this->getThreadWithReplyForm($this->_em->getRepository("AppBundle:Thread")->getThreadsWithLocation($location["bachelor"][0]->id)),
        ];

        $replies = $this->_em->getRepository("AppBundle:Reply")->findAll();
        return [
            "threads" => $allThreads,
            "thread_form" => $this->getThreadForm()->createView(),
            "replies" => $replies,
            "reply_form" => $this->getReplyForm()->createView(),
        ];
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

    public function getThreadsPerLocation($type)
    {
        $threads_location = $this->getThreadsLocationForUser()[$type][0]->id;
        $threads = $this->_em->getRepository("AppBundle:Thread")->getThreadsWithLocation($threads_location);
        return [
            "threads" => $this->getThreadWithReplyForm($threads),
        ];
    }

    public function getReplies(){
        return $this->_em->getRepository("AppBundle:Reply")->findAll();
    }

    public function getThreadForm() {
        $r = $this->getThreadsLocationForUser();

        $form = $this->_container->get('form.factory')->createBuilder()
            ->add("content", TextareaType::class)
            ->add("id_location", ChoiceType::class, [
                "choices"  => [
                    'Classe' => $r["classe"][0]->id,
                    'Bachelor' => $r["bachelor"][0]->id,
                    'Promotion' => $r["promotion"][0]->id,
                ],
            ])
            ->add("submit", SubmitType::class)
            ->getForm();
        return $form;
    }

    public function addThread($data) {
        if($data != null) {
            $thread = new Thread();
            $thread->setIdAuthor($this->_user->id);
            $thread->setContent($data["content"]);
            $thread->setIdLocation($data["id_location"]);
            $thread->setPostDate(new \DateTime());

            $this->_em->persist($thread);
            $this->_em->flush();

            return true;
        }
        return false;
    }

    public function getReplyForm()
    {
        $form = $this->_container->get('form.factory')->createBuilder()
            ->add("content", TextareaType::class)
            ->add("id_thread", HiddenType::class)
            ->add("submit", SubmitType::class)
            ->getForm();

        return $form;
    }

    public function addReply($data) {
        if($data != null) {
            $reply = new Reply();
            $reply->setIdAuthor($this->_user->id);
            $reply->setContent($data["content"]);
            $reply->setPostDate(new \DateTime());
            $reply->setIdThread($data["id_thread"]);
            $this->_em->persist($reply);
            $this->_em->flush();

            return true;
        }
        return false;
    }

    private function getThreadWithReplyForm($threads) {
        $allThreads = $threads;
        $reply_form = $this->getReplyForm();

        for($i=0;$i < count($allThreads); $i++){
            $allThreads[$i]["reply_form"] = $reply_form->createView();
        }

        return $allThreads;
    }
}
