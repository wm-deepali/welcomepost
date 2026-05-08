@extends('website.layout.layout')
@section('content')
<style>
        .page-header {
            position: relative;
            width: 100%;
            overflow: hidden;
        }
        
        .page-title {
            position: absolute;
            z-index: 2;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin: 0;
        }
        
        .banner-image {
            width: 100%;
            height: 200px; /* Set the desired height of the banner */
            object-fit: cover; /* Ensures the image covers the container */
            object-position: center; /* Centers the image */
        }

    </style>
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
<main class="main">
    <!-- Start of Page Header -->
    <div class="page-header">
        <div class="container d-flex justify-content-center align-items-center position-relative">
            <h1 class="page-title mb-0">Contact Us</h1>
            @if(isset($contact->image))
                <img src="{{ $contact->image}}" class="banner-image">
            @endif
        </div>
    </div>
    <!-- End of Page Header -->
    <!-- Start of PageContent -->
    <div class="page-content contact-us">
        <div class="container">
            <section class="content-title-section mb-10">
                <h3 class="title text-center mt-3 mb-3">Contact Information</h3>
            </section>
            <!-- End of Contact Title Section -->

            <section class="contact-information-section mb-10">
                <div class="swiper-wrapper row">
                    @if(isset($contact->email))
                    <div class="icon-box text-center icon-box-primary col-lg-4 mt-2">
                        <span class="icon-box-icon icon-email">
                            <i class="w-icon-envelop-closed"></i>
                        </span>
                       
                        <div class="icon-box-content">
                            <h4 class="icon-box-title">E-mail Address</h4>
                            <p><a href="mailto:{{ $contact->email}}">{{ $contact->email}}</a></p>
                        </div>
                        
                    </div>
                    @endif
                    @if(isset($contact->mobile))
                    <div class="icon-box text-center icon-box-primary col-lg-4 mt-2">
                        <span class="icon-box-icon icon-headphone">
                            <i class="w-icon-headphone"></i>
                        </span>
                        <div class="icon-box-content">
                            <h4 class="icon-box-title">Phone Number</h4>
                            <p>(+91) {{ $contact->mobile}}</p>
                        </div>
                    </div>
                    @endif
                    @if(isset($contact->location))
                    <div class="icon-box text-center icon-box-primary col-lg-4 mt-2">
                        <span class="icon-box-icon icon-map-marker">
                            <i class="w-icon-map-marker"></i>
                        </span>
                        <div class="icon-box-content">
                            <h4 class="icon-box-title">Address</h4>
                            <p>{{ $contact->location}}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </section>
            <!-- End of Contact Information section -->
            <hr class="divider mb-10 pb-1">
        </div>
        <!-- Google Maps - Go to the bottom of the page to change settings and map location. -->
        <!---div class="google-map contact-google-map" id="googlemaps">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3558.965225583244!2d80.8817031!3d26.872845899999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399bfe1eaba528ef%3A0x6235201eb0625c12!2sBalaganj%20Chauraha%2C%20Thakurganj%2C%20Daulatganj%2C%20Lucknow%2C%20Uttar%20Pradesh%20226003!5e0!3m2!1sen!2sin!4v1717505315151!5m2!1sen!2sin" width="100%" height="480" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div--->
        <!-- End Map Section -->
    </div>
    <!-- End of PageContent -->
</main>
@endsection