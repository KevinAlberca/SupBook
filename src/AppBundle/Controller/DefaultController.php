<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/home", name="home")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    public function userInfoAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $bachelor = $this->getDoctrine()->getRepository("AppBundle:Bachelor")->findOneBy([
            "id" => $user->id_bachelor,
        ]);

        return $this->render("default/sidebar.html.twig", [
            "user" => $user,
            "bachelor" => $bachelor,
        ]);
    }
}
