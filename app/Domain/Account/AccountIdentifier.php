<?php

namespace App\Domain\Account;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AccountIdentifier implements AggregateRootId
{
    /** @var \Ramsey\Uuid\UuidInterface */
    private $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function toString(): string
    {
        return $this->id->toString();
    }

    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static(Uuid::fromString($aggregateRootId));
    }

    public static function create(): self
    {
        return new static(Uuid::uuid4());
    }
}
