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
        #edit-enrollment-form { display: none; }
    </style>
</head>
<body>
    <h1>Enrollments Management</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <h2>All Enrollments</h2>

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

    <!-- Add New Enrollment Form -->
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

    <!-- Edit Enrollment Form -->
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
    </script>
</body>
</html>
