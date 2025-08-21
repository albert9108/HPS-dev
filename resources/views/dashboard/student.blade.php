@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Student Dashboard</h1>
                <div class="text-muted">
                    Welcome, {{ $student->E_name ?? Auth::user()->display_name }}
                </div>
            </div>
        </div>
    </div>

    <!-- Student Info & Attendance Summary -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>My Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Student ID:</strong> {{ Auth::user()->student_id }}</p>
                            <p><strong>English Name:</strong> {{ $student->E_name ?? 'N/A' }}</p>
                            <p><strong>Chinese Name:</strong> {{ $student->C_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Class:</strong> {{ $student->class ?? 'N/A' }}</p>
                            <p><strong>Cell Group:</strong> {{ $student->Cellgroup ?? 'N/A' }}</p>
                            <p><strong>Start Date:</strong> {{ $student->start_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Attendance Summary</h5>
                    <small class="text-muted">(Last 30 days)</small>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <h2 class="text-primary">{{ $attendancePercentage }}%</h2>
                        <p class="text-muted">Attendance Rate</p>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-success">{{ $attendanceSummary['present_days'] }}</h6>
                            <small>Present</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-danger">{{ $attendanceSummary['absent_days'] }}</h6>
                            <small>Absent</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- My Attendance History -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>My Attendance History</h5>
                </div>
                <div class="card-body">
                    @if($myAttendance->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($myAttendance->take(10) as $attendance)
                                    <tr>
                                        <td>{{ $attendance->date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $attendance->status_color }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $attendance->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($myAttendance->count() > 10)
                            <small class="text-muted">Showing latest 10 records of {{ $myAttendance->count() }} total</small>
                        @endif
                    @else
                        <p class="text-muted">No attendance records found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Class Info & Quick Links -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Class Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>My Class:</strong> {{ $student->class ?? 'N/A' }}</p>
                    <p><strong>Classmates:</strong> {{ $classmates }} students</p>
                    <a href="{{ route('filemanager') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-folder"></i> Class Resources
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-blog"></i> View Posts & Articles
                        </a>
                        <a href="{{ route('filemanager') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-download"></i> Download Resources
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Posts -->
    @if($recentPosts->count() > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Latest Posts & Articles</h5>
                    <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentPosts as $post)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                            {{ $post->title }}
                                        </a>
                                    </h6>
                                    <p class="card-text text-muted small">{{ $post->excerpt }}</p>
                                    <small class="text-muted">
                                        {{ $post->published_at->diffForHumans() }}
                                        @if($post->category)
                                            <span class="badge badge-secondary">{{ $post->category }}</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
