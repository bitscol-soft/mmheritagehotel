@foreach ($accountTypes as $key => $account_type)
    <div class="col-lg-3"></div>
    <div class="col-lg-4 text-left">
        {{ $account_type->name }}
    </div>
    <div class="col-lg-5 text-left">
        : <span>{{ $transaction_ledgers->where('payment_type', $account_type->id)->sum('in') }}</span>
    </div>
@endforeach