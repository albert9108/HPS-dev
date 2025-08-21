@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Attendance Records</h1>
                <div>
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary">Take Attendance</a>
                    <a href="{{ route('attendance.statistics') }}" class="btn btn-info">View Statistics</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Filter Records</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
                    </div>
                    <div class="col-md-4">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-control" id="class" name="class">
                            <option value="">All Classes</option>
                            @foreach($classes as $classOption)
                                <option value="{{ $classOption }}" {{ $classOption == $class ? 'selected' : '' }}>
                                    {{ $classOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-info form-control">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="card">
        <div class="card-header">
            <h5>
                Attendance Records
                @if($class)
                    for {{ $class }}
                @endif
                @if($date)
                    on {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                @endif
            </h5>
        </div>
        <div class="card-body">
            @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Recorded By</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                                <td>{{ $attendance->student_id }}</td>
                                <td>{{ $attendance->student->E_name ?? 'N/A' }}</td>
                                <td>{{ $attendance->class }}</td>
                                <td>
                                    <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : ($attendance->status == 'late' ? 'warning' : 'info')) }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td>{{ $attendance->notes ?? '-' }}</td>
                                <td>{{ $attendance->recorder->name ?? 'System' }}</td>
                                <td>{{ $attendance->created_at->format('H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary Statistics -->
                @if($attendances->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ $attendances->where('status', 'present')->count() }}</h4>
                                <p>Present</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h4>{{ $attendances->where('status', 'absent')->count() }}</h4>
                                <p>Absent</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ $attendances->where('status', 'late')->count() }}</h4>
                                <p>Late</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>{{ $attendances->where('status', 'excused')->count() }}</h4>
                                <p>Excused</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    No attendance records found for the selected criteria.
                    @if(!$class && !$date)
                        <br>Try selecting a specific date or class to view records.
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
