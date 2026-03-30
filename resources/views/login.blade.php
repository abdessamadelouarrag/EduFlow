<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            padding: 36px 30px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        h1 {
            text-align: center;
            font-size: 30px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            color: #6b7280;
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 18px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 15px;
        }

        button,
        .back-link {
            width: 100%;
            display: inline-block;
            text-align: center;
            text-decoration: none;
            padding: 13px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        button {
            border: none;
            background: #2563eb;
            color: #ffffff;
            cursor: pointer;
            margin-bottom: 12px;
        }

        .back-link {
            background: #e5edff;
            color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Login</h1>
        <p>Enter your email and password.</p>

        <form>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password">

            <button type="submit">Login</button>
            <a href="/" class="back-link">Back Home</a>
        </form>
    </div>
</body>
</html>
