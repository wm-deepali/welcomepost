@extends('admin.layout.layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add New Banner</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Banner</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Banner Details</h3>
                        </div>
                        <form method="POST" action="{{ route('banner.update', $banner->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{$banner->title}}" placeholder="Enter title" required>
                                </div>
                                <div class="form-group">
                                    <label for="title">Link</label>
                                    <input type="url" class="form-control" id="url" name="link" value="{{$banner->link}}" placeholder="Enter Link">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter description">{{$banner->description}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image">Banner Image(560x3376)</label>
                                    <input type="file" class="form-control-file" id="image" name="image" onchange="previewImage(event)">
                                    <img id="imagePreview" style="margin-top: 10px; max-width: 200px;">
                                </div>
                                <div class="form-group">
            				        <img src="{{ asset('public/'.$banner->image) }}" style="height:50px;width:50px;">
            				
                              </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{route('banner.index')}}" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('imagePreview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endsection
