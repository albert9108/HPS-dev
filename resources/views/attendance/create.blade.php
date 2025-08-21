@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Take Attendance</h1>
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">View Records</a>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf

        <div class="row mb-4">
            <div class="col-md-4">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $date }}" required>
            </div>
            <div class="col-md-4">
                <label for="class" class="form-label">Class</label>
                <select class="form-control" id="class" name="class" required>
                    @foreach($classes as $classOption)
                        <option value="{{ $classOption }}" {{ $classOption == $class ? 'selected' : '' }}>
                            {{ $classOption }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-info form-control" onclick="loadStudents()">
                    Load Students
                </button>
            </div>
        </div>

        @if($students->count() > 0)
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Mark Attendance for {{ $class }} - {{ $date }}</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-success" onclick="markAll('present')">Mark All Present</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="markAll('absent')">Mark All Absent</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>English Name</th>
                                <th>Chinese Name</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->E_name }}</td>
                                <td>{{ $student->C_name }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @foreach(['present', 'absent', 'late', 'excused'] as $status)
                                        <input type="radio" class="btn-check"
                                               name="attendance[{{ $student->student_id }}]"
                                               value="{{ $status }}"
                                               id="status_{{ $student->student_id }}_{{ $status }}"
                                               {{ ($existingAttendance[$student->student_id] ?? 'present') == $status ? 'checked' : '' }}>
                                        <label class="btn btn-outline-{{ $status == 'present' ? 'success' : ($status == 'absent' ? 'danger' : ($status == 'late' ? 'warning' : 'info')) }}"
                                               for="status_{{ $student->student_id }}_{{ $status }}">
                                            {{ ucfirst($status) }}
                                        </label>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm"
                                           name="notes[{{ $student->student_id }}]"
                                           placeholder="Optional notes">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Save Attendance
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Please select a date and class, then click "Load Students" to begin taking attendance.
        </div>
        @endif
    </form>
</div>

<script>
function loadStudents() {
    const date = document.getElementById('date').value;
    const classValue = document.getElementById('class').value;

    if (!date || !classValue) {
        alert('Please select both date and class');
        return;
    }

    const url = new URL(window.location.href);
    url.searchParams.set('date', date);
    url.searchParams.set('class', classValue);
    window.location.href = url.toString();
}

function markAll(status) {
    const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
    radios.forEach(radio => {
        radio.checked = true;
    });
}

// Auto-save warning
let formChanged = false;
document.addEventListener('change', function(e) {
    if (e.target.type === 'radio' || e.target.type === 'text') {
        formChanged = true;
    }
});

window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
    }
});
</script>
@endsection
