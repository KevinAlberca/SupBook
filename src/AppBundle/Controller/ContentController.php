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
        $cs = $this->get("content_service");
        $r = $cs->listAllThreads();

        return $this->render("homepage.html.twig", [
            "class_threads" => $r["threads"]["classe"],
            "bachelor_threads" => $r["threads"]["bachelor"],
            "promotion_threads" => $r["threads"]["promotion"],
            "thread_form" => $r["thread_form"],
            "replies" => $r["replies"],
        ]);
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
                "reply_form" => $cs->getReplyForm()->createView(),
                "id" => $id,
            ]);
        }

        return new Response("Thread not found", 404);
    }

    /**
     * @Route("/classe", name="class_group")
     */
    public function getClassThreadsAction(Request $request)
    {
        $cs = $this->get("content_service");
        $threads = $cs->getThreadsPerLocation("classe")["threads"];
        $replies = $cs->getReplies($threads);
        return $this->render("Content/list_thread.html.twig", [
            "threads" => $threads,
            "replies" => $replies,
            "thread_form" => $cs->getThreadForm()->createView(),
            "reply_form" => $cs->getReplyForm()->createView(),
        ]);
    }

    /**
     * @Route("/promotion", name="promotion_group")
     */
    public function getPromotionThreadsAction(Request $request)
    {
        $cs = $this->get("content_service");
        $threads = $cs->getThreadsPerLocation("promotion")["threads"];
        $replies = $cs->getReplies();

        return $this->render("Content/list_thread.html.twig", [
            "threads" => $threads,
            "replies" => $replies,
            "thread_form" => $cs->getThreadForm()->createView(),
            "reply_form" => $cs->getReplyForm()->createView(),
        ]);
    }

    /**
     * @Route("/bachelor", name="bachelor_group")
     */
    public function getBachelorThreadsAction(Request $request)
    {
        $cs = $this->get("content_service");
        $threads = $cs->getThreadsPerLocation("bachelor")["threads"];
        $replies = $cs->getReplies();

        return $this->render("Content/list_thread.html.twig", [
            "threads" => $threads,
            "replies" => $replies,
            "thread_form" => $cs->getThreadForm()->createView(),
            "reply_form" => $cs->getReplyForm()->createView(),
        ]);
    }

    /**
     * @Route("/add_thread", name="add_thread")
     */
    public function addContentAction(Request $request)
    {
        $cs = $this->get("content_service");
        $form = $cs->getThreadForm();
        $form->handleRequest($request);

        if($request->getMethod() == "POST") {
            if($form->isSubmitted() && $form->isValid()) {
                if($cs->addThread($form->getData())) {
                    return $this->redirectToRoute("homepage");
                }

            }
        }

        return $this->render("Content/add_thread.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/add_reply", name="add_reply")
     */
    public function addReplyAction(Request $request)
    {
        $cs = $this->get("content_service");
        $form = $cs->getReplyForm();
        $form->handleRequest($request);

        if($request->getMethod() == "POST") {
            if($form->isSubmitted() && $form->isValid()) {
                if($cs->addReply($form->getData())) {
                    return $this->redirectToRoute("homepage");
                }
            }
        }

        return $this->renderView("Content/add_reply.html.twig", [
            "reply_form" => $form,
        ]);
    }

}
