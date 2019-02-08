<?php

namespace App\Domain\Account\Projectors;

use App\Domain\Account\AccountCreated;
use App\Domain\Account\AccountDeleted;
use App\Domain\Account\MoneyAdded;
use App\Domain\Account\MoneySubtracted;
use App\TransactionCount;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;

class TransactionCountProjector implements Consumer
{
    public function handle(Message $message)
    {
        $event = $message->event();

        $uuid = $message->aggregateRootId()->toString();

        if ($event instanceof AccountCreated) {
            TransactionCount::create([
                'uuid' => $uuid,
                'user_id' => $event->user_id(),
            ]);

            return;
        }

        if ($event instanceof MoneyAdded || $event instanceof MoneySubtracted) {
            $transactionCount = TransactionCount::uuid($uuid);

            $transactionCount->incrementCount();

            return;
        }

        if ($event instanceof AccountDeleted) {
            $transactionCount = TransactionCount::uuid($uuid);

            $transactionCount->delete();

            return;
        }
    }
}
