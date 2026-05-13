@extends('admin.layout.layout')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>Ads Details</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="#">Home</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Ads Details
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </section>

    <section class="content">

        <div class="container-fluid">

            <div class="card card-primary card-outline">

                <div class="card-header">
                    <h3 class="card-title">
                        {{ $info->ad_title }}
                    </h3>
                </div>

                <div class="card-body">

                    {{-- Images --}}
                    <div class="row mb-4">

                        @if(count($adsinfoimages) > 0)

                            @foreach($adsinfoimages as $image)

                                <div class="col-md-3 mb-3">
                                    <img src="{{ $image->image }}"
                                         class="img-fluid rounded border"
                                         style="height:200px;width:100%;object-fit:cover;">
                                </div>

                            @endforeach

                        @else

                            <div class="col-md-3">
                                <img src="{{ $info->image }}"
                                     class="img-fluid rounded border">
                            </div>

                        @endif

                    </div>

                    {{-- User Details --}}
                    <div class="card">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0">User Details</h5>
                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4">
                                    <strong>Name</strong>
                                    <p>{{ $info->fullname }}</p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Email</strong>
                                    <p>{{ $info->email }}</p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Mobile</strong>
                                    <p>{{ $info->mobile }}</p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Location</strong>
                                    <p>{{ $info->location }}</p>
                                </div>

                                <div class="col-md-4">
                                    <strong>User Since</strong>
                                    <p>{{ date('d M Y', strtotime($customer->created_at)) }}</p>
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Ad Details --}}
                    <div class="card mt-4">

                        <div class="card-header bg-success">
                            <h5 class="mb-0">Ad Details</h5>
                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4">
                                    <strong>Ad Title</strong>
                                    <p>{{ $info->ad_title }}</p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Ad Type</strong>
                                    <p>{{ $info->ad_type }}</p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Category</strong>
                                    <p>{{ $category->name ?? '' }}</p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Sub Category</strong>
                                    <p>{{ $subcategory->name ?? '' }}</p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Price</strong>
                                    <p>
                                        @if($info->price)
                                            ₹ {{ $info->price }}
                                        @else
                                            ₹ {{ $info->salary_from }} - ₹ {{ $info->salary_to }}
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Views</strong>
                                    <p>{{ $info->ad_view_count }}</p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Status</strong>
                                    <p>
                                        @if($info->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Created At</strong>
                                    <p>{{ date('d M Y h:i A', strtotime($info->created_at)) }}</p>
                                </div>

                                <div class="col-md-4">
                                    <strong>Expiry Date</strong>
                                    <p>{{ date('d M Y', strtotime($info->ad_expiry)) }}</p>
                                </div>

                            </div>

                        </div>

                    </div>

					
{{-- Additional Details --}}
<div class="card mt-4">

    <div class="card-header bg-info">
        <h5 class="mb-0">Additional Details</h5>
    </div>

    <div class="card-body">

        <div class="row">

            @php
                $hideFields = [
                    'id',
                    'ads_id',
                    'created_at',
                    'updated_at',
                    'delete_status'
                ];
            @endphp

            @if($moreadsinfo)

                @foreach((array)$moreadsinfo as $key => $value)

                    @if(!in_array($key, $hideFields) && $value != '' && $value != null)

                        <div class="col-md-4 mb-3">

                            <strong>
                                {{ ucwords(str_replace('_', ' ', $key)) }}
                            </strong>

                            <p>
                                {{ $value }}
                            </p>

                        </div>

                    @endif

                @endforeach

            @endif

        </div>

    </div>

</div>

                    {{-- Description --}}
                    <div class="card mt-4">

                        <div class="card-header bg-dark">
                            <h5 class="mb-0">Description</h5>
                        </div>

                        <div class="card-body">

                            {!! nl2br(e($info->description)) !!}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection