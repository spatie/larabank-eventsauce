<?php

namespace App\Domain\Account;

use App\Domain\Account\Exceptions\CouldNotSubtractMoney;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Spatie\LaravelEventSauce\Concerns\IgnoresMissingMethods;

class AccountAggregateRoot implements AggregateRoot
{
    use AggregateRootBehaviour,
        IgnoresMissingMethods;

    /** @var int */
    private $balance = 0;

    /** @var int */
    private $accountLimit = -5000;

    private $insufficientFUndsInARow = 0;

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
        $this->insufficientFUndsInARow = 0;

        $this->balance += $event->amount();
    }

    public function subtractMoney(SubtractMoney $command)
    {
        if (! $this->hasSufficientFundsToSubtractAmount($command->amount())) {
            if ($this->seemsToBeBroke()) {
                $this->recordThat(new SeemsToBeBroke());
            }

            throw CouldNotSubtractMoney::notEnoughFunds($command->amount());
        }

        $this->recordThat(new MoneySubtracted(
            $command->amount()
        ));
    }

    protected function applyMoneySubtracted(MoneySubtracted $event)
    {
        $this->insufficientFUndsInARow = 0;

        $this->balance -= $event->amount();
    }

    protected function hasSufficientFundsToSubtractAmount(int $amount): bool
    {
        return $this->balance - $amount >= $this->accountLimit;
    }

    private function seemsToBeBroke()
    {
        $this->insufficientFUndsInARow >= 3;
    }
}
