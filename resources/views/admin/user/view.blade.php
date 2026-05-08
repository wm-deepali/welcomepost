@extends('admin.layout.layout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/default-skin/default-skin.css">
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">User Information</li>
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
   <!-- Main content -->
   <section class="content">
       
      <div class="container-fluid">
         <div class="card card-primary card-outline">
            <div class="card-header">
               <h3 class="card-title">
                 <b><?php echo $info->name; ?> </b> 
               </h3>
            </div>
            <div class="card-body">
               <h4></h4>
               <div class="row">
                  <div class="col-12 col-sm-12">
                     <div class="tab-content" id="vert-tabs-tabContent">
                        <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                           <section class="content">
                              <div class="container-fluid">
                                 <!-- SELECT2 EXAMPLE -->
                                 <div class="card card-default " >
                                    <div class="card-header headertop">
                                       <h3 class="card-title">Personal Profile</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                       <div class="row">
                                       <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>User Image</label></p>
                                            @if(empty($info->google_id))
                                                @if (empty($info->image))
                                                   <img src="{{ asset('admin/') }}/user.png" alt="avatar" alt="{{$info->name}}" class="img-fluid" />
                                                @else
                                                <img src="{{ asset('public/admin/images/') . '/' . $info->image }}" alt="{{$info->name}}" style="width:50px">
                                                @endif
                                            @else
                                                 <img src="{{ $info->image }}" alt="{{$info->name}}" class="img-fluid" />
                                            @endif

                                             
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Referral ID </label></p>
                                                <p><?php echo $info->referral_code; ?></p>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>User Type</label></p>
                                                <p><?php echo $info->user_type; ?></p>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Name</label></p>
                                                <p><?php echo $info->name; ?></p>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Mobile No</label></p>
                                                <p><?php echo $info->mobile; ?></p>
                                             </div>
                                          </div>
                                          <!-- /.col -->
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Email</label></p>
                                                <p><?php echo $info->email; ?></p>
                                             </div>
                                          </div>
                                          <!-- <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>password</label></p>
                                                <p><?php echo $info->password; ?></p>
                                             </div>
                                          </div> -->
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>dob</label></p>
                                                <p><?php echo $info->dob; ?></p>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>address</label></p>
                                                <p><?php echo $info->address; ?></p>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>country</label></p>
                                                <p><?php echo $info->country; ?></p>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>state</label></p>
                                                <p><?php echo $info->state; ?></p>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>city</label></p>
                                                <p><?php echo $info->city; ?></p>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>pin</label></p>
                                                <p><?php echo $info->pin; ?></p>
                                             </div>
                                          </div>
                                          <!-- <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>no_of_ads</label></p>
                                                <p><?php echo $info->no_of_ads; ?></p>
                                             </div>
                                          </div> -->

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Adhar Card Number</label></p>
                                                <p><?php echo $info->adhar_number; ?></p>
                                             </div>
                                          </div>

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Adhar Front Image</label></p>
                                                @if (empty($info->aadharfronts))
                                                   <img src="{{ asset('admin/') }}/user.png" alt="avatar" class="img-fluid" />
                                                @else
                                                <img src="{{ asset('public/admin/images/') . '/' . $info->aadharfronts }}" class="view-image" style="width:100px">
                                                @endif

                                             </div>
                                          </div>

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Adhar Back Image</label></p>
                                                @if (empty($info->aadharback))
                                                   <img src="{{ asset('admin/') }}/user.png" alt="avatar" class="img-fluid" />
                                                @else
                                                <img src="{{ asset('public/admin/images/') . '/' . $info->aadharback }}" class="view-image" style="width:100px">
                                                @endif
                                             </div>
                                          </div>
                                          
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Pan Card Number</label></p>
                                                <p><?php echo $info->pancard_num; ?></p>
                                             </div>
                                          </div>

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Pan Card Image</label></p>
                                                @if (empty($info->pancard))
                                                   <img src="{{ asset('admin/') }}/user.png" alt="avatar" class="img-fluid" />
                                                @else
                                                <img src="{{ asset('public/admin/images/') . '/' . $info->pancard }}" class="view-image" style="width:100px">
                                                @endif
                                             </div>
                                          </div>

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Bank Name</label></p>
                                                <p><?php echo $info->bank_name; ?></p>
                                             </div>
                                          </div>

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Bank Branch</label></p>
                                                <p><?php echo $info->bank_branch; ?></p>

                                             </div>
                                          </div>

                                        

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Account Name</label></p>
                                                <p><?php echo $info->account_name; ?></p>

                                             </div>
                                          </div>

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Account Number</label></p>
                                                <p><?php echo $info->account_number; ?></p>
                                               
                                             </div>
                                          </div>

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>Account IFSC</label></p>
                                                <p><?php echo $info->account_ifsc; ?></p>
                                               
                                             </div>
                                          </div>

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>UPI ID</label></p>
                                                <p><?php echo $info->upi_id; ?></p>

                                                
                                             </div>
                                          </div>

                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <p><label>QR Code </label></p>
                                                @if (empty($info->cheque))
                                                   <img src="{{ asset('admin/') }}/user.png" alt="avatar" class="img-fluid" />
                                                @else
                                                <img src="{{ asset('public/admin/images/') . '/' . $info->cheque }}" class="view-image" style="width:50px">
                                                @endif
                                             </div>
                                          </div>
                                          

                                       </div>
                                    </div>
                                    <!-- /.card-body -->

                                 </div>
                                 <!-- /.row -->
                              </div>
                              <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="pswp__bg"></div>
                                <div class="pswp__scroll-wrap">
                                    <div class="pswp__container">
                                        <div class="pswp__item"></div>
                                        <div class="pswp__item"></div>
                                        <div class="pswp__item"></div>
                                    </div>
                                    <div class="pswp__ui pswp__ui--hidden">
                                        <div class="pswp__top-bar">
                                            <div class="pswp__counter"></div>
                                            <button class="pswp__button pswp__button--close" aria-label="Close (Esc)"></button>
                                            <button class="pswp__button pswp__button--zoom" aria-label="Zoom in/out"></button>
                                            <div class="pswp__preloader">
                                                <div class="loading-spin"></div>
                                            </div>
                                        </div>
                                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                            <div class="pswp__share-tooltip"></div>
                                        </div>
                                        <button class="pswp__button pswp__button--arrow--left" aria-label="Previous (arrow left)"></button>
                                        <button class="pswp__button pswp__button--arrow--right" aria-label="Next (arrow right)"></button>
                                        <div class="pswp__caption">
                                            <div class="pswp__caption__center"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           </section>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
   </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe-ui-default.min.js"></script>
<script>
    var pswpElement = document.querySelectorAll('.pswp')[0];
    var items = [];

    // Fetch image data
    document.querySelectorAll('.view-image').forEach(function (el, index) {
        items.push({
            src: el.getAttribute('src'),
            w: el.naturalWidth,
            h: el.naturalHeight
        });

        el.addEventListener('click', function () {
            var options = {
                index: index
            };
            var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.init();
        });
    });
</script>
@endsection