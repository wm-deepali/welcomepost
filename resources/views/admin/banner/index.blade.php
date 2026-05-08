@extends('admin.layout.layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Banner</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Banner</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @if (session('success'))
    <div class="card-body">
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5>{{ Session::get('success') }}</h5>
            <?php Session::forget('success');?>
        </div>
    </div>
    @endif

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('banner.create') }}" class="btn btn-primary">Add New Banner</a>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Title</th>
                                        <th>Link</th>
                                        <th>Description</th>
                                        <th>Banner Image</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($banners as $key => $banner)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $banner->title }}</td>
                                        <td>{{ $banner->link }}</td>
                                        <td>{{ $banner->description }}</td>
                                        <td><a href="{{ url('public/'.$banner->image) }}" target="blank"><img src="{{ url('public/'.$banner->image) }}" style="width:150px;" alt=""></a></td>
                                        <td>{{ $banner->created_at }}</td>
                                        <td>
                                            <a href="{{url('admin/banner/edit/'.$banner->id)}}"><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button></a>
                                            <form action="{{ route('banner.destroy', $banner->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Banner Image</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
