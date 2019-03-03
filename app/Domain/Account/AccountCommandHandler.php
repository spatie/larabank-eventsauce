<?php

namespace App\Domain\Account;

class AccountCommandHandler
{
    /** @var \EventSauce\EventSourcing\AggregateRootRepository */
    private $repository;

    public function __construct(AccountAggregateRootRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(object $command)
    {
        $aggregateRootId = $command->identifier();

        /*
         * Retrieve all events for the account and apply them to a
         * fresh copy of the aggregate root.
         */
        $aggregateRoot = $this->repository->retrieve($aggregateRootId);

        /*
         * Pass the command to the aggregate root. The aggregate root will
         * record new events.
         */
        try {
            if ($command instanceof CreateAccount) {
                $aggregateRoot->createAccount($command);
            } elseif ($command instanceof DeleteAccount) {
                $aggregateRoot->deleteAccount($command);
            } elseif ($command instanceof AddMoney) {
                $aggregateRoot->addMoney($command);
            } elseif ($command instanceof SubtractMoney) {
                $aggregateRoot->subtractMoney($command);
            }
        } finally {
            /*
             * Apply the new events and persist them.
             */
            $this->repository->persist($aggregateRoot);
        }
    }
}
