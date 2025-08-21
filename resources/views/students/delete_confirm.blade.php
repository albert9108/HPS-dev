@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Delete Student - Confirmation Required') }}</span>
                        <a href="{{ route('students.show', $student->student_id) }}" class="btn btn-light btn-sm">Cancel</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Warning!</strong> This action cannot be undone. All student data and attendance records will be permanently deleted.
                    </div>

                    <div class="mb-4">
                        <h5>You are about to delete the following student:</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
                                <p><strong>English Name:</strong> {{ $student->E_name }}</p>
                                <p><strong>Chinese Name:</strong> {{ $student->C_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Class:</strong> {{ $student->class }}</p>
                                <p><strong>Cell Group:</strong> {{ $student->Cellgroup }}</p>
                                <p><strong>Start Date:</strong> {{ $student->start_date }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>What will be deleted:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Student profile and information</li>
                            <li>User login credentials</li>
                            <li>All attendance records</li>
                            <li>Access to class resources</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_text" class="form-label">
                            <strong>Type "DELETE" to confirm this action:</strong>
                        </label>
                        <input type="text" class="form-control" id="confirm_text" placeholder="Type DELETE here">
                        <small class="form-text text-muted">This confirmation is required to prevent accidental deletions.</small>
                    </div>

                    <form method="POST" action="{{ route('students.destroy', $student->student_id) }}" id="deleteForm">
                        @csrf
                        @method('DELETE')

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('students.show', $student->student_id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-danger" id="deleteButton" disabled>
                                <i class="fas fa-trash"></i> {{ __('Permanently Delete Student') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmText = document.getElementById('confirm_text');
    const deleteButton = document.getElementById('deleteButton');
    const deleteForm = document.getElementById('deleteForm');

    confirmText.addEventListener('input', function() {
        if (this.value.trim() === 'DELETE') {
            deleteButton.disabled = false;
            deleteButton.classList.remove('btn-secondary');
            deleteButton.classList.add('btn-danger');
        } else {
            deleteButton.disabled = true;
            deleteButton.classList.remove('btn-danger');
            deleteButton.classList.add('btn-secondary');
        }
    });

    deleteForm.addEventListener('submit', function(e) {
        if (confirmText.value.trim() !== 'DELETE') {
            e.preventDefault();
            alert('Please type "DELETE" to confirm this action.');
            return false;
        }

        if (!confirm('Are you absolutely sure you want to delete this student? This action cannot be undone!')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection
