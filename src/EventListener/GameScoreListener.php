<?php

namespace App\EventListener;

use App\Entity\Score;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Score::class)]
class GameScoreListener
{
    public function __construct(
        private Security $security,
    ) {}

    public function prePersist(Score $score, PrePersistEventArgs $event): void
    {
        if (!$this->security->getUser() instanceof User) {
            return;
        }
        $score->setUser($this->security->getUser());
    }
}