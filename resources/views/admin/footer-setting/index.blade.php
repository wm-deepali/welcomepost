@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">

    <section class="content-header">
        <!-- Content Header (Page header) -->
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Footer Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Footer Settings</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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
                        <div class="card-body">
                            <!-- Add the form here -->
                            <form action="{{ route('footer.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{$footer->title ?? ""}}" placeholder="Enter title">
                                </div>
                                <div class="form-group">
                                    <label for="button_text">Button Text</label>
                                    <input type="text" class="form-control" id="button_text" name="button_text" value="{{$footer->button_text ?? ""}}" placeholder="Enter Button Text">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description">{{$footer->description ?? ""}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="url">Redirect URL</label>
                                    <input type="text" class="form-control" id="url" name="url" value="{{$footer->url ?? ""}}" placeholder="Enter URL">
                                </div>
                                <hr>
                                <h3>Social Media Setting</h3>
                                <div class="form-group">
                                    <label for="youtube_link">Youtube</label>
                                    <input type="text" class="form-control" id="youtube_link" value="{{$footer->youtube_link ?? ""}}" name="youtube_link" placeholder="Enter URL">
                                </div>
                                <div class="form-group">
                                    <label for="facebook_link">Facebook</label>
                                    <input class="form-control" id="facebook_link" name="facebook_link" value="{{$footer->facebook_link ?? ""}}" placeholder="Enter URL">
                                </div>
                                <div class="form-group">
                                    <label for="linkedin_link">LinkedIN</label>
                                    <input type="text" class="form-control" id="linkedin_link" name="linkedin_link" value="{{$footer->linkedin_link ?? ""}}" placeholder="Enter URL">
                                </div>
                                <div class="form-group">
                                    <label for="twitter_link">Twitter</label>
                                    <input type="text" class="form-control" id="twitter_link" name="twitter_link" value="{{$footer->twitter_link ?? ""}}" placeholder="Enter URL">
                                </div>
                                <div class="form-group">
                                    <label for="twitter_link">Instagram</label>
                                    <input type="text" class="form-control" id="instagram_link" name="instagram_link" value="{{$footer->instagram_link ?? ""}}" placeholder="Enter URL">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
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
@endsection
