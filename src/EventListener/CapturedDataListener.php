<?php

namespace App\EventListener;

use App\Entity\CapturedData;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: CapturedData::class)]
final class CapturedDataListener
{
    public function __construct(private readonly Security $security)
    {
    }

    public function prePersist(CapturedData $capturedData, PrePersistEventArgs $event): void
    {
        $capturedData->setUser(
            $this->security->getUser()
        );
    }
}
