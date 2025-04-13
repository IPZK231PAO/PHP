<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments Management</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        .filter-container { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .filter-row { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 10px; }
        .filter-group { flex: 1; min-width: 200px; }
        .pagination-container { display: flex; justify-content: space-between; margin: 20px 0; }
        .per-page-selector { display: flex; align-items: center; gap: 10px; }
        #edit-enrollment-form { display: none; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Enrollments Management</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <div class="filter-container">
        <h3>Filter Enrollments</h3>
        <form method="GET" action="{{ route('enrollments.index') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="id">ID:</label>
                    <input type="number" name="id" id="id" value="{{ request('id') }}" placeholder="Filter by ID">
                </div>
                
                <div class="filter-group">
                    <label for="student_id">Student:</label>
                    <select name="student_id" id="student_id">
                        <option value="">All Students</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="course_id">Course:</label>
                    <select name="course_id" id="course_id">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label for="enrollment_date">Enrollment Date:</label>
                    <input type="date" name="enrollment_date" id="enrollment_date" value="{{ request('enrollment_date') }}">
                </div>
                
                <div class="filter-group">
                    <button type="submit">Apply Filters</button>
                    <a href="{{ route('enrollments.index') }}" style="margin-left: 10px;">Reset Filters</a>
                </div>
            </div>
        </form>
    </div>

    <div class="pagination-container">
        <div>
            Showing {{ $enrollments->firstItem() }} to {{ $enrollments->lastItem() }} of {{ $enrollments->total() }} entries
        </div>
        <div class="per-page-selector">
            <span>Items per page:</span>
            <select id="per-page-select" onchange="updateItemsPerPage(this.value)">
                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Course</th>
                <th>Enrollment Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $enrollment)
                <tr>
                    <td>{{ $enrollment->id }}</td>
                    <td>{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</td>
                    <td>{{ $enrollment->course->title }}</td>
                    <td>{{ $enrollment->enrollment_date }}</td>
                    <td>
                        <button class="edit-enrollment-btn" data-id="{{ $enrollment->id }}">Edit</button> |
                        <a href="{{ route('enrollments.destroy', $enrollment->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $enrollment->id }}').submit();">Delete</a>
                        <form id="delete-form-{{ $enrollment->id }}" action="{{ route('enrollments.destroy', $enrollment->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $enrollments->appends(request()->query())->links() }}
    </div>

    <form id="add-enrollment-form" method="POST" action="{{ route('enrollments.store') }}">
        @csrf
        <label for="student_id">Student:</label>
        <select name="student_id" id="student_id" required>
            @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
            @endforeach
        </select><br><br>

        <label for="course_id">Course:</label>
        <select name="course_id" id="course_id" required>
            @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->title }}</option>
            @endforeach
        </select><br><br>

        <label for="enrollment_date">Enrollment Date:</label>
        <input type="date" name="enrollment_date" id="enrollment_date" required><br><br>

        <button type="submit">Save</button>
        <button type="button" id="cancel-form-btn">Cancel</button>
    </form>

    <form id="edit-enrollment-form" method="POST" action="" style="display:none;">
        @csrf
        @method('PUT')
        <input type="hidden" name="enrollment_id" id="enrollment-id">
        
        <label for="edit_student_id">Student:</label>
        <select name="student_id" id="edit_student_id" required>
            @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
            @endforeach
        </select><br><br>

        <label for="edit_course_id">Course:</label>
        <select name="course_id" id="edit_course_id" required>
            @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->title }}</option>
            @endforeach
        </select><br><br>

        <label for="edit_enrollment_date">Enrollment Date:</label>
        <input type="date" name="enrollment_date" id="edit_enrollment_date" required><br><br>

        <button type="submit">Update</button>
        <button type="button" id="cancel-edit-form-btn">Cancel</button>
    </form>

    <script>
        document.querySelectorAll('.edit-enrollment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const enrollmentId = this.getAttribute('data-id');
                
                fetch(`/enrollments/${enrollmentId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit-enrollment-form').style.display = 'block';
                        document.getElementById('enrollment-id').value = data.id;
                        document.getElementById('edit_student_id').value = data.student_id;
                        document.getElementById('edit_course_id').value = data.course_id;
                        document.getElementById('edit_enrollment_date').value = data.enrollment_date;
                        document.getElementById('edit-enrollment-form').action = `/enrollments/${data.id}`;
                    });
            });
        });

        document.getElementById('cancel-edit-form-btn').addEventListener('click', function() {
            document.getElementById('edit-enrollment-form').style.display = 'none';
        });

        document.getElementById('cancel-form-btn').addEventListener('click', function() {
            document.getElementById('add-enrollment-form').reset();
        });

        function updateItemsPerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>