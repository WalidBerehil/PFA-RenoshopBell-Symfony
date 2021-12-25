<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends EasyAdminController
{
    private UserPasswordEncoderInterface $encoder;

    protected function persistUserEntity($user)
    {
        $encodedPassword = $this->encoder ->encodePassword($user, $user->getPassword());;
        $user->setPassword($encodedPassword);

        parent::persistEntity($user);
    }

    public function updateUserEntity($user)
    {
        $encodedPassword = $this->encoder ->encodePassword($user, $user->getPassword());;
        $user->setPassword($encodedPassword);

        parent::updateEntity($user);
    }






    /**
     * @required
     */
    public function setEncoder(UserPasswordEncoderInterface $encoder): void
    {
        $this->encoder = $encoder;
    }

}
