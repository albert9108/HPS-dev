@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Admin Dashboard</h1>
                <div class="btn-group">
                    <a href="{{ route('students.create') }}" class="btn btn-primary">Add Student</a>
                    <a href="{{ route('attendance.create') }}" class="btn btn-success">Take Attendance</a>
                    <a href="{{ route('posts.create') }}" class="btn btn-info">Create Post</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $totalStudents }}</h4>
                            <p>Total Students</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $activeStudents }}</h4>
                            <p>Active Students</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $attendanceStats['present'] }}</h4>
                            <p>Present Today</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $postStats['published'] }}</h4>
                            <p>Published Posts</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-blog fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Weekly Attendance Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Weekly Attendance Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Class Distribution -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Students by Class</h5>
                </div>
                <div class="card-body">
                    @foreach($studentsByClass as $class => $count)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $class }}</span>
                        <span class="badge badge-primary">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Recent Attendance -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Today's Attendance</h5>
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($todayAttendance->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayAttendance as $attendance)
                                    <tr>
                                        <td>{{ $attendance->student_id }}</td>
                                        <td>{{ $attendance->student->E_name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $attendance->status_color }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No attendance recorded today.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Posts -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Posts</h5>
                    <a href="{{ route('posts.manage') }}" class="btn btn-sm btn-outline-primary">Manage Posts</a>
                </div>
                <div class="card-body">
                    @if($recentPosts->count() > 0)
                        @foreach($recentPosts as $post)
                        <div class="mb-3">
                            <h6 class="mb-1">{{ $post->title }}</h6>
                            <small class="text-muted">
                                {{ $post->created_at->diffForHumans() }} by {{ $post->author->name }}
                                <span class="badge badge-{{ $post->status_color }}">{{ $post->status }}</span>
                            </small>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No posts available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Weekly Attendance Chart
const ctx = document.getElementById('attendanceChart').getContext('2d');
const attendanceChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($weeklyAttendance, 'date')) !!},
        datasets: [{
            label: 'Present',
            data: {!! json_encode(array_column($weeklyAttendance, 'present')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Absent',
            data: {!! json_encode(array_column($weeklyAttendance, 'absent')) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
