@extends('website.layout.layout')
@section('title', $page)
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<style>
    @media screen and (max-width: 635px) {
        .fil-mob.fil-mob-act {
            top: 135px;
        }
        .fil-mob-clo{
             top: 144px;
        }
    }
</style>
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
<section>
    <div class="all-list-bre all-pro-bre">
        <div class="container sec-all-list-bre">
            <div class="row">
                <h1>All Categories</h1>
                <ul>
                    <li><a href="{{ url('/')}}">Home</a></li>
                    <li><a href="#">All Category</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

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
                                    @foreach($allcategories as $key => $orderDetails)
										<option value="{{$orderDetails->id}}">{{$orderDetails->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <script>
							$(document).ready(function(){
							$("#cat_check").change(function(){
								//alert('we');
								var base_url = window.location.origin;
								//var host = window.location.host;
								var cat_check = $('#cat_check').val();
								location.replace(base_url+"/category-ads/"+cat_check)
				
							});

							});
							</script>
                        <!--END-->
                        <!--START-->
                        <div class="filt-com sub_cat_section pro-fil-sub">
                            <h4>Sub category</h4>
                            <ul>
                                @foreach($allsubcategories as $key => $orderDetails)
									<li>
                                        <div class="chbox">
                                            <input type="checkbox" class="sub_cat_check" name="checkbox" value="{{$orderDetails->id}}"
                                                id="sub_cat_check<?php echo $orderDetails->id;?>" />
                                            <label for="sub_cat_check<?php echo $orderDetails->id;?>">{{$orderDetails->name}}</label>
                                        </div>
                                    </li>
									
									<script>
									$(document).ready(function(){
									$("#sub_cat_check<?php echo $orderDetails->id;?>").click(function(){
									//alert('we');
									var base_url = window.location.origin;
									//var host = window.location.host;
									var sub_cat_check = $('#sub_cat_check<?php echo $orderDetails->id;?>').val();
									location.replace(base_url+"/subcategory-ads/"+sub_cat_check)

									});

									});
									</script>
									
                                     @endforeach
                                     </ul>
                                     <div class="col-md-4">
                                    <a href="{{url('all-product')}}" class="btn btn-primary">Clear All</a>
                                </div>
                        </div>
                        <!--END-->
                        <div class="filt-com pro-fil-pri">
                            <h4>Price</h4>
                            <div class="slider-container">
                                <input type="range" id="minPrice" name="minPrice" min="0" max="100000" value="0">
                                <label for="minPrice">Min Price: ₹<span id="minPriceValue">0</span></label>
                                <input type="range" id="maxPrice" name="maxPrice" min="0" max="100000" value="100000">
                                <label for="maxPrice">Max Price: ₹<span id="maxPriceValue">100000</span></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="all-list-sh all-product-total">
                        <ul class="products-wrapper">
                        @if(isset($allpost))
                           @foreach($allpost as $key => $orderDetails)
                           @php
                                if ($orderDetails->city) {
                                    $getcity = App\Models\City::find($orderDetails->city);
                                    $getstate = DB::table('states')->where('id', $getcity->state_id)->first();
                                }
                                $locationInfo = \Location::get($orderDetails->location);
                             @endphp
                            <li class="products-item" data-price="{{ $orderDetails->price?? $orderDetails->salary_to }}">
                                <div class="all-pro-box">
                                    <div class="all-pro-img">
                                        @if(isset($orderDetails->image))
                                            <img src="{{$orderDetails->image}}" alt="" loading="lazy">
                                            @else
                                            <img src="{{ asset('public/uploads/ads/welcomepost.png')}}" alt="" loading="lazy">
                                            @endif
                                            
                                       
                                    </div>
                                    <div class="all-pro-txt">
                                        <h4>{{$orderDetails->ad_title}}</h4>
                                        @if(isset($orderDetails->price))
                                        @if($orderDetails->price!='0')
                                        <span class="pri"><b class="pro-off">&#8377; {{$orderDetails->price??0}}</b> </span>
                                        @endif
                                        @else
                                        @if($orderDetails->salary_from!=0&&$orderDetails->salary_to!=0)
                                        <span class="pri"><b class="pro-off">&#8377; {{$orderDetails->salary_from}} - {{$orderDetails->salary_to}}</b> </span>
                                        @endif
                                        @endif
                                        <div style="border-top: 1px dashed rgba(36, 39, 44, .15);">
                                             <p class="m-0 p-0 "><span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                            <path
                                                d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                            </svg></span>&nbsp;{{ ucfirst($getcity->name ?? $locationInfo->cityName ?? '')}}, {{ ucfirst($getstate->name ?? $locationInfo->regionName ?? '')}}</p>
                                             <p class="ml-0 pl-0"><span class="ic-view">{{$orderDetails->ad_view_count}}</span></p>
                                        </div>
                                    </div>
                                    
                                    <a href="{{url('ads-details/'.$orderDetails->id)}}" class="pro-view-full"></a>
                                </div>
                            </li>
                            @endforeach
                        @else
                            <li class="products-item">No ads Found</li>
                        @endif
                        </ul>
                        <div id="product-pagination-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const minPriceInput = document.getElementById('minPrice');
        const maxPriceInput = document.getElementById('maxPrice');
        const minPriceValue = document.getElementById('minPriceValue');
        const maxPriceValue = document.getElementById('maxPriceValue');
    
        minPriceInput.addEventListener('input', updatePriceFilter);
        maxPriceInput.addEventListener('input', updatePriceFilter);
    
        function updatePriceFilter() {
            const minPrice = parseInt(minPriceInput.value);
            const maxPrice = parseInt(maxPriceInput.value);
    
            minPriceValue.textContent = minPrice;
            maxPriceValue.textContent = maxPrice;
    
            const posts = document.querySelectorAll('.products-wrapper > li');
    
            posts.forEach(post => {
                const postPrice = parseInt(post.getAttribute('data-price'));
                if (postPrice >= minPrice && postPrice <= maxPrice) {
                    post.style.display = ''; // Show post
                } else {
                    post.style.display = 'none'; // Hide post
                }
            });
    
            const visiblePosts = document.querySelectorAll('.products-wrapper > li:not([style*="display: none"])');
            const noResultsMessage = document.querySelector('#no-results-message');
    
            if (visiblePosts.length === 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        }
    });
</script>
@stop