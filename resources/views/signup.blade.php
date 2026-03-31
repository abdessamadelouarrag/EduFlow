<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 340px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        input,
        select,
        button,
        a {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input,
        select {
            border: 1px solid #ccc;
        }

        button {
            border: none;
            background: #2563eb;
            color: white;
            cursor: pointer;
        }

        a {
            display: block;
            text-align: center;
            text-decoration: none;
            background: #e5e7eb;
            color: black;
        }

        #message {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
            color: #b91c1c;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>Signup</h1>

        <form id="signupForm">
            <input type="text" id="name" placeholder="Full Name" required>
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Password" required>
            <select id="role">
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
            <button type="submit">Signup</button>
        </form>

        <a href="/">Back Home</a>
        <p id="message"></p>
    </div>

    <script src="/app.js"></script>
    <script>
        document.getElementById('signupForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const message = document.getElementById('message');
            message.textContent = '';

            try {
                const data = await EduFlow.api('/api/signup', {
                    method: 'POST',
                    body: JSON.stringify({
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value,
                        role: document.getElementById('role').value
                    })
                });

                EduFlow.setAuth(data);
                window.location.href = '/dashboard';
            } catch (error) {
                message.textContent = error.message || 'Signup error';
            }
        });
    </script>
</body>
</html>
