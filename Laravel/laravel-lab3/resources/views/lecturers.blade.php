<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Management</title>
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
        #lecturer-form { display: none; margin-top: 20px; }
        button { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Lecturer Management</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <div class="filter-container">
        <h3>Filter Lecturers</h3>
        <form method="GET" action="{{ route('lecturers.index') }}">
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
                    <label for="department">Department:</label>
                    <input type="text" name="department" id="department" value="{{ request('department') }}" placeholder="Filter by department">
                </div>
                
                <div class="filter-group">
                    <button type="submit">Apply Filters</button>
                    <a href="{{ route('lecturers.index') }}" style="margin-left: 10px;">Reset Filters</a>
                </div>
            </div>
        </form>
    </div>

    <div class="pagination-container">
        <div>
            Showing {{ $lecturers->firstItem() }} to {{ $lecturers->lastItem() }} of {{ $lecturers->total() }} entries
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

    <h2>All Lecturers</h2>
    <button id="add-lecturer-form-btn">Add Lecturer</button>

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

    <div style="margin-top: 20px;">
        {{ $lecturers->appends(request()->query())->links() }}
    </div>

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
        document.getElementById('add-lecturer-form-btn').addEventListener('click', function() {
            document.getElementById('lecturer-form').style.display = 'block';
            document.getElementById('form-title').innerText = 'Add New Lecturer';
            document.getElementById('lecturer-form-action').action = '{{ route('lecturers.store') }}';
            document.getElementById('method').value = 'POST';
            document.getElementById('lecturer-id').value = '';
            document.getElementById('first_name').value = '';
            document.getElementById('last_name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('department').value = '';
        });

        document.getElementById('cancel-form-btn').addEventListener('click', function() {
            document.getElementById('lecturer-form').style.display = 'none';
        });

        document.querySelectorAll('.edit-lecturer-btn').forEach(function(button) {
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

        function updateItemsPerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>