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
        $ps = $this->get("profile_service");
        $user = $ps->getUser();
        return $this->render("Profile/profile.html.twig", [
            "user" => $user,
        ]);
    }

    /**
     * @Route("/profile/{member_id}", name="member_profile")
     */
    public function showMemberProfileAction($member_id)
    {
        $ps = $this->get("profile_service");
        $user = $ps->getUser($member_id);
        if($user){
            return $this->render("Profile/profile.html.twig", [
                "user" => $user,
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
        $email_form = $ps->getChangeEmailForm();
        $password_form = $ps->getChangePasswordForm();

        $email_form->handleRequest($request);
        $password_form->handleRequest($request);

        if($email_form->isSubmitted() && $email_form->isValid()) {
            if($ps->changeEmail($email_form->getData())) {
                return $this->redirectToRoute("parameters");
            }
        }

        if($password_form->isSubmitted() && $password_form->isValid()) {
            if($ps->changePassword($password_form->getData())) {
                return $this->redirectToRoute("parameters");
            }
        }

        return $this->render("Profile/parameters.html.twig", [
            "user" => $user,
            "email_form" => $email_form->createView(),
            "password_form" => $password_form->createView(),
        ]);
    }

    /**
     * @Route("/parameters/change_email", name="change_email")
     */
    public function changeEmailAction() {
        $ps = $this->get("profile_service");
        return $this->render("Profile/change_email.html.twig", [
            "form" => $ps->getChangePasswordForm()->createView(),
        ]);
    }

}
