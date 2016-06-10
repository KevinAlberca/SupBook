<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 26/03/16
 * Time: 09:34
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class ProfileController extends Controller
{

    /**
     * @Route("/profile", name="user_profile")
     */
    public function showMyProfileAction()
    {
        $user_id = $this->get('security.token_storage')->getToken()->getUser()->id;
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:User")->findOneBy([
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

    /**
     * @Route("/profile/{member_id}", name="member_profile")
     */
    public function showMemberProfileAction($member_id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")->findOneBy(["id" => $member_id]);

        if($user){
            $threads = $em->getRepository("AppBundle:Thread")->findBy([
                "idAuthor" => $member_id,
            ]);

            return $this->render("Profile/profile.html.twig", [
                "user" => $user,
                "threads" => $threads,
            ]);
        } else {
            return new Response("This member ".$member_id." doesn't exists !");
        }

    }

    /**
     * @Route("/parameters", name="parameters")
     */
    public function memberParametersAction(Request $request)
    {
        $ps = $this->get("profile_service");
        $user = $ps->getUser();
        $form = $ps->getChangeEmailForm();

        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()){
            if($ps->changeEmail($form->getData())){
                
            }
        }


        return $this->render("Profile/parameters.html.twig", [
            "user" => $user,
            "email_form" => $form->createView()
        ]);
    }


}