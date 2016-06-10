<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 10/06/16
 * Time: 09:52
 */

namespace AppBundle;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class ProfileService
{
    protected $_context;
    protected $_em;
    private $_user;
    protected $_container;

    public function __construct(TokenStorage $context, EntityManager $em, Container $container)
    {
        $this->_context = $context;
        $this->_em = $em;
        $this->_user = $this->_context->getToken()->getUser();
        $this->_container = $container;
    }

    public function getUser() {
        return $this->_user;
    }


    public function changeEmail($data) {

        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($data["password"], $this->_user->getSalt());

        if($password == $this->_user->getPassword()){
            $this->_user->setEmail($data["email"]);
            $this->_em->persist($this->_user);
            $this->_em->flush();

            return true;
        }
        return false;
    }




    public function getChangeEmailForm()
    {
        $form = $this->_container->get('form.factory')->createBuilder()
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