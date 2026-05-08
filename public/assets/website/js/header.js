document.write(`
<title> Welcome Post</title>
    <!--== META TAGS ==-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="theme-color" content="#76cef1">
    <meta property="og:image" content="">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <!--== FAV ICON(BROWSER TAB ICON) ==-->
    <link rel="shortcut icon" href="images/home/favicon.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.css">
    <!--== CSS FILES ==-->
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    <style>
    .dropdown-toggle::after
    {
        margin-top:8px;
    }
    </style>
    </style>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>

    <!-- START -->
    <section>
        <div class="str ind2-home">
            <div class="hom-head">
                <div class="hom-top lr">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="hom-nav "><!--MOBILE MENU-->
                                <a href="index.html" class="top-log"><img src="images/home/logo.jpeg"
                                        style="width: 190px;" alt="" loading="lazy" class="ic-logo"></a>

                               
                                <!--END MOBILE MENU-->
                                <div class="top-post-search">
                                    <div class="job-sear tops">
                                        <form name="expert_filter_form" id="expert_filter_form" class="expert_filter_form">
                                            <ul>
                                                <li class="sr-loc">
                                                    <select class="chosen-select" id="job-select-city" name="serjobsloc">
                                                        <option value="1">Select Location</option>
                                                        <option value="2">Uttar Pradesh</option>
                                                        <option value="4">New york</option>
                                                        <option value="7">Delhi</option>
                                                        <option value="8">Noida</option>
                                                        <option value="9">Lucknow</option>
                                                    </select>
                                                </li>
                                                <li class="sr-btn">
                                                    <button id="expert_filter_submit"><i class="material-icons">search</i></button>
                                                </li>
                                            </ul>
                                        </form>
                                    </div>
                                </div>
                                <div class="top-ser">
                                    <form name="filter_form" id="filter_form" class="filter_form">
                                        <ul>
                                            <li class="sr-sea">
                                                <input type="text" autocomplete="off" id="top-select-search"
                                                    placeholder="What are you looking for?">
                                                <ul id="tser-res1" class="tser-res tser-res2">
                                                    <li>
                                                        <div>
                                                            <h4>Online classes for School Students</h4>
                                                            <span>Schools, university, colleges, online classes, tution
                                                                centers, distance education..</span>
                                                            <a href="#"></a>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div>
                                                            <h4>Software jobs waiting for you</h4>
                                                            <span>Jobs in New york, High pay salary</span>
                                                            <a href="#"></a>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div>
                                                            <h4>Home cleaning services near you</h4>
                                                            <span>Home cleaning, pet control and more</span>
                                                            <a href="#"></a>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div>
                                                            <h4>Best AC Service Expert near you</h4>
                                                            <span>Service expert, ac service, ac service in new
                                                                york</span>
                                                            <a href="#"></a>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div>
                                                            <h4>New year 2022 celebration started</h4>
                                                            <span>New year 2022, event booking, hotel booking and
                                                                more</span>
                                                            <a href="#"></a>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div>
                                                            <h4>Buy Iphone13 Pro now</h4>
                                                            <span>Iphone 13, 12, 11 and all apple product
                                                                available</span>
                                                            <a href="#"></a>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div>
                                                            <h4>Spa Center For Womens</h4>
                                                            <span>No:2, 4th Avenue, Newyork, USA, Near to Airport</span>
                                                            <a href="#"></a>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div>
                                                            <h4>Now easy to buy Villas, Plots and Flats</h4>
                                                            <span>New york City</span>
                                                            <a href="#"></a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>

                                            <li class="sbtn">
                                                <button type="button" class="btn btn-success" id="top_filter_submit"><i
                                                        class="material-icons">&nbsp;</i></button>
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                                
                                <ul class="bl">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="http://example.com"
                                            id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            English
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <li style="padding:5px 0;font-size:16px;"><a class="dropdown-item" href="#"
                                                    style="background:none;border:none;color:#000">Hindi</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="login.html">Sign in</a>
                                    </li>
                                    <li>
                                        <a href="post-ads.html">Sell for Free</a>
                                    </li>
                                </ul>
                                <!--MOBILE MENU-->
                                <div class="mob-menu">
                                    <div class="mob-me-ic"><i class="material-icons">menu</i></div>
                                    <div class="mob-me-all">
                                        <div class="mob-me-clo"><i class="material-icons">close</i></div>
                                        <div class="mv-bus">
                                            <h4></h4>
                                            <ul>
                                                <li>
                                                    <a href="post-ads.html">Post Free Ad</a>
                                                </li>
                                                <li>
                                                    <a href="login.html">Sign in</a>
                                                </li>
                                                <li>
                                                    <a href="login.html">Create
                                                        an account</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="mv-cate">
                                            <h4>All Categories</h4>
                                            <ul>
                                                <li>
                                                    <a href="all-products.html">Automobiles</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Hospitals</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Spa and Facial</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Electricals</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Restaurants</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Wedding halls</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Technology</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Pet shop</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Real Estate</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Sports</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Education</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Transportation</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--END MOBILE MENU-->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="ban-tit">
                            <h1>
                                <b>Find your <span>Local needs <i></i></span></b> Restaurants, cafe's, and bars in New
                                york
                            </h1>
                        </div>
                        <div class="ban-search ban-sear-all">
                            <form name="filter_form" id="filter_form" class="filter_form">
                                <ul>
                                    <li class="sr-cate">
                                        <select onchange="getSearchCategories(this.value);" name="explor_select"
                                            id="explor_select" data-placeholder="Select Services" class="chosen-select">
                                            <!--<option value="">Select Services</option>-->
                                            <option value="1">All Services</option>
                                            <option value="2">Service Experts</option>
                                            <option value="3">Jobs</option>
                                            <option value="4">Explore Travel</option>
                                            <option value="5">News & Magazines</option>
                                            <option value="6">Events</option>
                                            <option value="7">Products</option>
                                            <option value="8">Coupon & deals</option>
                                            <option value="9">Blogs</option>
                                        </select>
                                    </li>
                                    <li class="sr-cit">
                                        <select id="city_check" name="city_check" data-placeholder="Select City"
                                            class="chosen-select">
                                            <option value="48025">Los Angeles</option>
                                            <option value="48026">Chicago</option>
                                            <option value="48027">Houston</option>
                                            <option value="48028">Phoenix</option>
                                            <option value="48024">New York City</option>
                                            <option value="48029">Philadelphia</option>
                                            <option value="48030">San Antonio</option>
                                            <option value="48031">San Diego</option>
                                            <option value="48032">Dallas</option>
                                            <option value="48035">Illunois city</option>
                                            <option value=""></option>
                                        </select>
                                    </li>
                                    <li class="sr-nor">
                                        <select id="expert-select-search" name="expert-select-search"
                                            class="chosen-select">
                                            <option value="">What are you looking for?</option>
                                            <option value="Automobiles">Automobiles</option>
                                            <option value="Hospitals">Hospitals</option>
                                            <option value="Spa and Facial">Spa and Facial</option>
                                            <option value="Electricals">Electricals</option>
                                            <option value="Restaurants">Restaurants</option>
                                            <option value="Wedding halls">Wedding halls</option>
                                            <option value="Technology">Technology</option>
                                            <option value="Pet shop">Pet shop</option>
                                            <option value="Real Estate">Real Estate</option>
                                            <option value="Sports">Sports</option>
                                            <option value="Education">Education</option>
                                            <option value="Transportation">Transportation</option>
                                        </select>
                                    </li>
                                    <li class="sr-sea">
                                        <input type="text" autocomplete="off" id="select-search"
                                            placeholder="What are you looking for?" class="search-field">
                                        <ul id="tser-res" class="tser-res tser-res1">
                                            <li>
                                                <div>
                                                    <h4>Online classes for School Students</h4>
                                                    <span>Schools, university, colleges, online classes, tution centers,
                                                        distance education..</span>
                                                    <a href="#"></a>
                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <h4>Software jobs waiting for you</h4>
                                                    <span>Jobs in New york, High pay salary</span>
                                                    <a href="#"></a>
                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <h4>Home cleaning services near you</h4>
                                                    <span>Home cleaning, pet control and more</span>
                                                    <a href="#"></a>
                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <h4>Best AC Service Expert near you</h4>
                                                    <span>Service expert, ac service, ac service in new york</span>
                                                    <a href="#"></a>
                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <h4>New year 2022 celebration started</h4>
                                                    <span>New year 2022, event booking, hotel booking and more</span>
                                                    <a href="#"></a>
                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <h4>Buy Iphone13 Pro now</h4>
                                                    <span>Iphone 13, 12, 11 and all apple product available</span>
                                                    <a href="#"></a>
                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <h4>Spa Center For Womens</h4>
                                                    <span>No:2, 4th Avenue, Newyork, USA, Near to Airport</span>
                                                    <a href="#"></a>
                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <h4>Now easy to buy Villas, Plots and Flats</h4>
                                                    <span>New york City</span>
                                                    <a href="#"></a>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="sr-btn">
                                        <input type="submit" id="filter_submit" name="filter_submit" value="Search"
                                            class="filter_submit">
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <div class="ban-short-links">
                            <ul>
                                <li>
                                    <div>
                                        <img src="images/icon/shop.png" alt="" loading="lazy">
                                        <h4>All Services</h4>
                                        <a href="all-products.html" class="fclick"></a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/expert.png" alt="" loading="lazy">
                                        <h4>Experts</h4>
                                        <a href="#" class="fclick"></a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/employee.png" alt="" loading="lazy">
                                        <h4>Jobs</h4>
                                        <a href="#" class="fclick"></a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/news.png" alt="" loading="lazy">
                                        <h4>News</h4>
                                        <a href="#" class="fclick"></a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/calendar.png" alt="" loading="lazy">
                                        <h4>Events</h4>
                                        <a href="#" class="fclick"></a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/cart.png" alt="" loading="lazy">
                                        <h4>Products</h4>
                                        <a href="all-products.html" class="fclick"></a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/coupons.png" alt="" loading="lazy">
                                        <h4>Coupons</h4>
                                        <a href="#" class="fclick"></a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/blog1.png" alt="" loading="lazy">
                                        <h4>Blogs</h4>
                                        <a href="blog-posts.html" class="fclick"></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="h2-ban-ql">
                            <ul>
                                <li>
                                    <div>
                                        <img src="images/icon/listing.png" alt="" loading="lazy">
                                        <h5><span class="count1">12</span>All Services </h5>
                                        <a href="all-products.html">&nbsp;</a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/expert.png" alt="" loading="lazy">
                                        <h5><span class="count1">12</span>Service Experts </h5>
                                        <a href="#">&nbsp;</a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/employee.png" alt="" loading="lazy">
                                        <h5><span class="count1">12</span>Jobs </h5>
                                        <a href="#">&nbsp;</a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/shop.png" alt="" loading="lazy">
                                        <h5><span class="count1">12</span>Products </h5>
                                        <a href="all-products.html">&nbsp;</a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/event.png" alt="" loading="lazy">
                                        <h5><span class="count1">16</span>Events </h5>
                                        <a href="#">&nbsp;</a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/coupons.png" alt="" loading="lazy">
                                        <h5><span class="count1">11</span>Coupons </h5>
                                        <a href="#">&nbsp;</a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/blog.png" alt="" loading="lazy">
                                        <h5><span class="count1">16</span>Blogs </h5>
                                        <a href="blog-posts.html">&nbsp;</a>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <img src="images/icon/general.png" alt="" loading="lazy">
                                        <h5><span class="count1">189</span>Community </h5>
                                        <a href="#">&nbsp;</a>
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->
    <section class="news-top-menu" style="position: fixed;z-index: 16;">
        <div class="container-fluid">
            <div class="row">
                <div class="news-menu">
                    <ul>
                        <li class="categoru-menu-m">
                            <div class="menu">
                                <h4 class="dropdown-toggle">All Categories</h4>
                                
                            </div>
                            <div class="pop-menu">
                                <div class="container">
                                    <div class="row">
                                        
                                        <div class="pmenu-spri">
                                            <ul>
                                                <li><a href="#" class="act"><img src="images/icon/shop.png"
                                                            loading="lazy">All Services </a></li>
                                                <li><a href="#" class="act"><img src="images/icon/employee.png"
                                                            loading="lazy">Jobs </a>
                                                </li>
                                                <li><a href="#"><img src="images/icon/news.png" loading="lazy">News
                                                        & Magazines </a></li>
                                                <li><a href="#"><img src="images/icon/calendar.png"
                                                            loading="lazy">Events </a></li>
                                                <li><a href="#"><img src="images/icon/cart.png"
                                                            loading="lazy">Products </a></li>
                                                <li><a href="#"><img src="images/icon/coupons.png"
                                                            loading="lazy">Coupon & deals </a></li>
                                                <li><a href="#"><img src="images/icon/blog1.png"
                                                            loading="lazy">Blogs </a></li>
                                                <li><a href="#"><img src="images/icon/11.png"
                                                            loading="lazy">Community </a></li>
                                            </ul>
                                        </div>
                                        <div class="pmenu-cat">
                                            <h4>All Categories <i class="material-icons clopme">close</i></h4>
                                            <input type="text" id="pg-sear" placeholder="Search category">
                                            <ul id="pg-resu">
                                                <li>
                                                    <a href="all-products.html">Automobiles</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Hospitals</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Spa and Facial</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Electricals</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Restaurants</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Wedding halls</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Technology</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Pet shop</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Real Estate</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Sports</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Education</a>
                                                </li>
                                                <li>
                                                    <a href="all-products.html">Transportation</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="dir-home-nav-bot">
                                            <ul>
                                                <li>A few reasons you'll love Online Business Services <span>Call
                                                        us on: +91 9876543210</span></li>
                                                <li><a href="#" class="waves-effect waves-light btn-large"><i
                                                            class="material-icons">font_download</i> connect with
                                                        us </a>
                                                </li>
                                                <li><a href="#" class="waves-effect waves-light btn-large"> <i
                                                            class="material-icons">store</i> Add your business </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </li>
                        <li><a href="index.html" class="act">Home</a></li>
                        <li><a href="all-products.html" class="">Tech</a></li>
                        <li><a href="all-products.html" class="">Entertainment</a></li>
                        <li><a href="all-products.html" class="">Lifestyle</a></li>
                        <li><a href="all-products.html" class="">Women</a></li>
                        <li><a href="all-products.html" class="">Real Estate</a></li>
                        <li><a href="all-products.html" class="">Health</a></li>
                        <li><a href="blog-posts.html" class="">Blog</a></li>
                        <li><a href="profile.html" class="">Profile</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
<!--END-->

    `);