@extends('admin.layout.layout')
@section('content')
<script src="https://sitesupply.in/assets/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>  
<style>
    .ck.ck-balloon-panel.ck-balloon-panel_position_border-side_right.ck-powered-by-balloon {
    display: none !important;
}
.ck.ck-editor__main .ck {
    height: 200px !important;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Pages</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Add Pages</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
	
	@if ($errors->any())
	<div class="card-body">
	    <div class="alert alert-danger alert-dismissible">
	        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	        @foreach ($errors->all() as $error)
	            <p>{{ $error }}</p>
	        @endforeach
	   </div>
	 </div>
	 @endif
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
            <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Add Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{url('post-add-pages')}}" role="form" id="quickForm" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter Name">
                                </div>
				  
				                <div class="form-group">
                                    <label for="exampleInputEmail1">Heading</label>
                                    <input type="text" name="heading" class="form-control" placeholder="Enter Name">
                                </div>
				  
				                <div class="form-group">
                                    <label for="exampleInputEmail1">Description</label>
                                    <!--<div id="editor"></div>-->
                                    <textarea id="newEditor" name="description" class="form-control">
                                    </textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<script src="{{ asset('assets/adminlte/dist/js/ckeditor.js')}}"></script>
<script>
	ClassicEditor
		.create( document.querySelector( '#newEditor' ), {
			// toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
		} )
		.then( editor => {
			window.editor = editor;
		} )
		.catch( err => {
			console.error( err.stack );
		} );
</script>
@endsection