<?php

namespace App\Domain\Account;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Exception;
use Spatie\LaravelEventSauce\Concerns\IgnoresMissingMethods;

class AccountAggregateRoot implements AggregateRoot
{
    use AggregateRootBehaviour,
        IgnoresMissingMethods;

    private $balance = 0;

    public function createAccount(CreateAccount $command)
    {
        $this->recordThat(new AccountCreated(
            $command->name(),
            $command->user_id()
        ));
    }

    public function deleteAccount(DeleteAccount $command)
    {
        $this->recordThat(new AccountDeleted());
    }

    public function addMoney(AddMoney $command)
    {
        $this->recordThat(new MoneyAdded(
            $command->amount()
        ));
    }

    protected function applyMoneyAdded(MoneyAdded $event)
    {
        $this->balance += $event->amount();
    }

    public function subtractMoney(SubtractMoney $command)
    {
        if (($this->balance - $command->amount()) < -5000) {
            throw new Exception("You cannot go below -5000 on your account");
        }

        $this->recordThat(new MoneySubtracted(
            $command->amount()
        ));
    }

    protected function applyMoneySubtracted(MoneySubtracted $event)
    {
        $this->balance -= $event->amount();
    }
}
