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

    public function getUser($id = null) {
        if($id == null) {
            return $this->_user;
        } else {
            return $this->_em->getRepository("AppBundle:User")->findOneBy([
                "id" => $id,
            ]);
        }
    }

    public function changeEmail($data) {
        $password = $this->getHashedPassword($data["password"]);
        if($password == $this->_user->getPassword()){
            $this->_user->setEmail($data["email"]);
            $this->_em->persist($this->_user);
            $this->_em->flush();

            return true;
        }
        return false;
    }

    public function changePassword($data) {
        $hashed_old_pwd = $this->getHashedPassword($data["old_password"], $this->_user->getSalt());

        if($hashed_old_pwd === $this->_user->getPassword()) {
            $hashed_new_pwd = $this->getHashedPassword($data["password"], $this->_user->getSalt());
            $this->_user->setPassword($hashed_new_pwd);
            $this->_em->persist($this->_user);
            $this->_em->flush();

            return true;
        }
        return false;
    }

    public function getChangeEmailForm() {
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

    public function getChangePasswordForm() {
        $form = $this->_container->get('form.factory')->createBuilder()
            ->add("password", RepeatedType::class, [
                "first_options"  => ["label" => "New password"],
                "second_options" => ["label" => "Repeat new password"],
                "type" => PasswordType::class
            ])
            ->add("old_password", PasswordType::class)
            ->add("submit", SubmitType::class)
            ->getForm();
        return $form;
    }

    public function getHashedPassword($password, $salt) {
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        return $encoder->encodePassword($password, $salt);
    }

}