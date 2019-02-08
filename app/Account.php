<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $guarded = [];

    public static function uuid(string $uuid): self
    {
        return static::where('uuid', $uuid)->first();
    }

    public function addMoney(int $amount)
    {
        $this->balance += $amount;

        $this->save();

        return;
    }

    public function subtractMoney(int $amount)
    {
        $this->balance -= $amount;

        $this->save();

        return;
    }
}
