<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Management</title>
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
        button {
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Result Management</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <h2>All Results</h2>
    <a href="#" id="add-result-form-btn">Add Result</a>

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

    <div id="result-form" style="display:none;">
        <h2 id="form-title">Add New Result</h2>
        <form id="result-form-action" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST" id="method">
            <input type="hidden" name="result_id" id="result-id">

            <label for="student_id">Student:</label>
            <select name="student_id" id="student_id" required>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" id="student-{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                @endforeach
            </select><br><br>

            <label for="course_id">Course:</label>
            <select name="course_id" id="course_id" required>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" id="course-{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select><br><br>

            <label for="lecturer_id">Lecturer:</label>
            <select name="lecturer_id" id="lecturer_id" required>
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}" id="lecturer-{{ $lecturer->id }}">{{ $lecturer->first_name }}</option>
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


const editButtons = document.querySelectorAll('.edit-result-btn');
editButtons.forEach(function(button) {
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
            })
            .catch(error => console.error('Error fetching result data:', error));
    });
});

    </script>
</body>
</html>
