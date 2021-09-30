<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Movie;
use App\Entity\User;
use App\Entity\Review;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;


/**
 * POST /api/users
 * GET /api/users
 * GET /api/users/:id
 * PUT /api/users/:id
 */
#[Route("/api/users", defaults: ["_format" => "json"])]
class UserController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer, private ValidatorInterface $validator, private EntityManagerInterface $entityManager)
    {
    }

    #[Route("", methods: "POST")]
    public function post(Request $request): Response
    {
        $jsonSent = $request->getContent();
        $user = $this->serializer->deserialize($jsonSent, User::class, 'json');

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $jsonErrors = $this->serializer->serialize($errors, 'json');
            return JsonResponse::fromJsonString($jsonErrors, 400);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return JsonResponse::fromJsonString($this->serializer->serialize($user, 'json'), 201);
    }

    #[Route("", methods: "GET")]
    public function list(Request $request, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $review = new Review();
        $review->setRating(4)->setBody('Great movie !');
        $users[0]->addReview($review);

        $adapter = new ArrayAdapter($users);
        $paginatedUsers = new Pagerfanta($adapter);
        $paginatedUsers->setMaxPerPage(2);

        $page = $request->query->get('page', 1);
        $paginatedUsers->setCurrentPage($page);

        //$paginatedUsers = new Paginator('Select * from ...');
        return JsonResponse::fromJsonString($this->serializer->serialize($paginatedUsers, 'json', [
            'circular_reference_handler' => function($object, $format, $context) {
                return $object->getId();
            },
        ]));
        //return JsonResponse::fromJsonString($this->serializer->serialize($users, 'json'));
        //return JsonResponse::fromJsonString($this->serializer->serialize($users, 'json', [
            //'groups' => ['user_list', 'relation'],
            //'ignored_attributes' => ['password'],
        //]));
    }

    #[Route("/{id}", methods: "GET")]
    public function show($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return JsonResponse::fromJsonString($this->serializer->serialize($user, 'json'));
    }

    #[Route("/{id}", methods: "PUT")]
    public function edit(Request $request, $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);
        $this->entityManager->flush();

        return JsonResponse::fromJsonString($this->serializer->serialize($user, 'json'));
    }
}
