<?php

declare(strict_types=1);

namespace App\Controller\Workshop;

use App\Entity\Workshop;
use App\Pagination\PaginatedController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/workshops', name: 'list_workshop', methods: ['GET'])]
final class ListController extends PaginatedController
{
    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        parent::__construct($entityManager, $serializer);

        parent::setPaginatedEntity(Workshop::class);
    }
}
