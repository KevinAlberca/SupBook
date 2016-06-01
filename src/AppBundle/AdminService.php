<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 01/06/16
 * Time: 15:21
 */

namespace AppBundle;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AdminService
{
    protected $_context;
    protected $_em;
    private $_user;
    protected $_container;

    public function __construct(TokenStorage $context, EntityManager $em, Container $container) {
        $this->_context = $context;
        $this->_em = $em;
        $this->_user = $this->_context->getToken()->getUser();
        $this->_container = $container;
    }

    public function getHomepage() {
        return [
            "nb_user" => $this->countUsers(),
            "nb_threads" => $this->countThreads(),
            "nb_replies" => $this->countReplies(),
        ];
    }

    public function getUsers() {
        return $this->_em->getRepository("AppBundle:User")->findAll();
    }

    public function countUsers() {
        return count($this->_em->getRepository("AppBundle:User")->findAll());
    }

    public function countThreads() {
        return count($this->_em->getRepository("AppBundle:Thread")->findAll());
    }

    public function countReplies() {
        return count($this->_em->getRepository("AppBundle:Reply")->findAll());
    }

}