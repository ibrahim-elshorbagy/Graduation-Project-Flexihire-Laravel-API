<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body,
        table,
        td,
        a {
            text-size-adjust: 100%;
            font-family: Arial, sans-serif;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: #f4f4f7;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f7;
        }

        .content {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 26px;
            color: #333333;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #555555;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        a {
            color: #1a82e2;
            text-decoration: none;
        }

        .button {
            display: inline-block;
            padding: 14px 28px;
            font-size: 16px;
            color: #ffffff;
            background-color: #1a82e2;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
            font-weight: bold;
        }

        .footer {
            font-size: 14px;
            color: #999999;
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e4e4e4;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="content">
            <h1>Reset Your Password</h1>
            <p><strong>Hello, {{ $user->name }}</strong>,</p>
            <p>We have received a request to reset your password.</p>
            <p>If you did not request a password reset, please ignore this email.</p>
            <p>Your verification code is: <strong>{{ $token }}</strong></p>
        </div>
        <div class="footer">
            <p>Thank you for using our service!</p>
            <p>If you have any questions, please contact our support team.</p>
        </div>
    </div>
</body>

</html>
