<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register")
     */
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entityManager->persist($entity);

            $entityManager->flush();
            dump($entity, get_class($entity));
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login")
     */
    public function login(): Response
    {
        return $this->render('user/signin.html.twig');
    }
}
