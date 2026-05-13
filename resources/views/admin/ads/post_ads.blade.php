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

                <div class="main-card">

                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

                        <div>

                            <h3 class="mb-1">
                                Post New Advertisement
                            </h3>

                            <p class="text-muted mb-0">
                                Search customer, select category and publish ad
                            </p>

                        </div>

                    </div>


                    {{-- STEP 1 --}}
                    <div class="card-box">

                        <div class="section-title">
                            Find Customer
                        </div>

                        <div class="row">

                            <div class="col-md-5">

                                <div class="form-group">

                                    <label>
                                        Mobile Number
                                    </label>

                                    <input type="text" id="customer_mobile" class="form-control"
                                        placeholder="Enter mobile number">

                                </div>

                            </div>

                            <div class="col-md-5">

                                <div class="form-group">

                                    <label>
                                        Email Address
                                    </label>

                                    <input type="email" id="customer_email" class="form-control" placeholder="Enter email">

                                </div>

                            </div>

                            <div class="col-md-2 d-flex align-items-center">

                                <button type="button" class="btn btn-primary btn-theme btn-block" id="findCustomerBtn">

                                    Find Customer

                                </button>

                            </div>

                        </div>

                        {{-- Customer Found --}}
                        <div class="customer-card mt-4" id="customerCard">

                            <div class="d-flex align-items-center">

                                <div class="customer-avatar mr-3" id="customerAvatar">
                                    D
                                </div>

                                <div>

                                    <h5 class="mb-1" id="customerName">
                                        Deepali Garg
                                    </h5>

                                    <div class="text-muted">

                                        <span id="customerMobile"></span>

                                        |

                                        <span id="customerEmail"></span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Create Customer --}}
                        <div class="create-customer-box mt-4" id="createCustomerBox">

                            <div class="alert alert-warning">

                                Customer not found.
                                Please create a new customer.

                            </div>

                            <form id="createCustomerForm">

                                @csrf

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Full Name
                                            </label>

                                            <input type="text" name="name" class="form-control" required>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Mobile
                                            </label>

                                            <input type="text" name="mobile" class="form-control" id="new_mobile" required>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Email
                                            </label>

                                            <input type="email" name="email" class="form-control" id="new_email">

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Password
                                            </label>

                                            <input type="text" name="password" class="form-control"
                                                placeholder="Leave blank for auto">

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                State
                                            </label>

                                            <select name="customer_state" class="form-control customer_state" required>

                                                <option value="">
                                                    Select State
                                                </option>

                                                @foreach($states as $state)

                                                    <option value="{{ $state->id }}">
                                                        {{ $state->name }}
                                                    </option>

                                                @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                City
                                            </label>

                                            <select name="customer_city" id="customer_city" class="form-control" required>

                                                <option value="">
                                                    Select City
                                                </option>

                                            </select>

                                        </div>

                                    </div>

                                </div>

                                <button type="submit" class="btn btn-success btn-theme">

                                    Create Customer & Continue

                                </button>

                            </form>

                        </div>

                    </div>

                    {{-- STEP 2 --}}
                    <div class="card-box category-box" id="categoryBox">

                        <div class="section-title">
                            Select Category & Subcategory
                        </div>

                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Category
                                    </label>

                                    <select id="category_id" class="form-control">

                                        <option value="">
                                            Select Category
                                        </option>

                                        @foreach($allcategories as $category)

                                            <option value="{{ $category->id }}" data-formtype="{{ $category->formtype }}">

                                                {{ $category->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Subcategory
                                    </label>

                                    <select id="subcategory_id" class="form-control">

                                        <option value="">
                                            Select Subcategory
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Loader --}}
                    <div class="loader-box" id="loaderBox">

                        <div class="card shadow-sm mt-4">

                            <div class="card-body text-center p-5">

                                <div class="spinner-border text-primary"></div>

                                <p class="mt-3 mb-0">
                                    Loading Form...
                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- Dynamic Form --}}
                    <div id="dynamicFormArea"></div>

                </div>

            </div>

        </section>

    </div>

    <script>

        let selectedUserId = '';

        /*
        |--------------------------------------------------------------------------
        | Subcategories
        |--------------------------------------------------------------------------
        */

        let subcategories = {

            @foreach($allcategories as $category)

                "{{ $category->id }}": [

                @php

                    $subcats = DB::table('subcategories')
                        ->where('category_id', $category->id)
                        ->where('delete_status', 0)
                        ->orderBy('name', 'ASC')
                        ->get();

                @endphp

                    @foreach($subcats as $sub)

                                {
                            id: "{{ $sub->id }}",
                            name: "{{ $sub->name }}"
                        },

                    @endforeach

                ],

            @endforeach

        };

        /*
        |--------------------------------------------------------------------------
        | Find Customer
        |--------------------------------------------------------------------------
        */

        $("#findCustomerBtn").click(function () {

            let mobile = $("#customer_mobile").val();
            let email = $("#customer_email").val();

            $.ajax({

                url: "{{ url('admin/find-customer') }}",

                type: "POST",

                data: {
                    mobile: mobile,
                    email: email,
                    _token: "{{ csrf_token() }}"
                },

                success: function (response) {

                    if (response.status == true) {

                        selectedUserId = response.customer.id;

                        $("#customerName").text(response.customer.name);

                        $("#customerMobile").text(response.customer.mobile);

                        $("#customerEmail").text(response.customer.email);

                        if (response.customer.image) {

                            $("#customerAvatar").html(`
                                <img src="${response.customer.image}">
                            `);

                        } else {

                            $("#customerAvatar").html(
                                response.customer.name.charAt(0).toUpperCase()
                            );

                        }

                        $("#customerCard").show();

                        $("#createCustomerBox").hide();

                        $("#categoryBox").show();

                        $("#step2").addClass('active');

                    } else {

                        $("#customerCard").hide();

                        $("#createCustomerBox").show();

                        $("#categoryBox").hide();

                        $("#new_mobile").val(mobile);

                        $("#new_email").val(email);

                    }

                }

            });

        });

        /*
        |--------------------------------------------------------------------------
        | Create Customer
        |--------------------------------------------------------------------------
        */

        $("#createCustomerForm").submit(function (e) {

            e.preventDefault();

            $(".validation-error").remove();

            $.ajax({

                url: "{{ route('admin.create.customer.for.ad') }}",

                type: "POST",

                data: $(this).serialize(),

                success: function (response) {

                    if (response.status == false) {

                        $.each(response.errors, function (key, value) {

                            $('[name="' + key + '"]')
                                .after(
                                    '<small class="text-danger validation-error">' + value[0] + '</small>'
                                );

                        });

                        return;
                    }

                    selectedUserId = response.customer.id;

                    $("#customerName").text(response.customer.name);

                    $("#customerMobile").text(response.customer.mobile);

                    $("#customerEmail").text(response.customer.email);

                    if (response.customer.image) {

                        $("#customerAvatar").html(`
                        <img src="${response.customer.image}">
                    `);

                    } else {

                        $("#customerAvatar").html(
                            response.customer.name.charAt(0).toUpperCase()
                        );

                    }

                    $("#customerCard").show();

                    $("#createCustomerBox").hide();

                    $("#categoryBox").show();

                    $("#step2").addClass('active');

                },

                error: function () {

                    alert('Something went wrong');

                }

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Load Cities
        |--------------------------------------------------------------------------
        */

        $(document).on("change", ".customer_state", function () {

            let state_id = $(this).val();

            $.ajax({

                url: "{{ url('cities-by-state') }}",

                type: "POST",

                data: {
                    state_id: state_id,
                    _token: "{{ csrf_token() }}"
                },

                success: function (result) {

                    $("#customer_city").html(result);

                }

            });

        });

        /*
        |--------------------------------------------------------------------------
        | Load Subcategories
        |--------------------------------------------------------------------------
        */

        $("#category_id").change(function () {

            let category_id = $(this).val();

            let options = `
                <option value="">
                    Select Subcategory
                </option>
            `;

            $("#dynamicFormArea").html('');

            if (subcategories[category_id]) {

                subcategories[category_id].forEach(function (sub) {

                    options += `
                        <option value="${sub.id}">
                            ${sub.name}
                        </option>
                    `;

                });

            }

            $("#subcategory_id").html(options);

        });

        /*
        |--------------------------------------------------------------------------
        | Load Dynamic Form
        |--------------------------------------------------------------------------
        */

        $("#subcategory_id").change(function () {

            let subcategory_id = $(this).val();

            let category_id = $("#category_id").val();

            let formtype = $("#category_id option:selected")
                .data("formtype");

            if (subcategory_id == '') {

                $("#dynamicFormArea").html('');

                return;
            }

            $("#loaderBox").show();

            $("#dynamicFormArea").html('');

            $.ajax({

                url: "{{ url('admin/render-ad-form') }}",

                type: "POST",

                data: {

                    formtype: formtype,
                    category_id: category_id,
                    subcategory_id: subcategory_id,
                    user_id: selectedUserId,
                    _token: "{{ csrf_token() }}"

                },

                success: function (response) {

                    $("#loaderBox").hide();

                    $("#dynamicFormArea").html(response);

                    $("#step3").addClass('active');

                    $('html, body').animate({

                        scrollTop:
                            $("#dynamicFormArea").offset().top - 80

                    }, 500);

                },

                error: function () {

                    $("#loaderBox").hide();

                    alert('Something went wrong');

                }

            });

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