@extends('admin.layout.layout')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Notification</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Notification</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    @if (session('success'))
    <div class="card-body">
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5>{{ Session::get('message') }}</h5>
            <?php Session::forget('success'); ?>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="card-body">
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5>{{ Session::get('message') }}</h5>
            <?php Session::forget('error'); ?>
        </div>
    </div>
    @endif
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                    <section class="content">
                       <div class="container mt-5 mb-5">
                            <h1>Send Notification</h1>
                            <form action="{{ url('/send-notification') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox" id="sendToAll" name="send_to_all">
                                      <label class="form-check-label" for="sendToAll">Send to All</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox" id="sendToIndividual" name="send_to_individual" checked>
                                      <label class="form-check-label" for="sendToIndividual">Send to Individual</label>
                                    </div>
                                    
                                </div>
                                <div class="form-group">
                                    <label for="customer">Customers</label>
                                    <select name="users[]" id="customer" class="form-control" multiple>
                                        @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label for="body">Body</label>
                                    <textarea class="form-control" id="body" name="body" rows="3" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <img id="imagePreview" src="#" alt="Image Preview" style="display:none; max-width: 100px; margin-top: 10px;"/>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Notification</button>
                            </form>
                        </div>
    
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
      <!-- /.container-fluid -->
   </section>
</div>
<script>
    $("#image").change(function() {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imagePreview').attr('src', e.target.result);
            $('#imagePreview').show();
        }
        reader.readAsDataURL(this.files[0]);
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sendToAll = document.getElementById('sendToAll');
        const sendToIndividual = document.getElementById('sendToIndividual');
        const customerSelect = document.getElementById('customer');

        sendToAll.addEventListener('change', function () {
            if (sendToAll.checked) {
                sendToIndividual.checked = false;
                customerSelect.disabled = true;
            } else {
                customerSelect.disabled = false;
            }
        });

        sendToIndividual.addEventListener('change', function () {
            if (sendToIndividual.checked) {
                sendToAll.checked = false;
                customerSelect.disabled = false;
            }
        });
    });
</script>
@endsection

