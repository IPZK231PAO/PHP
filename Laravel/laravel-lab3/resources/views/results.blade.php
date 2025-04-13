<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Management</title>
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
        #result-form { display: none; margin-top: 20px; }
        button { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Result Management</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <div class="filter-container">
        <h3>Filter Results</h3>
        <form method="GET" action="{{ route('results.index') }}">
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
                    <button type="submit">Apply Filters</button>
                    <a href="{{ route('results.index') }}" style="margin-left: 10px;">Reset Filters</a>
                </div>
            </div>
        </form>
    </div>

    <div class="pagination-container">
        <div>
            Showing {{ $results->firstItem() }} to {{ $results->lastItem() }} of {{ $results->total() }} entries
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

    <h2>All Results</h2>
    <button id="add-result-form-btn">Add Result</button>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Course</th>
                <th>Lecturer</th>
                <th>Score</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->student->first_name }} {{ $result->student->last_name }}</td>
                    <td>{{ $result->course->title }}</td>
                    <td>{{ $result->lecturer->first_name }}</td>
                    <td>{{ $result->score }}</td>
                    <td>
                        <a href="#" class="edit-result-btn" data-id="{{ $result->id }}">Edit</a> |
                        <a href="{{ route('results.destroy', $result->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $result->id }}').submit();">Delete</a>
                        <form id="delete-form-{{ $result->id }}" action="{{ route('results.destroy', $result->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $results->appends(request()->query())->links() }}
    </div>

    <div id="result-form" style="display:none;">
        <h2 id="form-title">Add New Result</h2>
        <form id="result-form-action" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST" id="method">
            <input type="hidden" name="result_id" id="result-id">

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

            <label for="lecturer_id">Lecturer:</label>
            <select name="lecturer_id" id="lecturer_id" required>
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}">{{ $lecturer->first_name }}</option>
                @endforeach
            </select><br><br>

            <label for="score">Score:</label>
            <input type="number" name="score" id="score" min="0" max="100" required><br><br>

            <button type="submit">Save</button>
            <button type="button" id="cancel-form-btn">Cancel</button>
        </form>
    </div>

    <script>
        document.getElementById('add-result-form-btn').addEventListener('click', function() {
            document.getElementById('result-form').style.display = 'block';
            document.getElementById('form-title').innerText = 'Add New Result';
            document.getElementById('result-form-action').action = '{{ route('results.store') }}';
            document.getElementById('method').value = 'POST';
            document.getElementById('result-id').value = '';
            document.getElementById('student_id').value = '';
            document.getElementById('course_id').value = '';
            document.getElementById('lecturer_id').value = '';
            document.getElementById('score').value = '';
        });

        document.getElementById('cancel-form-btn').addEventListener('click', function() {
            document.getElementById('result-form').style.display = 'none';
        });

        document.querySelectorAll('.edit-result-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const resultId = button.getAttribute('data-id');
                fetch(`/results/${resultId}/edit`)
                    .then(response => response.json())
                    .then(result => {
                        document.getElementById('result-form').style.display = 'block';
                        document.getElementById('form-title').innerText = 'Edit Result';
                        document.getElementById('result-form-action').action = `/results/${resultId}`;
                        document.getElementById('method').value = 'PUT';
                        document.getElementById('result-id').value = result.id;
                        document.getElementById('student_id').value = result.student_id;
                        document.getElementById('course_id').value = result.course_id;
                        document.getElementById('lecturer_id').value = result.lecturer_id;
                        document.getElementById('score').value = result.score;
                    });
            });
        });

        function updateItemsPerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>