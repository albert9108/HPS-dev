<!-- filepath: /C:/laragon/www/Learning/resources/views/home.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Resources for Class: ') . $class }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5>{{ __('Directories') }}</h5>
                    <ul>
                        @foreach ($directories as $directory)
                            <li>
                                <strong>{{ basename($directory) }}</strong>
                                <ul>
                                    @foreach (File::files($directory) as $subFile)
                                        <li><a href="{{ asset('resources/' . $class . '/' . basename($directory) . '/' . basename($subFile)) }}" target="_blank">{{ basename($subFile) }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>

                    <h5>{{ __('Files') }}</h5>
                    <ul>
                        @foreach ($files as $file)
                            <li><a href="{{ asset('resources/' . $class . '/' . basename($file)) }}" target="_blank">{{ basename($file) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
