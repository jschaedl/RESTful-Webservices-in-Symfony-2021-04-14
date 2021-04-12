<?php

declare(strict_types=1);

namespace App\Controller\Attendee;

use App\Entity\Attendee;
use App\Pagination\PaginatedController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/attendees', name: 'list_attendee', methods: ['GET'])]
final class ListController extends PaginatedController
{
    public function __invoke(Request $request): Response
    {
        parent::setPaginatedEntity(Attendee::class);

        return parent::__invoke($request);
    }
}
