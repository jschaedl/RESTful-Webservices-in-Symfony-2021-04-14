<?php

declare(strict_types=1);

namespace App\Pagination;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PaginatedController
{
    protected string $paginatedEntity;
    protected string $routeName;

    public function setPaginatedEntity(string $paginatedEntity): void
    {
        $this->paginatedEntity = $paginatedEntity;
    }

    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
    }

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface $serializer,
        protected UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $paginatedCollection = $this->createPaginatedCollection(
            $this->paginatedEntity,
            $request->query->getInt('page', 1),
            $request->query->getInt('size', 10),
            $this->routeName
        );

        $serializedData = $this->serializer->serialize($paginatedCollection, 'json');

        return new Response($serializedData, Response::HTTP_OK, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function createPaginatedCollection(string $entityClass, int $page = 1, int $size = 10, string $routeName): PaginatedColletion
    {
        $repository = $this->entityManager->getRepository($entityClass);
        $query = $repository->createQueryBuilder('u')->getQuery();

        $paginator = new Paginator($query);
        $total = \count($paginator);
        $pageCount = ceil($total / $size);

        $paginator
            ->getQuery()
            ->setFirstResult($size * ($page - 1))
            ->setMaxResults($size);

        $paginatedCollection = new PaginatedColletion($paginator->getIterator(), $total);

        $paginatedCollection->addLink('self', $this->urlGenerator->generate($routeName, [
            'page' => $page,
            'size' => $size,
        ]));

        if ($page < $pageCount) {
            $paginatedCollection->addLink('next', $this->urlGenerator->generate($routeName, [
                'page' => $page + 1,
                'size' => $size,
            ]));
        }

        if ($page > 1) {
            $paginatedCollection->addLink('prev', $this->urlGenerator->generate($routeName, [
                'page' => $page - 1,
                'size' => $size,
            ]));
        }

        $paginatedCollection->addLink('first', $this->urlGenerator->generate($routeName, [
            'page' => 1,
            'size' => $size,
        ]));

        $paginatedCollection->addLink('last', $this->urlGenerator->generate($routeName, [
            'page' => $pageCount,
            'size' => $size,
        ]));

        return $paginatedCollection;
    }
}
