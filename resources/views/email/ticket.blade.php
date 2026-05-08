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
    <title>{{$customerName.' raised a ticket about -> '.$ticketSubject }}</title>
</head>
<body>
    <h1>Hello, {{ $customerName }}</h1>
    <p>{{ $messageContent }}</p>
    <div class="container mt-3">
        <div class="footer-logo">
            <img src="https://welcomepost.in/assets/website/images/logo.png" style="height: 50px;">
        </div>
        <div class="footer-copyright">
            © {{date('Y')}} WELCOME POST
        </div>
    </div>
</body>
</html>
