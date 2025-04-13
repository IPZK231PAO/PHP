<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Management</title>
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
        .success-msg {
            color: green;
        }
    </style>
</head>
<body>
    <h1>Students Management</h1>

    @if(session('success'))
        <p class="success-msg">{{ session('success') }}</p>
    @endif

    <h2>All Students</h2>
    <a href="#" id="add-student-form-btn">Add Student</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->first_name }}</td>
                    <td>{{ $student->last_name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>
                        <a href="#" class="edit-student-btn" data-id="{{ $student->id }}">Edit</a> |
                        <a href="{{ route('students.destroy', $student->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $student->id }}').submit();">Delete</a>
                        <form id="delete-form-{{ $student->id }}" action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div id="student-form" style="display:none;">
        <h2 id="form-title">Add New Student</h2>
        <form id="student-form-action" method="POST" action="{{ route('students.store') }}">
            @csrf
            <input type="hidden" name="_method" value="POST" id="method">
            <input type="hidden" name="student_id" id="student-id">

            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" required><br><br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br><br>

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" name="date_of_birth" id="date_of_birth" required><br><br>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" required><br><br>

            <button type="submit">Save</button>
            <button type="button" id="cancel-form-btn">Cancel</button>
        </form>
    </div>

    <script>
    
        document.getElementById('add-student-form-btn').addEventListener('click', function() {
            document.getElementById('student-form').style.display = 'block';
            document.getElementById('form-title').innerText = 'Add New Student';
            document.getElementById('student-form-action').action = '{{ route('students.store') }}';
            document.getElementById('method').value = 'POST';
            document.getElementById('student-id').value = '';
            document.getElementById('first_name').value = '';
            document.getElementById('last_name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('date_of_birth').value = '';
            document.getElementById('phone').value = '';
        });


        document.getElementById('cancel-form-btn').addEventListener('click', function() {
            document.getElementById('student-form').style.display = 'none';
        });


        const editButtons = document.querySelectorAll('.edit-student-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const studentId = button.getAttribute('data-id');
                fetch(`/students/${studentId}`)
                    .then(response => response.json())
                    .then(student => {
                        document.getElementById('student-form').style.display = 'block';
                        document.getElementById('form-title').innerText = 'Edit Student';
                        document.getElementById('student-form-action').action = `/students/${studentId}`;
                        document.getElementById('method').value = 'PUT';
                        document.getElementById('student-id').value = student.id;
                        document.getElementById('first_name').value = student.first_name;
                        document.getElementById('last_name').value = student.last_name;
                        document.getElementById('email').value = student.email;
                        document.getElementById('date_of_birth').value = student.date_of_birth;
                        document.getElementById('phone').value = student.phone;
                    });
            });
        });
    </script>
</body>
</html>
