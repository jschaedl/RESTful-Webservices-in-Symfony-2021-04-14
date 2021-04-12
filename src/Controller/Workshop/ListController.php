<?php

declare(strict_types=1);

namespace App\Controller\Workshop;

use App\Entity\Workshop;
use App\Pagination\PaginatedController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/workshops', name: 'list_workshop', methods: ['GET'])]
final class ListController extends PaginatedController
{
    public function __invoke(Request $request): Response
    {
        parent::setPaginatedEntity(Workshop::class);

        return parent::__invoke($request);
    }
}
