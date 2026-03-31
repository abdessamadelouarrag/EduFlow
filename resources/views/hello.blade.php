<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
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
        .card button {
            text-decoration: none;
            border: none;
            background: #2563eb;
            color: white;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .top a.secondary,
        .card button.secondary {
            background: #e5e7eb;
            color: #111827;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }

        input {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
        }

        .courses {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 18px;
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .card p {
            color: #4b5563;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        #message {
            margin-bottom: 16px;
            color: #1d4ed8;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="top">
        <a href="/">Home</a>
        <a href="/dashboard" class="secondary">Dashboard</a>
        <button id="logoutBtn" class="secondary" type="button">Logout</button>
    </div>

    <h1>Courses</h1>
    <p id="message"></p>

    <div class="filters">
        <input type="text" id="search" placeholder="Search by title">
    </div>

    <div id="courses" class="courses"></div>

    <script src="/app.js"></script>
    <script>
        if (!EduFlow.requireAuth()) {
            throw new Error('Authentication required');
        }

        const coursesBox = document.getElementById('courses');
        const messageBox = document.getElementById('message');

        function showMessage(text) {
            messageBox.textContent = text;
        }

        async function loadCourses() {
            coursesBox.innerHTML = 'Loading...';

            const search = document.getElementById('search').value;
            const params = new URLSearchParams();

            if (search) {
                params.append('search', search);
            }

            try {
                const data = await EduFlow.api('/api/courses?' + params.toString());
                const courses = data.data || [];
                const user = EduFlow.getUser();

                if (courses.length === 0) {
                    coursesBox.innerHTML = 'No courses found.';
                    return;
                }

                coursesBox.innerHTML = '';

                courses.forEach(function (course) {
                    const card = document.createElement('div');
                    card.className = 'card';

                    let actions = `
                        <div class="actions">
                            <button type="button" onclick="viewCourse(${course.id})">View</button>
                        </div>
                    `;

                    if (user && user.role === 'student') {
                        actions = `
                            <div class="actions">
                                <button type="button" onclick="viewCourse(${course.id})">View</button>
                                <button type="button" onclick="addWishlist(${course.id})">Wishlist</button>
                                <button type="button" class="secondary" onclick="enrollCourse(${course.id})">Enroll</button>
                            </div>
                        `;
                    }

                    card.innerHTML = `
                        <h3>${course.title}</h3>
                        <p>${course.description}</p>
                        <p><strong>Domain:</strong> ${course.domain}</p>
                        <p><strong>Price:</strong> ${course.price} MAD</p>
                        ${actions}
                    `;

                    coursesBox.appendChild(card);
                });
            } catch (error) {
                coursesBox.innerHTML = 'Error loading courses.';
            }
        }

        async function viewCourse(id) {
            try {
                const data = await EduFlow.api('/api/courses/' + id);
                const course = data.course;
                showMessage('Course: ' + course.title + ' | Domain: ' + course.domain + ' | Price: ' + course.price + ' MAD');
            } catch (error) {
                showMessage('Cannot load course.');
            }
        }

        async function addWishlist(id) {
            try {
                const data = await EduFlow.api('/api/wishlist/' + id, {
                    method: 'POST'
                });
                showMessage(data.message);
            } catch (error) {
                showMessage(error.message || 'Wishlist error');
            }
        }

        async function enrollCourse(id) {
            const paymentMethod = prompt('Enter payment method');

            if (!paymentMethod) {
                return;
            }

            try {
                const data = await EduFlow.api('/api/courses/' + id + '/enroll', {
                    method: 'POST',
                    body: JSON.stringify({
                        payment_method: paymentMethod
                    })
                });
                showMessage(data.message);
            } catch (error) {
                showMessage(error.message || 'Enrollment error');
            }
        }

        document.getElementById('search').addEventListener('input', loadCourses);

        document.getElementById('logoutBtn').addEventListener('click', async function () {
            try {
                await EduFlow.api('/api/logout', { method: 'POST' });
            } catch (error) {
            }

            EduFlow.clearAuth();
            window.location.href = '/';
        });

        loadCourses();
    </script>
</body>
</html>
