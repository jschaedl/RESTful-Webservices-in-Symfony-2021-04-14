<?php

declare(strict_types=1);

namespace App\Controller\Attendee;

use App\Entity\Attendee;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/attendees/{identifier}', name: 'read_attendee', methods: ['GET'])]
final class ReadController
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Attendee $attendee, Request $request): Response
    {
        $format = str_replace('application/', '', $request->headers->get('Accept'));

        $serializedAttendee = $this->serializer->serialize($attendee, $format);

        return new Response($serializedAttendee, Response::HTTP_OK, [
            'Content-Type' => $request->headers->get('Accept'),
        ]);
    }
}
