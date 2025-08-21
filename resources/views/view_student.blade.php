<!-- filepath: /c:/laragon/www/Learning/resources/views/view_student.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span style="margin-right: 8px;">{{ __('View Students') }}</span>

                        <a href="{{ route('students.export', ['sort_by' => $sortBy, 'sort_order' => $sortOrder, 'per_page' => $perPage]) }}" class="btn btn-success">{{ __('Export to Excel') }}</a>
                    </div>
                    <form method="GET" action="{{ route('students.index') }}" class="form-inline d-flex">
                        <input type="text" name="search" id="search" class="form-control mr-2" placeholder="Search" value="{{ request('search') }}">
                    </form>
                </div>

                <div class="card-body" id="student-table">
                    @include('partials.student_table', ['students' => $students, 'sortBy' => $sortBy, 'sortOrder' => $sortOrder, 'perPage' => $perPage, 'search' => $search])
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

    $(document).ready(function() {
        console.log('Document is ready');

        $('#search').on('input', function() {
            console.log('Input event triggered');
            let search = $(this).val();
            let url = '{{ route("students.search") }}';
            let params = {
                search: search,
                sort_by: '{{ $sortBy }}',
                sort_order: '{{ $sortOrder }}',
                per_page: '{{ $perPage }}'
            };

            console.log(`Fetching: ${url}?${$.param(params)}`);

            $.ajax({
                url: url,
                type: 'GET',
                data: params,
                success: function(response) {
                    $('#student-table').html(response);
                    console.log('Table updated successfully');
                },
                error: function(xhr) {
                    console.error('There was a problem with the fetch operation:', xhr);
                }
            });
        });
    });
</script>
@endsection

