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

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            });

            const data = await response.json();

            if (response.ok) {
                document.getElementById('message').textContent = data.message;
            } else {
                document.getElementById('message').textContent = data.message || 'Login error';
            }
        });
    </script>
</body>
</html>
