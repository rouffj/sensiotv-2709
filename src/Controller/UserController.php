<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/register")
     */
    public function register(): Response
    {
        return $this->render('user/register.html.twig');
    }

    /**
     * @Route("/login")
     */
    public function login(): Response
    {
        return $this->render('user/signin.html.twig');
    }
}
