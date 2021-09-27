<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * # [Route("/api", defaults: ["_format" => "json"])]
 * @Route("/api", defaults={"_format": "json"})
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("")
     */
    public function index(): Response
    {
        $movies = [
            ['title' => 'Dune'],
            ['title' => 'La cité de la peur'],
        ];

        throw new \InvalidArgumentException('Hello');

        return new Response(json_encode($movies));
        //return $this->json($movies);
    }
}
