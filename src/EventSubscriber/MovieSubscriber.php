<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\MovieImportedEvent;

class MovieSubscriber implements EventSubscriberInterface
{
    public function onMovieImported(MovieImportedEvent $event)
    {
        $email = [
            'from' => 'admin',
            'to' => 'all@sensiotv.io',
            'subject' => sprintf('Movie "%s" imported', $event->getMovie()->getTitle()),
        ];

        dump($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            'movie_imported' => 'onMovieImported',
        ];
    }
}
