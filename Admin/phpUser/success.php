<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            height: 100vh;
            background-image: url('https://img.freepik.com/free-vector/background-pixel-rain-abstract_23-2148361453.jpg'); /* Change to your image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .content-wrapper {
            background-color: white; /* Light purple background */
            padding: 70px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 600px;
        }
        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #6b5b95; /* Dark purple */
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
        .checkmark::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 40px;
            border: solid white;
            border-width: 0 8px 8px 0;
            transform: rotate(45deg);
            animation: checkmark-animation 0.4s ease forwards;
        }
        @keyframes checkmark-animation {
            from {
                width: 0;
                height: 0;
            }
            to {
                width: 20px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="content-wrapper">
            <div class="checkmark mb-4"></div>
            <h2 class="text-primary">User Added Successfully!</h2> <!-- Use Bootstrap's text-primary class for purple text -->
            <p class="lead">New user has been successfully added to the database.</p>
            <a href="../index.html" class="btn btn-secondary mt-3">Return to homepage</a> <!-- Use a darker button color -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
