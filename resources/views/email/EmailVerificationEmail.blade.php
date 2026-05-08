<html lang="en"><head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .wrapper {
            margin: 40px 0;
        }
        .container {
            max-width: 800px;
            background-color: #ffff;
            padding: 0;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.05);
        }
        .p-4.bg-dark.text-white.text-center img {
            width: 200px;
        }
        .mt-4.heading h5 {
            font-weight: 600;
        }

        .mt-4.heading {
            padding: 0 25px;
            padding-bottom: 25px;
        }

        hr {
            color: #9e9e9e;
            border-top: 1.7px solid #9e9e9e;
        }
        .container.mt-3 {
    	box-shadow: none;
    	padding: 0 25px;
        }
        a {
        	color: #515457;
        	text-decoration: none;
        }
        a:hover {
        	color: #515457;
        }
        .btn-btnverify {
        	width: 150px;
        	background: orange;
        	color: #fff;
        	font-weight: 600;
        	padding: 10px 10px;
        	text-align: center;
        	border-radius: 2px;
        }
        .footer-logo img {
        	width: 170px;
        }
        .p-1 {
        	padding-left: 20px !important;
        }
        .mt-4.heading h2 {
        	font-size: 40px;
        	font-weight: 800;
        	margin-bottom: 20px;
        }
        .mt-4.heading p {
        	margin-bottom: 30px;
        	font-weight: 600;
        }
    </style>
</head>    
    <body>
        <div class="wrapper">
            <div class="container">
                <div class="mt-4 heading">
                    <h1>Your Verification Mail</h1>
                    <p>Please verify your email with below link: </p>
                    <a class="btn-btnverify" href="{{ route('user.verify', $mailData['token']) }}">Verify Email</a>
                </div>
            </div>
            <div class="container mt-3">
                <div class="footer-logo">
                    <img src="https://welcomepost.in/assets/website/images/logo.png" style="height: 50px;">
                </div>
                <div class="footer-copyright">
                    © {{date('Y')}} WELCOME POST
                </div>
            </div>
        </div>
    </body>
</html>
