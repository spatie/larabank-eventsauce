<?php

namespace Tests\Domain\Account;

use App\Domain\Account\AccountAggregateRoot;
use App\Domain\Account\AccountCommandHandler;
use App\Domain\Account\AccountIdentifier;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\Concerns\MocksApplicationServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;

abstract class AccountTestCase extends AggregateRootTestCase
{
    use CreatesApplication,
        MakesHttpRequests,
        InteractsWithAuthentication,
        InteractsWithConsole,
        InteractsWithDatabase,
        InteractsWithExceptionHandling,
        InteractsWithSession,
        MocksApplicationServices,
        RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->createApplication();
    }

    public function handle(object $command)
    {
        (new AccountCommandHandler(
            $this->repository
        ))->handle($command);
    }

    protected function newAggregateRootId(): AggregateRootId
    {
        return AccountIdentifier::create();
    }

    protected function aggregateRootClassName(): string
    {
        return AccountAggregateRoot::class;
    }
}
