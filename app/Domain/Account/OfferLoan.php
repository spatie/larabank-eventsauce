<?php

namespace App\Domain\Account;

use App\Mail\LoanProposalMail;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use Illuminate\Support\Facades\Mail;

class OfferLoan implements Consumer
{
    public function handle(Message $message)
    {
        $event = $message->event();

        if (! $event instanceof SeemsToBeBroke) {
            return;
        }

        $uuid = $message->aggregateRootId()->toString();

        $account = Account::where('uuid', $uuid)->first();

        Mail::to($account->user)->send(new LoanProposalMail());
    }
}