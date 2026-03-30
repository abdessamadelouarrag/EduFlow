<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
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
            max-width: 460px;
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

        input,
        select {
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
        <h1>Signup</h1>
        <p>Create a new account.</p>

        <form>
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password">

            <label for="role">Role</label>
            <select id="role" name="role">
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>

            <button type="submit">Signup</button>
            <a href="/" class="back-link">Back Home</a>
        </form>
    </div>
</body>
</html>
