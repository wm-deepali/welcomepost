@extends('website.layout.layout')
@section('title', $page)
@section('content')
<style>
.news-top-menu {
    margin-top: var(--topspac1);
    position: static !important;
}

@media(max-width:768px) {
    .news-top-menu {
        margin-top: 0px;
        position: static !important;
    }
}

.dropdown-toggle::after {
    margin-top: 8px;
}
button.add-type1.active {
    background-color: #bbc0c9 !important;
}
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
<section class="news-hom-big news-details">
    <div class="container">
        <div class="row">
            <div class="col-10 m-auto">
                <div class="all-list-bre news-bre">
                    <ul>
                        <li><a href="{{ url('/')}}">Home</a></li>
                        <li><a href="#">Post Ads</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-12 col-md-10 m-auto">
                <div class="add-post-container">
                    <div class="col-md-12 text-center">
                        <h4>{{$singlecategory->name}} Ad Form</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="add-post-form">
                        <form id="login_form" name="login_form" method="post" action="{{url('post-job-form')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="heading top">
                                <h3><b>Selected category</b></h3>
                                <div class="all-list-bre news-bre">
                                    <ul>
                                        <li><a href="#">{{$singlecategory->name}}</a></li>
                                        <li><a href="#">{{ $singlesubcatid->name }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <hr class="add-post-hr">
                            <div class="heading t">
                                <h3><b>ADD Details</b></h3>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Salary period *</div>
                                <div  class="select-add-type">
                                    @if(old('salary_period')!="")
                                        <button value="{{old('salary_period')}}" type="button" class="add-type1 salary_period">{{old('salary_period')}}</button>
                                    @endif
                                    <button value="Hourly" type="button" class="add-type1 salary_period">Hourly</button>
                                    <button value="Weekly" type="button" class="add-type1 salary_period">Weekly</button>
                                    <button value="Monthly" type="button" class="add-type1 salary_period">Monthly</button>
                                    <button value="Yearly" type="button" class="add-type1 salary_period">Yearly</button>
                                    <input type="hidden" name="salary_period" id="hidden-salary-period">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Position Type</div>
                                <div class="select-add-type"  name="position_type" id="prsition_type">
                                    @if(old('position_type')!="")
                                        <button value="{{old('position_type')}}" type="button" class="add-type1 salary_period">{{old('position_type')}}</button>
                                    @endif
                                    @if(isset($jobs))
                                    @foreach($jobs as $orderDetail)
                                    <button value="{{ ucfirst($orderDetail->jobtype) }}" type="button" class="add-type1 position_type">{{ ucfirst($orderDetail->jobtype)}}</button>
                                    @endforeach
                                    @endif
                                  
                                    <input type="hidden" name="position_type" id="hidden-position-type">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Salary from *</div>
                                <div class="select-add-type">
                                    <input type="number" autocomplete="off" value="{{ old('salary_from') }}" name="salary_from" id="salary_from"class="form-control" placeholder="Enter Salary From*" title="Enter salary from" required="">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Salary to *</div>
                                <div class="select-add-type">
                                    <input type="number" autocomplete="off" value="{{ old('salary_to') }}" name="salary_to" id="salary_to" class="form-control" placeholder="Enter Salary to*" title="Enter salary to" required="">
                                </div>
                                <p style="color:red;">If you dont want to show the price, than input 0 in price field*</p>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Post Title</div>
                                <div class="select-add-type">
                                    <input type="text" autocomplete="off" value="{{ old('ad_title') }}" name="ad_title" id="ad_title" class="form-control" placeholder="Enter post tittle*" title="Enter post tittle" required="">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading"> Description *</div>
                                <div class="select-add-type">
                                    <textarea autocomplete="off" id="description" class="form-control" maxlength="4096" name="description" placeholder="Enter Description" style="height: 96px;">{{ old('description') }}</textarea>
                                    <span class="float-right counter-text"> 
                                        <span id="counter-display" class="tag is-success">0</span>/4096
                                    </span>
                                </div>
                            </div>
                            <hr class="add-post-hr">
                            <div class="add-type">
                                <div class="add-heading price"> Upload up to 5 photos</div>
                                <div class="select-add-type">
								   
									<div class="upload-photo-cont active" id="image">
									     <span class="close closed" style="display:none;cursor: pointer;">&times;</span>
										<div class="sing-img-preview" id="OpenImgUpload" type="file">
										    <img style="height:100px;width:100px;display:none" id="blah" src="{{ old('file') }}" alt="your image"  />
											<svg width="36px" class="blah" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
												<path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z"></path>
											</svg><br>
											<input type="file" name="file" value="{{ old('file') }}" id="imgupload" style="display:none;" accept="image/jpeg, image/png">
											<span class="text-center" id="add_photo">Add photo</span>
										</div>
									</div>
									<div class="upload-photo-cont" id="image1">
									    <span class="close1 closed" style="display:none;cursor: pointer;">&times;</span>
										<div class="sing-img-preview"  id="Image_1" type="input">
										    
										    <img style="height:100px;width:100px;display:none" id="blah_1" src="{{ old('file1') }}" alt="your image"  />
											<svg width="36px" class="blah_1" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
												<path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z"></path>
											</svg><br>
											 <input type="file" name="file1" id="imgupload_1" value="{{ old('file1') }}" style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_1">Add photo</span>
										</div>
									</div>
									<div class="upload-photo-cont" id="image2">
									    <span class="close2 closed" style="display:none;cursor: pointer;">&times;</span>
										<div class="sing-img-preview" type="input" id="Image_2">
										    
										    <img style="height:100px;width:100px;display:none" id="blah_2" src="{{ old('file2') }}" alt="your image"  />
											<svg width="36px" class="blah_2" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
												<path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
												</path>
											</svg><br>
											 <input type="file" name="file2" id="imgupload_2" value="{{ old('file2') }}" style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_2" >Add photo</span>
										</div>
									</div>
									<div class="upload-photo-cont"  id="image3">
									    <span class="close3 closed" style="display:none;cursor: pointer;">&times;</span>
										<div class="sing-img-preview" type="input" id="Image_3">
										    
										    <img style="height:100px;width:100px;display:none" id="blah_3" src="{{ old('file3') }}" alt="your image"  />
											<svg width="36px" class="blah_3" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
												<path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z"></path>
											</svg><br>
											 <input type="file" name="file3" id="imgupload_3" value="{{ old('file3') }}" style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_3">Add photo</span>
										</div>
									</div>
									<div class="upload-photo-cont" id="image4">
									    <span class="close4 closed" style="display:none;cursor: pointer;">&times;</span>
										<div class="sing-img-preview" type="input" id="Image_4">
										    
										    <img style="height:100px;width:100px;display:none" id="blah_4" src="{{ old('file4') }}" alt="your image"  />
											<svg width="36px" class="blah_4" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
												<path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z"></path>
											</svg><br>
											 <input type="file" name="file4" id="imgupload_4" value="{{ old('file4') }}" style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_4">Add photo</span>
										</div>
									</div>
								</div>
                            </div>
                            <div class="add-type">
									<span class="text-danger">Atleast 1 Image is required*</span>
							</div>
                            <hr class="add-post-hr">
                            <div class="add-type">
                                <div class="add-heading price">Confirm your location</div>
                                <div class="select-add-type">
                                    <ul class="tabs">
                                        <li class="tab-link current current_list" data-tab="list">Custom Location</li>
                                        <li class="tab-link get_current_location" data-tab="c-location">Current Location</li>
                                    </ul>
                                    <div id="list" class="tab-content current">
                                        <div class="select-add-type">
                                            <div class="add-heading"> Select State</div>
                                            <div class="form-group">
                                                <select name="state" id="state" class="form-control chosen-select">
                                                    @if(old('state')!="")
                                                    <option value="{{old('state')}}">{{\App\Models\States::findOrFail(old('state'))->name}}</option>
                                                    @else
                                                    <option value="">Select State</option>
                                                    @endif
                                                    @foreach($state as $key => $orderDetails)
                                                        <option value="{{$orderDetails->id}}"> {{$orderDetails->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="select-add-type">
                                            <div class="add-heading"> Select City</div>
                                            <div class="form-group">
                                                <select name="city" id="city" class="form-control chosen-select select-box">
                                                    @if(old('city')!="")
                                                    <option value="{{old('city')}}">{{\App\Models\City::findOrFail(old('city'))->name}}</option>
                                                    @else
                                                    <option value="">Select City</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="select-add-type">
                                            <div class="add-heading">   Neighbourhood * </div>
                                            <div class="form-group">
                                                <input type="text" name="neibourhood" id="neibourhood" value="{{old('neibourhood')}}" placeholder="Enter Neighbourhood" class="form-control">
                                                <!--<select name="neibourhood" id="neibourhood" class="form-control chosen-select neighbor-select-box" style="display: none;">-->
                                                <!--    <option value="">Select Neighbourhood</option>-->
                                                <!--</select>-->
                                            </div>
                                        </div> 
                                        <span class="text-danger">This field is mandatory*</span>   
                                    </div>
                                    <div id="c-location" class="tab-content">
                                        <div class="live location">
                                            <ul class="list-style-none">
                                                <li>State<span class="float-right state_name">{{ @$locationinfo->regionName}}</span></li>
    											<input type="hidden" class="hidden_state_name" value="" name="state_name">
    											<li>City<span class="float-right city_name" >{{ @$locationinfo->cityName}}</span></li>
    											<input type="hidden" class="hidden_neibourhood" value="" name="neibourhood_name">
    											<li>Neighbourhood<span class="float-right">{{ @$locationinfo->latitude ?? ''}}{{ $locationinfo->longitude ?? ''}}</span></li>
                                            </ul>
                                        </div>     
                                    </div>
                                    <hr class="add-post-hr">
                                    {{--<div class="add-type">
                                        <div class="add-heading price"> Review Profile Details</div>
                                         @if(session()->has('id'))
                                            @php
                                                $ct_id = session('id');
                                                $username = DB::table('customers')->where('id',$ct_id)->get();
                                            @endphp
                                        <div class="profile_user">
                                            <div class="user-profile">
                                                @if(isset($username[0]->image))
                                                    <img src="{{ $username[0]->image }}" name="userprofile" class="userprofile" style="width: 100px; height: 100px;">
                                                <div class="change-profile" id="userprofile">
                                                        <svg width="36px" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
                                                            <path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z"></path>
                                                        </svg>
                                                    </div>
                                                    
                                                <input type="file" name="userchangeprofile" id="userchangeprofile" style="display:none;">
                                                @else
                                                <span class="closechangeprofile closed" style="display:none;cursor: pointer;">&times;</span>
												<img style="height:100px;width:100px;display:none"  id="changeprofile1" src="#" alt="your image"  />
													<figure class="changeprofile1" id="changeprofileImg" style="width: 100px; height: 100px; background-image: url(../../../assets/website/images/avatar_3.png) ;"> 
														<div class="change-profile">
                                                        <svg width="36px" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
                                                            <path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <input type="file" name="changeprofile" id="changeprofile" style="display:none;">
                                                @endif
                                                @if($username[0]->image == '')
                                                </figure>
                                                @endif
                                            </div>
                                            <div class="fl">
                                                <div class="add-heading"> Name </div>
                                                <div class="select-add-type">
                                                    <input type="text" autocomplete="off" name="fullname" id="fullname" value="{{ $userinfo->name }}" class="form-control" placeholder="Enter name *" title="Enter name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" value="1" name="is_mobile_hide" id="chkMobile">
                                            <span class="slider round"></span>
                                        </label>
                                        <div class="toggle_mobile_number" style="display:none;">
                                        <div class="add-heading">Mobile number</div>
                                        <div class="select-add-type">
                                            <input type="text" autocomplete="off" name="mobile" id="mobile"class="form-control" placeholder="Enter Mobile number *" value="{{ $userinfo->mobile }}" >
                                        </div>
                                        </div>
                                        @endif
                                    </div>--}}
                                </div>
                            </div><br>
                            <input type="hidden"  name="fullname" id="fullname" class="form-control" value="{{$userinfo->name}}"  required="" />
                            <input type="hidden"  name="mobile" id="mobile" class="form-control" value="{{$userinfo->mobile}}"/>
                
                            <input type="hidden"  name="email" id="email" class="form-control" value="{{$userinfo->email}}"  required="" />
                        
                            
                        
                            <input type="hidden"  name="location" id="location" class="form-control" value="{{$userinfo->address}}"  required="" />
                         
                            <input type="hidden"  name="user_id" id="location" class="form-control" value="{{$userinfo->id}}"  required="" />
                            <input type="hidden"  name="formtype" id="location" class="form-control" value="{{$form_id}}"  required="" />
                            <input type="hidden"  name="category_id" id="location" class="form-control" value="{{$categoryid}}"  required="" />
                            <input type="hidden"  name="subcatid" id="location" class="form-control" value="{{$subcatid}}"  required="" />
                            <div class="select-add-type">
                                <button type="submit"  name="login_submit" value="submit" class="btn btn-primary form-control">
                                    Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$("#chkMobile").click(function () {
    if ($(this).is(":checked")) {
        $(".toggle_mobile_number").show();
    } else {
        $(".toggle_mobile_number").hide();
    }
});
$(".salary_period").click(function() {  
    var id = $(this).val(); 
    $(".salary_period").removeClass("active");
    $(this).addClass('active');
    $("#hidden-salary-period").val(id);
    
});

$(".position_type").click(function() {
    var id = $(this).val(); 
    $(".position_type").removeClass("active");
    $(this).addClass('active');
    $("#hidden-position-type").val(id);
    
});


$('#description').keyup(function() {
  var textlen = 0 + $(this).val().length;
  $('#counter-display').text(textlen);
});

$('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });
$('#Image_1').click(function(){ $('#imgupload_1').trigger('click'); });
$('#Image_2').click(function(){ $('#imgupload_2').trigger('click'); });
$('#Image_3').click(function(){ $('#imgupload_3').trigger('click'); });
$('#Image_4').click(function(){ $('#imgupload_4').trigger('click'); });
$('#Image_5').click(function(){ $('#imgupload_5').trigger('click'); });
$('#Image_6').click(function(){ $('#imgupload_6').trigger('click'); });
$('#Image_7').click(function(){ $('#imgupload_7').trigger('click'); });
$("#changeprofileImg").click(function(){ $("#changeprofile").trigger('click'); });
$('#userprofile').click(function(){ $('#userchangeprofile').trigger('click'); });

$(document).on('click', '.close', function() {
    $(this).css('display','none');
    
    $('#blah').css('display','none');
    $('.blah').css('display','block');
    $('#add_photo').css('display','block');
    //$("#image").addClass('active');
    // $(this).parent().parent().remove();
  });
  $(document).on('click', '.close1', function() {
    $(this).css('display','none');
    
    $('#blah_1').css('display','none');
    $('.blah_1').css('display','block');
    $('#add_photo_1').css('display','block');
    // $(this).parent().parent().remove();
  });
   $(document).on('click', '.close2', function() {
    $(this).css('display','none');
    
    $('#blah_2').css('display','none');
    $('.blah_2').css('display','block');
    $('#add_photo_2').css('display','block');
    // $(this).parent().parent().remove();
  });
  $(document).on('click', '.close3', function() {
    $(this).css('display','none');
    
    $('#blah_3').css('display','none');
    $('.blah_3').css('display','block');
    $('#add_photo_3').css('display','block');
    // $(this).parent().parent().remove();
  });
  $(document).on('click', '.close4', function() {
    $(this).css('display','none');
    
    $('#blah_4').css('display','none');
    $('.blah_4').css('display','block');
    $('#add_photo_4').css('display','block');
    // $(this).parent().parent().remove();
  });
   $(document).on('click', '.close5', function() {
    $(this).css('display','none');
    
    $('#blah_5').css('display','none');
    $('.blah_5').css('display','block');
    $('#add_photo_5').css('display','block');
    // $(this).parent().parent().remove();
  });
  $(document).on('click', '.close6', function() {
    $(this).css('display','none');
    
    $('#blah_6').css('display','none');
    $('.blah_6').css('display','block');
    $('#add_photo_6').css('display','block');
    // $(this).parent().parent().remove();
  });
  $(document).on('click', '.close7', function() {
    $(this).css('display','none');
    
    $('#blah_7').css('display','none');
    $('.blah_7').css('display','block');
    $('#add_photo_7').css('display','block');
    // $(this).parent().parent().remove();
  });
  
  $(document).on('click', '.closechangeprofile', function() {
    $(this).css('display','none');
    
    $('#changeprofile1').css('display','none');
    $('.changeprofile1').css('display','block');
    //$('#add_photo_7').css('display','block');
    // $(this).parent().parent().remove();
  });
$(document).ready(()=>{  
	$('#imgupload').change(function(){
        const file = this.files[0];
        console.log(file);
         $('#blah').css('display','block');
         $('.close').first().css('display','block');
         $('.blah').css('display','none');
         $('#add_photo').css('display','none');
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('body #blah').attr('src', event.target.result);
           // $("#image").removeClass('active');
           // $("#image1").addClass('active');
          }
          reader.readAsDataURL(file);
        }
      });
      
      $('#imgupload_1').change(function(){
        const file = this.files[0];
        console.log(file);
         $('#blah_1').css('display','block');
         $('.close1').first().css('display','block');
         $('.blah_1').css('display','none');
         $('#add_photo_1').css('display','none');
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('body #blah_1').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });

      $('#imgupload_2').change(function(){
        const file = this.files[0];
        console.log(file);
         $('#blah_2').css('display','block');
         $('.close2').first().css('display','block');
         $('.blah_2').css('display','none');
         $('#add_photo_2').css('display','none');
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('body #blah_2').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });

       $('#imgupload_3').change(function(){
        const file = this.files[0];
        console.log(file);
         $('#blah_3').css('display','block');
         $('.close3').first().css('display','block');
         $('.blah_3').css('display','none');
         $('#add_photo_3').css('display','none');
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('body #blah_3').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });

      $('#imgupload_4').change(function(){
        const file = this.files[0];
        console.log(file);
         $('#blah_4').css('display','block');
         $('.close4').first().css('display','block');
         $('.blah_4').css('display','none');
         $('#add_photo_4').css('display','none');
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('body #blah_4').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });

      $('#imgupload_5').change(function(){
        const file = this.files[0];
        console.log(file);
         $('#blah_5').css('display','block');
         $('.close5').first().css('display','block');
         $('.blah_5').css('display','none');
         $('#add_photo_5').css('display','none');
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('body #blah_5').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });

      $('#imgupload_6').change(function(){
        const file = this.files[0];
        console.log(file);
         $('#blah_6').css('display','block');
         $('.close6').first().css('display','block');
         $('.blah_6').css('display','none');
         $('#add_photo_6').css('display','none');
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('body #blah_6').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });


      $('#imgupload_7').change(function(){
        const file = this.files[0];
        console.log(file);
         $('#blah_7').css('display','block');
         $('.close7').first().css('display','block');
         $('.blah_7').css('display','none');
         $('#add_photo_7').css('display','none');
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('body #blah_7').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });
      
      $('#userchangeprofile').change(function(){
        const file = this.files[0];
        console.log(file);
        //  $('#blah').css('display','block');
        //  $('.close').first().css('display','block');
        //  $('.blah').css('display','none');
        //  $('#add_photo').css('display','none');
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('body .userprofile').attr('src', event.target.result);
           // $("#image").removeClass('active');
           // $("#image1").addClass('active');
          }
          reader.readAsDataURL(file);
        }
      });
      
      $('#changeprofile').change(function(){
        const file = this.files[0];
        console.log(file);
         $('#changeprofile1').css('display','block');
         $('.closechangeprofile').first().css('display','block');
         $('.changeprofile1').css('display','none');
        // $('#closechangeprofile').css('display','none');
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('body #changeprofile1').attr('src', event.target.result);
           // $("#image").removeClass('active');
           // $("#image1").addClass('active');
          }
          reader.readAsDataURL(file);
        }
      });
});

$('ul.tabs li').click(function(){
    var tab_id = $(this).attr('data-tab');
    $('ul.tabs li').removeClass('current');
    $('.tab-content').removeClass('current');
    $(this).addClass('current');
    $("#"+tab_id).addClass('current');
});
$(".get_current_location").on("click", function(){
    var state_name = $(".state_name").html();  
    var city_name   = $(".city_name").html();
    $(".hidden_state_name").val(state_name);
    $(".hidden_city_name").val(city_name);
});

$(".current_list").on("click", function(){
    var empty_data = '';
    $(".hidden_state_name").val(empty_data);
    $(".hidden_city_name").val(empty_data);
});
</script>    
<script>
$(document).ready(function(){
    $("#state").change(function(){
        var state_id = $('#state').val();
        $.ajax({
            url:'{{url("get-city")}}',
            method:'POST',
            data:{state_id:state_id,'_token':"{{csrf_token()}}"},
            success:function(data){
                $('#city').html(data);
                $(".select-box").css("display",'block');
                $("#city_chosen").css("display",'none');
            }
        });
    });
});

$(document).ready(function(){
    $("#city").change(function(){
    var city_id = $('#city').val();
    
        $.ajax({
            url:'{{url("get-location")}}',
            method:'POST',
            data:{city_id:city_id,'_token':"{{csrf_token()}}"},
            success:function(data){
                $('#neibourhood').html(data);
                $(".neighbor-select-box").css('display','block');
                $("#neibourhood_chosen").css('display','none');
            }
        });
    });
});

function restrictNumber(e) {
    var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
    this.value = newValue;
}
var salary_from = document.querySelector('#salary_from');
salary_from.addEventListener('input', restrictNumber);

var salary_to = document.querySelector('#salary_to');
salary_to.addEventListener('input', restrictNumber);

var price = document.querySelector('#price');
price.addEventListener('input', restrictNumber);

</script>
@stop