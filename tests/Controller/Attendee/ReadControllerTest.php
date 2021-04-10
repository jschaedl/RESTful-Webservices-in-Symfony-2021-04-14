<?php

declare(strict_types=1);

namespace App\Tests\Controller\Attendee;

use App\Tests\ApiTestCase;

class ReadControllerTest extends ApiTestCase
{
    public function test_it_should_requested_attendee(): void
    {
        $this->loadFixtures([
            __DIR__.'/fixtures/read_attendee.yaml',
        ]);

        $this->browser->request('GET', '/attendees/b901c47a-ca57-4a45-8cc7-19657d328a8b');

        static::assertResponseIsSuccessful();

        $this->assertMatchesJsonSnapshot($this->browser->getResponse()->getContent());
    }
}
