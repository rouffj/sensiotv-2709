<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Omdb\OmdbClient;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\MovieImportedEvent;

/**
 * @Route("/movie", name="movie_", methods={"GET"})
 */
class MovieController extends AbstractController
{
    public function __construct(private OmdbClient $omdbClient)
    {
    }

    #[Route("/{id}", name:"show", requirements:["id" => "\d+"], defaults:["id" => 1])]
    public function show($id, MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->find($id);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route("/search", name: "search")]
    public function search(Request $request): Response
    {
        $keyword = $request->query->get('keyword', 'Tech');
        $search = $this->omdbClient->requestAllBySearch($keyword);

        return $this->render('movie/search.html.twig', [
            'keyword' => $keyword,
            'movies' => $search['Search'],
        ]);
    }

    /**
     * @Route("/latest", name="latest")
     */
    public function latest(MovieRepository $movieRepository): Response
    {
        $movies  = $movieRepository->findAll();

        return $this->render('movie/latest.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route("/{imdbId}/import")]
    public function import($imdbId, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher): Response
    {
        $movieAsArray = $this->omdbClient->requestOneById($imdbId);
        $movie = Movie::fromApi($movieAsArray);
        $entityManager->persist($movie);
        $entityManager->flush();

        $eventDispatcher->dispatch(new MovieImportedEvent($movie), 'movie_imported');

        return $this->redirectToRoute('movie_latest');
    }
}
