@extends('layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                {{ __('Customers Data') }}
            </h2>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="text-center">
                <h2 class="{{ $netDues >= 0 ? 'text-success' : 'text-danger' }}">Net Dues: {{ config('settings.currency') }} {{ $netDues }}</h2>
            </div>

            @include('layouts.flash_alerts')

            <div class="alert alert-info d-flex justify-content-between">
                <h3 class="text-success ">Total Dues: {{ config('settings.currency') }} {{ $totalDues }}</h3>
                <h3 class="text-danger ">Total Customer Balances: {{ config('settings.currency') }} {{ $totalCustomerBalance }}</h3>
                <h3 class="text-danger ">Total Customer Bonus: {{ config('settings.currency') }} {{ $totalCustomerBonus }}</h3>
                <button type="button" class="btn btn-sm btn-success rounder" data-bs-toggle="modal" data-bs-target="#customerAdd">
                    Add Customer
                </button>
            </div>

            <div class="card">

                <div>
                    <form action="{{ route('customers.index') }}" method="GET" class="w-75 d-flex justify-content-between my-3">
                        <input type="text" placeholder="Search by name, email or phone number..." name="search" class="form-control">
                        <button class="btn btn-primary ms-2">Search</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email Address') }}</th>
                            <th>{{ __('Phone Number') }}</th>
                            <th>{{ __('Dues') }} ({{ config('settings.currency') }})</th>
                            <th>{{ __('Balance') }} ({{ config('settings.currency') }})</th>
                            <th>{{ __('Created at') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone_number }}</td>
                                <td>{{ $customer->customerBalance->due_amount }}</td>
                                <td>{{ $customer->customerBalance->customer_balance }}</td>
                                <td>{{ $customer->created_at->diffForhumans() }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#customerEdit{{ $customer->id }}" title="Edit Customer Info">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a href="{{ route('customers.ledger.index',$customer->id) }}" class="btn btn-info ms-5" title="View Customer Ledger"><i class="fa fa-file"></i></a>
                                </td>
                            </tr>



    <!-- Edit Customer Modal -->
    <div class="modal fade" id="customerEdit{{ $customer->id }}" tabindex="-1" aria-labelledby="customerEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerEditLabel">Customer Date Update</h5>
                </div>
                <form action="{{ route('customers.update',$customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="name">Customer Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $customer->name }}" id="nameEdit">
                        </div>

                        <div class="form-group mb-3">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ $customer->phone_number }}" id="phoneNumberEdit">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $customer->email }}" id="emailEdit">
                        </div>

                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Customer Modal -->
    <div class="modal fade" id="customerRemove{{ $customer->id }}" tabindex="-1" aria-labelledby="customerRemoveLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('customers.destroy',[$customer->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center py-4">
                        <h2 class="text-danger">Are You Sure You Want To Remove The Customer?</h2>
                        <p>You can retrieve this data further.</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger btn-sm">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if( $customers->hasPages() )
                    <div class="card-footer pb-0">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Customer Modal -->
    <div class="modal fade" id="customerAdd" tabindex="-1" aria-labelledby="customerAddLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerAddLabel">Customer Add</h5>
                </div>
                <form action="{{ route('customers.store') }}" method="POST" class="myform">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="name">Customer Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name">
                        </div>

                        <div class="form-group mb-3">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" id="phoneNumber">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" id="email">
                        </div>

                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                        <button type="button" onclick="return Disable()" class="btn btn-success submitReq">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
