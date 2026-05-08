@extends('website.layout.layout')
@section('content')

<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>


<!-- START -->
    <section>
        <div class="all-list-bre all-pro-bre">
            <div class="container sec-all-list-bre">
                <div class="row">
                    <h1>All Categories Posts</h1>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="#">All Category</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->
    <!-- START -->
    <section>
        <div class="all-listing all-products">
            <!--FILTER ON MOBILE VIEW-->
            <div class="fil-mob fil-mob-act">
                <h4>Product filters <i class="material-icons">filter_list</i></h4>
            </div>
            <div class="container">
                <div class="row">

                    <div class="col-md-3 fil-mob-view">
                        <div class="all-filt">
                            <span class="fil-mob-clo"><i class="material-icons">close</i></span>

                            <!--START-->
                            <div class="filt-com lhs-cate">
                                <h4>Categories</h4>
                                <div class="dropdown">
                                    <select class="cat_check chosen-select" name="cat_check" id="cat_check">
                                        <option>Select Category</option>
                                        <option>Clothings</option>
                                        <option>Footwear</option>
                                        <option>Shoes</option>
                                        <option>Jewellery</option>
                                        <option>Toys</option>
                                        <option>Baby care</option>
                                        <option>Fruits</option>
                                        <option>Mens</option>
                                        <option>Health</option>
                                        <option>Sports</option>
                                        <option>Education</option>
                                        <option>Electricals</option>
                                        <option>Automobilers</option>
                                    </select>
                                </div>
                            </div>
                            <!--END-->

                            <!--START-->
                            <div class="filt-com sub_cat_section pro-fil-sub">
                                <h4>Sub category</h4>
                                <ul>
								
									@foreach($allsubcategories as $key => $orderDetails)
									
									
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" class="sub_cat_check" name="sub_cat_check" value="{{$orderDetails->id}}"
                                                id="Diet food" />
                                            <label for="Diet food">{{$orderDetails->name}}</label>
                                        </div>
                                    </li>
                                    @endforeach
									
                                </ul>
                            </div>
                            <!--END-->

                            <!--START-->
                            <div class="filt-com pro-fil-pri">
                                <h4>Price</h4>
                                <ul>
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" name="price_check" value="10000" class="price_check"
                                                id="price_check5" />
                                            <label for="price_check5">Above $1000</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" name="price_check" value="1000" class="price_check"
                                                id="price_check4" />
                                            <label for="price_check4">$501 - $1000</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" name="price_check" value="500" class="price_check"
                                                id="price_check3" />
                                            <label for="price_check3">$251 - $500</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" name="price_check" value="250" class="price_check"
                                                id="price_check2" />
                                            <label for="price_check2">$101 - $250</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" name="price_check" value="100" class="price_check"
                                                id="price_check1" />
                                            <label for="price_check1">Below $100</label>
                                        </div>
                                    </li>


                                </ul>
                            </div>
                            <!--END-->

                            <div class="filt-com pro-fil-dis">
                                <h4>Discounts</h4>
                                <ul>
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" name="discount_check" value="100"
                                                class="discount_check" id="discount_check5" />
                                            <label for="discount_check5">Above 70%</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" name="discount_check" value="70"
                                                class="discount_check" id="discount_check4" />
                                            <label for="discount_check4">51% - 70%</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" name="discount_check" value="50"
                                                class="discount_check" id="discount_check3" />
                                            <label for="discount_check3">26% - 50%</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" name="discount_check" value="25"
                                                class="discount_check" id="discount_check2" />
                                            <label for="discount_check2">11% - 25%</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="chbox">
                                            <input type="checkbox" name="discount_check" value="10"
                                                class="discount_check" id="discount_check1" />
                                            <label for="discount_check1">Below 10%</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="all-list-sh all-product-total">
                            <ul class="products-wrapper">
					
								@foreach($allpost as $key => $orderDetails)
								
								
								<li class="products-item">
                                    <div class="all-pro-box">
                                        <div class="all-pro-img">
                                            <img src="{{$orderDetails->image}}" alt="" loading="lazy">
                                        </div>
                                        <div class="all-pro-txt">
                                            <h4>{{$orderDetails->ad_title}}</h4>
                                            <span class="pri"><b class="pro-off">{{$orderDetails->salary_from}}</b> </span>
                                            <div class="links btn-2">
                                                <a href="#">Get quote</a>                                                     
                                            </div>
                                        </div>
                                        <a href="#" class="pro-view-full"></a>
                                    </div>
                                </li>

								@endforeach
                               

                                
                            </ul>
                            <div id="product-pagination-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->
@endsection