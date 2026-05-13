@extends('admin.layout.layout')
<style>
    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .active-user {
        color: #fff;
        background-color: #28a745; /* Green color for active status */
    }
    
    .inactive {
        color: #fff;
        background-color: #dc3545; /* Red color for inactive status */
    }
</style>
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Sub Admin and Roles</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item active">Sub Admin and Roles</li>
               </ol>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
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
                      <div class="col-md-12 mb-3">
                         <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                          Create User
                        </button>
                      </div>
                    <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="card">
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                          <table id="example3" class="table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                      <th>S.No</th>
                                                      <th>Profile Picture</th>
                                                      <th>Name</th>
                                                      <th>Email</th>
                                                      <th>Mobile</th>
                                                      <th>Privileges</th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $count = 1;
                                                @endphp
                                                @foreach($users as $user)
                                                <tr>
                                                    <td>{{$count++}}</td>
                                                    <td><img style="width:40px;height:40px;border-radius:30px;" src="{{asset('storage/app/public/'.$user->profile_pic)}}" alt="{{$user->name}}"></td>
                                                    <td>{{$user->name}}</td>
                                                    <td>{{$user->email}}</td>
                                                    <td>{{$user->mobile}}</td>
                                                    <td>
                                                        @if($user->role_id == 1)
                                                            Read and Write
                                                        @else
                                                            Read Only
                                                        @endif
                                                    </td>
                                                    <td> @if ($user->active_status == 0)
                                                            <span class="status-badge active-user">Active</span>
                                                        @else
                                                            <span class="status-badge inactive">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->id != 1)
                                                            <a href="{{route('sub-admin-delete',$user->id)}}" id="deleteUser" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                            <a class="btn btn-info" href="{{route('sub-admin-show',$user->id)}}" id="editUserBtn"><i class="fas fa-edit"></i> Edit User</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                        <th>S.No</th>
                                                        <th>Profile Picture</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Mobile</th>
                                                        <th>Privileges</th>
                                                        <th>Status</th>
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
                  <!-- /.card-body -->
               </div>
               <!-- /.card -->
            </div>
            <!-- /.col -->
         </div>
         <!-- /.row -->
      </div>
      <!-- Modal -->
        <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <!-- Form -->
                <form id="createUserForm" method="post" action="{{route('sub-admin-create')}}" enctype="multipart/form-data">
                @csrf
                  <!-- Name -->
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                  <!-- Email -->
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                  </div>
                  <!-- Mobile -->
                  <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="number" class="form-control" id="mobile" name="mobile" required>
                  </div>
                  <!-- Profile Picture -->
                  <div class="form-group">
                    <label for="profilePic">Profile Picture</label>
                    <input type="file" class="form-control-file" id="profilePic" name="profilePic">
                  </div>
                  <div class="form-group">
                    <label for="role">Privileges</label>
                    <select class="form-control" id="role" name="role" required>
                      <option value="2">Read Only</option>
                      <option value="1">Read and Write</option>
                    </select>
                  </div>
                  <!-- Permissions -->
                  <div class="form-group">
                    <label for="role">Permissions:</label>
                    <div class="form-check" style="display: flex; align-items: center;"> 
                      <input type="checkbox" class="form-check-input" id="masterEdit" name="masterEdit">
                      <label class="form-check-label" for="masterEdit">Master Settings</label>
                    </div>
                    <div class="form-check" style="display: flex; align-items: center;">
                      <input type="checkbox" class="form-check-input" id="userEdit" name="userEdit">
                      <label class="form-check-label" for="userEdit">Users</label>
                    </div>
                    <div class="form-check" style="display: flex; align-items: center;">
                      <input type="checkbox" class="form-check-input" id="chatEdit" name="chatEdit">
                      <label class="form-check-label" for="chatEdit">Chat Support</label>
                    </div>
                    <div class="form-check" style="display: flex; align-items: center;">
                      <input type="checkbox" class="form-check-input" id="invoiceEdit" name="invoiceEdit">
                      <label class="form-check-label" for="invoiceEdit">Invoice and Orders</label>
                    </div>
                    <div class="form-check" style="display: flex; align-items: center;">
                      <input type="checkbox" class="form-check-input" id="subscriptionEdit" name="subscriptionEdit">
                      <label class="form-check-label" for="subscriptionEdit">Subscription</label>
                    </div>
                    <div class="form-check" style="display: flex; align-items: center;">
                      <input type="checkbox" class="form-check-input" id="adsEdit" name="adsEdit">
                      <label class="form-check-label" for="adsEdit">Ads Inquiries</label>
                    </div>
                    <div class="form-check" style="display: flex; align-items: center;">
                      <input type="checkbox" class="form-check-input" id="contentEdit" name="contentEdit">
                      <label class="form-check-label" for="contentEdit">Content Management</label>
                    </div>
                    <div class="form-check" style="display: flex; align-items: center;">
                      <input type="checkbox" class="form-check-input" id="helpEdit" name="helpEdit">
                      <label class="form-check-label" for="helpEdit">Help and Support</label>
                    </div>
                    <div class="form-check" style="display: flex; align-items: center;">
                      <input type="checkbox" class="form-check-input" id="walletEdit" name="walletEdit">
                      <label class="form-check-label" for="walletEdit">Wallet & Payouts</label>
                    </div>
                    <div class="form-check" style="display: flex; align-items: center;">
                      <input type="checkbox" class="form-check-input" id="misEdit" name="misEdit">
                      <label class="form-check-label" for="misEdit">MIS Reports</label>
                    </div>
                  </div>
                  <!-- Password -->
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <!-- Submit Button -->
                  <button id="submitFormBtn" class="btn btn-primary">Create</button>
                </form>
              </div>
            </div>
          </div>
        </div>

      <!-- /.container-fluid -->
   </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        
        $('#submitFormBtn').on('click', function() {
            $.ajax({
                url: $('#createUserForm').attr('action'),
                type: 'POST',
                data: new FormData($('#createUserForm')[0]),
                processData: false,
                contentType: false,
                success: function(response) {
                    // Handle success response
                    if(response.success){
                        Swal.fire({
                          title: "Good job!",
                          text: response.message,
                          icon: "success"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection