<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .top {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .top a,
        .top button,
        .section button {
            text-decoration: none;
            border: none;
            background: #2563eb;
            color: white;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
        }

        .muted {
            color: #6b7280;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .list {
            display: grid;
            gap: 12px;
            margin-top: 12px;
        }

        #message {
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="top">
        <a href="/">Home</a>
        <a href="/cours">Courses</a>
        <button id="logoutBtn" type="button">Logout</button>
    </div>

    <h1>Dashboard</h1>
    <p id="message"></p>

    <div class="section">
        <h2>My Profile</h2>
        <div id="profileData" class="grid"></div>
    </div>

    <div id="studentSections" style="display:none;">
        <div class="section">
            <h2>Recommendations</h2>
            <button id="loadRecommendations" type="button">Load Recommendations</button>
            <div id="recommendationsData" class="list"></div>
        </div>

        <div class="section">
            <h2>Wishlist</h2>
            <button id="loadWishlist" type="button">Load Wishlist</button>
            <div id="wishlistData" class="list"></div>
        </div>
    </div>

    <div id="teacherSections" style="display:none;">
        <div class="section">
            <h2>Create Course</h2>
            <form id="createCourseForm">
                <input type="text" id="title" placeholder="Title" required>
                <textarea id="description" placeholder="Description" required></textarea>
                <input type="number" id="price" placeholder="Price" required>
                <input type="text" id="domain" placeholder="Domain" required>
                <button type="submit">Create Course</button>
            </form>
        </div>

        <div class="section">
            <h2>Teacher Stats</h2>
            <button id="loadStats" type="button">Load Stats</button>
            <div id="statsData" class="list"></div>
        </div>

        <div class="section">
            <h2>Teacher Groups</h2>
            <button id="loadGroups" type="button">Load Groups</button>
            <div id="groupsData" class="list"></div>
        </div>

        <div class="section">
            <h2>Course Students</h2>
            <input type="number" id="courseId" placeholder="Course ID">
            <button id="loadStudents" type="button">Load Students</button>
            <div id="studentsData" class="list"></div>
        </div>
    </div>

    <script src="/app.js"></script>
    <script>
        if (!EduFlow.requireAuth()) {
            throw new Error('Authentication required');
        }

        const message = document.getElementById('message');
        const profile = document.getElementById('profileData');
        const recommendations = document.getElementById('recommendationsData');
        const wishlist = document.getElementById('wishlistData');
        const stats = document.getElementById('statsData');
        const groups = document.getElementById('groupsData');
        const students = document.getElementById('studentsData');

        function showMessage(text) {
            message.textContent = text;
        }

        const token = localStorage.getItem('token');

        async function apiFetch(url, method = 'GET', body = null) {
            const options = {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token
                }
            };

            if (body) {
                options.headers['Content-Type'] = 'application/json';
                options.body = JSON.stringify(body);
            }

            const response = await fetch(url, options);
            const data = await response.json();

            if (!response.ok) {
                throw data;
            }

            return data;
        }

        function courseCard(course) {
            return `
                <div class="card">
                    <strong>${course.title}</strong>
                    <div class="muted">Domain: ${course.domain}</div>
                    <div class="muted">Price: ${course.price} MAD</div>
                </div>
            `;
        }

        function showCourses(box, list, emptyText) {
            if (list.length === 0) {
                box.innerHTML = `<div class="muted">${emptyText}</div>`;
                return;
            }

            box.innerHTML = '';

            list.forEach(function (course) {
                box.innerHTML += courseCard(course);
            });
        }

        async function loadProfile() {
            try {
                const data = await apiFetch('/api/me');
                const user = data.user;

                localStorage.setItem('user', JSON.stringify(user));

                profile.innerHTML = `
                    <div class="card"><strong>Name</strong><div class="muted">${user.name}</div></div>
                    <div class="card"><strong>Email</strong><div class="muted">${user.email}</div></div>
                    <div class="card"><strong>Role</strong><div class="muted">${user.role}</div></div>
                    <div class="card"><strong>ID</strong><div class="muted">${user.id}</div></div>
                `;

                if (user.role === 'student') {
                    document.getElementById('studentSections').style.display = 'block';
                }

                if (user.role === 'teacher') {
                    document.getElementById('teacherSections').style.display = 'block';
                }
            } catch (error) {
                showMessage('Please login first.');
            }
        }

        async function loadRecommendations() {
            try {
                const data = await apiFetch('/api/recommendations');
                showCourses(recommendations, data.courses, 'No recommendations.');
            } catch (error) {
                showMessage('Recommendations error');
            }
        }

        async function loadWishlist() {
            try {
                const data = await apiFetch('/api/wishlist');
                showCourses(wishlist, data.wishlist, 'No wishlist data.');
            } catch (error) {
                showMessage('Wishlist error');
            }
        }

        async function createCourse(event) {
            event.preventDefault();

            try {
                const data = await apiFetch('/api/courses', 'POST', {
                    title: document.getElementById('title').value,
                    description: document.getElementById('description').value,
                    price: document.getElementById('price').value,
                    domain: document.getElementById('domain').value
                });

                showMessage(data.message);
                event.target.reset();
            } catch (error) {
                showMessage('Create course error');
            }
        }

        async function loadStats() {
            try {
                const data = await apiFetch('/api/teacher/stats');

                stats.innerHTML = `
                    <div class="grid">
                        <div class="card"><strong>Total Courses</strong><div class="muted">${data.total_courses}</div></div>
                        <div class="card"><strong>Paid Enrollments</strong><div class="muted">${data.total_paid_enrollments}</div></div>
                        <div class="card"><strong>Total Groups</strong><div class="muted">${data.total_groups}</div></div>
                    </div>
                `;

                data.courses.forEach(function (course) {
                    stats.innerHTML += `
                        <div class="card">
                            <strong>${course.title}</strong>
                            <div class="muted">Domain: ${course.domain}</div>
                            <div class="muted">Price: ${course.price} MAD</div>
                            <div class="muted">Enrollments: ${course.enrollments_count}</div>
                            <div class="muted">Groups: ${course.groups_count}</div>
                        </div>
                    `;
                });
            } catch (error) {
                showMessage('Stats error');
            }
        }

        async function loadGroups() {
            try {
                const data = await apiFetch('/api/teacher/groups');

                if (data.groups.length === 0) {
                    groups.innerHTML = '<div class="muted">No groups found.</div>';
                    return;
                }

                groups.innerHTML = '';

                data.groups.forEach(function (group) {
                    let studentList = 'No students yet.';

                    if (group.enrollments.length > 0) {
                        studentList = '';

                        group.enrollments.forEach(function (enrollment) {
                            studentList += enrollment.student.name + ' (' + enrollment.student.email + ')<br>';
                        });
                    }

                    groups.innerHTML += `
                        <div class="card">
                            <strong>${group.name}</strong>
                            <div class="muted">Course: ${group.course.title}</div>
                            <div class="muted">${studentList}</div>
                        </div>
                    `;
                });
            } catch (error) {
                showMessage('Groups error');
            }
        }

        async function loadStudents() {
            try {
                const courseId = document.getElementById('courseId').value;
                const data = await apiFetch('/api/teacher/courses/' + courseId + '/students');

                if (data.students.length === 0) {
                    students.innerHTML = '<div class="muted">No students found.</div>';
                    return;
                }

                students.innerHTML = '';

                data.students.forEach(function (item) {
                    students.innerHTML += `
                        <div class="card">
                            <strong>${item.student.name}</strong>
                            <div class="muted">Email: ${item.student.email}</div>
                            <div class="muted">Group: ${item.group ? item.group.name : 'No group'}</div>
                            <div class="muted">Payment: ${item.payment_status}</div>
                        </div>
                    `;
                });
            } catch (error) {
                showMessage('Students error');
            }
        }

        async function logout() {
            try {
                await apiFetch('/api/logout', 'POST');
            } catch (error) {
            }

            EduFlow.clearAuth();
            window.location.href = '/';
        }

        document.getElementById('loadRecommendations').addEventListener('click', loadRecommendations);
        document.getElementById('loadWishlist').addEventListener('click', loadWishlist);
        document.getElementById('createCourseForm').addEventListener('submit', createCourse);
        document.getElementById('loadStats').addEventListener('click', loadStats);
        document.getElementById('loadGroups').addEventListener('click', loadGroups);
        document.getElementById('loadStudents').addEventListener('click', loadStudents);
        document.getElementById('logoutBtn').addEventListener('click', logout);

        loadProfile();
    </script>
</body>
</html>
