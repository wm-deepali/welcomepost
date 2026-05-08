<!DOCTYPE html>
<html>
<head>
     <style>
        .footer-logo img {
        	width: 170px;
        }
        .container.mt-3 {
        	box-shadow: none;
        	padding: 0 25px;
        }
    </style>
    <title>OTP Code</title>
</head>
<body>
    <h5>Hello, {{ $userName }}</h5>
    <p>Your OTP code is: <strong>{{ $otp }}</strong></p>
    <p>Please use this code to complete your email change request.</p>
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