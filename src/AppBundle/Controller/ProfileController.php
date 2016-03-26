<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 26/03/16
 * Time: 09:34
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProfileController extends Controller
{

    /**
     * @Route("/profile")
     */
    public function showMyProfileAction()
    {
        $user_id = $this->get('security.token_storage')->getToken()->getUser()->id;
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:User")->findBy([
            "id" => $user_id,
        ]);

        $threads = $em->getRepository("AppBundle:Thread")->findBy([
            "idAuthor" => $user_id,
        ]);


        return $this->render("Profile/profile.html.twig", [
            "user" => $user,
            "threads" => $threads
        ]);
    }
}