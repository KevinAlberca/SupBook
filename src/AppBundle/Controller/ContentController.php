<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reply;
use AppBundle\Entity\Thread;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function listAction(Request $request)
    {
        return $this->redirectToRoute("class_group");
//        $cs = $this->get("content_service");
//        $threads = $cs->listAllThreads($this->getReplyForm());
//        $replies = $cs->getReplies();
//
//        var_dump($replies);
//
//        return $this->render("Content/list_thread.html.twig", [
//            "threads" => $threads,
//            "replies" => $replies,
//            "thread_form" => $this->getThreadForm()->createView(),
//            "reply_form" => $this->getReplyForm()->createView(),
//        ]);
    }

    /**
     * @Route("/threads/{id}", name="one_thread")
     */
    public function getOneThreadAction(Request $request, $id)
    {
        $cs = $this->get("content_service");
        if($cs->getOneThreadPerId($id)){
            $r = $cs->getOneThreadPerId($id);
            return $this->render("Content/one_thread.html.twig", [
                "thread" => $r["thread"],
                "replies" => $r["replies"],
                "reply_form" => $this->getReplyForm()->createView(),
                "id" => $id,
            ]);
        }

        return new Response("Thread not found", 404);
    }

    /**
     * @Route("/promotion", name="promotion_group")
     */
    public function getYourPromotionThreads(Request $request)
    {
        $cs = $this->get("content_service");
        $threads = $cs->getThreadsPerLocation("promotion");
        $replies = $cs->getReplies();

        if($threads) {
            return $this->render("Content/list_thread.html.twig", [
                "threads" => $threads,
                "replies" => $replies,
                "thread_form" => $this->getThreadForm()->createView(),
                "reply_form" => $this->getReplyForm()->createView(),
            ]);
        } else {
            return new Response("It's not your bachelor", 403);
        }

    }

    /**
     * @Route("/bachelor", name="bachelor_group")
     */
    public function getYourBachelorThreads(Request $request)
    {
        $cs = $this->get("content_service");
        $threads = $cs->getThreadsPerLocation("bachelor");
        $replies = $cs->getReplies();

        if($threads) {
            return $this->render("Content/list_thread.html.twig", [
                "threads" => $threads,
                "replies" => $replies,
                "thread_form" => $this->getThreadForm()->createView(),
                "reply_form" => $this->getReplyForm()->createView(),
            ]);
        } else {
            return new Response("It's not your bachelor", 403);
        }

    }

    /**
     * @Route("/add_thread", name="add_thread")
     */
    public function addContentAction(Request $request)
    {
        $form = $this->getThreadForm();

        $form->handleRequest($request);

        if($request->getMethod() == "POST") {
            if($form->isSubmitted() && $form->isValid()) {
                if($form->getData()->content != null) {
                    $em = $this->getDoctrine()->getManager();
                    $thread = new Thread();
                    $thread->setIdAuthor($this->get('security.token_storage')->getToken()->getUser()->id);
                    $thread->setContent($form->getData()->content);
                    $thread->setPostDate(new \DateTime());
                    $thread->setIdLocation($form->getData()->idLocation);
                    $em->persist($thread);
                    $em->flush();
                }

                return $this->redirectToRoute("homepage");
            }
        }

        return $this->render("Content/add_thread.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/classe", name="class_group")
     */
    public function getYourClassThreads(Request $request)
    {
        $cs = $this->get("content_service");
        $threads = $cs->getThreadsPerLocation("classe");
        $replies = $cs->getReplies($threads);

        if($threads) {
            return $this->render("Content/list_thread.html.twig", [
                "threads" => $threads,
                "replies" => $replies,
                "thread_form" => $this->getThreadForm()->createView(),
                "reply_form" => $this->getReplyForm()->createView(),
            ]);
        } else {
            return new Response("It's not your class", 403);
        }

    }

    /**
     * @Route("/add_reply", name="add_reply")
     */
    public function addReplyAction(Request $request)
    {
        $form = $this->getReplyForm();
        $form->handleRequest($request);

        if($request->getMethod() == "POST") {
            if($form->isSubmitted() && $form->isValid()) {
                if($form->getData()->content != null) {
                    $em = $this->getDoctrine()->getManager();
                    $reply = new Reply();
                    $reply->setIdAuthor($this->get('security.token_storage')->getToken()->getUser()->id);
                    $reply->setContent($form->getData()->content);
                    $reply->setPostDate(new \DateTime());
                    $reply->setIdThread($form->getData()->idThread);
                    $em->persist($reply);
                    $em->flush();
                }

                return $this->redirectToRoute("homepage");
            }
        }

        return $this->renderView("Content/add_reply.html.twig", [
            "reply_form" => $form,

        ]);
    }

    public function getReplyForm()
    {
        $reply = new Reply();
        $form = $this->createFormBuilder($reply)
                ->add("content", TextareaType::class)
                ->add("id_thread", HiddenType::class)
                ->add("submit", SubmitType::class)
                ->getForm();

        return $form;
    }

    public function getThreadForm()
    {
        $cs = $this->get("content_service");
        $r = $cs->getThreadsLocationForUser();

        $thread = new Thread();
        $form = $this->createFormBuilder($thread)
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

}