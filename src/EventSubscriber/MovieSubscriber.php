<?php

namespace App\EventSubscriber;

use App\Movie\Event\MovieImportEvent;
use App\Movie\Event\MovieUnderageEvent;
use App\Movie\Notifications\MovieNotifier;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MovieSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MovieNotifier $notifier,
        private readonly UserRepository $userRepository
    ) {}

    public function onMovieImportEvent(MovieImportEvent $event): void
    {
        $user = $this->userRepository->findOneBy([]);
        $this->notifier->sendNotification($event->getMovie()->getTitle(), $user);
    }

    public function onMovieUnderageEvent(MovieUnderageEvent $event): void
    {
        // prévenir les admins
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MovieImportEvent::class => 'onMovieImportEvent',
            MovieUnderageEvent::class => 'onMovieUnderageEvent',
        ];
    }
}
