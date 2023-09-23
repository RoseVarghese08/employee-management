@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
                <!-- Your dashboard content goes here -->

<div class="dashboard-content">
    <!-- ... -->
    <a href="{{ route('employee.create') }}">Add New Employee</a>
    <!-- ... -->
</div>

<!-- Rest of your dashboard content -->

            </div>
        </div>
    </div>
</div>
@endsection
