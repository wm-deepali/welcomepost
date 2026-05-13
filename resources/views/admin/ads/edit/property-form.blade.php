@extends('admin.layout.layout')

@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        .content-wrapper {
            background: #f4f6f9;
        }

        .main-card {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
        }

        .stepper-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 35px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .step-item {
            flex: 1;
            min-width: 180px;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 22px;
            right: -50%;
            width: 100%;
            height: 3px;
            background: #dbe2ea;
            z-index: 1;
        }

        .step-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #dbe2ea;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .step-item.active .step-circle {
            background: #007bff;
            color: #fff;
        }

        .step-title {
            font-size: 15px;
            font-weight: 600;
        }

        .step-subtitle {
            font-size: 12px;
            color: #777;
        }

        .card-box {
            background: #fff;
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #edf0f5;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .form-control {
            min-height: 48px;
            border-radius: 10px;
            border: 1px solid #d7dce2;
            box-shadow: none !important;
        }

        textarea.form-control {
            min-height: 130px;
        }

        .btn-theme {
            min-height: 48px;
            border-radius: 10px;
            font-weight: 600;
            padding: 0 25px;
        }

        .customer-card {
            background: #f8fbff;
            border: 1px solid #d9ebff;
            border-radius: 14px;
            padding: 20px;
            display: none;
        }

        .customer-avatar {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: #007bff;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            overflow: hidden;
        }

        .customer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .create-customer-box {
            display: none;
        }

        .category-box {
            display: none;
        }

        .loader-box {
            display: none;
        }

        .upload-photo-cont {
            position: relative;
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 15px;
            vertical-align: top;
        }

        .sing-img-preview {
            width: 140px;
            height: 140px;
            border: 2px dashed #d6dce5;
            border-radius: 14px;
            background: #fafbfd;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            overflow: hidden;
            transition: 0.3s;
            cursor: pointer;
        }

        .sing-img-preview:hover {
            border-color: #007bff;
            background: #f4f8ff;
        }

        .sing-img-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .closed {
            position: absolute;
            right: -5px;
            top: -8px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #ff4d4f;
            color: #fff;
            font-size: 18px;
            text-align: center;
            line-height: 24px;
            z-index: 9;
        }

        @media(max-width:768px) {

            .main-card {
                padding: 20px;
            }

            .step-item {
                min-width: 100%;
            }

            .step-item:not(:last-child)::after {
                display: none;
            }

        }

        .add-post-container {
            background: #fff;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            max-width: 1100px;
            margin: auto;
        }

        .heading h3 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .add-post-hr {
            margin: 30px 0;
            border-top: 1px solid #ececec;
        }

        .add-type {
            margin-bottom: 25px;
        }

        .add-heading {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #333;
        }

        .select-add-type {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .add-type1 {
            border: 1px solid #d7dce2;
            background: #fff;
            color: #333;
            padding: 10px 18px;
            border-radius: 10px;
            transition: 0.3s;
            font-size: 14px;
            font-weight: 500;
        }

        .add-type1:hover {
            background: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        button.add-type1.active {
            background: #007bff !important;
            border-color: #007bff !important;
            color: #fff !important;
        }

        .form-control {
            border-radius: 10px;
            min-height: 48px;
            border: 1px solid #d7dce2;
            box-shadow: none !important;
        }

        textarea.form-control {
            min-height: 130px;
            padding-top: 12px;
        }

        .counter-text {
            font-size: 13px;
            color: #666;
        }

        .upload-photo-cont {
            position: relative;
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 15px;
            vertical-align: top;
        }

        .sing-img-preview {
            width: 140px;
            height: 140px;
            border: 2px dashed #d6dce5;
            border-radius: 14px;
            background: #fafbfd;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            overflow: hidden;
            transition: 0.3s;
            cursor: pointer;
        }

        .sing-img-preview:hover {
            border-color: #007bff;
            background: #f4f8ff;
        }

        .sing-img-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .closed {
            position: absolute;
            right: -5px;
            top: -8px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #ff4d4f;
            color: #fff;
            font-size: 18px;
            text-align: center;
            line-height: 24px;
            z-index: 9;
        }

        .tabs {
            list-style: none;
            display: flex;
            gap: 10px;
            padding: 0;
            margin-bottom: 20px;
        }

        .tabs li {
            background: #eef2f7;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 500;
        }

        .tabs li.current {
            background: #007bff;
            color: #fff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.current {
            display: block;
        }

        .live.location {
            background: #f8f9fb;
            padding: 20px;
            border-radius: 12px;
        }

        .live.location ul {
            padding: 0;
            margin: 0;
        }

        .live.location ul li {
            list-style: none;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .live.location ul li:last-child {
            border-bottom: none;
        }

        .badge-category {
            background: #007bff;
            color: #fff;
            padding: 8px 14px;
            border-radius: 30px;
            font-size: 13px;
            margin-right: 8px;
        }

        .badge-subcategory {
            background: #28a745;
            color: #fff;
            padding: 8px 14px;
            border-radius: 30px;
            font-size: 13px;
        }

        .btn-primary {
            min-height: 52px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .alert-danger {
            border-radius: 10px;
        }

        @media(max-width:768px) {

            .add-post-container {
                padding: 20px;
            }

            .sing-img-preview {
                width: 120px;
                height: 120px;
            }

            .tabs {
                flex-direction: column;
            }

            .tabs li {
                width: 100%;
                text-align: center;
            }

        }
    </style>

    <div class="content-wrapper">

        <section class="content-header">

            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h1 class="m-0">
                            Edit Property Advertisement
                        </h1>

                        <p class="text-muted mb-0">

                            Editing ad for:
                            <strong>{{ $userinfo->name }}</strong>

                        </p>

                    </div>

                    <a href="{{ url()->previous() }}" class="btn btn-secondary">

                        Back

                    </a>

                </div>

            </div>

        </section>

        <section class="content">

            <div class="container-fluid">


                <div class="card shadow-sm mt-4">

                    <div class="card-body">

                        <form id="login_form" name="login_form" method="post"
                            action="{{ url('admin/update-property-form/' . $ad->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="heading t">
                                <h3><b>Include some details</b></h3>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Type*</div>
                                <div class="select-add-type">
                                    <button
                                        class="add-type1 property_type {{ ($formData->property_type ?? '') == 'Apartments' ? 'active' : '' }}"
                                        type="button" value="Apartments">

                                        Apartments

                                    </button>

                                    <button
                                        class="add-type1 property_type {{ ($formData->property_type ?? '') == 'Builder Floors' ? 'active' : '' }}"
                                        type="button" value="Builder Floors">

                                        Builder Floors

                                    </button>

                                    <button
                                        class="add-type1 property_type {{ ($formData->property_type ?? '') == 'Farm House' ? 'active' : '' }}"
                                        type="button" value="Farm House">

                                        Farm House

                                    </button>

                                    <button
                                        class="add-type1 property_type {{ ($formData->property_type ?? '') == 'Houses And Villas' ? 'active' : '' }}"
                                        type="button" value="Houses And Villas">

                                        Houses And Villas

                                    </button>

                                    <button
                                        class="add-type1 property_type {{ ($formData->property_type ?? '') == 'Shops' ? 'active' : '' }}"
                                        type="button" value="Shops">

                                        Shops

                                    </button>

                                    <button
                                        class="add-type1 property_type {{ ($formData->property_type ?? '') == 'Plots' ? 'active' : '' }}"
                                        type="button" value="Plots">

                                        Plots

                                    </button>

                                    <input type="hidden" name="property_type" id="hidden-property-type"
                                        value="{{ $formData->property_type }}">

                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Bedrooms</div>
                                <div class="select-add-type">
                                    <button
                                        class="add-type1 bedroom {{ ($formData->bedroom ?? '') == '1' ? 'active' : '' }}"
                                        type="button" value="1">1</button>
                                    <button
                                        class="add-type1 bedroom {{ ($formData->bedroom ?? '') == '2' ? 'active' : '' }}"
                                        type="button" value="2">2</button>
                                    <button
                                        class="add-type1 bedroom {{ ($formData->bedroom ?? '') == '3' ? 'active' : '' }}"
                                        type="button" value="3">3</button>
                                    <button
                                        class="add-type1 bedroom {{ ($formData->bedroom ?? '') == '4' ? 'active' : '' }}"
                                        type="button" value="4">4</button>
                                    <button
                                        class="add-type1 bedroom {{ ($formData->bedroom ?? '') == '4+' ? 'active' : '' }}"
                                        type="button" value="4+">4+</button>
                                    <input type="hidden" name="bedroom" id="hidden-bedroom"
                                        value="{{ $formData->bedroom }}">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading"> Bathrooms</div>
                                <div class="select-add-type">
                                    <button
                                        class="add-type1 bathroom {{ ($formData->bathroom ?? '') == '1' ? 'active' : '' }}"
                                        type="button" value="1">1</button>
                                    <button
                                        class="add-type1 bathroom {{ ($formData->bathroom ?? '') == '2' ? 'active' : '' }}"
                                        type="button" value="2">2</button>
                                    <button
                                        class="add-type1 bathroom {{ ($formData->bathroom ?? '') == '3' ? 'active' : '' }}"
                                        type="button" value="3">3</button>
                                    <button
                                        class="add-type1 bathroom {{ ($formData->bathroom ?? '') == '4' ? 'active' : '' }}"
                                        type="button" value="4">4</button>
                                    <button
                                        class="add-type1 bathroom {{ ($formData->bathroom ?? '') == '4+' ? 'active' : '' }}"
                                        type="button" value="4+">4+</button>
                                    <input type="hidden" name="bathroom" id="hidden-bathroom"
                                        value="{{ $formData->bathroom }}">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Residence</div>
                                <div class="select-add-type">
                                    @if(isset($residence))
                                        @foreach($residence as $orderDetail)
                                            <button
                                                class="add-type1 residence_status {{ ($formData->residence ?? '') == ucfirst($orderDetail->residencetype) ? 'active' : '' }}"
                                                type="button"
                                                value="{{ ucfirst($orderDetail->residencetype)}}">{{ ucfirst($orderDetail->residencetype)}}</button>
                                        @endforeach
                                    @endif

                                    <input type="hidden" name="residence_status" id="hidden-residence-status"
                                        value="{{ $formData->residence_status }}">
                                </div>
                            </div>

                            <div class="add-type">
                                <div class="add-heading">Furnishing</div>
                                <div class="select-add-type">
                                    @if(isset($furnishing))
                                        @foreach($furnishing as $orderDetail)
                                            <button
                                                class="add-type1 furnishing_status {{ ($formData->furnishing_status ?? '') == ucfirst($orderDetail->furnishingtype) ? 'active' : '' }}"
                                                type="button"
                                                value="{{ ucfirst($orderDetail->furnishingtype)}}">{{ ucfirst($orderDetail->furnishingtype)}}</button>
                                        @endforeach
                                    @endif

                                    <input type="hidden" name="furnishing_status" id="hidden-furnishing-status"
                                        value="{{ $formData->furnishing_status }}">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Construction Status</div>
                                <div class="select-add-type">
                                    @if(isset($construction))
                                        @foreach($construction as $orderDetail)
                                            <button type="button"
                                                class="add-type1 construction_status {{ ($formData->construction_status ?? '') == ucfirst($orderDetail->constructiontype) ? 'active' : '' }}"
                                                value="{{ ucfirst($orderDetail->constructiontype)}}">

                                                {{ ucfirst($orderDetail->constructiontype)}}

                                            </button>
                                        @endforeach
                                    @endif

                                    <input type="hidden" name="construction_status" id="hidden-construction-status"
                                        value="{{ $formData->construction_status }}">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Listed by</div>
                                <div class="select-add-type">
                                    <button
                                        class="add-type1 listed_by {{ ($formData->listed_by ?? '') == 'Builder' ? 'active' : '' }}"
                                        type="button" value="Builder" name="listed_by">Builder</button>
                                    <button
                                        class="add-type1 listed_by {{ ($formData->listed_by ?? '') == 'Dealer' ? 'active' : '' }}"
                                        type="button" value="Dealer" name="listed_by">Dealer</button>
                                    <button
                                        class="add-type1 listed_by {{ ($formData->listed_by ?? '') == 'Owner' ? 'active' : '' }}"
                                        type="button" value="Owner" name="listed_by">Owner</button>
                                    <input type="hidden" name="listed_by" id="hidden-listed-by"
                                        value="{{ $formData->listed_by }}">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Plot Type</div>
                                <div class="select-add-type">
                                    <button
                                        class="add-type1 plot_type {{ ($formData->plot_type ?? '') == 'Residential' ? 'active' : '' }}"
                                        type="button" value="Residential" name="plot_type">Residential</button>
                                    <button
                                        class="add-type1 plot_type {{ ($formData->plot_type ?? '') == 'Commercial' ? 'active' : '' }}"
                                        type="button" value="Commercial" name="plot_type">Commercial</button>
                                    <button
                                        class="add-type1 plot_type {{ ($formData->plot_type ?? '') == 'Agricultural' ? 'active' : '' }}"
                                        type="button" value="Agricultural" name="plot_type">Agricultural</button>
                                    <button
                                        class="add-type1 plot_type {{ ($formData->plot_type ?? '') == 'Industrial' ? 'active' : '' }}"
                                        type="button" value="Industrial" name="plot_type">Industrial</button>
                                    <input type="hidden" name="plot_type" id="hidden-plot-type"
                                        value="{{ $formData->plot_type }}">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Price for Month</div>
                                <div class="select-add-type">
                                    <button
                                        class="add-type1 price_mention {{ ($formData->price_mention ?? '') == 'Sell' ? 'active' : '' }}"
                                        type="button" value="Sell" name="price_mention">Sell</button>
                                    <button
                                        class="add-type1 price_mention {{ ($formData->price_mention ?? '') == 'Rent' ? 'active' : '' }}"
                                        type="button" value="Rent" name="price_mention">Rent</button>

                                    <input type="hidden" name="price_mention" id="hidden-price-mention"
                                        value="{{ $formData->price_mention }}">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Super Builtup area (ft²) *</div>
                                <div class="select-add-type">
                                    <input type="text" autocomplete="off" name="builtup_area" id="builtup_area"
                                        class="form-control" placeholder="Enter Super Builtup Area*"
                                        value="{{ $formData->builtup_area }}" required="">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Carpet Area (ft²) *</div>
                                <div class="select-add-type">
                                    <input type="text" autocomplete="off" name="carpet_area" id="carpet_area"
                                        value="{{ $formData->carpet_area }}" class="form-control"
                                        placeholder="Enter Carpet Area*" required="">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Maintenance (Monthly)</div>
                                <div class="select-add-type">
                                    <input type="text" autocomplete="off" name="maintenance" id="builduparea"
                                        value="{{ $formData->maintenance }}" class="form-control"
                                        placeholder="Enter Maintenance (Monthly)" required="">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Total Floors</div>
                                <div class="select-add-type">
                                    <input type="text" autocomplete="off" name="total_floor" id="total_floor"
                                        value="{{ $formData->total_floor }}" class="form-control"
                                        placeholder="Enter Total Floors " required="">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Floor No</div>
                                <div class="select-add-type">
                                    <input type="text" autocomplete="off" name="floor_no" id="floor_no"
                                        value="{{ $formData->floor_no }}" class="form-control" placeholder="Enter Floor No"
                                        required="">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Car Parking</div>
                                <div class="select-add-type">
                                    <button
                                        class="add-type1 car_parking {{ ($formData->car_parking ?? '') == '0' ? 'active' : '' }}"
                                        type="button" value="0">0</button>
                                    <button
                                        class="add-type1 car_parking {{ ($formData->car_parking ?? '') == '1' ? 'active' : '' }}"
                                        type="button" value="1">1</button>
                                    <button
                                        class="add-type1 car_parking {{ ($formData->car_parking ?? '') == '2' ? 'active' : '' }}"
                                        type="button" value="2">2</button>
                                    <button
                                        class="add-type1 car_parking {{ ($formData->car_parking ?? '') == '3' ? 'active' : '' }}"
                                        type="button" value="3">3</button>
                                    <button
                                        class="add-type1 car_parking {{ ($formData->car_parking ?? '') == '3+' ? 'active' : '' }}"
                                        type="button" value="3+">3+</button>
                                    <input type="hidden" name="car_parking" id="hidden-car-parking"
                                        value="{{ $formData->car_parking }}">
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Facing</div>
                                <div class="select-add-type">
                                    <div class="form-group">
                                        <select name="facing" id="user_type" class="form-control chosen-select">
                                            <option value="">Select Facing</option>
                                            @foreach($facing as $orderDetail)
                                                <option value="{{ $orderDetail->facingtype}}" {{ ($formData->facing ?? '') == $orderDetail->facingtype ? 'selected' : '' }}>
                                                    {{ ucfirst($orderDetail->facingtype) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Project Name</div>
                                <div class="select-add-type">
                                    <input type="text" autocomplete="off" name="project_name"
                                        value="{{ $formData->project_name }}" id="project-name" class="form-control"
                                        placeholder="Enter Project Name" maxlength="70" required="">
                                    <span class="float-right counter-text">
                                        <span id="project-name-display" class="tag is-success">0</span>/70
                                    </span>
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading"> Ad title *</div>
                                <div class="select-add-type">
                                    <input type="text" autocomplete="off" name="ad_title" id="ad_title" class="form-control"
                                        maxlength="70" placeholder="Enter Ad title *" value="{{ $formData->ad_title }}"
                                        required="">
                                    <span class="float-right counter-text">
                                        <span id="counter-display" class="tag is-success ad_title_counter">0</span>/70
                                    </span>
                                </div>
                            </div>
                            <div class="add-type">
                                <div class="add-heading">Description *</div>
                                <div class="select-add-type">
                                    <textarea autocomplete="off" maxlength="4096" id="description" class="form-control"
                                        name="description" placeholder="Enter Description" style="height: 96px;"
                                        required>{{ $formData->description }}</textarea>
                                    <span class="float-right counter-text">
                                        <span id="counter-display"
                                            class="tag is-success counter-display-description">0</span>/4096
                                    </span>
                                </div>
                            </div>
                            <hr class="add-post-hr">
                            <div class="add-type">
                                <div class="add-heading">Price </div>
                                <div class="select-add-type">
                                    <input type="text" autocomplete="off" name="price" id="price" class="form-control"
                                        placeholder="Enter Price *" value="{{ $formData->price }}" required="">

                                </div>
                                <p style="color:red;">If you dont want to show the price, than input 0 in price field*</p>
                            </div>

                            <hr class="add-post-hr">
                            <div class="add-type">

                                <div class="d-flex flex-wrap">
                                    @if(isset($images) && count($images) > 0)

                                        <div class="mb-4">

                                            <label class="font-weight-bold d-block mb-3">

                                                Existing Images

                                            </label>

                                            @foreach($images as $img)

                                                <img src="{{ $img->image }}" style="width:120px;
                                                         height:120px;
                                                         object-fit:cover;
                                                         border-radius:10px;
                                                         margin-right:10px;
                                                         margin-bottom:10px;
                                                         border:1px solid #ddd;">

                                            @endforeach

                                        </div>

                                    @endif
                                </div>

                                <div class="add-heading price"> Upload up to 5 photos</div>
                                <div class="select-add-type">

                                    <div class="upload-photo-cont active" id="image">
                                        <span class="close closed" style="display:none;cursor: pointer;">&times;</span>
                                        <div class="sing-img-preview" id="OpenImgUpload" type="file">
                                            <img style="height:100px;width:100px;display:none" id="blah" src="#"
                                                alt="your image" />
                                            <svg width="36px" class="blah" height="36px" viewBox="0 0 1024 1024"
                                                data-aut-id="icon" class="" fill-rule="evenodd">
                                                <path class="rui-2qwuD"
                                                    d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                </path>
                                            </svg><br>
                                            <input type="file" name="file" id="imgupload" style="display:none;"
                                                accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo">Add photo</span>
                                        </div>
                                    </div>
                                    <div class="upload-photo-cont" id="image1">
                                        <span class="close1 closed" style="display:none;cursor: pointer;">&times;</span>
                                        <div class="sing-img-preview" id="Image_1" type="input">

                                            <img style="height:100px;width:100px;display:none" id="blah_1" src="#"
                                                alt="your image" />
                                            <svg width="36px" class="blah_1" height="36px" viewBox="0 0 1024 1024"
                                                data-aut-id="icon" class="" fill-rule="evenodd">
                                                <path class="rui-2qwuD"
                                                    d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                </path>
                                            </svg><br>
                                            <input type="file" name="file1" id="imgupload_1" style="display:none;"
                                                accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_1">Add photo</span>
                                        </div>
                                    </div>
                                    <div class="upload-photo-cont" id="image2">
                                        <span class="close2 closed" style="display:none;cursor: pointer;">&times;</span>
                                        <div class="sing-img-preview" type="input" id="Image_2">

                                            <img style="height:100px;width:100px;display:none" id="blah_2" src="#"
                                                alt="your image" />
                                            <svg width="36px" class="blah_2" height="36px" viewBox="0 0 1024 1024"
                                                data-aut-id="icon" class="" fill-rule="evenodd">
                                                <path class="rui-2qwuD"
                                                    d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                </path>
                                            </svg><br>
                                            <input type="file" name="file2" id="imgupload_2" style="display:none;"
                                                accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_2">Add photo</span>
                                        </div>
                                    </div>
                                    <div class="upload-photo-cont" id="image3">
                                        <span class="close3 closed" style="display:none;cursor: pointer;">&times;</span>
                                        <div class="sing-img-preview" type="input" id="Image_3">

                                            <img style="height:100px;width:100px;display:none" id="blah_3" src="#"
                                                alt="your image" />
                                            <svg width="36px" class="blah_3" height="36px" viewBox="0 0 1024 1024"
                                                data-aut-id="icon" class="" fill-rule="evenodd">
                                                <path class="rui-2qwuD"
                                                    d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                </path>
                                            </svg><br>
                                            <input type="file" name="file3" id="imgupload_3" style="display:none;"
                                                accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_3">Add photo</span>
                                        </div>
                                    </div>
                                    <div class="upload-photo-cont" id="image4">
                                        <span class="close4 closed" style="display:none;cursor: pointer;">&times;</span>
                                        <div class="sing-img-preview" type="input" id="Image_4">

                                            <img style="height:100px;width:100px;display:none" id="blah_4" src="#"
                                                alt="your image" />
                                            <svg width="36px" class="blah_4" height="36px" viewBox="0 0 1024 1024"
                                                data-aut-id="icon" class="" fill-rule="evenodd">
                                                <path class="rui-2qwuD"
                                                    d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                </path>
                                            </svg><br>
                                            <input type="file" name="file4" id="imgupload_4" style="display:none;"
                                                accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_4">Add photo</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-type">
                                <span class="text-danger">Upload images only if you want to change them</span>
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

                                                    @if($formData->state != "")

                                                        <option selected value="{{ $formData->state }}">

                                                            {{ \App\Models\States::find($formData->state)->name ?? '' }}

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

                                                    @if($formData->city != "")

                                                        <option selected value="{{ $formData->city }}">

                                                            {{ \App\Models\City::find($formData->city)->name ?? '' }}

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
                                                    value="{{ $formData->neibourhood }}" placeholder="Enter Neighbourhood"
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

                            <input type="hidden" name="email" id="email" class="form-control" value="{{$userinfo->email}}"
                                required="" />
                            <input type="hidden" name="fullname" id="fullname" class="form-control"
                                value="{{$userinfo->name}}" required="" />
                            <input type="hidden" name="mobile" id="mobile" class="form-control"
                                value="{{$userinfo->mobile}}" />

                            <input type="hidden" name="location" id="location" class="form-control"
                                value="{{$userinfo->address}}" required="" />

                            <input type="hidden" name="user_id" id="location" class="form-control" value="{{$userinfo->id}}"
                                required="" />
                            <input type="hidden" name="formtype" id="location" class="form-control" value="{{$form_id}}"
                                required="" />
                            <input type="hidden" name="category_id" id="location" class="form-control"
                                value="{{$categoryid}}" required="" />
                            <input type="hidden" name="subcatid" id="location" class="form-control" value="{{$subcatid}}"
                                required="" />
                            <div class="select-add-type">
                                <button type="submit" name="login_submit" value="submit"
                                    class="btn btn-primary form-control">
                                    Update Advertisement
                                </button>
                            </div>
                        </form>


                    </div>
                </div>

            </div>

        </section>

    </div>

    <script>

        $(document).ready(function () {

            var textlen = $('#description').val().length;

            $('.counter-display-description').text(textlen);

            $('#project-name-display').text($('#project-name').val().length);

            $('.ad_title_counter').text($('#ad_title').val().length);

        });
        $(document).on("click", ".get_current_location", function () {

            var state_name = $(".state_name").html();

            var city_name = $(".city_name").html();

            $(".hidden_state_name").val(state_name);

            $(".hidden_city_name").val(city_name);

        });

        $(document).on("click", ".current_list", function () {

            $(".hidden_state_name").val('');

            $(".hidden_city_name").val('');

        });

        /*
        |--------------------------------------------------------------------------
        | State Change
        |--------------------------------------------------------------------------
        */

        $(document).on("change", "#state", function () {

            var state_id = $('#state').val();

            $.ajax({

                url: '{{url("get-city")}}',

                method: 'POST',

                data: {
                    state_id: state_id,
                    '_token': "{{csrf_token()}}"
                },

                success: function (data) {

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

        $(document).on("change", "#city", function () {

            var city_id = $('#city').val();

            $.ajax({

                url: '{{url("get-location")}}',

                method: 'POST',

                data: {
                    city_id: city_id,
                    '_token': "{{csrf_token()}}"
                },

                success: function (data) {

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

        $(document).on('keyup', '#description', function () {

            var textlen = $(this).val().length;

            $('.counter-display-description').text(textlen);

        });

        $(document).on('keyup', '#project-name', function () {

            var textlen = $(this).val().length;

            $('#project-name-display').text(textlen);

        });

        $(document).on('keyup', '#ad_title', function () {

            var textlen = $(this).val().length;

            $('.ad_title_counter').text(textlen);

        });

        /*
        |--------------------------------------------------------------------------
        | Property Type
        |--------------------------------------------------------------------------
        */

        $(document).on("click", ".property_type", function () {

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

        $(document).on("click", ".bedroom", function () {

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

        $(document).on("click", ".bathroom", function () {

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

        $(document).on("click", ".residence_status", function () {

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

        $(document).on("click", ".furnishing_status", function () {

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

        $(document).on("click", ".construction_status", function () {

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

        $(document).on("click", ".listed_by", function () {

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

        $(document).on("click", ".plot_type", function () {

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

        $(document).on("click", ".price_mention", function () {

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

        $(document).on("click", ".car_parking", function () {

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

        $(document).on("click", "ul.tabs li", function () {

            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('current');

            $('.tab-content').removeClass('current');

            $(this).addClass('current');

            $("#" + tab_id).addClass('current');

        });

    </script>

    <script>

        $(document).on('click', '#OpenImgUpload', function (e) {

            $('#imgupload').trigger('click');
        });

        $(document).on('click', '#Image_1', function () {

            $('#imgupload_1').trigger('click');

        });

        $(document).on('click', '#Image_2', function () {

            $('#imgupload_2').trigger('click');

        });

        $(document).on('click', '#Image_3', function () {

            $('#imgupload_3').trigger('click');

        });

        $(document).on('click', '#Image_4', function () {

            $('#imgupload_4').trigger('click');

        });

        $(document).on('click', '.close', function () {
            $(this).css('display', 'none');
            $('#blah').css('display', 'none');
            $('.blah').css('display', 'block');
            $('#add_photo').css('display', 'block');
        });

        $(document).on('click', '.close1', function () {
            $(this).css('display', 'none');
            $('#blah_1').css('display', 'none');
            $('.blah_1').css('display', 'block');
            $('#add_photo_1').css('display', 'block');
        });

        $(document).on('click', '.close2', function () {
            $(this).css('display', 'none');
            $('#blah_2').css('display', 'none');
            $('.blah_2').css('display', 'block');
            $('#add_photo_2').css('display', 'block');
        });

        $(document).on('click', '.close3', function () {
            $(this).css('display', 'none');
            $('#blah_3').css('display', 'none');
            $('.blah_3').css('display', 'block');
            $('#add_photo_3').css('display', 'block');
        });

        $(document).on('click', '.close4', function () {
            $(this).css('display', 'none');
            $('#blah_4').css('display', 'none');
            $('.blah_4').css('display', 'block');
            $('#add_photo_4').css('display', 'block');
        });

        $(document).on('change', '#imgupload', function () {
            const file = this.files[0];
            $('#blah').css('display', 'block');
            $('.close').first().css('display', 'block');
            $('.blah').css('display', 'none');
            $('#add_photo').css('display', 'none');
            if (file) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $('body #blah').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        $(document).on('change', '#imgupload_1', function () {
            const file = this.files[0];
            $('#blah_1').css('display', 'block');
            $('.close1').first().css('display', 'block');
            $('.blah_1').css('display', 'none');
            $('#add_photo_1').css('display', 'none');
            if (file) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $('body #blah_1').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        $(document).on('change', '#imgupload_2', function () {
            const file = this.files[0];
            $('#blah_2').css('display', 'block');
            $('.close2').first().css('display', 'block');
            $('.blah_2').css('display', 'none');
            $('#add_photo_2').css('display', 'none');
            if (file) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $('body #blah_2').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        $(document).on('change', '#imgupload_3', function () {
            const file = this.files[0];
            $('#blah_3').css('display', 'block');
            $('.close3').first().css('display', 'block');
            $('.blah_3').css('display', 'none');
            $('#add_photo_3').css('display', 'none');
            if (file) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $('body #blah_3').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        $(document).on('change', '#imgupload_4', function () {
            const file = this.files[0];
            $('#blah_4').css('display', 'block');
            $('.close4').first().css('display', 'block');
            $('.blah_4').css('display', 'none');
            $('#add_photo_4').css('display', 'none');
            if (file) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $('body #blah_4').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

    </script>
@endsection