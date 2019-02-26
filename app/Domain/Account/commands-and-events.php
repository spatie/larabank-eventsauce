<?php

namespace App\Domain\Account;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

final class AccountCreated implements SerializableEvent
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $user_id;

    public function __construct(
        string $name,
        int $user_id
    ) {
        $this->name = $name;
        $this->user_id = $user_id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function user_id(): int
    {
        return $this->user_id;
    }
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new AccountCreated(
            (string) $payload['name'],
            (int) $payload['user_id']
        );
    }

    public function toPayload(): array
    {
        return [
            'name' => (string) $this->name,
            'user_id' => (int) $this->user_id,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withNameAndUser_id(string $name, int $user_id): AccountCreated
    {
        return new AccountCreated(
            $name,
            $user_id
        );
    }
}

final class MoneyAdded implements SerializableEvent
{
    /**
     * @var int
     */
    private $amount;

    public function __construct(
        int $amount
    ) {
        $this->amount = $amount;
    }

    public function amount(): int
    {
        return $this->amount;
    }
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new MoneyAdded(
            (int) $payload['amount']
        );
    }

    public function toPayload(): array
    {
        return [
            'amount' => (int) $this->amount,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withAmount(int $amount): MoneyAdded
    {
        return new MoneyAdded(
            $amount
        );
    }
}

final class MoneySubtracted implements SerializableEvent
{
    /**
     * @var int
     */
    private $amount;

    public function __construct(
        int $amount
    ) {
        $this->amount = $amount;
    }

    public function amount(): int
    {
        return $this->amount;
    }
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new MoneySubtracted(
            (int) $payload['amount']
        );
    }

    public function toPayload(): array
    {
        return [
            'amount' => (int) $this->amount,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withAmount(int $amount): MoneySubtracted
    {
        return new MoneySubtracted(
            $amount
        );
    }
}

final class AccountDeleted implements SerializableEvent
{
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new AccountDeleted();
    }

    public function toPayload(): array
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): AccountDeleted
    {
        return new AccountDeleted();
    }
}

final class AccountLimitHit implements SerializableEvent
{
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new AccountLimitHit();
    }

    public function toPayload(): array
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): AccountLimitHit
    {
        return new AccountLimitHit();
    }
}

final class MoreMoneyNeeded implements SerializableEvent
{
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new MoreMoneyNeeded();
    }

    public function toPayload(): array
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): MoreMoneyNeeded
    {
        return new MoreMoneyNeeded();
    }
}

final class CreateAccount
{
    /**
     * @var AccountIdentifier
     */
    private $identifier;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $user_id;

    public function __construct(
        AccountIdentifier $identifier,
        string $name,
        int $user_id
    ) {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->user_id = $user_id;
    }

    public function identifier(): AccountIdentifier
    {
        return $this->identifier;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function user_id(): int
    {
        return $this->user_id;
    }
}

final class AddMoney
{
    /**
     * @var AccountIdentifier
     */
    private $identifier;

    /**
     * @var int
     */
    private $amount;

    public function __construct(
        AccountIdentifier $identifier,
        int $amount
    ) {
        $this->identifier = $identifier;
        $this->amount = $amount;
    }

    public function identifier(): AccountIdentifier
    {
        return $this->identifier;
    }

    public function amount(): int
    {
        return $this->amount;
    }
}

final class SubtractMoney
{
    /**
     * @var AccountIdentifier
     */
    private $identifier;

    /**
     * @var int
     */
    private $amount;

    public function __construct(
        AccountIdentifier $identifier,
        int $amount
    ) {
        $this->identifier = $identifier;
        $this->amount = $amount;
    }

    public function identifier(): AccountIdentifier
    {
        return $this->identifier;
    }

    public function amount(): int
    {
        return $this->amount;
    }
}

final class DeleteAccount
{
    /**
     * @var AccountIdentifier
     */
    private $identifier;

    public function __construct(
        AccountIdentifier $identifier
    ) {
        $this->identifier = $identifier;
    }

    public function identifier(): AccountIdentifier
    {
        return $this->identifier;
    }
}
