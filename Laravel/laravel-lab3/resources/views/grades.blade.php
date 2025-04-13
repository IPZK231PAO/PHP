<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades Management</title>
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
        #edit-grade-form { display: none; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Grades Management</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <div class="filter-container">
        <h3>Filter Grades</h3>
        <form method="GET" action="{{ route('grades.index') }}">
            <div class="filter-row">
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
                
                <div class="filter-group">
                    <label for="lecturer_id">Lecturer:</label>
                    <select name="lecturer_id" id="lecturer_id">
                        <option value="">All Lecturers</option>
                        @foreach($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}" {{ request('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                                {{ $lecturer->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label for="score_min">Min Score:</label>
                    <input type="number" name="score_min" id="score_min" min="0" max="100" value="{{ request('score_min') }}">
                </div>
                
                <div class="filter-group">
                    <label for="score_max">Max Score:</label>
                    <input type="number" name="score_max" id="score_max" min="0" max="100" value="{{ request('score_max') }}">
                </div>
                
                <div class="filter-group">
                    <label for="exam_date">Exam Date:</label>
                    <input type="date" name="exam_date" id="exam_date" value="{{ request('exam_date') }}">
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <button type="submit">Apply Filters</button>
                    <a href="{{ route('grades.index') }}" style="margin-left: 10px;">Reset Filters</a>
                </div>
            </div>
        </form>
    </div>

    <div class="pagination-container">
        <div>
            Showing {{ $grades->firstItem() }} to {{ $grades->lastItem() }} of {{ $grades->total() }} entries
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

    <div class="form-container">
        <h3>Add New Grade</h3>
        <form action="{{ route('grades.store') }}" method="POST">
            @csrf
            <label for="student_id">Student:</label>
            <select name="student_id" required>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                @endforeach
            </select><br><br>

            <label for="course_id">Course:</label>
            <select name="course_id" required>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select><br><br>

            <label for="lecturer_id">Lecturer:</label>
            <select name="lecturer_id" required>
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}">{{ $lecturer->first_name }}</option>
                @endforeach
            </select><br><br>

            <label for="score">Score:</label>
            <input type="number" name="score" min="0" max="100" required><br><br>

            <label for="exam_date">Exam Date:</label>
            <input type="date" name="exam_date" required><br><br>

            <button type="submit">Create Grade</button>
        </form>
    </div>

    <hr>

    <h2>All Grades</h2>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Course</th>
                <th>Lecturer</th>
                <th>Score</th>
                <th>Exam Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $grade)
                <tr>
                    <td>{{ $grade->student->first_name }} {{ $grade->student->last_name }}</td>
                    <td>{{ $grade->course->title }}</td>
                    <td>{{ $grade->lecturer->first_name }}</td>
                    <td>{{ $grade->score }}</td>
                    <td>{{ $grade->exam_date }}</td>
                    <td>
                        <a href="#" class="edit-grade-btn" data-id="{{ $grade->id }}">Edit</a> |
                        <a href="{{ route('grades.destroy', $grade->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $grade->id }}').submit();">Delete</a>
                        <form id="delete-form-{{ $grade->id }}" action="{{ route('grades.destroy', $grade->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $grades->appends(request()->query())->links() }}
    </div>

    <div id="edit-grade-form" style="display: none;">
        <h3>Edit Grade</h3>
        <form id="edit-grade-action" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="grade_id" id="grade-id">

            <label for="edit-student_id">Student:</label>
            <select name="student_id" id="edit-student_id" required>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                @endforeach
            </select><br><br>

            <label for="edit-course_id">Course:</label>
            <select name="course_id" id="edit-course_id" required>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select><br><br>

            <label for="edit-lecturer_id">Lecturer:</label>
            <select name="lecturer_id" id="edit-lecturer_id" required>
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}">{{ $lecturer->first_name }}</option>
                @endforeach
            </select><br><br>

            <label for="edit-score">Score:</label>
            <input type="number" name="score" id="edit-score" min="0" max="100" required><br><br>

            <label for="edit-exam_date">Exam Date:</label>
            <input type="date" name="exam_date" id="edit-exam_date" required><br><br>

            <button type="submit">Update Grade</button>
            <button type="button" id="cancel-edit-btn">Cancel</button>
        </form>
    </div>

    <script>
        document.querySelectorAll('.edit-grade-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const gradeId = button.getAttribute('data-id');
                
                fetch(`/grades/${gradeId}/edit`)
                    .then(response => response.json())
                    .then(grade => {
                        document.getElementById('edit-grade-form').style.display = 'block';
                        document.getElementById('edit-grade-action').action = `/grades/${gradeId}`;
                        document.getElementById('grade-id').value = grade.id;
                        document.getElementById('edit-student_id').value = grade.student_id;
                        document.getElementById('edit-course_id').value = grade.course_id;
                        document.getElementById('edit-lecturer_id').value = grade.lecturer_id;
                        document.getElementById('edit-score').value = grade.score;
                        document.getElementById('edit-exam_date').value = grade.exam_date;
                    });
            });
        });

        document.getElementById('cancel-edit-btn').addEventListener('click', function() {
            document.getElementById('edit-grade-form').style.display = 'none';
        });

        function updateItemsPerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>