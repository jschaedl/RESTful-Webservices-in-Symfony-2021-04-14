<?php

declare(strict_types=1);

namespace App\Pagination;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class PaginatedController
{
    protected string $paginatedEntity;

    public function setPaginatedEntity(string $paginatedEntity): void
    {
        $this->paginatedEntity = $paginatedEntity;
    }

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface $serializer
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $paginatedCollection = $this->createPaginatedCollection(
            $this->paginatedEntity,
            $request->query->getInt('page', 1),
            $request->query->getInt('size', 10)
        );

        $serializedData = $this->serializer->serialize($paginatedCollection, 'json');

        return new Response($serializedData, Response::HTTP_OK, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function createPaginatedCollection(string $entityClass, int $page = 1, int $size = 10): PaginatedColletion
    {
        $repository = $this->entityManager->getRepository($entityClass);
        $query = $repository->createQueryBuilder('u')->getQuery();

        $paginator = new Paginator($query);
        $total = \count($paginator);

        $paginator
            ->getQuery()
            ->setFirstResult($size * ($page - 1))
            ->setMaxResults($size);

        return new PaginatedColletion($paginator->getIterator(), $total);
    }
}
