@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Edit Student Information') }}</span>
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

                    <form method="POST" action="{{ route('students.update', $student->student_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="student_id">{{ __('Student ID') }}</label>
                            <input type="text" class="form-control" id="student_id" name="student_id"
                                   value="{{ $student->student_id }}" readonly>
                            <small class="form-text text-muted">Student ID cannot be changed</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="E_name">{{ __('English Name') }}</label>
                            <input type="text" class="form-control @error('E_name') is-invalid @enderror"
                                   id="E_name" name="E_name" value="{{ old('E_name', $student->E_name) }}" required>
                            @error('E_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="C_name">{{ __('Chinese Name') }}</label>
                            <input type="text" class="form-control @error('C_name') is-invalid @enderror"
                                   id="C_name" name="C_name" value="{{ old('C_name', $student->C_name) }}" required>
                            @error('C_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="start_date">{{ __('Start Date') }}</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                   id="start_date" name="start_date" value="{{ old('start_date', $student->start_date) }}" required>
                            @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="Cellgroup">{{ __('Cellgroup') }}</label>
                            <input type="text" class="form-control @error('Cellgroup') is-invalid @enderror"
                                   id="Cellgroup" name="Cellgroup" value="{{ old('Cellgroup', $student->Cellgroup) }}" required>
                            @error('Cellgroup')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="class">{{ __('Class') }}</label>
                            <select class="form-control @error('class') is-invalid @enderror" id="class" name="class" required>
                                <option value="第一班" {{ old('class', $student->class) == '第一班' ? 'selected' : '' }}>第一班</option>
                                <option value="第二班" {{ old('class', $student->class) == '第二班' ? 'selected' : '' }}>第二班</option>
                                <option value="第三班" {{ old('class', $student->class) == '第三班' ? 'selected' : '' }}>第三班</option>
                                <option value="旁听生" {{ old('class', $student->class) == '旁听生' ? 'selected' : '' }}>旁听生</option>
                            </select>
                            @error('class')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="text" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" value="{{ old('password', $student->password) }}"
                                   placeholder="Leave blank to keep current password">
                            <small class="form-text text-muted">Password is stored unhashed for church administration purposes. Leave blank to keep current password.</small>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('students.show', $student->student_id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">{{ __('Update Student') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
