@extends('admin.layout.layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>InfoCard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active">InfoCard</li>
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
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Infocard</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('infocard.update', $infocard->id) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{$infocard->title}}" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description">{{$infocard->description}}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="icon">Icon</label>
                                <input type="file" class="form-control-file" id="icon" name="icon" onchange="previewImage(event)">
                                <img id="imagePreview" style="margin-top: 10px; max-width: 200px;">
                            </div>
                            <div class="form-group">
            				    <img src="{{ asset('public/'.$infocard->icon) }}" style="height:50px;width:50px;">
            				
                              </div>

                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" class="form-control" id="url" name="url" value="{{$infocard->url}}">
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{route('infocard.index')}}" class="btn btn-default">Cancel</a>
                        </form>
                        </div>
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
