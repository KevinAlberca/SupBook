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
        $user_id = $this->get('security.token_storage')->getToken()->getUser()->id;
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:User")->findOneBy([
            "id" => $user_id,
        ]);

        $email_form = $this->getChangeEmailForm();

        $email_form->handleRequest($request);

        if($email_form->isValid() && $email_form->isSubmitted()){
            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword($email_form->getData()["password"], $user->getSalt());

            if($password == $user->getPassword()){
                $user->setEmail($email_form->getData()["email"]);
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute("parameters");
            }
        }


        return $this->render("Profile/parameters.html.twig", [
            "user" => $user,
            "email_form" => $email_form->createView()
        ]);
    }

    private function getChangeEmailForm()
    {
        $user = new User();
        $form = $this->createFormBuilder()
            ->add("email", RepeatedType::class, [
                "first_options"  => ["label" => "New e-mail"],
                "second_options" => ["label" => "Repeat new e-mail"],
                "type" => EmailType::class
            ])
            ->add("password", PasswordType::class)
            ->add("submit", SubmitType::class)
            ->getForm();
        return $form;
    }
}