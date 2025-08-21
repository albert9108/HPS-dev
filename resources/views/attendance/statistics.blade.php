@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Attendance Statistics</h1>
                <div>
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary">Take Attendance</a>
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">View Records</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Filter Options</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.statistics') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-info form-control">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Overall Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $totalRecords }}</h3>
                    <p>Total Records</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $presentCount }}</h3>
                    <p>Present</p>
                    <small>{{ $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3>{{ $absentCount }}</h3>
                    <p>Absent</p>
                    <small>{{ $totalRecords > 0 ? round(($absentCount / $totalRecords) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3>{{ $lateCount + $excusedCount }}</h3>
                    <p>Late/Excused</p>
                    <small>{{ $totalRecords > 0 ? round((($lateCount + $excusedCount) / $totalRecords) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Student-wise Statistics -->
    <div class="card">
        <div class="card-header">
            <h5>Student-wise Attendance Statistics</h5>
            <small class="text-muted">{{ $startDate }} to {{ $endDate }}</small>
        </div>
        <div class="card-body">
            @if($studentStats->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Total Days</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Late</th>
                                <th>Excused</th>
                                <th>Attendance Rate</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentStats as $stat)
                            @php
                                $attendanceRate = $stat->total_days > 0 ? round(($stat->present_days / $stat->total_days) * 100, 1) : 0;
                                $statusColor = $attendanceRate >= 90 ? 'success' : ($attendanceRate >= 75 ? 'warning' : 'danger');
                                $statusText = $attendanceRate >= 90 ? 'Excellent' : ($attendanceRate >= 75 ? 'Good' : 'Needs Improvement');
                            @endphp
                            <tr>
                                <td>{{ $stat->student_id }}</td>
                                <td>{{ $stat->student->E_name ?? 'N/A' }}</td>
                                <td>{{ $stat->student->class ?? 'N/A' }}</td>
                                <td>{{ $stat->total_days }}</td>
                                <td><span class="badge bg-success">{{ $stat->present_days }}</span></td>
                                <td><span class="badge bg-danger">{{ $stat->absent_days }}</span></td>
                                <td><span class="badge bg-warning">{{ $stat->late_days }}</span></td>
                                <td><span class="badge bg-info">{{ $stat->excused_days }}</span></td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $statusColor }}" role="progressbar"
                                             style="width: {{ $attendanceRate }}%">
                                            {{ $attendanceRate }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusColor }}">{{ $statusText }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Export Options -->
                <div class="mt-3">
                    <button class="btn btn-success" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </button>
                    <button class="btn btn-info" onclick="printReport()">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No attendance records found for the selected criteria.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function exportToExcel() {
    // Simple table to Excel export
    const table = document.querySelector('table');
    const wb = XLSX.utils.table_to_book(table, {sheet: "Attendance Statistics"});
    XLSX.writeFile(wb, `attendance_statistics_${new Date().toISOString().split('T')[0]}.xlsx`);
}

function printReport() {
    window.print();
}

// Print styles
const printCSS = `
@media print {
    .btn, .card-header a, .breadcrumb { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
    .table { font-size: 12px; }
}`;
const style = document.createElement('style');
style.textContent = printCSS;
document.head.appendChild(style);
</script>

<!-- Include XLSX library for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endsection
