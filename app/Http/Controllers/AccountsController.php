<?php

namespace App\Http\Controllers;

use App\Account;
use App\Domain\Account\AccountCommandHandler;
use App\Domain\Account\AccountIdentifier;
use App\Domain\Account\AddMoney;
use App\Domain\Account\CreateAccount;
use App\Domain\Account\DeleteAccount;
use App\Domain\Account\SubtractMoney;
use App\Http\Requests\UpdateAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller
{
    /** @var \App\Domain\Account\AccountCommandHandler */
    protected $commandHandler;

    public function __construct(AccountCommandHandler $commandHandler)
    {
        $this->commandHandler = $commandHandler;
    }

    public function index()
    {
        $accounts = Account::where('user_id', Auth::user()->id)->get();

        return view('accounts.index', compact('accounts'));
    }

    public function store(Request $request, AccountCommandHandler $commandHandler)
    {
        $commandHandler->handle(new CreateAccount(
            AccountIdentifier::create(),
            $request->name,
            Auth::user()->id
        ));

        return back();
    }

    public function update(Account $account, UpdateAccountRequest $request)
    {
        $accountIdentifier = AccountIdentifier::fromString($account->uuid);

        $command = $request->adding()
            ? new AddMoney($accountIdentifier, (int)$request->amount)
            : new SubtractMoney($accountIdentifier, (int)$request->amount);

        $this->commandHandler->handle($command);

        return back();
    }

    public function destroy(Account $account)
    {
        $accountIdentifier = AccountIdentifier::fromString($account->uuid);

        $this->commandHandler->handle(new DeleteAccount($accountIdentifier));

        return back();
    }
}
