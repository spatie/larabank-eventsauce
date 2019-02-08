<?php

namespace Tests\Domain\Account;

use App\Domain\Account\AccountCreated;
use App\Domain\Account\CreateAccount;

class CreateAccountTest extends AccountTestCase
{
    /** @test */
    public function it_can_create_an_account()
    {
        $this->when(new CreateAccount(
            $this->aggregateRootId(),
            'Savings',
            1
        ))->then(
            new AccountCreated('Savings', 1)
        );
    }
}
