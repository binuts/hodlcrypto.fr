<?php

namespace App\EventListener;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

final class LastLoginListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof Users) {
            $user->setLastLogin(new \DateTimeImmutable('Europe/Paris'));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}
