<?php

namespace App\Domain\Account;

use App\Domain\Account\Projectors\AccountProjector;
use App\Domain\Account\Projectors\TransactionCountProjector;
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
