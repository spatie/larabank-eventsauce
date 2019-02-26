<?php

namespace App\Domain\Account\Consumers;

use App\Account;
use App\Domain\Account\MoreMoneyNeeded;
use App\Mail\LoanProposalMail;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use Illuminate\Support\Facades\Mail;

class OfferLoan implements Consumer
{
    public function handle(Message $message)
    {
        $event = $message->event();

        if (! $event instanceof MoreMoneyNeeded) {
            return;
        }

        $uuid = $message->aggregateRootId()->toString();

        $account = Account::where('uuid', $uuid)->first();

        Mail::to($account->user)->send(new LoanProposalMail());
    }
}