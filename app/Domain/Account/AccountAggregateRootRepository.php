<?php

namespace App\Domain\Account;

use App\Domain\Account\Consumers\AccountProjector;
use App\Domain\Account\Consumers\OfferLoan;
use App\Domain\Account\Consumers\TransactionCountProjector;
use Spatie\LaravelEventSauce\AggregateRootRepository;

/** @method AccountAggregateRoot retrieve */
class AccountAggregateRootRepository extends AggregateRootRepository
{
    /** @var string */
    protected $aggregateRoot = AccountAggregateRoot::class;

    protected $tableName = 'account_domain_messages';

    /** @var array */
    protected $consumers = [
        AccountProjector::class,
    ];

    /** @var array */
    protected $queuedConsumers = [
        TransactionCountProjector::class,
        OfferLoan::class,
    ];
}
