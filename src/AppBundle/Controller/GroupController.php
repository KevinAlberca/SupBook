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
            return new Response("OK");
        } else {
            return new Response("Tu ne fais pas partit de cette classe gros !", 403);
        }
    }
}