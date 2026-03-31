<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduFlow Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .box {
            background: white;
            border-radius: 14px;
            padding: 30px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
        }

        p {
            color: #555;
            margin-bottom: 24px;
        }

        a {
            display: block;
            text-decoration: none;
            margin-bottom: 12px;
            padding: 12px;
            border-radius: 8px;
            background: #2563eb;
            color: white;
        }

        a.secondary {
            background: #e5e7eb;
            color: #111827;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>EduFlow</h1>
        <p>Simple front-end to consume your API.</p>

        <a href="/login">Login</a>
        <a href="/signup">Signup</a>
        <a href="/cours" class="secondary">Courses</a>
        <a href="/dashboard" class="secondary">Dashboard</a>
    </div>
</body>
</html>
