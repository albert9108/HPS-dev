<!-- filepath: /c:/laragon/www/Learning/resources/views/partials/student_table.blade.php -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th><a href="{{ route('students.index', ['sort_by' => 'student_id', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc', 'per_page' => $perPage, 'search' => request('search')]) }}">{{ __('ID') }}</a></th>
            <th><a href="{{ route('students.index', ['sort_by' => 'E_name', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc', 'per_page' => $perPage, 'search' => request('search')]) }}">{{ __('English Name') }}</a></th>
            <th><a href="{{ route('students.index', ['sort_by' => 'C_name', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc', 'per_page' => $perPage, 'search' => request('search')]) }}">{{ __('Chinese Name') }}</a></th>
            <th><a href="{{ route('students.index', ['sort_by' => 'start_date', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc', 'per_page' => $perPage, 'search' => request('search')]) }}">{{ __('Start Date') }}</a></th>
            <th><a href="{{ route('students.index', ['sort_by' => 'Cellgroup', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc', 'per_page' => $perPage, 'search' => request('search')]) }}">{{ __('Cellgroup') }}</a></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $student)
            <tr onclick="window.location='{{ route('students.show', $student->student_id) }}'" style="cursor: pointer;">
                <td>{{ $student->student_id }}</td>
                <td>{{ $student->E_name }}</td>
                <td>{{ $student->C_name }}</td>
                <td>{{ $student->start_date }}</td>
                <td>{{ $student->Cellgroup }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-between align-items-center">
    <form method="GET" action="{{ route('students.index') }}" class="form-inline" id="perPageForm">
        <div class="form-group">
            <label for="per_page" class="mr-2">{{ __('Entries per page') }}</label>
            <select name="per_page" id="per_page" class="form-control mr-2" onchange="document.getElementById('perPageForm').submit();">
                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
            </select>
        </div>
    </form>
    <div>
        {{ $students->appends(['sort_by' => $sortBy, 'sort_order' => $sortOrder, 'per_page' => $perPage, 'search' => request('search')])->links('pagination::bootstrap-4') }}
    </div>
</div>
