<?php

namespace App\Domain\Account\Consumers;

use App\Account;
use App\Domain\Account\AccountCreated;
use App\Domain\Account\AccountDeleted;
use App\Domain\Account\MoneyAdded;
use App\Domain\Account\MoneySubtracted;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;

class AccountProjector implements Consumer
{
    public function handle(Message $message)
    {
        $event = $message->event();

        $uuid = $message->aggregateRootId()->toString();

        if ($event instanceof AccountCreated) {
            Account::create([
                'uuid' => $uuid,
                'name' => $event->name(),
                'user_id' => $event->user_id()
            ]);

            return;
        }

        if ($event instanceof MoneyAdded) {
            $account = Account::uuid($uuid);

            $account->balance += $event->amount();
            $account->save();

            return;
        }

        if ($event instanceof MoneySubtracted) {
            $account = Account::uuid($uuid);

            $account->balance -= $event->amount();
            $account->save();

            return;
        }

        if ($event instanceof AccountDeleted) {
            Account::uuid($uuid)->delete();

            return;
        }
    }
}
