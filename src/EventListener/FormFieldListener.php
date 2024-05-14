<?php

namespace App\EventListener;

use App\Entity\Field;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Field::class)]
class FormFieldListener
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function prePersist(Field $field, PrePersistEventArgs $event): void
    {
        $field->setFieldId(
            strtolower($this->slugger->slug($field->getName(), '_'))
        );
    }
}
