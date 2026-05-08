 @extends('admin.layout.layout')
    @section('content')
      <div class="content-wrapper">
      
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>View All Reserved Users</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">View All Reserved Users</li>
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
          <div class="container-fluid justify-content-center">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                     
                    <table id="example1" class="table-responsive table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Date & Time </th>
                          <th>Member Name </th>
                          <th>Mobile Number </th>
                          <th>Email Id  </th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($users as $pu)
                          <tr>
                              <td>{{ $pu->id }}</td>
                              <td>{{ $pu->created_at }}</td>
                              <td>{{ $pu->name ?? '' }}</td>
                              <td>{{ $pu->mobile ?? '' }}</td>
                              <td>{{ $pu->email ?? '' }}</td>
                              <td>{{ $pu->status }}</td>
                              <td>
                                  <a href="{{route('view-user',[$pu->id])}}"><button type="button" class="btn btn-primary">View User Detail</button></a>
                              </td>
                          </tr>
                      @endforeach
                        {{--<div class="modal fade" id="modal-delete">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h4 class="modal-title">Alert</h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                      </div>
                                      <div class="modal-body">
                                          <p >Are You Sure You Want To Delete This Item ?</p>
                                      </div>
                                      <div class="modal-footer justify-content-between">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                          <a href=""><button type="button" class="btn btn-primary">Yes</button></a>
                                      </div>
                                  </div>
                                  <!-- /.modal-content -->
                              </div>
                                    <!-- /.modal-dialog -->
                          </div>--}}
                      </tbody>
                      <tfoot>
                        <tr>
                         <th>S.No</th>
                          <th>Date & Time </th>
                          <th>Member Name </th>
                          <th>Mobile Number </th>
                          <th>Email Id  </th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </tfoot>
                  </table>
                </div>
                  <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>  
</div>
@endsection