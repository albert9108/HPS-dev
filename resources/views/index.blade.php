<!-- filepath: /c:/laragon/www/Learning/resources/views/resources/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Resources for Class: ') . $class }}</div>

                <div class="card-body">
                    <h5>{{ __('Directories') }}</h5>
                    <ul>
                        @foreach ($directories as $directory)
                            <li>{{ basename($directory) }}</li>
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
