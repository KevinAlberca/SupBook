<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 01/06/16
 * Time: 15:15
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_homepage")
     */
    public function indexAction() {
        $as = $this->get("admin_service");
        $r = $as->getHomepage();
        
        return $this->render("Admin/homepage.html.twig", [
            "nb_user" => $r["nb_user"],
            "nb_threads" => $r["nb_threads"],
            "nb_replies" => $r["nb_replies"],
        ]);
    }

    /**
     * @Route("/users/list", name="list_users")
     */
    public function listUsersAction() {
        $as = $this->get("admin_service");
        $users = $as->getUsers();

        return $this->render("Admin/list_users.html.twig", [
            "users" => $users,
            "user_form" =>  $as->getUserForm()->createView(),
        ]);
    }

    /**
     * @Route("/list/threads", name="list_threads")
     */
    public function listThreadsAction() {
        $as = $this->get("admin_service");
        $threads = $as->getThreads();

        return $this->render("Admin/list_threads.html.twig", [
            "threads" => $threads,
        ]);
    }

    /**
     * @Route("/list/replies", name="list_replies")
     */
    public function listRepliesAction() {
    }

    /**
     * @Route("/users/add", name="add_user")
     */
    public function add_user(Request $request) {
        $as = $this->get("admin_service");
        $form = $as->getUserForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($as->add_user($form->getData())) {
                return $this->redirectToRoute("admin_homepage");
            } else {
                
                return new Response("Error", 500);
            }
        }

        return $this->render("Admin/add_user.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}