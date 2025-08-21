@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Change Student Password') }}</span>
                        <a href="{{ route('students.show', $student->student_id) }}" class="btn btn-secondary btn-sm">Back to Profile</a>
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
                        <h6>Student Information:</h6>
                        <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
                        <p><strong>English Name:</strong> {{ $student->E_name }}</p>
                        <p><strong>Chinese Name:</strong> {{ $student->C_name }}</p>
                        <p><strong>Current Password:</strong> {{ $student->password }}</p>
                    </div>

                    <form method="POST" action="{{ route('students.change-password', $student->student_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="password">{{ __('New Password') }}</label>
                            <input type="text" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            <small class="form-text text-muted">Password will be stored unhashed for church administration purposes.</small>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">{{ __('Confirm New Password') }}</label>
                            <input type="text" class="form-control"
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('students.show', $student->student_id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-warning">{{ __('Change Password') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
