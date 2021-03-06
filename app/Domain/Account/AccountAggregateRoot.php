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

    private $accountLimitHitInARow = 0;

    public function createAccount(CreateAccount $command)
    {
        $this->recordThat(new AccountCreated(
            $command->name(),
            $command->user_id()
        ));
    }

    public function applyCreateAccount()
    {

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
        // \Log::info('apply money subtracted' . $event->amount());

        $this->accountLimitHitInARow = 0;

        $this->balance += $event->amount();
    }

    public function subtractMoney(SubtractMoney $command)
    {
        if (! $this->hasSufficientFundsToSubtractAmount($command->amount())) {
            $this->recordThat(new AccountLimitHit());

            if ($this->needsMoreMoney()) {
                $this->recordThat(new MoreMoneyNeeded());
            }

            throw CouldNotSubtractMoney::notEnoughFunds($command->amount());
        }

        $this->recordThat(new MoneySubtracted(
            $command->amount()
        ));
    }

    protected function applyMoneySubtracted(MoneySubtracted $event)
    {
        // \Log::info('apply money subtracted' . $event->amount());

        $this->balance -= $event->amount();

        $this->accountLimitHitInARow = 0;
    }

    public function applyAccountLimitHit()
    {
        $this->accountLimitHitInARow++;
    }

    protected function hasSufficientFundsToSubtractAmount(int $amount): bool
    {
        return $this->balance - $amount >= $this->accountLimit;
    }

    protected function needsMoreMoney()
    {
        return $this->accountLimitHitInARow >= 3;
    }
}
