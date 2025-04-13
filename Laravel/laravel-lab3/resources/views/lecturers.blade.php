<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Management</title>
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
    <h1>Lecturer Management</h1>

    <!-- Success Message -->
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <!-- Display Lecturer Table -->
    <h2>All Lecturers</h2>
    <a href="#" id="add-lecturer-form-btn">Add Lecturer</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lecturers as $lecturer)
                <tr>
                    <td>{{ $lecturer->id }}</td>
                    <td>{{ $lecturer->first_name }}</td>
                    <td>{{ $lecturer->last_name }}</td>
                    <td>{{ $lecturer->email }}</td>
                    <td>{{ $lecturer->department }}</td>
                    <td>
                        <a href="#" class="edit-lecturer-btn" data-id="{{ $lecturer->id }}">Edit</a> |
                        <a href="{{ route('lecturers.destroy', $lecturer->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $lecturer->id }}').submit();">Delete</a>
                        <form id="delete-form-{{ $lecturer->id }}" action="{{ route('lecturers.destroy', $lecturer->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add/Edit Lecturer Form -->
    <div id="lecturer-form" style="display:none;">
        <h2 id="form-title">Add New Lecturer</h2>
        <form id="lecturer-form-action" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST" id="method">
            <input type="hidden" name="lecturer_id" id="lecturer-id">

            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" required><br><br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br><br>

            <label for="department">Department:</label>
            <input type="text" name="department" id="department" required><br><br>

            <button type="submit">Save</button>
            <button type="button" id="cancel-form-btn">Cancel</button>
        </form>
    </div>

    <script>
        // Show the Add/Edit Lecturer Form
        document.getElementById('add-lecturer-form-btn').addEventListener('click', function() {
     document.getElementById('lecturer-form').style.display = 'block';
     document.getElementById('form-title').innerText = 'Add New Lecturer';
     document.getElementById('lecturer-form-action').action = '{{ route('lecturers.store') }}';  // Важливо, щоб тут був правильний маршрут
     document.getElementById('method').value = 'POST';  // Встановлює метод POST
     document.getElementById('lecturer-id').value = '';  // Очищає старі дані
     document.getElementById('first_name').value = '';
     document.getElementById('last_name').value = '';
     document.getElementById('email').value = '';
     document.getElementById('department').value = '';
});

        // Cancel the Add/Edit Form
        document.getElementById('cancel-form-btn').addEventListener('click', function() {
            document.getElementById('lecturer-form').style.display = 'none';
        });

        // Show the Edit Form
        const editButtons = document.querySelectorAll('.edit-lecturer-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const lecturerId = button.getAttribute('data-id');
                fetch(`/lecturers/${lecturerId}`)
                    .then(response => response.json())
                    .then(lecturer => {
                        document.getElementById('lecturer-form').style.display = 'block';
                        document.getElementById('form-title').innerText = 'Edit Lecturer';
                        document.getElementById('lecturer-form-action').action = `/lecturers/${lecturerId}`;
                        document.getElementById('method').value = 'PUT';
                        document.getElementById('lecturer-id').value = lecturer.id;
                        document.getElementById('first_name').value = lecturer.first_name;
                        document.getElementById('last_name').value = lecturer.last_name;
                        document.getElementById('email').value = lecturer.email;
                        document.getElementById('department').value = lecturer.department;
                    });
            });
        });
    </script>
</body>
</html>
