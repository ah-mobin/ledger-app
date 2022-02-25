@extends('layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                {{ __('Users') }}
            </h2>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            @include('layouts.flash_alerts')

            <div class="alert alert-info overflow-hidden">
                <div class="alert-title float-start">Customer Data</div>
                <button type="button" class="btn btn-sm btn-success rounder float-end" data-bs-toggle="modal" data-bs-target="#customerAdd">
                    Add Customer
                </button>
            </div>

            <div class="card">

                <div>
                    <form action="" method="GET" class="w-75 d-flex justify-content-between my-3">
                        <input type="text" name="search" class="form-control">
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
                                <td>{{ $customer->created_at->diffForhumans() }}</td>
                                <td>
                                    <a href="" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                    <a href="" class="btn btn-info"><i class="fa fa-file"></i></a>
                                </td>
                            </tr>
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

    <!-- Modal -->
    <div class="modal fade" id="customerAdd" tabindex="-1" aria-labelledby="customerAddLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerAddLabel">Customer Add</h5>
                </div>
                <form action="{{ route('customers.store') }}" method="POST">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
