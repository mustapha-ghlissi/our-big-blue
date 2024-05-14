<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @class EasyAdminSubscriber
 */
class EasyAdminSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['encryptPassword'],
            BeforeEntityUpdatedEvent::class => ['encryptPassword'],
        ];
    }

    /**
     * @param BeforeEntityPersistedEvent|BeforeEntityUpdatedEvent $event
     * @return void
     */
    public function encryptPassword(BeforeEntityPersistedEvent|BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword($entity, $entity->getPassword());
        $entity->setPassword($hashedPassword);
    }
}
