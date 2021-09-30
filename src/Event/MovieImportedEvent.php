<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\Movie;

class MovieImportedEvent extends Event
{
    private $movie;

    public function __construct(Movie $movie)
    {
        $this->movie =  $movie;
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }
}
