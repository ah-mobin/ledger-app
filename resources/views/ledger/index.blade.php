@extends('layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                {{ __('Ledger') }}
            </h2>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            @include('layouts.flash_alerts')

            <div class="alert alert-info overflow-hidden">
                <div class="alert-title float-start">Ledger Data: Customer -> <span class="text-danger"> {{ $customer->customer->name }} </span> </div>
                <button type="button" class="btn btn-sm btn-success rounder float-end" data-bs-toggle="modal" data-bs-target="#customerLedgerAdd">
                    Update Ledger
                </button>
            </div>

            <div class="card">

                <div>
                    <form action="{{ route('customers.ledger.index',$customer->customer_id) }}" method="GET" class="w-75 d-flex justify-content-between my-3">
                        <input type="date" class="form-control" name="from_date">
                        <input type="date" class="form-control" name="to_date">
                        <select name="type" class="form-control">
                            <option selected disabled>Choose Type</option>
                            <option value="all">All</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->type }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary ms-2">Search</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Amount') }} ({{ config('settings.currency') }})</th>
                            <th>{{ __('Update') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($ledger as $item)
                            <tr>
                                <td>{{ $item->date->format('Y-m-d') }}</td>
                                <td>{{ $item->paymentType->type }}</td>
                                <td>{{ $item->amount }}</td>
                                <td>
                                    @if($item->payment_type_id != \App\Constants\PaymentTypeConstants::LEDGER_OPEN)
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#ledgerEdit{{ $item->id }}" title="Edit Ledger Data">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>



                            @if($item->payment_type_id != \App\Constants\PaymentTypeConstants::LEDGER_OPEN)
                            <!-- Update Ledger Modal -->
                            <div class="modal fade" id="ledgerEdit{{ $item->id }}" tabindex="-1" aria-labelledby="ledgerEditLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ledgerEditLabel">Ledger Update</h5>
                                        </div>
                                        <form action="{{ route('customers.ledger.update',[$customer->customer_id,$item->id]) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group mb-3">
                                                    <label for="type">Type</label>
                                                    <select name="type" class="form-control" id="type">
                                                        <option selected disabled>Select an option</option>
                                                        @foreach($types as $type)
                                                            @if($type->id == \App\Constants\PaymentTypeConstants::LEDGER_OPEN)
                                                            @continue
                                                            @endif
                                                            <option value="{{ $type->id }}" @selected($type->id == $item->payment_type_id)>{{ $type->type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="date">Date</label>
                                                    <input type="text" class="form-control" readonly disabled value="{{ $item->date->format('d/m/y') }}">
                                                    <input type="date" name="date" class="form-control" id="date">
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="amount">Amount ({{ config('settings.currency') }})</label>
                                                    <input type="number" name="amount" class="form-control" value="{{ $item->amount }}" id="amount">
                                                </div>

                                                <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Create</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    No Data Found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if( $ledger->hasPages() )
                    <div class="card-footer pb-0">
                        {{ $ledger->links() }}
                    </div>
                @endif
            </div>

            <div class="mt-4 p-2 d-flex justify-content-between">
                <h3 class="text-danger">Total Due: {{ config('settings.currency') }} {{ $customer->balance->due_amount }}</h3>
                <h3 class="text-green">Total Balance: {{ config('settings.currency') }} {{ $customer->balance->customer_balance }}</h3>
                <h3 class="text-info">Total Bonus: {{ config('settings.currency') }} {{ $customer->balance->bonus_amount }}</h3>
            </div>
        </div>
    </div>

    <!-- Create Customer Ledger Modal -->
    <div class="modal fade" id="customerLedgerAdd" tabindex="-1" aria-labelledby="customerLedgerAddLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerLedgerAddLabel">Ledger Update</h5>
                </div>
                <form action="{{ route('customers.ledger.store',$customer->customer_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="type">Type</label>
                            <select name="type" class="form-control" id="type">
                                <option selected disabled>Select an option</option>
                                @foreach($types as $type)
                                    @if($type->id == \App\Constants\PaymentTypeConstants::LEDGER_OPEN)
                                    @continue
                                    @endif
                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="date">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date') }}" id="date">
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount">Amount ({{ config('settings.currency') }})</label>
                            <input type="number" name="amount" class="form-control" placeholder="500" value="{{ old('amount') }}" id="amount">
                        </div>

                        <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
