<?php

namespace Tests\Concerns;

use Laravel\Passport\ClientRepository;

trait CreatesPassportClients
{
    protected function setUpPassportClient(): void
    {
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null,
            'Test Personal Access Client',
            config('app.url')
        );
    }
}
