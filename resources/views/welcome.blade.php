<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f7fb;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 18px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        .small-text {
            color: #2563eb;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        h1 {
            font-size: 32px;
            color: #1f2937;
            margin-bottom: 12px;
        }

        p {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.2s ease;
        }

        .btn-login {
            background: #2563eb;
            color: #ffffff;
        }

        .btn-signup {
            background: #e5edff;
            color: #1d4ed8;
        }

        .btn:hover {
            transform: translateY(-2px);
            opacity: 0.95;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="small-text">EduFlow</div>
        <h1>Welcome Home</h1>
        <p>
            This is a simple home page with two buttons for login and signup.
        </p>

        <div class="buttons">
            <a href="/login" class="btn btn-login">Login</a>
            <a href="/signup" class="btn btn-signup">Signup</a>
        </div>
    </div>
</body>
</html>
