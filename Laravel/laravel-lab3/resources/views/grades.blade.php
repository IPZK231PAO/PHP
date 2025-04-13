<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .form-container {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Grades Management</h1>

    <!-- Success Message -->
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <!-- Create New Grade Form -->
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

    <!-- Display Grades Table -->
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
                        <!-- Edit Button -->
                        <a href="#" class="edit-grade-btn" data-id="{{ $grade->id }}">Edit</a> |
                        
                        <!-- Delete Button -->
                        <a href="{{ route('grades.destroy', $grade->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $grade->id }}').submit();">Delete</a>
                        
                        <!-- Delete Form -->
                        <form id="delete-form-{{ $grade->id }}" action="{{ route('grades.destroy', $grade->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Edit Grade Form (hidden initially) -->
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

        // Hide Edit Form
        document.getElementById('cancel-edit-btn').addEventListener('click', function() {
            document.getElementById('edit-grade-form').style.display = 'none';
        });
    </script>
</body>
</html>
