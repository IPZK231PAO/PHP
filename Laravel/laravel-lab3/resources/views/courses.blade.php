<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Management</title>
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
        .filter-container {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        .pagination-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .per-page-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <h1>Courses Management</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <!-- Filter Form -->
    <div class="filter-container">
        <h3>Filter Courses</h3>
        <form method="GET" action="{{ route('courses.index') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="id">ID:</label>
                    <input type="number" name="id" id="id" value="{{ request('id') }}" placeholder="Filter by ID">
                </div>
                
                <div class="filter-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" value="{{ request('title') }}" placeholder="Filter by title">
                </div>
                
                <div class="filter-group">
                    <label for="lecturer_id">Lecturer:</label>
                    <select name="lecturer_id" id="lecturer_id">
                        <option value="">All Lecturers</option>
                        @foreach($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}" {{ request('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                                {{ $lecturer->first_name }} {{ $lecturer->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label for="credits">Credits:</label>
                    <input type="number" name="credits" id="credits" value="{{ request('credits') }}" placeholder="Filter by credits">
                </div>
                
                <div class="filter-group">
                    <label for="start_date">Start Date From:</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}">
                </div>
                
                <div class="filter-group">
                    <label for="end_date">End Date To:</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}">
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <button type="submit">Apply Filters</button>
                    <a href="{{ route('courses.index') }}" style="margin-left: 10px;">Reset Filters</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Create New Course Form -->
    <div class="form-container">
        <h3>Add New Course</h3>
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf
            <label for="title">Course Title:</label>
            <input type="text" name="title" required><br><br>

            <label for="lecturer_id">Lecturer:</label>
            <select name="lecturer_id" required>
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}">{{ $lecturer->first_name }} {{ $lecturer->last_name }}</option>
                @endforeach
            </select><br><br>

            <label for="credits">Credits:</label>
            <input type="number" name="credits" required><br><br>

            <label for="description">Description:</label>
            <textarea name="description" required></textarea><br><br>

            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required><br><br>

            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required><br><br>

            <button type="submit">Create Course</button>
        </form>
    </div>

    <hr>

    <!-- Pagination and Items Per Page -->
    <div class="pagination-container">
        <div>
            Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} entries
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

    <!-- Courses Table -->
    <h2>All Courses</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Lecturer</th>
                <th>Credits</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->title }}</td>
                    <td>
                        @if($course->lecturer)
                            {{ $course->lecturer->first_name }} {{ $course->lecturer->last_name }}
                        @else
                            <em>No lecturer assigned</em>
                        @endif
                    </td>
                    <td>{{ $course->credits }}</td>
                    <td>{{ $course->start_date }}</td>
                    <td>{{ $course->end_date }}</td>
                    <td>
                        <a href="#" class="edit-course-btn"
                            data-id="{{ $course->id }}"
                            data-title="{{ $course->title }}"
                            data-lecturer-id="{{ $course->lecturer_id }}"
                            data-credits="{{ $course->credits }}"
                            data-description="{{ $course->description }}"
                            data-start-date="{{ $course->start_date }}"
                            data-end-date="{{ $course->end_date }}">
                            Edit
                        </a>

                        <a href="{{ route('courses.destroy', $course->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $course->id }}').submit();">Delete</a>

                        <form id="delete-form-{{ $course->id }}" action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div style="margin-top: 20px;">
        {{ $courses->appends(request()->query())->links() }}
    </div>

    <!-- Edit Course Form -->
    <div id="edit-course-form" style="display: none; margin-top: 30px;">
        <h3>Edit Course</h3>
        <form id="edit-course-action" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="course_id" id="course-id">

            <label for="edit-title">Course Title:</label>
            <input type="text" name="title" id="edit-title" required><br><br>

            <label for="edit-lecturer_id">Lecturer:</label>
            <select name="lecturer_id" id="edit-lecturer_id" required>
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}">{{ $lecturer->first_name }} {{ $lecturer->last_name }}</option>
                @endforeach
            </select><br><br>

            <label for="edit-credits">Credits:</label>
            <input type="number" name="credits" id="edit-credits" required><br><br>

            <label for="edit-description">Description:</label>
            <textarea name="description" id="edit-description" required></textarea><br><br>

            <label for="edit-start_date">Start Date:</label>
            <input type="date" name="start_date" id="edit-start_date" required><br><br>

            <label for="edit-end_date">End Date:</label>
            <input type="date" name="end_date" id="edit-end_date" required><br><br>

            <button type="submit">Update Course</button>
            <button type="button" id="cancel-edit-btn">Cancel</button>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        document.querySelectorAll('.edit-course-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                const courseId = this.dataset.id;
                const title = this.dataset.title;
                const lecturerId = this.dataset.lecturerId;
                const credits = this.dataset.credits;
                const description = this.dataset.description;
                const startDate = this.dataset.startDate;
                const endDate = this.dataset.endDate;

                document.getElementById('edit-course-form').style.display = 'block';
                document.getElementById('edit-course-action').action = `/courses/${courseId}`;
                document.getElementById('course-id').value = courseId;
                document.getElementById('edit-title').value = title;
                document.getElementById('edit-lecturer_id').value = lecturerId;
                document.getElementById('edit-credits').value = credits;
                document.getElementById('edit-description').value = description;
                document.getElementById('edit-start_date').value = startDate;
                document.getElementById('edit-end_date').value = endDate;
            });
        });

        document.getElementById('cancel-edit-btn').addEventListener('click', function() {
            document.getElementById('edit-course-form').style.display = 'none';
        });

        function updateItemsPerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>