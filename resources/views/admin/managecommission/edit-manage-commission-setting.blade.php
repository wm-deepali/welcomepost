@extends('admin.layout.layout')
@section('content')
<style>
   .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    top: 34px;
    left: 50px;
   }
   .switch input { 
   opacity: 0;
   width: 0;
   height: 0;
   }
   .slider {
   position: absolute;
   cursor: pointer;
   top: 0;
   left: 0;
   right: 0;
   bottom: 0;
   background-color: #bd1414;
   -webkit-transition: .4s;
   transition: .4s;
   }
   .slider:before {
   position: absolute;
   content: "";
   height: 26px;
   width: 26px;
   left: 4px;
   bottom: 4px;
   background-color: white;
   -webkit-transition: .4s;
   transition: .4s;
   }
   input:checked + .slider {
   background-color: #356211;
   }
   input:focus + .slider {
   box-shadow: 0 0 1px #356211;
   }
   input:checked + .slider:before {
   -webkit-transform: translateX(26px);
   -ms-transform: translateX(26px);
   transform: translateX(26px);
   }
   /* Rounded sliders */
   .slider.round {
   border-radius: 34px;
   }
   .slider.round:before {
   border-radius: 50%;
   }
   .add_button
   {
    margin-top: 13%;
   }
   .remove_button
   {
    margin-top: 13%;
   }
</style>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit  COMMISSION SETTING</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Edit  COMMISSION SETTING</li>
               </ol>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
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
                     <h3 class="card-title">Edit Details</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                @php
               
                  @endphp
                  <form action="{{url('post-manage-commission-setting')}}"  method="post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="id" value="{{$data['edit_commission']->id ?? ''}}">
                     <div class="card-body">
                        <div class="form-group row">
                           <div class="col">
                  <label>Subscription</label>
                    @php
                    $subscriptions = DB::table('subscriptions')->where('delete_status',0)->get();
                    @endphp
                    <select class="select2 fav_clr" name="subscription_packge_id" data-placeholder="Select a Category" style="width: 100%;" required>
                       <option value="">Select</option>
                        @foreach($subscriptions as $item)
							<option @if($data['edit_commission']['subscription_packge_id'] == $item->id) selected @endif value="{{ $item->id ?? '' }}">{{ $item->package ?? '' }}</option>
					    @endforeach
                    </select>


                              {{--<input type="text" name="subscription_id" class="form-control" placeholder="Enter With in Pan card" value="{{$data['info']->package}}" readonly>--}}
                           </div>
                           
                           <div class="col">
                              <label for="exampleInputEmail1">Commission(%)</label>
                              <input type="text" name="commission" class="form-control" required placeholder="Enter Commission" value="{{ $data['edit_commission']->commission ?? '' }}" >
                           </div>
                           <div class="col">
                                
                           <label for="exampleInputEmail1">Auto joining</label> 
                                 <label class="switch">
                             <input type="checkbox" @if(@$data['edit_commission']->auto_join==1){{"checked"}} @else {{ "" }} @endif id="changestatus" onchange="toggleValue()">
                                 <span class="slider round "></span>
                           </label>
                                
                           </div>
                             <input type="hidden" id="statusvalue" name="auto_join" value="{{ $data['edit_commission']->auto_join ?? '0' }}">

                           <div class="col">
                              <label for="exampleInputEmail1">Auto joining Members</label>
                              <input type="text" name="auto_join_member" class="form-control" placeholder="Enter Auto joining Members" value="{{ $data['edit_commission']->auto_join_member ?? '' }}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Minimum Views</label>
                              <input type="text" name="minimum_views" required class="form-control" placeholder="Enter Minimum Views" value="{{ $data['edit_commission']->minimum_views ?? ''  }}">
                           </div>
                           <div class="col">
                              <label for="commission_level_type">Commssion Level</label>
                              <select name="commission_level_type" id="commission_level_type" class="form-control" required>
                                    <option value="">Select Commssion Level Type</option>
                                    <option value="1" {{ $data['edit_commission']->commission_level_type == 1 ? 'selected' : ''  }}>Single</option>
                                    <option value="2" {{ $data['edit_commission']->commission_level_type == 2 ? 'selected' : ''  }}>Multiple</option>
                              </select>
                           </div>
                        </div>
                           <div class="form-group field_wrapper">
                              @if(isset($data['levels']) && count($data['levels']) > 0)
                              @foreach($data['levels'] as $level)
                              <div class="subdiv" style="display: flex">
                                 <div class="col-md-3">
                                    <label for="level_name">Level Name</label>
                                    <input type="text" name="level_name[]" value="{{$level->level_name}}" class="form-control" required/>
                                 </div>
                                 <div class="col-md-3">
                                    <label for="level_commission">Level Commission(%)</label>
                                    <input type="text" name="level_commission[]" value="{{$level->level_commission}}" class="form-control" required/>
                                 </div>
                                 <div class="col-md-3">
                                    <label for="level_status">Status</label>
                                    <select class="form-control" name="level_status[]">
                                       <option value="1" {{$level->status == 1 ? 'selected' : ''}}>Active</option>
                                       <option value="0" {{$level->status == 0 ? 'selected' : ''}}>Inactive</option>
                                    </select>
                                 </div>
                                 
                              </div>
                              @endforeach
                              <div class="col-md-3">
                                    <a href="javascript:void(0);" class="add_button btn btn-success" title="Add field"><i class="fa fa-plus"></i> Add More</a>
                                 </div>
                              @endif
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
            <!-- right column -->
            <div class="col-md-6">
            </div>
            <!--/.col (right) -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
</div>
@endsection

<script>
        function toggleValue() {
            var checkbox = document.getElementById("changestatus");
            // If checked, set value to 1; otherwise, set value to 0
            var value = checkbox.checked ? 1 : 0;
            $("#statusvalue").val(value);
            console.log("Switch value:", value);
            // You can use 'value' as needed, for example, send it to the server or perform other actions.
        }
    </script>

@push('after-scripts')
  <script>

  $('#commission_level_type').on('change', function (e) {
      var optionSelected = $("option:selected", this);
      var valueSelected = this.value;
      if(valueSelected == 2)
      {
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML = '<div class="subdiv" style="display: flex"><div class="col-md-3"><label for="level_name">Level Name</label><input type="text" name="level_name[]" class="form-control" required/></div><div class="col-md-3"><label for="level_commission">Level Commission(%)</label><input type="text" name="level_commission[]" class="form-control" required/></div><div class="col-md-3"><label for="level_status">Status</label><select class="form-control" name="level_status[]"><option value="1">Active</option><option value="0">Inactive</option></select></div><div class="col-md-3"><a href="javascript:void(0);" class="add_button btn btn-success" title="Add field"><i class="fa fa-plus"></i> Add More</a></div></div>'; 
        $(wrapper).append(fieldHTML);
      }
      // else{
      //   $(wrapper).html('');
      // }
      
  });
$(document).ready(function(){
          
          var maxField = 10; //Input fields increment limitation
          var addButton = $('.add_button'); //Add button selector
          var wrapper = $('.field_wrapper'); //Input field wrapper
          var fieldHTML = '<div class="subdiv" style="display: flex"><div class="col-md-3"><label for="level_name">Level Name</label><input type="text" name="level_name[]" class="form-control" required/></div><div class="col-md-3"><label for="level_commission">Level Commission(%)</label><input type="text" name="level_commission[]" class="form-control" required/></div><div class="col-md-3"><label for="level_status">Status</label><select class="form-control" name="level_status[]"><option value="1">Active</option><option value="0">Inactive</option></select></div><div class="col-md-3"><a href="javascript:void(0);" class="remove_button btn btn-danger" title="Remove field"><i class="fa fa-trash"></i> Remove</a></div></div>'; 
          var x = 1; //Initial field counter is 1
          
          // Once add button is clicked
          $(wrapper).on('click', '.add_button', function(e){
            
              //Check maximum number of input fields
              if(x < maxField){ 
                  x++; //Increase field counter
                  $(wrapper).append(fieldHTML); //Add field html
              }else{
                  alert('A maximum of '+maxField+' fields are allowed to be added. ');
              }
          });
          
          // Once remove button is clicked
          $(wrapper).on('click', '.remove_button', function(e){
              e.preventDefault();
              $(this).closest('.subdiv').remove(); //Remove field html
              x--; //Decrease field counter
          });
      });
    </script>
  @endpush