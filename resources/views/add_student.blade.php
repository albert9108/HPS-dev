@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add New Student') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('students.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="id">{{ __('Student ID') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">HPS</span>
                                </div>
                                <input type="text" class="form-control" id="user_id" name="user_id"  required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="E_name">{{ __('English Name') }}</label>
                            <input type="text" class="form-control" id="E_name" name="E_name" required>
                        </div>

                        <div class="form-group">
                            <label for="C_name">{{ __('Chinese Name') }}</label>
                            <input type="text" class="form-control" id="C_name" name="C_name" required>
                        </div>

                        <div class="form-group">
                            <label for="start_date">{{ __('Start Date') }}</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>

                        <div class="form-group">
                            <label for="Cellgroup">{{ __('Cellgroup') }}</label>
                            <input type="text" class="form-control" id="Cellgroup" name="Cellgroup" required>
                        </div>

                        <div class="form-group">
                            <label for="class">{{ __('Class') }}</label>
                            <select class="form-control" id="class" name="class" required>
                                <option value="第一班">第一班</option>
                                <option value="第二班">第二班</option>
                                <option value="第三班">第三班</option>
                                <option value="旁听生">旁听生</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Add Student') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
