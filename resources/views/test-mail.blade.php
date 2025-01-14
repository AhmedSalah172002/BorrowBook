<!DOCTYPE html>
<html>
<head>
    <title>Book Borrowed Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            color: #4CAF50;
            font-size: 24px;
            text-align: center;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            color: #555;
        }

        .book-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .book-details p {
            margin: 8px 0;
        }

        .highlight {
            font-weight: bold;
            color: #4CAF50;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }

        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="email-container">
    <h1>Hello, {{ $username }}!</h1>
    <p>You have successfully borrowed the following book:</p>

    <div class="book-details">
        <p><span class="highlight">Book Name:</span> {{ $bookName }}</p>
        <p><span class="highlight">Book Type:</span> {{ $bookType }}</p>
        <p><span class="highlight">ISBN:</span> {{ $isbn }}</p>
    </div>

    <p>Thank you for using our library system!</p>

    <div class="footer">
        <p>For more details, visit our <a href="#">library website</a>.</p>
    </div>
</div>
</body>
</html>
