@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Change Admin Password') }}</span>
                        <a href="{{ route('admin.index') }}" class="btn btn-secondary btn-sm">Back to Admin List</a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6>Admin Information:</h6>
                        <p><strong>Name:</strong> {{ $admin->name }}</p>
                        <p><strong>Admin ID:</strong> {{ $admin->student_id }}</p>
                        <p><strong>Email:</strong> {{ $admin->email }}</p>
                    </div>

                    <form method="POST" action="{{ route('admin.change-password', $admin->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="password">{{ __('New Password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">{{ __('Confirm New Password') }}</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-warning">{{ __('Change Password') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
