@extends('admin.layout.layout')
@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/default-skin/default-skin.css">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 d-flex align-items-center">

                        <h1 class="mb-0">
                            Post Ads
                        </h1>

                        <a href="{{ route('admin.post.ads') }}" class="btn btn-primary ml-3">

                            <i class="fa fa-plus"></i>

                            Add Post

                        </a>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Post Ads</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#pending"
                                            data-toggle="tab">Pending</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#published"
                                            data-toggle="tab">Published</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#rejected" data-toggle="tab">Rejected</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="pending">
                                        <section class="content">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <!-- /.card-header -->
                                                            <div class="card-body">
                                                                <table id="example1"
                                                                    class="table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>S.No</th>
                                                                            <th>Image</th>
                                                                            <th>Name</th>
                                                                            <th>Mobile</th>
                                                                            <th>Category</th>
                                                                            <th>Sub Category</th>
                                                                            <th>Ad Title</th>
                                                                            <th>Ad type</th>
                                                                            <th>Status</th>
                                                                            <th>Description</th>
                                                                            <th>Price</th>
                                                                            <th>Address</th>
                                                                            <th>Count of images used</th>
                                                                            <th>Registered Date</th>
                                                                            <th>Reject</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($pending as $key => $orderDetails)
                                                                                                                                            @php
                                                                                                                                                $customer = App\Models\Customer::with('states', 'cities')->find($orderDetails->user_id);
                                                                                                                                                $imageCount = App\Models\AdPostingImage::where('ads_id', $orderDetails->ad_id)->count();
                                                                                                                                                $adImages = App\Models\AdPostingImage::where('ads_id', $orderDetails->ad_id)->orderBy('id', 'asc')->get();
                                                                                                                                            @endphp
                                                                                                                                            <tr>
                                                                                                                                                <td>{{$key + 1}}</td>
                                                                                                                                                <td><img src="{{ $orderDetails->image }}"
                                                                                                                                                        class="view-image"
                                                                                                                                                        data-ad-id="{{$orderDetails->ad_id}}"
                                                                                                                                                        style="height:50px;width:50px;"></td>
                                                                                                                                                @foreach($adImages as $imageAd)
                                                                                                                                                    <img src="{{$imageAd->image}}"
                                                                                                                                                        class="view-image"
                                                                                                                                                        data-ad-id="{{$orderDetails->ad_id}}"
                                                                                                                                                        style="height:0px;width:0px;">
                                                                                                                                                @endforeach

                                                                                                                                                <td>{{$orderDetails->fullname}}</td>
                                                                                                                                                <td>{{$orderDetails->mobile}}</td>
                                                                                                                                                <td>
                                                                                                                                                    <?php
                                                                            $result = DB::table('categories')->where('id', $orderDetails->category_id)->get();
                                                                            echo $result[0]->name;
                                                                                                                                                                                                                                                                                    ?>
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                    <?php
                                                                            $result = DB::table('subcategories')->where('id', $orderDetails->sub_category_id)->get();
                                                                            echo $result[0]->name;
                                                                                                                                                                                                                                                                                    ?>
                                                                                                                                                </td>
                                                                                                                                                <td>{{$orderDetails->ad_title}}</td>
                                                                                                                                                <td>{{$orderDetails->ad_type}}</td>
                                                                                                                                                <td width="15%">
                                                                                                                                                    <select name="document"
                                                                                                                                                        onchange="changeState(this, {{ $orderDetails->id }})"
                                                                                                                                                        id="status{{ $orderDetails->id }}"
                                                                                                                                                        class="form-control"
                                                                                                                                                        data-id="{{ $orderDetails->id }}">
                                                                                                                                                        <option value="0" {{ $orderDetails->status == 0 ? 'selected' : '' }}>Pending</option>
                                                                                                                                                        <option value="1" {{ $orderDetails->status == 1 ? 'selected' : '' }}>Publish</option>
                                                                                                                                                        <option value="2" {{ $orderDetails->status == 2 ? 'selected' : '' }}>Reject</option>
                                                                                                                                                    </select>
                                                                                                                                                </td>
                                                                                                                                                <td>{{$orderDetails->description}}</td>
                                                                                                                                                <td>{{$orderDetails->price}}</td>
                                                                                                                                                <td>
                                                                                                                                                    {{ $customer->address ?? "--" }},
                                                                                                                                                    {{ $customer->cities->name ?? '--'}}
                                                                                                                                                    {{ $customer->states->name ?? '--' }}
                                                                                                                                                </td>
                                                                                                                                                <td>{{$imageCount}}</td>
                                                                                                                                                <input type="hidden"
                                                                                                                                                    id="statusval<?php    echo $orderDetails->id;?>"
                                                                                                                                                    value="<?php    echo $orderDetails->status; ?>">
                                                                                                                                                <input type="hidden"
                                                                                                                                                    id="proff_id<?php    echo $orderDetails->id;?>"
                                                                                                                                                    value="<?php    echo $orderDetails->id; ?>">
                                                                                                                                                <td>{{$orderDetails->created_at}}</td>
                                                                                                                                                <td>
                                                                                                                                                    <button type="button" class="btn btn-danger"
                                                                                                                                                        id="reject-button-{{ $orderDetails->id }}"
                                                                                                                                                        data-toggle="modal"
                                                                                                                                                        data-target="#rejectreason{{ $orderDetails->id }}">Reject</button>
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                    <div class="dropdown">
                                                                                                                                                        <button
                                                                                                                                                            class="btn btn-primary btn-sm dropdown-toggle"
                                                                                                                                                            type="button"
                                                                                                                                                            data-toggle="dropdown">
                                                                                                                                                            Actions
                                                                                                                                                        </button>

                                                                                                                                                        <div
                                                                                                                                                            class="dropdown-menu dropdown-menu-right">

                                                                                                                                                            <a class="dropdown-item"
                                                                                                                                                                href="{{ route('admin.view.ad', $orderDetails->id) }}">
                                                                                                                                                                <i class="fa fa-eye mr-2"></i>
                                                                                                                                                                View
                                                                                                                                                            </a>

                                                                                                                                                            <a class="dropdown-item"
                                                                                                                                                                href="{{ url('admin/edit-ad/' . $orderDetails->id) }}">
                                                                                                                                                                <i class="fa fa-edit mr-2"></i>
                                                                                                                                                                Edit
                                                                                                                                                            </a>

                                                                                                                                                            <a class="dropdown-item text-danger"
                                                                                                                                                                href="{{ url('admin/delete-ad/' . $orderDetails->id) }}"
                                                                                                                                                                onclick="return confirm('Are you sure you want to delete this advertisement?')">
                                                                                                                                                                <i class="fa fa-trash mr-2"></i>
                                                                                                                                                                Delete
                                                                                                                                                            </a>

                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </td>
                                                                                                                                            </tr>

                                                                                                                                            <div class="modal fade"
                                                                                                                                                id="rejectreason<?php    echo $orderDetails->id; ?>">
                                                                                                                                                <div class="modal-dialog">
                                                                                                                                                    <form action="{{url('reject-post')}}"
                                                                                                                                                        role="form" id="quickForm"
                                                                                                                                                        method="post">
                                                                                                                                                        <div class="modal-content">
                                                                                                                                                            <div class="modal-header">
                                                                                                                                                                <h4 class="modal-title">Alert
                                                                                                                                                                </h4>
                                                                                                                                                                <button type="button"
                                                                                                                                                                    class="close"
                                                                                                                                                                    data-dismiss="modal"
                                                                                                                                                                    aria-label="Close">
                                                                                                                                                                    <span
                                                                                                                                                                        aria-hidden="true">&times;</span>
                                                                                                                                                                </button>
                                                                                                                                                            </div>
                                                                                                                                                            @csrf
                                                                                                                                                            <div class="modal-body">
                                                                                                                                                                <p>Reason For Rejection ?</p>
                                                                                                                                                                <input type="hidden"
                                                                                                                                                                    name="proff_id"
                                                                                                                                                                    value="<?php    echo $orderDetails->id; ?>">
                                                                                                                                                                <input type="hidden"
                                                                                                                                                                    name="status" value="2">
                                                                                                                                                                <textarea name="reason"
                                                                                                                                                                    class="form-control"
                                                                                                                                                                    required="" rows="4"
                                                                                                                                                                    style="width:100%;"></textarea>
                                                                                                                                                            </div>
                                                                                                                                                            <div
                                                                                                                                                                class="modal-footer justify-content-between">
                                                                                                                                                                <button type="button"
                                                                                                                                                                    class="btn btn-default"
                                                                                                                                                                    data-dismiss="modal">No</button>
                                                                                                                                                                <button type="submit"
                                                                                                                                                                    class="btn btn-primary">Reject</button>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </form>
                                                                                                                                                    <!-- /.modal-content -->
                                                                                                                                                </div>
                                                                                                                                                <!-- /.modal-dialog -->
                                                                                                                                            </div>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                        <!-- /.card -->
                                                    </div>
                                                    <!-- /.col -->
                                                </div>
                                                <!-- /.row -->
                                            </div>
                                            <!-- /.container-fluid -->
                                        </section>
                                    </div>
                                    <div class="tab-pane" id="published">
                                        <section class="content">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <!-- /.card-header -->
                                                            <div class="card-body">
                                                                <table id="example3"
                                                                    class="table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>S.No</th>
                                                                            <th>Image</th>
                                                                            <th>Name</th>
                                                                            <th>Mobile</th>
                                                                            <th>Category</th>
                                                                            <th>Sub Category</th>
                                                                            <th>Ad Title</th>
                                                                            <th>Ad type</th>
                                                                            <th>Status</th>
                                                                            <th>Expiry Date</th>
                                                                            <th>Publish Date</th>
                                                                            <th>Registered Date</th>
                                                                            <th>Reject</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($published as $key => $orderDetails)
                                                                                                                                            @php
                                                                                                                                                $adImages = App\Models\AdPostingImage::where('ads_id', $orderDetails->ad_id)->orderBy('id', 'asc')->get();
                                                                                                                                            @endphp

                                                                                                                                            <tr>
                                                                                                                                                <td>{{$key + 1}}</td>
                                                                                                                                                <td>
                                                                                                                                                    <img src="{{$orderDetails->image}}"
                                                                                                                                                        style="height:50px;width:50px;"
                                                                                                                                                        data-ad-id="{{$orderDetails->ad_id}}"
                                                                                                                                                        class="view-image">
                                                                                                                                                </td>
                                                                                                                                                @foreach($adImages as $imageAd)
                                                                                                                                                    <img src="{{$imageAd->image}}"
                                                                                                                                                        class="view-image"
                                                                                                                                                        data-ad-id="{{$orderDetails->ad_id}}"
                                                                                                                                                        style="height:0px;width:0px;">
                                                                                                                                                @endforeach
                                                                                                                                                <td>{{$orderDetails->fullname}}</td>
                                                                                                                                                <td>{{$orderDetails->mobile}}</td>
                                                                                                                                                <td>
                                                                                                                                                    <?php
                                                                            $result = DB::table('categories')->where('id', $orderDetails->category_id)->get();
                                                                            echo $result[0]->name;
                                                                                                                                                                                                                                                                                                                                                                    ?>
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                    <?php
                                                                            $result = DB::table('subcategories')->where('id', $orderDetails->sub_category_id)->get();
                                                                            echo $result[0]->name;
                                                                                                                                                                                                                                                                                                                                                                    ?>
                                                                                                                                                </td>
                                                                                                                                                <td>{{$orderDetails->ad_title}}</td>
                                                                                                                                                <td>{{$orderDetails->ad_type}}</td>
                                                                                                                                                <td width="15%">
                                                                                                                                                    <select name="document"
                                                                                                                                                        id="status{{$orderDetails->id}}"
                                                                                                                                                        class="form-control">
                                                                                                                                                        <option value="0"
                                                                                                                                                            @if($orderDetails->status == 0)
                                                                                                                                                            selected @endif>Pending</option>
                                                                                                                                                        <option value="1"
                                                                                                                                                            @if($orderDetails->status == 1)
                                                                                                                                                            selected @endif>Publish</option>
                                                                                                                                                        <option value="2"
                                                                                                                                                            @if($orderDetails->status == 2)
                                                                                                                                                            selected @endif>Reject</option>
                                                                                                                                                    </select>
                                                                                                                                                </td>
                                                                                                                                                <input type="hidden"
                                                                                                                                                    id="statusval{{$orderDetails->id}}"
                                                                                                                                                    value="{{$orderDetails->status}}">
                                                                                                                                                <input type="hidden"
                                                                                                                                                    id="proff_id{{$orderDetails->id}}"
                                                                                                                                                    value="{{$orderDetails->id}}">
                                                                                                                                                <td>{{$orderDetails->ad_expiry}}</td>
                                                                                                                                                <td>{{$orderDetails->published_date}}</td>
                                                                                                                                                <td>{{$orderDetails->created_at}}</td>
                                                                                                                                                <td>
                                                                                                                                                    <button type="button" class="btn btn-danger"
                                                                                                                                                        id="reject-button-{{$orderDetails->id}}"
                                                                                                                                                        data-toggle="modal"
                                                                                                                                                        data-target="#rejectreason{{$orderDetails->id}}">Reject</button>
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                    <div class="dropdown">
                                                                                                                                                        <button
                                                                                                                                                            class="btn btn-primary btn-sm dropdown-toggle"
                                                                                                                                                            type="button"
                                                                                                                                                            data-toggle="dropdown">
                                                                                                                                                            Actions
                                                                                                                                                        </button>

                                                                                                                                                        <div
                                                                                                                                                            class="dropdown-menu dropdown-menu-right">

                                                                                                                                                            <a class="dropdown-item"
                                                                                                                                                                href="{{ route('admin.view.ad', $orderDetails->id) }}">
                                                                                                                                                                <i class="fa fa-eye mr-2"></i>
                                                                                                                                                                View
                                                                                                                                                            </a>

                                                                                                                                                            <a class="dropdown-item"
                                                                                                                                                                href="{{ url('admin/edit-ad/' . $orderDetails->id) }}">
                                                                                                                                                                <i class="fa fa-edit mr-2"></i>
                                                                                                                                                                Edit
                                                                                                                                                            </a>

                                                                                                                                                            <a class="dropdown-item text-danger"
                                                                                                                                                                href="{{ url('admin/delete-ad/' . $orderDetails->id) }}"
                                                                                                                                                                onclick="return confirm('Are you sure you want to delete this advertisement?')">
                                                                                                                                                                <i class="fa fa-trash mr-2"></i>
                                                                                                                                                                Delete
                                                                                                                                                            </a>

                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </td>
                                                                                                                                            </tr>
                                                                                                                                            <script>
                                                                                                                                                $(document).ready(function () {
                                                                                                                                                    $("#status{{$orderDetails->id}}").change(function () {
                                                                                                                                                        var status = $('#status{{$orderDetails->id}}').val();
                                                                                                                                                        var proff_id = $('#proff_id{{$orderDetails->id}}').val();

                                                                                                                                                        if (status == 2) {
                                                                                                                                                            document.querySelector('#reject-button-{{$orderDetails->id}}').click();
                                                                                                                                                            return;
                                                                                                                                                        }

                                                                                                                                                        $.ajax({
                                                                                                                                                            url: '{{url("change-job-ad-status")}}',
                                                                                                                                                            method: 'POST',
                                                                                                                                                            data: {
                                                                                                                                                                status: status,
                                                                                                                                                                proff_id: proff_id,
                                                                                                                                                                _token: '{{csrf_token()}}'
                                                                                                                                                            },
                                                                                                                                                            success: function (data) {
                                                                                                                                                                location.reload(true)
                                                                                                                                                            }
                                                                                                                                                        });
                                                                                                                                                    });
                                                                                                                                                });
                                                                                                                                            </script>


                                                                                                                                            <div class="modal fade"
                                                                                                                                                id="rejectreason{{$orderDetails->id}}">
                                                                                                                                                <div class="modal-dialog">
                                                                                                                                                    <form action="{{url('reject-post')}}"
                                                                                                                                                        role="form" id="quickForm"
                                                                                                                                                        method="post">
                                                                                                                                                        <div class="modal-content">
                                                                                                                                                            <div class="modal-header">
                                                                                                                                                                <h4 class="modal-title">Alert
                                                                                                                                                                </h4>
                                                                                                                                                                <button type="button"
                                                                                                                                                                    class="close"
                                                                                                                                                                    data-dismiss="modal"
                                                                                                                                                                    aria-label="Close">
                                                                                                                                                                    <span
                                                                                                                                                                        aria-hidden="true">&times;</span>
                                                                                                                                                                </button>
                                                                                                                                                            </div>
                                                                                                                                                            @csrf
                                                                                                                                                            <div class="modal-body">
                                                                                                                                                                <p>Reason For Rejection?</p>
                                                                                                                                                                <input type="hidden"
                                                                                                                                                                    name="proff_id"
                                                                                                                                                                    value="{{$orderDetails->id}}">
                                                                                                                                                                <input type="hidden"
                                                                                                                                                                    name="status" value="2">
                                                                                                                                                                <textarea name="reason"
                                                                                                                                                                    class="form-control"
                                                                                                                                                                    required rows="4"
                                                                                                                                                                    style="width:450px;"></textarea>
                                                                                                                                                            </div>
                                                                                                                                                            <div
                                                                                                                                                                class="modal-footer justify-content-between">
                                                                                                                                                                <button type="button"
                                                                                                                                                                    class="btn btn-default"
                                                                                                                                                                    data-dismiss="modal">No</button>
                                                                                                                                                                <button type="submit"
                                                                                                                                                                    class="btn btn-primary">Reject</button>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </form>
                                                                                                                                                    <!-- /.modal-content -->
                                                                                                                                                </div>
                                                                                                                                                <!-- /.modal-dialog -->
                                                                                                                                            </div>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th>S.No</th>
                                                                            <th>Image</th>
                                                                            <th>Name</th>
                                                                            <th>Mobile</th>
                                                                            <th>Category</th>
                                                                            <th>Sub Category</th>
                                                                            <th>Ad Title</th>
                                                                            <th>Ad type</th>
                                                                            <th>Status</th>
                                                                            <th>Expiry Date</th>
                                                                            <th>Publish Date</th>
                                                                            <th>Registered Date</th>
                                                                            <th>Reject</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                        <!-- /.card -->
                                                    </div>
                                                    <!-- /.col -->
                                                </div>
                                                <!-- /.row -->
                                            </div>
                                            <!-- /.container-fluid -->
                                        </section>
                                    </div>
                                    <div class="tab-pane" id="rejected">
                                        <section class="content">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <!-- /.card-header -->
                                                            <div class="card-body">
                                                                <table id="example5"
                                                                    class="table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>S.No</th>
                                                                            <th>Image</th>
                                                                            <th>Name</th>
                                                                            <th>Mobile</th>
                                                                            <th>Category</th>
                                                                            <th>Sub Category</th>
                                                                            <th>Ad Title</th>
                                                                            <th>Ad type</th>
                                                                            <th>Status</th>
                                                                            <th>Expiry Date</th>
                                                                            <th>Registered Date</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($rejected as $key => $orderDetails)
                                                                                                                                            @php
                                                                                                                                                $adImages = App\Models\AdPostingImage::where('ads_id', $orderDetails->ad_id)->orderBy('id', 'asc')->get();
                                                                                                                                               @endphp
                                                                                                                                            <tr>
                                                                                                                                                <td>{{$key + 1}}</td>
                                                                                                                                                <td><img src="{{$orderDetails->image}}"
                                                                                                                                                        class="view-image"
                                                                                                                                                        data-ad-id="{{$orderDetails->ad_id}}"
                                                                                                                                                        style="height:50px;width:50px;"></td>
                                                                                                                                                @foreach($adImages as $imageAd)
                                                                                                                                                    <img src="{{$imageAd->image}}"
                                                                                                                                                        class="view-image"
                                                                                                                                                        data-ad-id="{{$orderDetails->ad_id}}"
                                                                                                                                                        style="height:0px;width:0px;">
                                                                                                                                                @endforeach
                                                                                                                                                <td>{{$orderDetails->fullname}}</td>
                                                                                                                                                <td>{{$orderDetails->mobile}}</td>
                                                                                                                                                <td>
                                                                                                                                                    <?php
                                                                            $result = DB::table('categories')->where('id', $orderDetails->category_id)->get();
                                                                            echo $result[0]->name;
                                                                                                                                                                                                                                                                                    ?>
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                    <?php
                                                                            $result = DB::table('subcategories')->where('id', $orderDetails->sub_category_id)->get();
                                                                            echo $result[0]->name;
                                                                                                                                                                                                                                                                                    ?>
                                                                                                                                                </td>
                                                                                                                                                <td>{{$orderDetails->ad_title}}</td>
                                                                                                                                                <td>{{$orderDetails->ad_type}}</td>
                                                                                                                                                <td width="15%">
                                                                                                                                                    <select name="document"
                                                                                                                                                        id="status<?php    echo $orderDetails->id;?>"
                                                                                                                                                        class="form-control">
                                                                                                                                                        <option value="0" <?php    if ($orderDetails->status == 0) {
                                                                                echo 'selected';
                                                                            }?>>Pending</option>
                                                                                                                                                        <option value="1" <?php    if ($orderDetails->status == 1) {
                                                                                echo 'selected';
                                                                            }?>> Publish</option>
                                                                                                                                                        <option value="2" <?php    if ($orderDetails->status == 2) {
                                                                                echo 'selected';
                                                                            }?>>Reject</option>
                                                                                                                                                    </select>
                                                                                                                                                </td>
                                                                                                                                                <input type="hidden"
                                                                                                                                                    id="statusval<?php    echo $orderDetails->id;?>"
                                                                                                                                                    value="<?php    echo $orderDetails->status; ?>">
                                                                                                                                                <input type="hidden"
                                                                                                                                                    id="proff_id<?php    echo $orderDetails->id;?>"
                                                                                                                                                    value="<?php    echo $orderDetails->id; ?>">
                                                                                                                                                <td>{{$orderDetails->ad_expiry}}</td>
                                                                                                                                                <td>{{$orderDetails->created_at}}</td>
                                                                                                                                                <td>
                                                                                                                                                    <div class="dropdown">
                                                                                                                                                        <button
                                                                                                                                                            class="btn btn-primary btn-sm dropdown-toggle"
                                                                                                                                                            type="button"
                                                                                                                                                            data-toggle="dropdown">
                                                                                                                                                            Actions
                                                                                                                                                        </button>

                                                                                                                                                        <div
                                                                                                                                                            class="dropdown-menu dropdown-menu-right">

                                                                                                                                                            <a class="dropdown-item"
                                                                                                                                                                href="{{ route('admin.view.ad', $orderDetails->id) }}">
                                                                                                                                                                <i class="fa fa-eye mr-2"></i>
                                                                                                                                                                View
                                                                                                                                                            </a>

                                                                                                                                                            <a class="dropdown-item"
                                                                                                                                                                href="{{ url('admin/edit-ad/' . $orderDetails->id) }}">
                                                                                                                                                                <i class="fa fa-edit mr-2"></i>
                                                                                                                                                                Edit
                                                                                                                                                            </a>

                                                                                                                                                            <a class="dropdown-item text-danger"
                                                                                                                                                                href="{{ url('admin/delete-ad/' . $orderDetails->id) }}"
                                                                                                                                                                onclick="return confirm('Are you sure you want to delete this advertisement?')">
                                                                                                                                                                <i class="fa fa-trash mr-2"></i>
                                                                                                                                                                Delete
                                                                                                                                                            </a>

                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </td>
                                                                                                                                            </tr>
                                                                                                                                            <script>
                                                                                                                                                $(document).ready(function () {
                                                                                                                                                    $("#status<?php    echo $orderDetails->id;?>").change(function () {



                                                                                                                                                        var status = $('#status<?php    echo $orderDetails->id;?>').val();
                                                                                                                                                        var proff_id = $('#proff_id<?php    echo $orderDetails->id;?>').val();

                                                                                                                                                        if (status == 2) {
                                                                                                                                                            $('#rejectreason{{ $orderDetails->id }}').modal('show');
                                                                                                                                                        }

                                                                                                                                                        $.ajax({
                                                                                                                                                            url: '{{url("change-job-ad-status")}}',
                                                                                                                                                            method: 'POST',
                                                                                                                                                            data: { status: status, proff_id: proff_id, '_token': "{{csrf_token()}}" },
                                                                                                                                                            success: function (data) {
                                                                                                                                                                location.reload(true)
                                                                                                                                                            }
                                                                                                                                                        });



                                                                                                                                                    });
                                                                                                                                                });
                                                                                                                                            </script>
                                                                                                                                            <div class="modal fade"
                                                                                                                                                id="rejectreason<?php    echo $orderDetails->id; ?>">
                                                                                                                                                <div class="modal-dialog">
                                                                                                                                                    <form action="{{url('reject-post')}}"
                                                                                                                                                        role="form" id="quickForm"
                                                                                                                                                        method="post">
                                                                                                                                                        <div class="modal-content">
                                                                                                                                                            <div class="modal-header">
                                                                                                                                                                <h4 class="modal-title">Alert
                                                                                                                                                                </h4>
                                                                                                                                                                <button type="button"
                                                                                                                                                                    class="close"
                                                                                                                                                                    data-dismiss="modal"
                                                                                                                                                                    aria-label="Close">
                                                                                                                                                                    <span
                                                                                                                                                                        aria-hidden="true">&times;</span>
                                                                                                                                                                </button>
                                                                                                                                                            </div>
                                                                                                                                                            @csrf
                                                                                                                                                            <div class="modal-body">
                                                                                                                                                                <p>Reason For Rejection ?</p>
                                                                                                                                                                <input type="hidden"
                                                                                                                                                                    name="proff_id"
                                                                                                                                                                    value="<?php    echo $orderDetails->id; ?>">
                                                                                                                                                                <input type="hidden"
                                                                                                                                                                    name="status" value="2">
                                                                                                                                                                <textarea name="reason"
                                                                                                                                                                    class="form-control"
                                                                                                                                                                    required="" rows="4"
                                                                                                                                                                    style="width:450px;"></textarea>
                                                                                                                                                            </div>
                                                                                                                                                            <div
                                                                                                                                                                class="modal-footer justify-content-between">
                                                                                                                                                                <button type="button"
                                                                                                                                                                    class="btn btn-default"
                                                                                                                                                                    data-dismiss="modal">No</button>
                                                                                                                                                                <button type="submit"
                                                                                                                                                                    class="btn btn-primary">Reject</button>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </form>
                                                                                                                                                    <!-- /.modal-content -->
                                                                                                                                                </div>
                                                                                                                                                <!-- /.modal-dialog -->
                                                                                                                                            </div>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th>S.No</th>
                                                                            <th>Image</th>
                                                                            <th>Name</th>
                                                                            <th>Mobile</th>
                                                                            <th>Category</th>
                                                                            <th>Sub Category</th>
                                                                            <th>Ad Title</th>
                                                                            <th>Ad type</th>
                                                                            <th>Status</th>
                                                                            <th>Expiry Date</th>
                                                                            <th>Registered Date</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                        <!-- /.card -->
                                                    </div>
                                                    <!-- /.col -->
                                                </div>
                                                <!-- /.row -->
                                            </div>
                                            <!-- /.container-fluid -->
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- PhotoSwipe Gallery Structure -->
        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="pswp__bg"></div>
            <div class="pswp__scroll-wrap">
                <div class="pswp__container">
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                </div>
                <div class="pswp__ui pswp__ui--hidden">
                    <div class="pswp__top-bar">
                        <div class="pswp__counter"></div>
                        <button class="pswp__button pswp__button--close" aria-label="Close (Esc)"></button>
                        <button class="pswp__button pswp__button--zoom" aria-label="Zoom in/out"></button>
                        <div class="pswp__preloader">
                            <div class="loading-spin"></div>
                        </div>
                    </div>
                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                        <div class="pswp__share-tooltip"></div>
                    </div>
                    <button class="pswp__button pswp__button--arrow--left" aria-label="Previous (arrow left)"></button>
                    <button class="pswp__button pswp__button--arrow--right" aria-label="Next (arrow right)"></button>
                    <div class="pswp__caption">
                        <div class="pswp__caption__center"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe-ui-default.min.js"></script>
    <script>
        function changeState(selectElement, proff_id) {
            var status = selectElement.value;

            // If status is "Reject", trigger the modal
            if (status == 2) {
                document.querySelector('#reject-button-' + proff_id).click();
                return;
            }

            // Make AJAX call to update status
            $.ajax({
                url: '{{ url("change-job-ad-status") }}',
                method: 'POST',
                data: {
                    status: status,
                    proff_id: proff_id,
                    '_token': "{{ csrf_token() }}"
                },
                success: function (data) {
                    location.reload(true);
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var pswpElement = document.querySelectorAll('.pswp')[0];

            document.querySelectorAll('.view-image').forEach(function (thumbnail) {
                thumbnail.addEventListener('click', function () {
                    var adId = this.getAttribute('data-ad-id');
                    var fallbackImage = this.getAttribute('src');

                    fetch('{{ url("get-ad-images") }}/' + adId)
                        .then(response => response.json())
                        .then(data => {
                            var items = data.length ? data.map(image => ({
                                src: image.url,
                                // w: image.width,
                                // h: image.height
                                w: this.naturalWidth,
                                h: this.naturalHeight
                            })) : [{
                                src: fallbackImage,
                                w: this.naturalWidth,
                                h: this.naturalHeight
                            }];

                            var options = {
                                index: 0 // Start at the first image
                            };

                            var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                            gallery.init();
                        });
                });
            });
        });
    </script>

@endsection