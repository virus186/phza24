<?php


namespace Modules\Account\Observers;


use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\Transaction;

class TransactionsObserver
{
    public function creating(Transaction $transaction)
    {
        $transaction->created_by = getParentSellerId();
    }

    public function updating(Transaction $transaction)
    {
        $transaction->updated_by = Auth::id();
    }
}
