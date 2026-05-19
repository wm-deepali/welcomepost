
<div class="card shadow-sm mt-4">

    <div class="card-body">


                    <form id="login_form" name="login_form" method="post" action="{{url('admin/post-property-forms')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="heading t">
                            <h3><b>Include some details</b></h3>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Type*</div>
                            <div class="select-add-type">
                                <button class="add-type1 property_type @if(old('property_type') !="" && old('property_type') == 'Apartments') active @endif" type="button" value="Apartments">Apartments</button>
                                <button class="add-type1 property_type @if(old('property_type') !="" && old('property_type') == 'Builder Floors') active @endif" type="button" value="Builder Floors">Builder Floors</button>
                                <button class="add-type1 property_type @if(old('property_type') !="" && old('property_type') == 'Farm House') active @endif" type="button" value="Farm House">Farm House</button>
                                <button class="add-type1 property_type @if(old('property_type') !="" && old('property_type') == 'Houses And Villas') active @endif" type="button" value="Houses And Villas">Houses And Villas</button>
                                <button class="add-type1 property_type @if(old('property_type') !="" && old('property_type') == 'Houses And Villas') active @endif" type="button" value="Houses And Villas">Shops</button>
                                <button class="add-type1 property_type @if(old('property_type') !="" && old('property_type') == 'Houses And Villas') active @endif" type="button" value="Houses And Villas">Plots</button>
                                <input type="hidden" name="property_type" id="hidden-property-type" value="{{old('property_type')}}">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Bedrooms</div>
                            <div class="select-add-type">
                                <button class="add-type1 bedroom @if(old('bedroom') !="" && old('bedroom') == '1') active @endif" type="button"  value="1">1</button>
                                <button class="add-type1 bedroom @if(old('bedroom') !="" && old('bedroom') == '2') active @endif" type="button"  value="2">2</button>
                                <button class="add-type1 bedroom @if(old('bedroom') !="" && old('bedroom') == '3') active @endif" type="button"  value="3">3</button>
                                <button class="add-type1 bedroom @if(old('bedroom') !="" && old('bedroom') == '4') active @endif" type="button" value="4">4</button>
                                <button class="add-type1 bedroom @if(old('bedroom') !="" && old('bedroom') == '4+') active @endif" type="button"  value="4 +">4+</button>
                                <input type="hidden" name="bedroom" id="hidden-bedroom" value="{{old('bedroom')}}">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading"> Bathrooms</div>
                            <div class="select-add-type">
                                <button class="add-type1 bathroom @if(old('bathroom') !="" && old('bathroom') == '1') active @endif" type="button" value="1">1</button>
                                <button class="add-type1 bathroom @if(old('bathroom') !="" && old('bathroom') == '2') active @endif" type="button"  value="2">2</button>
                                <button class="add-type1 bathroom @if(old('bathroom') !="" && old('bathroom') == '3') active @endif" type="button"  value="3">3</button>
                                <button class="add-type1 bathroom @if(old('bathroom') !="" && old('bathroom') == '4') active @endif" type="button" value="4">4</button>
                                <button class="add-type1 bathroom @if(old('bathroom') !="" && old('bathroom') == '4+') active @endif" type="button" value="4 +">4+</button>
                                <input type="hidden" name="bathroom" id="hidden-bathroom" value="{{old('bathroom')}}">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Residence</div>
                            <div class="select-add-type">
                                @if(isset($residence))
                                @foreach($residence as $orderDetail)
                                <button class="add-type1 residence_status" type="button" value="{{ ucfirst($orderDetail->residencetype)}}">{{ ucfirst($orderDetail->residencetype)}}</button>
                                @endforeach
                                @endif
                                
                                <input type="hidden" name="residence_status" id="hidden-residence-status">
                            </div>
                        </div>
                        
                        <div class="add-type">
                            <div class="add-heading">Furnishing</div>
                            <div class="select-add-type">
                                @if(isset($furnishing))
                                @foreach($furnishing as $orderDetail)
                                <button class="add-type1 furnishing_status" type="button" value="{{ ucfirst($orderDetail->furnishingtype)}}">{{ ucfirst($orderDetail->furnishingtype)}}</button>
                                @endforeach
                                @endif
                                
                                <input type="hidden" name="furnishing_status" id="hidden-furnishing-status">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Construction Status</div>
                            <div class="select-add-type">
                                @if(isset($construction))
                                @foreach($construction as $orderDetail)
                                <button  type="button" class="add-type1 construction_status" value="{{ ucfirst($orderDetail->constructiontype)}}">{{ ucfirst($orderDetail->constructiontype)}}</button>
                                @endforeach
                                @endif
                                
                                <input type="hidden" name="construction_status" id="hidden-construction-status">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Listed by</div>
                            <div class="select-add-type">
                                <button class="add-type1 listed_by @if(old('listed_by') !="" && old('listed_by') == 'Builder') active @endif" type="button" value="Builder" name="listed_by">Builder</button>
                                <button class="add-type1 listed_by @if(old('listed_by') !="" && old('listed_by') == 'Dealer') active @endif" type="button" value="Dealer" name="listed_by">Dealer</button>
                                <button class="add-type1 listed_by @if(old('listed_by') !="" && old('listed_by') == 'Owner') active @endif" type="button" value="Owner" name="listed_by">Owner</button>
                                <input type="hidden" name="listed_by" id="hidden-listed-by" value="{{old('listed_by')}}">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Plot Type</div>
                            <div class="select-add-type">
                                <button class="add-type1 plot_type @if(old('plot_type') !="" && old('plot_type') == 'Residential') active @endif" type="button" value="Residential" name="plot_type">Residential</button>
                                <button class="add-type1 plot_type @if(old('plot_type') !="" && old('plot_type') == 'Commercial') active @endif" type="button" value="Commercial" name="plot_type">Commercial</button>
                                <button class="add-type1 plot_type @if(old('plot_type') !="" && old('plot_type') == 'Agricultural') active @endif" type="button" value="Agricultural" name="plot_type">Agricultural</button>
                                <button class="add-type1 plot_type @if(old('plot_type') !="" && old('plot_type') == 'Industrial') active @endif" type="button" value="Industrial" name="plot_type">Industrial</button>
                                <input type="hidden" name="plot_type" id="hidden-plot-type" value="{{old('plot_type')}}">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Price for Month</div>
                            <div class="select-add-type">
                                <button class="add-type1 price_mention @if(old('price_mention') !="" && old('price_mention') == 'Sell') active @endif" type="button" value="Sell" name="price_mention">Sell</button>
                                <button class="add-type1 price_mention @if(old('price_mention') !="" && old('price_mention') == 'Rent') active @endif" type="button" value="Rent" name="price_mention">Rent</button>
                                
                                <input type="hidden" name="price_mention" id="hidden-price-mention" value="{{old('price_mention')}}">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Super Builtup area (ft²) *</div>
                            <div class="select-add-type">
                                <input type="text" autocomplete="off" name="builtup_area" id="builtup_area"
                                class="form-control" placeholder="Enter Super Builtup Area*" value="{{old('builtup_area')}}" required="">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Carpet Area (ft²) *</div>
                            <div class="select-add-type">
                                <input type="text" autocomplete="off" name="carpet_area" id="carpet_area" value="{{old('carpet_area')}}" class="form-control" placeholder="Enter Carpet Area*" required="">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Maintenance (Monthly)</div>
                            <div class="select-add-type">
                                <input type="text" autocomplete="off" name="maintenance" id="builduparea" value="{{old('maintenance')}}" class="form-control" placeholder="Enter Maintenance (Monthly)" required="">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Total Floors</div>
                            <div class="select-add-type">
                                <input type="text" autocomplete="off" name="total_floor" id="total_floor" value="{{old('total_floor')}}" class="form-control" placeholder="Enter Total Floors " required="">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Floor No</div>
                            <div class="select-add-type">
                                <input type="text" autocomplete="off" name="floor_no" id="floor_no" value="{{old('floor_no')}}" class="form-control" placeholder="Enter Floor No" required="">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Car Parking</div>
                            <div class="select-add-type">
                                <button class="add-type1 car_parking" type="button" value="0">0</button>
                                <button class="add-type1 car_parking" type="button" value="1">1</button>
                                <button class="add-type1 car_parking" type="button" value="2">2</button>
                                <button class="add-type1 car_parking" type="button" value="3" >3</button>
                                <button class="add-type1 car_parking" type="button" value="3 +">3+</button>
                                <input type="hidden" name="car_parking" id="hidden-car-parking">
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Facing</div>
                            <div class="select-add-type">
                                <div class="form-group">
                                    <select name="facing" id="user_type"
                                        class="form-control chosen-select">
                                        <option value="">Select Facing</option>
                                        @foreach($facing as $orderDetail)
                                        <option value="{{ $orderDetail->facingtype}}" @if(old('facing') !="" && old('facing') == $orderDetail->facingtype) selected @endif>{{ ucfirst($orderDetail->facingtype) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Project Name</div>
                            <div class="select-add-type">
                                <input type="text" autocomplete="off" name="project_name" value="{{old('project_name')}}" id="project-name" class="form-control" placeholder="Enter Project Name" maxlength="70"  required="">
                                <span class="float-right counter-text"> 
                                    <span id="project-name-display" class="tag is-success">0</span>/70
                                </span>
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading"> Ad title *</div>
                            <div class="select-add-type">
                                <input type="text" autocomplete="off" name="ad_title" id="ad_title"
                                    class="form-control" maxlength="70" placeholder="Enter Ad title *" value="{{old('ad_title')}}" required="">
                                <span class="float-right counter-text"> 
                                    <span id="counter-display" class="tag is-success ad_title_counter">0</span>/70
                                </span>
                            </div>
                        </div>
                        <div class="add-type">
                            <div class="add-heading">Description *</div>
                            <div class="select-add-type">
                                <textarea autocomplete="off" maxlength="4096" id="description" class="form-control" name="description" placeholder="Enter Description"
                                    style="height: 96px;" required>{{old('description')}}</textarea>
                                <span class="float-right counter-text"> 
                                    <span id="counter-display" class="tag is-success counter-display-description">0</span>/4096
                                </span>
                            </div>
                        </div>
                        <hr class="add-post-hr">
                        <div class="add-type">
                            <div class="add-heading">Price </div>
                            <div class="select-add-type">
                                <input type="text" autocomplete="off" name="price" id="price"
                                    class="form-control" placeholder="Enter Price *" value="{{old('price')}}" required="">

                            </div>
                            <p style="color:red;">If you dont want to show the price, than input 0 in price field*</p>
                        </div>

                        <hr class="add-post-hr">
                        <div class="add-type">
                            <div class="add-heading price"> Upload up to 5 photos</div>
                            <div class="select-add-type">
								   
									<div class="upload-photo-cont active" id="image">
									     <span class="close closed" style="display:none;cursor: pointer;">&times;</span>
										<div class="sing-img-preview" id="OpenImgUpload" type="file">
										    <img style="height:100px;width:100px;display:none" id="blah" src="#" alt="your image"  />
											<svg width="36px" class="blah" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
												<path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z"></path>
											</svg><br>
											<input type="file" name="file" id="imgupload" style="display:none;" accept="image/jpeg, image/png">
											<span class="text-center" id="add_photo">Add photo</span>
										</div>
									</div>
									<div class="upload-photo-cont" id="image1">
									    <span class="close1 closed" style="display:none;cursor: pointer;">&times;</span>
										<div class="sing-img-preview"  id="Image_1" type="input">
										    
										    <img style="height:100px;width:100px;display:none" id="blah_1" src="#" alt="your image"  />
											<svg width="36px" class="blah_1" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
												<path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z"></path>
											</svg><br>
											 <input type="file" name="file1" id="imgupload_1" style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_1">Add photo</span>
										</div>
									</div>
									<div class="upload-photo-cont" id="image2">
									    <span class="close2 closed" style="display:none;cursor: pointer;">&times;</span>
										<div class="sing-img-preview" type="input" id="Image_2">
										    
										    <img style="height:100px;width:100px;display:none" id="blah_2" src="#" alt="your image"  />
											<svg width="36px" class="blah_2" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
												<path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
												</path>
											</svg><br>
											 <input type="file" name="file2" id="imgupload_2" style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_2" >Add photo</span>
										</div>
									</div>
									<div class="upload-photo-cont"  id="image3">
									    <span class="close3 closed" style="display:none;cursor: pointer;">&times;</span>
										<div class="sing-img-preview" type="input" id="Image_3">
										    
										    <img style="height:100px;width:100px;display:none" id="blah_3" src="#" alt="your image"  />
											<svg width="36px" class="blah_3" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
												<path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z"></path>
											</svg><br>
											 <input type="file" name="file3" id="imgupload_3" style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_3">Add photo</span>
										</div>
									</div>
									<div class="upload-photo-cont" id="image4">
									    <span class="close4 closed" style="display:none;cursor: pointer;">&times;</span>
										<div class="sing-img-preview" type="input" id="Image_4">
										    
										    <img style="height:100px;width:100px;display:none" id="blah_4" src="#" alt="your image"  />
											<svg width="36px" class="blah_4" height="36px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
												<path class="rui-2qwuD" d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z"></path>
											</svg><br>
											 <input type="file" name="file4" id="imgupload_4" style="display:none;" accept="image/jpeg, image/png">
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
                             <div class="add-heading">
                                    Confirm your location
                                </div>

                                <ul class="tabs">

                                    <li class="tab-link current current_list" data-tab="list">

                                        Custom Location

                                    </li>

                                    <li class="tab-link get_current_location" data-tab="c-location">

                                        Current Location

                                    </li>

                                </ul>
                            
                                <div id="list" class="tab-content current">

                                    <div class="row">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Select State</label>

                                                <select name="state" id="state" class="form-control chosen-select">

                                                    @if(old('state') != "")

                                                        <option value="{{old('state')}}">

                                                            {{\App\Models\States::findOrFail(old('state'))->name}}

                                                        </option>

                                                    @else

                                                        <option value="">
                                                            Select State
                                                        </option>

                                                    @endif

                                                    @foreach($state as $orderDetails)

                                                        <option value="{{$orderDetails->id}}">
                                                            {{$orderDetails->name}}
                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Select City</label>

                                                <select name="city" id="city" class="form-control chosen-select select-box">

                                                    @if(old('city') != "")

                                                        <option value="{{old('city')}}">

                                                            {{\App\Models\City::findOrFail(old('city'))->name}}

                                                        </option>

                                                    @else

                                                        <option value="">
                                                            Select City
                                                        </option>

                                                    @endif

                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Neighbourhood *</label>

                                                <input type="text" name="neibourhood" id="neibourhood"
                                                    value="{{old('neibourhood')}}" placeholder="Enter Neighbourhood"
                                                    class="form-control">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div id="c-location" class="tab-content">
                                    <div class="live location">
                                        <ul class="list-style-none">
                                            <li>State<span
                                                    class="float-right state_name">{{ @$locationinfo->regionName}}</span>
                                            </li>
                                            <input type="hidden" class="hidden_state_name" value="" name="state_name">
                                            <li>City<span class="float-right city_name">{{ @$locationinfo->cityName}}</span>
                                            </li>
                                            <input type="hidden" class="hidden_neibourhood" value=""
                                                name="neibourhood_name">
                                            <li>Neighbourhood<span
                                                    class="float-right">{{ @$locationinfo->latitude ?? ''}}{{ $locationinfo->longitude ?? ''}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <hr class="add-post-hr">
                            </div>
                            
                        <input type="hidden"  name="email" id="email" class="form-control" value="{{$userinfo->email}}"  required="" />
                        <input type="hidden"  name="fullname" id="fullname" class="form-control" value="{{$userinfo->name}}"  required="" />
                        <input type="hidden"  name="mobile" id="mobile" class="form-control" value="{{$userinfo->mobile}}"/>
                    
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




<script>

$(document).on("click", ".get_current_location", function(){

    var state_name = $(".state_name").html();

    var city_name = $(".city_name").html();

    $(".hidden_state_name").val(state_name);

    $(".hidden_city_name").val(city_name);

});

$(document).on("click", ".current_list", function(){

    $(".hidden_state_name").val('');

    $(".hidden_city_name").val('');

});

/*
|--------------------------------------------------------------------------
| State Change
|--------------------------------------------------------------------------
*/

$(document).on("change", "#state", function(){

    var state_id = $('#state').val();

    $.ajax({

        url:'{{url("get-city")}}',

        method:'POST',

        data:{
            state_id:state_id,
            '_token':"{{csrf_token()}}"
        },

        success:function(data){

            $('#city').html(data);

            $('#city').trigger("chosen:updated");

        }

    });

});

/*
|--------------------------------------------------------------------------
| City Change
|--------------------------------------------------------------------------
*/

$(document).on("change", "#city", function(){

    var city_id = $('#city').val();

    $.ajax({

        url:'{{url("get-location")}}',

        method:'POST',

        data:{
            city_id:city_id,
            '_token':"{{csrf_token()}}"
        },

        success:function(data){

            $('#neibourhood').html(data);

        }

    });

});

/*
|--------------------------------------------------------------------------
| Restrict Number
|--------------------------------------------------------------------------
*/

function restrictNumber() {

    var newValue = this.value.replace(/[^\d]/ig, "");

    this.value = newValue;

}

$(document).on('input', '#builtup_area', restrictNumber);

$(document).on('input', '#carpet_area', restrictNumber);

$(document).on('input', '#total_floor', restrictNumber);

$(document).on('input', '#floor_no', restrictNumber);

$(document).on('input', '#price', restrictNumber);

/*
|--------------------------------------------------------------------------
| Counters
|--------------------------------------------------------------------------
*/

$(document).on('keyup', '#description', function() {

    var textlen = $(this).val().length;

    $('.counter-display-description').text(textlen);

});

$(document).on('keyup', '#project-name', function() {

    var textlen = $(this).val().length;

    $('#project-name-display').text(textlen);

});

$(document).on('keyup', '#ad_title', function() {

    var textlen = $(this).val().length;

    $('.ad_title_counter').text(textlen);

});

/*
|--------------------------------------------------------------------------
| Property Type
|--------------------------------------------------------------------------
*/

$(document).on("click", ".property_type", function() {

    var id = $(this).val();

    $(".property_type").removeClass("active");

    $(this).addClass('active');

    $("#hidden-property-type").val(id);

});

/*
|--------------------------------------------------------------------------
| Bedroom
|--------------------------------------------------------------------------
*/

$(document).on("click", ".bedroom", function() {

    var id = $(this).val();

    $(".bedroom").removeClass("active");

    $(this).addClass('active');

    $("#hidden-bedroom").val(id);

});

/*
|--------------------------------------------------------------------------
| Bathroom
|--------------------------------------------------------------------------
*/

$(document).on("click", ".bathroom", function() {

    var id = $(this).val();

    $(".bathroom").removeClass("active");

    $(this).addClass('active');

    $("#hidden-bathroom").val(id);

});

/*
|--------------------------------------------------------------------------
| Residence Status
|--------------------------------------------------------------------------
*/

$(document).on("click", ".residence_status", function() {

    var id = $(this).val();

    $(".residence_status").removeClass("active");

    $(this).addClass('active');

    $("#hidden-residence-status").val(id);

});

/*
|--------------------------------------------------------------------------
| Furnishing Status
|--------------------------------------------------------------------------
*/

$(document).on("click", ".furnishing_status", function() {

    var id = $(this).val();

    $(".furnishing_status").removeClass("active");

    $(this).addClass('active');

    $("#hidden-furnishing-status").val(id);

});

/*
|--------------------------------------------------------------------------
| Construction Status
|--------------------------------------------------------------------------
*/

$(document).on("click", ".construction_status", function() {

    var id = $(this).val();

    $(".construction_status").removeClass("active");

    $(this).addClass('active');

    $("#hidden-construction-status").val(id);

});

/*
|--------------------------------------------------------------------------
| Listed By
|--------------------------------------------------------------------------
*/

$(document).on("click", ".listed_by", function() {

    var id = $(this).val();

    $(".listed_by").removeClass("active");

    $(this).addClass('active');

    $("#hidden-listed-by").val(id);

});

/*
|--------------------------------------------------------------------------
| Plot Type
|--------------------------------------------------------------------------
*/

$(document).on("click", ".plot_type", function() {

    var id = $(this).val();

    $(".plot_type").removeClass("active");

    $(this).addClass('active');

    $("#hidden-plot-type").val(id);

});

/*
|--------------------------------------------------------------------------
| Price Mention
|--------------------------------------------------------------------------
*/

$(document).on("click", ".price_mention", function() {

    var id = $(this).val();

    $(".price_mention").removeClass("active");

    $(this).addClass('active');

    $("#hidden-price-mention").val(id);

});

/*
|--------------------------------------------------------------------------
| Car Parking
|--------------------------------------------------------------------------
*/

$(document).on("click", ".car_parking", function() {

    var id = $(this).val();

    $(".car_parking").removeClass("active");

    $(this).addClass('active');

    $("#hidden-car-parking").val(id);

});

/*
|--------------------------------------------------------------------------
| Tabs
|--------------------------------------------------------------------------
*/

$(document).on("click", "ul.tabs li", function(){

    var tab_id = $(this).attr('data-tab');

    $('ul.tabs li').removeClass('current');

    $('.tab-content').removeClass('current');

    $(this).addClass('current');

    $("#" + tab_id).addClass('current');

});


$(document).on("submit", "#login_form", function(e) {

    $(".frontend-error").remove();

    let hasError = false;

    /*
    |--------------------------------------------------------------------------
    | Get Values
    |--------------------------------------------------------------------------
    */

    let property_type = $("#hidden-property-type").val();

    let residence_status = $("#hidden-residence-status").val();

    let furnishing_status = $("#hidden-furnishing-status").val();

    let construction_status = $("#hidden-construction-status").val();

    let listed_by = $("#hidden-listed-by").val();

    let plot_type = $("#hidden-plot-type").val();

    let price_mention = $("#hidden-price-mention").val();

    let car_parking = $("#hidden-car-parking").val();

    let facing = $("select[name='facing']").val();

    let builtup_area = $("#builtup_area").val().trim();

    let carpet_area = $("#carpet_area").val().trim();

    let maintenance = $("input[name='maintenance']").val().trim();

    let total_floor = $("#total_floor").val().trim();

    let floor_no = $("#floor_no").val().trim();

    let project_name = $("#project-name").val().trim();

    let ad_title = $("#ad_title").val().trim();

    let description = $("#description").val().trim();

    let price = $("#price").val().trim();

    let state = $("#state").val();

    let city = $("#city").val();

    let neighbourhood = $("#neibourhood").val().trim();

    let file = $("#imgupload")[0].files.length;

    /*
    |--------------------------------------------------------------------------
    | Property Type
    |--------------------------------------------------------------------------
    */

    if (property_type == '') {

        $(".property_type").last().after(
            '<small class="text-danger frontend-error d-block mt-2">Please select property type</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Residence
    |--------------------------------------------------------------------------
    */

    if (residence_status == '') {

        $(".residence_status").last().after(
            '<small class="text-danger frontend-error d-block mt-2">Please select residence status</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Furnishing
    |--------------------------------------------------------------------------
    */

    if (furnishing_status == '') {

        $(".furnishing_status").last().after(
            '<small class="text-danger frontend-error d-block mt-2">Please select furnishing status</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Construction
    |--------------------------------------------------------------------------
    */

    if (construction_status == '') {

        $(".construction_status").last().after(
            '<small class="text-danger frontend-error d-block mt-2">Please select construction status</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Listed By
    |--------------------------------------------------------------------------
    */

    if (listed_by == '') {

        $(".listed_by").last().after(
            '<small class="text-danger frontend-error d-block mt-2">Please select listed by</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Plot Type
    |--------------------------------------------------------------------------
    */

    if (plot_type == '') {

        $(".plot_type").last().after(
            '<small class="text-danger frontend-error d-block mt-2">Please select plot type</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Price Mention
    |--------------------------------------------------------------------------
    */

    if (price_mention == '') {

        $(".price_mention").last().after(
            '<small class="text-danger frontend-error d-block mt-2">Please select price type</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Car Parking
    |--------------------------------------------------------------------------
    */

    if (car_parking == '') {

        $(".car_parking").last().after(
            '<small class="text-danger frontend-error d-block mt-2">Please select car parking</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Facing
    |--------------------------------------------------------------------------
    */

    if (facing == '') {

        $("select[name='facing']").after(
            '<small class="text-danger frontend-error">Please select facing</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Builtup Area
    |--------------------------------------------------------------------------
    */

    if (builtup_area == '') {

        $("#builtup_area").after(
            '<small class="text-danger frontend-error">Builtup area is required</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Carpet Area
    |--------------------------------------------------------------------------
    */

    if (carpet_area == '') {

        $("#carpet_area").after(
            '<small class="text-danger frontend-error">Carpet area is required</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Project Name
    |--------------------------------------------------------------------------
    */

    if (project_name == '') {

        $("#project-name").after(
            '<small class="text-danger frontend-error">Project name is required</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Ad Title
    |--------------------------------------------------------------------------
    */

    if (ad_title == '') {

        $("#ad_title").after(
            '<small class="text-danger frontend-error">Ad title is required</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    */

    if (description == '') {

        $("#description").after(
            '<small class="text-danger frontend-error">Description is required</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Price
    |--------------------------------------------------------------------------
    */

    if (price == '') {

        $("#price").after(
            '<small class="text-danger frontend-error">Price is required</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Image
    |--------------------------------------------------------------------------
    */

    if (file == 0 && $("#blah").attr('src') == '#') {

        $("#image").after(
            '<small class="text-danger frontend-error d-block">At least 1 image is required</small>'
        );

        hasError = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Location
    |--------------------------------------------------------------------------
    */

    if ($("#list").hasClass("current")) {

        if (state == '') {

            $("#state").after(
                '<small class="text-danger frontend-error">Please select state</small>'
            );

            hasError = true;
        }

        if (city == '') {

            $("#city").after(
                '<small class="text-danger frontend-error">Please select city</small>'
            );

            hasError = true;
        }

        if (neighbourhood == '') {

            $("#neibourhood").after(
                '<small class="text-danger frontend-error">Neighbourhood is required</small>'
            );

            hasError = true;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Stop Submit
    |--------------------------------------------------------------------------
    */

    if (hasError) {

        e.preventDefault();

        $('html, body').animate({

            scrollTop: $(".frontend-error").first().offset().top - 120

        }, 500);

    }

});

</script>