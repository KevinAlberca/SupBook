<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 12/05/16
 * Time: 08:07
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{

    /**
     * @Route("/promo/{year}", name="class_group")
     */
    public function getYourClassThreads(Request $request, $year)
    {
        $user_promotion = ($this->get('security.token_storage')->getToken()->getUser()->promotion_year != $year) ? new Response("Error") : true;
        $em = $this->getDoctrine()->getManager();
        $bachelor_ids = $em->getRepository("AppBundle:User")->getDistinctPromotion();

        if(in_array($year, $bachelor_ids)){
            return new Response("OK", 200);
        } else {
            return new Response("Tu ne fais pas partit de cette classe gros !", 403);
        }
    }

    /**
     * @Route("/{shortcut_bachelor}", name="bachelor_group")
     */
    public function getYourBachelorThreads(Request $request, $shortcut_bachelor)
    {
        $em = $this->getDoctrine()->getManager();
        $user_bachelor =  $em->getRepository("AppBundle:Bachelor")->findOneBy(["id" => $this->get('security.token_storage')->getToken()->getUser()->id_bachelor]);

        if($user_bachelor->shortcut == $shortcut_bachelor){
            return new Response("Ok, it's your bachelor !", 200);
        } else {
            return new Response("It's not your bachelor", 403);
        }

    }
}