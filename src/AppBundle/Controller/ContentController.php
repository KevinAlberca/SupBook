<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Thread;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContentController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getRepository("AppBundle:Thread");

        $threads = $em->findAll();

        $form = $this->getContentForm();


        // replace this example code with whatever you need
        return $this->render('Content/list_thread.html.twig', [
            "threads" => $threads,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/add_content", name="add_content")
     */
    public function addContentAction(Request $request)
    {
        $form = $this->getContentForm();

        $form->handleRequest($request);

        if($request->getMethod() == "POST"){
            if($form->isSubmitted() && $form->isValid()){
                if($form->getData()->content != null) {
                    $em = $this->getDoctrine()->getManager();
                    $content = new Thread();
                    $content->setIdAuthor($this->get('security.token_storage')->getToken()->getUser()->id);
                    $content->setContent($form->getData()->content);
                    $content->setPostDate(new \DateTime());
                    $em->persist($content);
                    $em->flush();
                }

                return $this->redirectToRoute("homepage");
            }
        }

        return $this->render("Content/add_thread.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    private function getContentForm(){
        $content = new Thread();
        $form = $this->createFormBuilder($content)
            ->add("content", TextareaType::class)
            ->add("submit", SubmitType::class)
            ->getForm();
        return $form;
    }

}
