<?php

namespace App\EntityListener;

use App\Entity\Collection;
use App\Entity\CollectionFounderMember;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CollectionListener
{
    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public function prePersist(Collection $collection): void
    {
        // Associate the founder
        /* @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $founder = new CollectionFounderMember();
        $founder
            ->setUser($user)
            ->setCollection($collection)
        ;
        $this->entityManager->persist($founder);
    }
}