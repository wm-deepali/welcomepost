@extends('admin.layout.layout')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center">

                    <h1>View Advertisement</h1>

                    <a href="{{ url()->previous() }}" class="btn btn-secondary">

                        Back

                    </a>

                </div>

            </div>

        </section>

        <section class="content">

            <div class="container-fluid">

                <div class="card">

                    <div class="card-body">

                        <div class="row">

                            {{-- LEFT IMAGES --}}
                            <div class="col-md-7">

                                @if(count($images) > 0)

                                    <img src="{{ $images[0]->image }}" class="img-fluid rounded mb-3 border"
                                        style="width:100%;height:450px;object-fit:cover;">

                                    <div class="d-flex flex-wrap">

                                        @foreach($images as $image)

                                            <img src="{{ $image->image }}" style="width:90px;
                                                         height:90px;
                                                         object-fit:cover;
                                                         margin-right:10px;
                                                         margin-bottom:10px;
                                                         border-radius:10px;
                                                         border:1px solid #ddd;">

                                        @endforeach

                                    </div>

                                @endif

                            </div>

                            {{-- RIGHT DETAILS --}}
                            <div class="col-md-5">

                                <h2 class="mb-3">

                                    {{ $ad->ad_title }}

                                </h2>

                                @if(!empty($ad->price))

                                    <h4 class="text-primary mb-4">

                                        ₹ {{ number_format($ad->price) }}

                                    </h4>

                                @endif

                                <table class="table table-bordered">

                                    <tr>
                                        <th width="35%">Customer</th>
                                        <td>{{ $customer->name ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Mobile</th>
                                        <td>{{ $customer->mobile ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $customer->email ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Category</th>
                                        <td>{{ $category->name ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Subcategory</th>
                                        <td>{{ $subcategory->name ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Ad Type</th>
                                        <td>{{ $ad->ad_type }}</td>
                                    </tr>

                                    <tr>
                                        <th>Status</th>
                                        <td>

                                            @if($ad->status == 1)

                                                <span class="badge badge-success">
                                                    Published
                                                </span>

                                            @elseif($ad->status == 0)

                                                <span class="badge badge-warning">
                                                    Pending
                                                </span>

                                            @else

                                                <span class="badge badge-danger">
                                                    Rejected
                                                </span>

                                            @endif

                                        </td>
                                    </tr>

                                </table>

                            </div>

                        </div>

                        {{-- DESCRIPTION --}}
                        <hr>

                        <h4 class="mb-3">
                            Description
                        </h4>

                        <div class="mb-4">

                            {!! nl2br(e($ad->description)) !!}

                        </div>

                        {{-- DYNAMIC FORM DATA --}}
                        @if($formData)

                            <hr>

                            <h4 class="mb-3">
                                Advertisement Details
                            </h4>

                            <div class="row">

                                @foreach($formData->getAttributes() as $key => $value)

                                    @php

                                        $skipFields = [

                                            'id',
                                            'ads_id',
                                            'user_id',
                                            'category_id',
                                            'sub_category_id',
                                            'formtype',

                                            'delete_status',
                                            'status',

                                            'created_at',
                                            'updated_at',

                                            'image',
                                            'location',

                                            'state',
                                            'city',

                                            'state_name',
                                            'city_name',

                                            'description',

                                        ];

                                    @endphp

                                    @if(!in_array($key, $skipFields) && !empty($value))

                                        <div class="col-md-6 mb-3">

                                            <div class="border rounded p-3 h-100">

                                                <strong class="text-capitalize">

                                                    {{ str_replace('_', ' ', $key) }}

                                                </strong>

                                                <br>

                                                <span class="text-muted">

                                                    {{ $value }}

                                                </span>

                                            </div>

                                        </div>

                                    @endif

                                @endforeach

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection