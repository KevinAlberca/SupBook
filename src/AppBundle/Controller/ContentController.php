<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContentController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getRepository("AppBundle:Content");

        $contents = $em->findAll();

        // replace this example code with whatever you need
        return $this->render('Content/list.html.twig', [
            "contents" => $contents,
        ]);
    }

}
