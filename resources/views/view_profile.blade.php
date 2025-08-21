@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Student Profile') }}</span>
                        @if(Auth::user()->isAdmin())
                        <div class="btn-group">
                            <a href="{{ route('students.edit', $student->student_id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                            <a href="{{ route('students.change-password-form', $student->student_id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-key"></i> Change Password
                            </a>
                            <a href="{{ route('students.delete-confirm', $student->student_id) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete Student
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="student_id">{{ __('Student ID') }}</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" value="{{ $student->student_id }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="E_name">{{ __('English Name') }}</label>
                        <input type="text" class="form-control" id="E_name" name="E_name" value="{{ $student->E_name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="C_name">{{ __('Chinese Name') }}</label>
                        <input type="text" class="form-control" id="C_name" name="C_name" value="{{ $student->C_name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="start_date">{{ __('Start Date') }}</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $student->start_date }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="Cellgroup">{{ __('Cellgroup') }}</label>
                        <input type="text" class="form-control" id="Cellgroup" name="Cellgroup" value="{{ $student->Cellgroup }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="class">{{ __('Class') }}</label>
                        <input type="text" class="form-control" id="class" name="class" value="{{ $student->class }}" readonly>
                    </div>

                    @if(Auth::user()->isAdmin())
                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input type="text" class="form-control" id="password" name="password" value="{{ $student->password }}" readonly>
                        <small class="form-text text-muted">Password is visible for church administration purposes.</small>
                    </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to Student List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
