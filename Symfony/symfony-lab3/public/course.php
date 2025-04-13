<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .form-container, .courses-list {
            margin: 20px;
        }
    </style>
    <script>
        let currentCourseId = null;

        async function fetchCourses() {
            const response = await fetch('/course/');
            const courses = await response.json();
            const coursesList = document.getElementById('courses-list');
            coursesList.innerHTML = '';

            courses.forEach(course => {
                const li = document.createElement('li');
                li.innerHTML = `
                    ID: ${course.id}, Title: ${course.title}, Credits: ${course.credits} 
                    <button onclick="editCourse(${course.id})">Edit</button>
                    <button onclick="deleteCourse(${course.id})">Delete</button>
                `;
                coursesList.appendChild(li);
            });
        }

        async function deleteCourse(id) {
            const response = await fetch(`/course/${id}`, {
                method: 'DELETE'
            });
            if (response.ok) {
                fetchCourses();
            }
        }

        function editCourse(id) {
            currentCourseId = id;
            fetch(`/course/${id}`)
                .then(response => response.json())
                .then(course => {
                    document.getElementById('title').value = course.title;
                    document.getElementById('credits').value = course.credits;
                    document.getElementById('submit-btn').innerText = 'Update Course';
                });
        }

        async function createOrUpdateCourse(event) {
            event.preventDefault();
            const title = document.getElementById('title').value;
            const credits = document.getElementById('credits').value;

            let response;
            if (currentCourseId) {
                response = await fetch(`/course/${currentCourseId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: title,
                        credits: parseInt(credits)
                    })
                });
                currentCourseId = null; // reset for new entries
                document.getElementById('submit-btn').innerText = 'Create Course';
            } else {
                response = await fetch('/course/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: title,
                        credits: parseInt(credits)
                    })
                });
            }

            if (response.ok) {
                fetchCourses();
                document.getElementById('title').value = '';
                document.getElementById('credits').value = '';
            }
        }

        window.onload = fetchCourses;
    </script>
</head>
<body>
    <h1>Course Management</h1>

    <!-- Form for creating or updating a course -->
    <div class="form-container">
        <h2 id="form-title">Create New Course</h2>
        <form onsubmit="createOrUpdateCourse(event)">
            <label for="title">Title:</label>
            <input type="text" id="title" required><br><br>
            <label for="credits">Credits:</label>
            <input type="number" id="credits" required><br><br>
            <button type="submit" id="submit-btn">Create Course</button>
        </form>
    </div>

    <!-- List of courses -->
    <div class="courses-list">
        <h2>All Courses</h2>
        <ul id="courses-list"></ul>
    </div>
</body>
</html>
