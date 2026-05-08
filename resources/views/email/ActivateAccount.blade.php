<!DOCTYPE html>
<html lang="en">
<head>
  <title>Welcome Post</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
  	.top-section {
	background-color: #FFF !important;
	color: #4f89b7 !important;
}
.container-fluid.p-5.bg-primary.text-white.text-center.top-section h1 {
	text-align: left;
	font-weight: 700;
}
    .main-wrapper {
	max-width: 800px;
	margin: auto;
	margin-top: 20px;
    margin-bottom:30px;
    box-shadow: 0px 2px 20px rgba(0, 0, 0, 0.1);
    }
    .fa.fa-envelope {
	font-size: 25px;
    }
    .vem {
	font-weight: 600;
	margin-top: 15px;
	color: #2c2c3c;
    }
    .vfb {
	width: 50px;
	margin-top: 20px !important;
	margin: auto;
	color: #078ccc;
	border: 2px solid;
	opacity: 1;
    }
    .text-center.text.mt-3 span {
	font-size: 24px;
	font-weight: 600;
	border: 1px solid #000;
	padding: 0 5px;
    }
    .main-wrapper {
	padding-bottom: 17px;
	border-radius: 4px;
    }
    @media screen and (min-width: 800px) {
        .container.mt-5.x {
	padding: 0 60px;
    }
    }
    a {
	color: #078ccc;
	text-decoration: none;
    }
    .container-fluid.p-5.bg-primary.text-white.text-center.top-section p {
	text-align: justify;
	padding: 20px 0 0 0;
	color: #737373;
}
.logo {
	text-align: initial;
	margin-bottom: 40px;
}
.ico img {
	width: 100%;
}
  </style>
</head>
<body>
<div class="main-wrapper">
@php
    $adminsetting = \App\Models\Adminsettings::first();
@endphp
    <div class="container-fluid p-5 bg-primary text-white text-center top-section">
    <div class="logo">
        <img src="https://welcomepost.in/assets/website/images/logo.png" />
    </div>
    <h1>Account Activate</h1>
   
    <p>Account has been deactivate. You can activate account within 30 days. After 30 days your account has been permanently closed.</p>
    <div class="row mt-5">
        <div class="col-sm-4">
            <div class="ico">
                <a href="tel:{{$adminsetting->contact_no}}" target="_blank">
                    <img src="https://webmingo.com/thank-you/assets/img/call.png" />
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ico">
                <a href="https://wa.me/{{$adminsetting->contact_no}}" target="_blank">
                    <img src="https://webmingo.com/thank-you/assets/img/whatsa.png" />
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ico">
                <a href="mailto:{{$adminsetting->email_id}}" target="_blank">
                    <img src="https://webmingo.com/thank-you/assets/img/email.png" />
                </a>
            </div>
        </div>
    </div>
    <div class="bottom-section-th">
        <p>
            Thank you<br>
            Regards<br>
            Business Development Team<br>
            Welcome Post<br>
            Lucknow Uttar Pradesh, India<br>India
        </p>
    </div>
  </div>


</div>

</body>
</html>