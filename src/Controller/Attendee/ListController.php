<?php

declare(strict_types=1);

namespace App\Controller\Attendee;

use App\Entity\Attendee;
use App\Pagination\PaginatedController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/attendees', name: 'list_attendee', methods: ['GET'])]
final class ListController extends PaginatedController
{
    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        parent::__construct($entityManager, $serializer);
        parent::setPaginatedEntity(Attendee::class);
    }
}
