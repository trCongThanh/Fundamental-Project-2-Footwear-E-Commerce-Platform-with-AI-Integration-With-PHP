<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('assets/images/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: white;
        }

        .contact-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            max-width: 800px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .contact-info h1, .contact-form h1 {
            margin-bottom: 30px;
            text-align: center;
        }

        .submit-button {
            background-color: black;
            color: white;
            border: none;
            border-radius: 3px;
            font-size: 16px;
            cursor: pointer;
            align-self: center;
        }

        .submit-button:hover {
            background-color: white;
            color: black;
        }

        .response-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.9);
            color: black;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            display: none;
            text-align: center;
        }

        .response-box button {
            background-color: black;
            color: white;
            border: none;
            border-radius: 3px;
            font-size: 16px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .response-box button:hover {
            background-color: white;
            color: black;
        }
    </style>
</head>
<body>

    <div class="container contact-container">
        <div class="contact-form">
            <h1>Get in Touch</h1>
            <form id="contact-form" method="POST" action="send_email.php">
                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="message">Message <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn submit-button btn-block">Send</button>
            </form>
        </div>
        
        <div class="contact-info">
            <br><br>
            <h1>CONTACT US</h1>
            <p>Email: shoestore@email.com</p>
            <p>Phone: +84 123 456 789</p>
            <p>Location: Happy street, Love Avenue, Haeven, Sky</p>
            <p>Business Hours: Mon - Fri: 10 am - 8 pm, Sat, Sun: Closed</p>
        </div>
    </div>

    <!-- Response box (hidden by default) -->
    <div class="response-box" id="response-box">
        <p id="response-message"></p>
        <button id="response-button">OK</button>
    </div>

    <script>
        const form = document.getElementById('contact-form');
        const responseBox = document.getElementById('response-box');
        const responseMessage = document.getElementById('response-message');
        const responseButton = document.getElementById('response-button');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(form);

            try {
                const response = await fetch('send_email.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                // Show the response box with the appropriate message
                if (result.status === 'success') {
                    responseMessage.innerHTML = "Your message has been sent successfully!";
                    responseBox.style.display = 'block';

                    responseButton.onclick = function() {
                        window.location.href = 'index.php';  // Redirect to home page after success
                    };
                } else {
                    responseMessage.innerHTML = "An error occurred, please try again.";
                    responseBox.style.display = 'block';

                    responseButton.onclick = function() {
                        responseBox.style.display = 'none';  // Hide response box and keep form
                    };
                }
            } catch (error) {
                console.error("Error:", error);
                alert("An unexpected error occurred.");
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
