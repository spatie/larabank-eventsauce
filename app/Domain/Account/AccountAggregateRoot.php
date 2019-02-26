<?php

namespace App\Domain\Account;

use App\Domain\Account\Exceptions\CouldNotSubtractMoney;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Exception;
use Spatie\LaravelEventSauce\Concerns\IgnoresMissingMethods;

class AccountAggregateRoot implements AggregateRoot
{
    use AggregateRootBehaviour,
        IgnoresMissingMethods;

    /** @var int */
    private $balance = 0;

    /** @var int */
    private $accountLimit = -5000;

    private $accountLimitHitInARow = 0;

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
        $this->accountLimitHitInARow = 0;

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
        if ($this->canSubtractAmount($command->amount())) {
            $this->accountLimitHitInARow++;

            //record event here

            throw CouldNotSubtractMoney::notEnoughFunds($command->amount());
        }

        $this->accountLimitHitInARow = 0;

        $this->recordThat(new MoneySubtracted(
            $command->amount()
        ));
    }

    protected function applyMoneySubtracted(MoneySubtracted $event)
    {
        $this->balance -= $event->amount();
    }

    protected function canSubtractAmount(int $amount): bool
    {
        return $this->balance - $amount >= $this->accountLimit;
    }
}
