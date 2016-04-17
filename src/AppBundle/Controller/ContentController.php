<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reply;
use AppBundle\Entity\Thread;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $em = $this->getDoctrine()->getManager();

        $threads = $em->getRepository("AppBundle:Thread")->getAllThreads();
        $replies = $em->getRepository("AppBundle:Reply")->findAll();
        $allThreads = [];
        $reply_form = $this->getReplyForm();


        foreach ($threads as $thread) {
            $thread["reply_form"] = $reply_form->createView();
//            $allThreads[] = array_merge($thread, $reply_form->createView());
            $allThreads[] = $thread;
        }


        $thread_form = $this->getThreadForm();


        return $this->render('Content/list_thread.html.twig', [
            "threads" => $allThreads,
            "thread_form" => $thread_form->createView(),
//            "reply_form" => $reply_form->createView(),
            "replies" => $replies,
        ]);
//        return new Response("OK");
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

    private function getThreadForm()
    {
        $thread = new Thread();
        $form = $this->createFormBuilder($thread)
            ->add("content", TextareaType::class)
            ->add("submit", SubmitType::class)
            ->getForm();
        return $form;
    }

    private function getReplyForm()
    {
        $reply = new Reply();
        $form = $this->createFormBuilder($reply)
                ->add("content", TextareaType::class)
                ->add("id_thread", HiddenType::class)
                ->add("submit", SubmitType::class)
                ->getForm();

        return $form;
    }

}
