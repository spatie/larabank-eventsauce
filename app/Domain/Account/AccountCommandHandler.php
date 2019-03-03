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


        $aggregateRoot = $this->repository->retrieve($aggregateRootId);

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
            $this->repository->persist($aggregateRoot);
        }
    }
}
