<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            width: 320px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        input,
        button,
        a {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input {
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
        <h1>Login</h1>

        <form id="loginForm">
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <a href="/">Back Home</a>
        <p id="message"></p>
    </div>

    <script src="/app.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const message = document.getElementById('message');
            message.textContent = '';

            try {
                const data = await EduFlow.api('/api/login', {
                    method: 'POST',
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value
                    })
                });

                EduFlow.setAuth(data);
                window.location.href = '/dashboard';
            } catch (error) {
                message.textContent = error.message || 'Login error';
            }
        });
    </script>
</body>
</html>
