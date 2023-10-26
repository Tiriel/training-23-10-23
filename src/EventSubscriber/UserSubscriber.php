<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class UserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
    ) {}

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        if (($user = $event->getAuthenticationToken()->getUser()) instanceof User) {
            /** @var User $user */
            $user->setLastConnectedAt(new \DateTimeImmutable());

            $this->manager->persist($user);
            $this->manager->flush();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        ];
    }
}
