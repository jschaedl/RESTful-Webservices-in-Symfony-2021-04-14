<?php

declare(strict_types=1);

namespace App\Controller\Workshop;

use App\Entity\Workshop;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/workshops/{identifier}', name: 'read_workshop', methods: ['GET'])]
final class ReadController
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Workshop $workshop, Request $request): Response
    {
        $format = str_replace('application/', '', $request->headers->get('Accept'));

        $serializedWorkshop = $this->serializer->serialize($workshop, $format);

        return new Response($serializedWorkshop, Response::HTTP_OK, [
            'Content-Type' => $request->headers->get('Accept'),
        ]);
    }
}
