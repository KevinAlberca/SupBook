<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 01/06/16
 * Time: 15:21
 */

namespace AppBundle;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

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

    public function getThreads() {
        return $this->_em->getRepository("AppBundle:Thread")->getAllThreads();
    }

    public function getUserForm() {
        $form = $this->_container->get('form.factory')->createBuilder()
            ->add("last_name", TextType::class)
            ->add("first_name", TextType::class)
            ->add("email", TextType::class)
            ->add("promotion_year", IntegerType::class)
            ->add("id_bachelor", IntegerType::class)
            ->add("submit", SubmitType::class)
            ->getForm();
        return $form;
    }

    public function add_user($data) {
        if($data != null) {
            $roles = $this->_em->getRepository("AppBundle:Role")->findOneBy(["name" => "ROLE_USER"]);

            $salt = $this->generateSalt();
            $password = $this->generatePassword();

            $user = new User();

            $user->setUsername(strtolower($data["first_name"].".".$data["last_name"]));
            $user->setFirstname(ucfirst(strtolower($data["first_name"])));
            $user->setLastname(strtoupper($data["last_name"]));
            $user->setEmail(strtolower($data["email"]));
            $user->setPromotionYear($data["promotion_year"]);
            $user->setIdBachelor($data["id_bachelor"]);
            $user->setSalt(md5($salt.time()));

            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $pass = $encoder->encodePassword($password, $user->getSalt());
            $user->setPassword($pass);
            $user->getUserRoles()->add($roles);

            $this->_em->persist($user);
            $this->_em->flush();

            return true;
        }
        return false;
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

    private function generatePassword() {
        $p_chars = "'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $p = "";
        for($i=0; $i<8; $i++){
            $p .= $p_chars[rand(0, strlen($p_chars)-1)];
        }

        return $p;
    }

    private function generateSalt() {
        $salt_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+{}[]|\\/.,<>';
        $salt = '';
        for($i=0; $i<20; $i++){
            $salt .= $salt_chars[rand(0, strlen($salt_chars)-1)];
        }

        return $salt;
    }
}