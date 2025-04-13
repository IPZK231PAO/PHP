<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        .links {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
        }
        .links a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }
        .links a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h1>Welcome to the Management Dashboard</h1>

<div class="links">
    <a href="{{ route('students.index') }}">Manage Students</a>
    <a href="{{ route('courses.index') }}">Manage Courses</a>
    <a href="{{ route('enrollments.index') }}">Manage Enrollments</a>
    <a href="{{ route('grades.index') }}">Manage Grades</a>
    <a href="{{ route('results.index') }}">Manage Results</a>
    <a href="{{ route('lecturers.index') }}">Manage Lecturers</a>
</div>

</body>
</html>
