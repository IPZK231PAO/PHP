<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link { transition: all 0.3s ease; }
        .nav-link:hover { transform: translateY(-2px); }
        .welcome-message { margin: 2rem 0; }
        .route-list { column-count: 2; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">University MS</a>
            
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile') }}">Profile</a>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    Admin Tools
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('students.create') }}">Add Student</a></li>
                                    <li><a class="dropdown-item" href="{{ route('lecturers.index') }}">Manage Lecturers</a></li>
                                    <li><a class="dropdown-item" href="{{ route('courses.create') }}">Add Course</a></li>
                                    <li><a class="dropdown-item" href="{{ route('enrollments.create') }}">Create Enrollment</a></li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @auth
            <div class="welcome-message">
                <h2>Welcome, {{ auth()->user()->name }}!</h2>
                <p class="text-muted">Role: {{ ucfirst(auth()->user()->role) }}</p>
            </div>
        @endif

        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Quick Links</div>
                    <div class="card-body">
                        <ul class="list-group route-list">
                            <li class="list-group-item">
                                <a href="{{ route('students.index') }}">Students</a>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('courses.index') }}">Courses</a>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('enrollments.index') }}">Enrollments</a>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('grades.index') }}">Grades</a>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('results.index') }}">Results</a>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('results.by_course', ['course' => 1]) }}">Results by Course</a>
                            </li>
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <li class="list-group-item">
                                    <a href="{{ route('lecturers.index') }}">Lecturers</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Основний контент залишається без змін -->
                @if(isset($students) && $students->count() > 0)
                    <div class="card">
                        <div class="card-header">Latest Students</div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        @auth
                                            @if(auth()->user()->role !== 'client')
                                                <th>Actions</th>
                                            @endif
                                        @endauth
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                            @auth
                                                @if(auth()->user()->role !== 'client')
                                                    <td>
                                                        <a href="{{ route('students.edit', $student->id) }}" 
                                                           class="btn btn-sm btn-outline-primary">Edit</a>
                                                        @if(auth()->user()->isAdmin())
                                                            <form action="{{ route('students.destroy', $student->id) }}" 
                                                                  method="POST" style="display:inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-outline-danger"
                                                                        onclick="return confirm('Are you sure?')">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                @endif
                                            @endauth
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        No students found. 
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('students.create') }}">Add first student</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>