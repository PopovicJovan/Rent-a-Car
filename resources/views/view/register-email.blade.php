<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Our Site</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4CAF50;
        }
        p {
            line-height: 1.5;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Thank you for registering on our site using your email address: <strong>{{ $user->email }}</strong>.</p>
    <p>We are excited to have you as part of our community!</p>
    <p>If you have any questions, feel free to reach out to us.</p>

    <div class="footer">
        <p>Best regards,<br>Rent a car company</p>
    </div>
</div>
</body>
</html>
