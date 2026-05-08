<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
     <style>
        .footer-logo img {
        	width: 170px;
        }
        .container.mt-3 {
        	box-shadow: none;
        	padding: 0 25px;
        }
    </style>
    <title>Ad-Report Notification</title>
</head>
<body>
    <h2>Ad-Report Notification</h2>
    <p>Your ad has been reported with the following reason:</p>
    <p><strong>Reason:</strong> {{ $blockReason }}</p>
    <div class="container mt-3">
        <div class="footer-logo">
            <img src="https://welcomepost.in/assets/website/images/logo.png" style="height: 50px;">
        </div>
        <div class="footer-copyright">
            © {{date('Y')}} WELCOMEPOST PVT. LTD.
        </div>
    </div>
</body>
</html>
