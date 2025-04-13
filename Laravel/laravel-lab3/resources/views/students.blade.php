<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Management</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        .success-msg { color: green; }
        .filter-container { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .filter-row { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 10px; }
        .filter-group { flex: 1; min-width: 200px; }
        .pagination-container { display: flex; justify-content: space-between; margin: 20px 0; }
        .per-page-selector { display: flex; align-items: center; gap: 10px; }
        #student-form { display: none; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Students Management</h1>

    @if(session('success'))
        <p class="success-msg">{{ session('success') }}</p>
    @endif

    <div class="filter-container">
        <h3>Filter Students</h3>
        <form method="GET" action="{{ route('students.index') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="id">ID:</label>
                    <input type="number" name="id" id="id" value="{{ request('id') }}" placeholder="Filter by ID">
                </div>
                
                <div class="filter-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" id="first_name" value="{{ request('first_name') }}" placeholder="Filter by first name">
                </div>
                
                <div class="filter-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" value="{{ request('last_name') }}" placeholder="Filter by last name">
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email" value="{{ request('email') }}" placeholder="Filter by email">
                </div>
                
                <div class="filter-group">
                    <label for="date_of_birth">Date of Birth:</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ request('date_of_birth') }}">
                </div>
                
                <div class="filter-group">
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" id="phone" value="{{ request('phone') }}" placeholder="Filter by phone">
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <button type="submit">Apply Filters</button>
                    <a href="{{ route('students.index') }}" style="margin-left: 10px;">Reset Filters</a>
                </div>
            </div>
        </form>
    </div>

    <div class="pagination-container">
        <div>
            Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} entries
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

    <h2>All Students</h2>
    <button id="add-student-form-btn">Add Student</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Date of Birth</th>
                <th>Phone</th>
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
                    <td>{{ $student->date_of_birth }}</td>
                    <td>{{ $student->phone }}</td>
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

    <div style="margin-top: 20px;">
        {{ $students->appends(request()->query())->links() }}
    </div>

    <div id="student-form" style="display:none;">
        <h2 id="form-title">Add New Student</h2>
        <form id="student-form-action" method="POST">
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

        document.querySelectorAll('.edit-student-btn').forEach(function(button) {
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

        function updateItemsPerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>