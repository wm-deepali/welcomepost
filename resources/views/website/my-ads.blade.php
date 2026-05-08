<style>
    .table-responsive table {
        display: block;
        width: 100%;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }
</style>
<style>
    .modal-body {
      position: relative;
      text-align: center;
    }
    
    /* CSS for image inside modal body */
    .modal-body img {
      max-width: 100%;
      height: auto;
    }
    
    /* CSS for previous and next buttons */
    .prev1, .next1 {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: auto;
      padding: 10px;
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    
    .prev1 {
      left: 15px;
    }
    
    .next1 {
      right: 15px;
    }
    
    .prev1:hover, .next1:hover {
      background-color: rgba(0, 0, 0, 0.8);
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/default-skin/default-skin.css">
@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
	 		<div class="col-sm-12 col-md-9">
	 			<div class="profile-cont">
	 			    <button type="button" id="bucketBut" class="btn btn-primary" data-total="{{$remaining_ads}}" data-toggle="modal" data-target="#adsModal">Post Ads</button>

                    <div class="modal fade" id="adsModal" tabindex="-1" role="dialog" aria-labelledby="adsModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#337ab7">
                                    <h5 class="modal-title" id="adsModalLabel" style="color:#fff; font-weight:600; font-size:20px;">Ads Bucket</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="overflow:scroll;overflow-y:hidden;">
                                    <div class="col-12">
                                        @if($subscription_exists)
                                            <div class="col-md-12">
                                                <table class="table table-responsive">
                                                    <thead style="background-color:#80808057;">
                                                        <tr>
                                                            
                                                            <th scope="col" style="border:1px solid white;"><div style="width:130px;">Subscription ID</div></th>
                                                            <th scope="col" style="border:1px solid white;"><div style="width:130px;">Ad Bucket</div></th>
                                                            <th scope="col" style="border:1px solid white;"><div style="width:130px;">Ad Status</div></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($subscription_bucket as $subscription)
                                                            @php
                                                            	$used_ads = \App\Models\Adposting::where('user_id',$user_id)->where('subscription_id',$subscription->id)->where('delete_status',0)->orderby('id','desc')->get();
                                                            @endphp
                                                            @for($i = 0; $i < $subscription->remaining_ads; $i++)
                                                            <tr>
                                                                <td>{{ $subscription->subscription_number }}</td>
                                                                <td>Bucket {{ $used_ads[$i]->id ?? $subscription->id+$i }}</td>
                                                                <td>
                                                                    @if(isset($used_ads[$i]))
                                                                        <a href="#" class="btn btn-danger btn-sm" disabled>Used</a>
                                                                    @else
                                                                        <a href="{{route('post-ads')}}" class="btn btn-success btn-sm">Create</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @endfor
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <hr>
                                            <h3>No active subscription found 
                                                <a href="{{ route('purchase-subscription') }}" class="btn btn-primary">Subscribe</a>
                                            </h3>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
	 				<h3>My Ads 
	 					<span class="filter float-right">
		 					<select class="ads-sort" id="ads-sort">
		 						<option value="all">View All</option>
		 						<option value="pending">Pending Ads</option>
		 						<option value="active">Active Ads</option>
		 						<option value="reject">Reject</option>
		 						<option value="expire">Expire Ads</option>
		 					</select>
	 					</span>
	 				</h3>
	 				<div class="sub-exp">
	 			     @if(isset($expiry_history))
                     @php
                     $result 		= DB::table('subscriptions')->where('id',$expiry_history->subscription_id)->first();
                     $status_dates 	= date("d-m-Y");  
                     $no    			= explode(" ",$expiry_history->subscription_validity);
                     $nos            = $no[0] + 1;
                     $dates 			= $expiry_history->created_at;	
                     $date  			= date_create($dates);
                     date_add($date,date_interval_create_from_date_string($nos."days"));
                     $subscription_expiry = date_format($date,"d-m-Y");
                     $subs_date = explode(" ",$expiry_history->created_at);  
                     $dats_subs = date_create($subs_date[0]); 
                     $time_subs = date_create($subs_date[1]);
                     $cal_date = date_format($dats_subs,"d-m-Y");
                     $days_ago = date('Y-m-d', strtotime('-3 days', strtotime($subscription_expiry)));
                     @endphp
                     {{--Subscription Expiring On -	<span class="red">{{ date("jS F Y", strtotime($subscription_expiry)) }}</span> | {{ $used_ads }}/{{ $remaining_ads }} Ads Used
                     @if(strtotime($status_dates) > strtotime($subscription_expiry))
                     <a href="{{ url('purchase-subscription')}}"><span style="cursor:pointer;background-color:#3d3f94; color:#fff; padding:7px 15px; margin-left:10px; font-size:14px; border-radius: 5px;">Upgrade Now <i class="material-icons" style="vertical-align:middle;font-size:22px;margin-left:5px;transition:all 0.4s ease;">arrow_forward</i></span></a>
                     @endif
                     @else--}}
                     @if(!$subscription_exists)
                     You have no subscription, please Buy atleast one subscription
                     <a href="{{ url('purchase-subscription')}}"><span style="cursor:pointer;background-color:#3d3f94; color:#fff; padding:10px;">Upgrade Now <i class="material-icons" style="vertical-align:middle;font-size:22px;margin-left:5px;transition:all 0.4s ease;">arrow_forward</i></span></a>
                     @endif
                     @endif 
                     <!--<p>All active or live ads will expire on Subscription Expiry Date</p>-->
	 				</div>
	 				<div class="table-responsive">
	 				    <table class="table">
	 					<thead class="colored-thead" style="background-color:#80808057;" >
	 						<tr>
	 							<th scope="col" style="border:1px solid white;">Date & Time</th>
	                            <th scope="col" style="border:1px solid white;" >Image</th>
	                            <th scope="col" style="border:1px solid white;"> Ad Title</th>
	                            <th scope="col" style="border:1px solid white;"> Published Date</th>
	                            <th scope="col" style="border:1px solid white;"> Expiry Date</th>
	                            <th scope="col" style="border:1px solid white;"> Bucket Name</th>
	                            <th scope="col" style="border:1px solid white;"> Subscription ID</th>
	                            <th scope="col" style="border:1px solid white;"> Category</th>
	                            <th scope="col" style="border:1px solid white;">Status</th>
	                            <th scope="col" style="border:1px solid white;">Action</th>
	                        </tr>
	                    </thead>
	                    <tbody id="sort-ads-html">
	                    	@if(isset($my_ads) && count($my_ads)>0)
                            @foreach($my_ads as $index=>$ads)
                            @php
                                $adImages = App\Models\AdPostingImage::where('ads_id', $ads->ad_id)->orderBy('id', 'asc')->get();
                            @endphp
	                    	<tr>
	                    		<th scope="row">{{$ads->created_at}}</th>
	                    	    <td>
	                    	        <img src="{{ $ads->image }}" width="60px" class="img-thumbnail view-image"  data-ad-id="{{ $ads->ad_id }}">
	                    	    </td>
	                    	    @foreach($adImages as $imageAd)
                                    <img src="{{$imageAd->image}}" class="view-image" data-ad-id="{{$ads->ad_id}}" style="height:0px;width:0px;">
                                @endforeach
	                            <td>{{ $ads->ad_title}}</td>
	                             <td>{{ $ads->published_date}}</td>
	                             <td>{{ $ads->ad_expiry}}</td>
	                             <td>Bucket {{ $ads->id ?? ""}}</td>
	                             <td>#{{ $ads->subscriptionhistory->subscription_number ?? ""}}</td>
	                             <td>{{ $ads->category->name ?? "-"}}</td>
	                            <td>
	                                @if(strtotime($ads->subscriptionhistory->subscription_expiry) >= strtotime(now()))
	                            	@if($ads->status == 0)
	                            		Pending
	                            	@elseif($ads->status == 1)
	                            		Active
	                            	@elseif($ads->status == 2)
	                            		Reject
	                            	@elseif($ads->status == 3 || $ads->active_status == 0)
	                            		Expire
	                            	@endif
	                            	@else
	                            	Expire
                                    @endif
	                            </td>
	                            <td>
	                                @if(strtotime($ads->subscriptionhistory->subscription_expiry) >= strtotime(now()))
	                                @if($ads->ad_expiry > date('Y-m-d'))
                                        <a title="Edit" href="{{ url('edit-ads')}}/{{ encrypt($ads->formtype) }}/{{ encrypt($ads->category_id) }}/{{ encrypt($ads->id) }}"><i class="material-icons">edit</i></a>
                                    @endif
                                    @endif
	                                <a title="View" class="btn-view" data_id="{{$ads->id}}" data-toggle="modal" data-target="#ads-modal" style="cursor:pointer;"><i class="material-icons">visibility</i></a>
	                                @if($ads->ad_expiry > date('Y-m-d'))
	                                <a title="Delete" class="btn-delete" href="javascript:void(0)" data-href="{{ url('delete-ads')}}/{{$ads->id}}"><i class="material-icons">delete</i></a>
	                                @endif
	                                <!--<a title="Connect to Chat" href="{{ url('user-chat')}}" ><i class="material-icons">chat</i></a>-->
	                                </td>
	                        </tr>
	                        @if($ads->status == 2 && !is_null($ads->reason))
                                <tr>
                                    <td colspan="9">
                                        <div class="rejection-note">
                                            <strong>Rejection Note:</strong> {{$ads->reason}}
                                        </div>
                                    </td>
                                </tr>
                            @endif
	                        @endforeach
	                        @endif
	                    </tbody>
	                </table>
	 				</div>
	            </div>
	        </div>
 <!--   	</div>-->
	<!--</div>-->
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
<div id="ads-modal" class="modal fade" aria-labelledby="myAdsModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myAdsModalLabel">View</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Date & Time:-</b><p id="date"></p>
                    </div>
                    <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Image:-</b><p><img src="" id="image" width="60px"></p>
                    </div>
                    <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Ad Title:-</b><p id="ad-title"></p>
                    </div>
                    <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Published Date:-</b><p id="publish-date"></p>
                    </div>
                    <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Expiry Date:-</b><p id="expiry-date"></p>
                    </div>
                    <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Subscription ID:-</b><p id="subscription-id"></p>
                    </div>
                    <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Category:-</b><p id="category"></p>
                    </div>
                     <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Sub Category:-</b><p id="sub-category"></p>
                    </div>
                    <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Ad Type:-</b><p id="ad-type"></p>
                    </div>
                    <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Description:-</b><p id="description"></p>
                    </div>
                    <div class="col-sm-3">
                        <b class="label-control label" style="color: blue;">Price:-</b><p id="price"></p> 
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe-ui-default.min.js"></script>
<script>
// document.addEventListener('DOMContentLoaded', function () {
//     var pswpElement = document.querySelectorAll('.pswp')[0];

//     function setupThumbnail(thumbnail) {
//         thumbnail.addEventListener('click', function () {
//             var adId = this.getAttribute('data-ad-id');
//             var fallbackImage = this.getAttribute('src');
            
//             fetch('{{ url("get-ad-images") }}/' + adId)
//                 .then(response => response.json())
//                 .then(data => {
//                     var items = data.length ? data.map(image => ({
//                         src: image.url,
//                         w: image.width,
//                         h: image.height
//                     })) : [{
//                         src: fallbackImage,
//                         w: this.naturalWidth,
//                         h: this.naturalHeight
//                     }];

//                     var options = {
//                         index: 0 // Start at the first image
//                     };

//                     var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
//                     gallery.init();
//                 });
//         });
//     }
//     document.querySelectorAll('.img-thumbnail').forEach(setupThumbnail);
// });
</script>
<script>
    var currentIndex = 0;
    var slides = document.getElementsByClassName("slider-image");

    function showSlide(index) {
        if (index >= slides.length) {
            index = 0;
        }
        if (index < 0) {
            index = slides.length - 1;
        }
        for (var i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[index].style.display = "block";
        currentIndex = index;
    }

    function nextImage() {
        showSlide(currentIndex + 1);
    }

    function prevImage() {
        showSlide(currentIndex - 1);
    }

    // Show the first slide initially
    showSlide(0);
</script>
<script type="text/javascript">
 $(document).ready(function () {
        $(document).on("click", ".btn-delete", function(event) {
            event.preventDefault();
            var url = $(this).data('href');
            var button = $(this);

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                         data: { "_token": "{{csrf_token()}}" },
                        success: function (response) {
                            if (response.success) {
                                // $(this).parents("tr").remove();
                                // Item deleted successfully
                                Swal.fire('Deleted!', `Ads deleted successfully.`, 'success');
                                 button.closest('tr').remove();
                                // You can also remove the item from the UI here
                            } else {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'Failed to delete the item.', 'error');
                        }
                    });
                }
            });
        });
         $(document).on("click", ".btn-view", function(event) {
            let id = $(this).attr('data_id');
            $.ajax({
                url: `{{ URL::to('view-ads/${id}') }}`,
                type: "get",
                dataType: "json",
                success: function(result) {
                    if (result.success) {
                       let data = result.data;
                        // Populate modal fields with data
                        let createdAtDate = new Date(data.created_at);
                        // Define options for date formatting
                        let options = { day: '2-digit', month: 'short', year: 'numeric' };
                        // Format the date
                        let formattedDate = createdAtDate.toLocaleDateString('en-US', options);
                        // Populate the modal with the formatted date
                        $('#ads-modal').find('#date').text(formattedDate);
                        $('#ads-modal').find('#image').attr('src', data.image);
                        $('#ads-modal').find('#ad-title').text(data.ad_title);
                        $('#ads-modal').find('#publish-date').text(data.published_date);
                        $('#ads-modal').find('#expiry-date').text(data.ad_expiry);
                        $('#ads-modal').find('#subscription-id').text(`#${data.subscriptionhistory.subscription_number}`);
                        $('#ads-modal').find('#category').text(data.category.name);
                        $('#ads-modal').find('#sub-category').text(data.subcategory.name);
                        $('#ads-modal').find('#ad-type').text(data.ad_type);
                        $('#ads-modal').find('#description').html(data.description);
                        $('#ads-modal').find('#price').html(data.price);
                    } else {
                        Swal.fire('error','error encountered ' + result.msgText);
                    }
                },
                error: function(error) {
                    Swal.fire('eroor','error encountered ' + error.statusText);
                }
            });
        });
        $(document).on("click", '.close', function(event){
            $('#ads-modal').modal('hide');
        });
    });
$(document).ready(function(){
	$(".ads-sort").change(function(){
		var id = $('.ads-sort').val();
		$.ajax({
            url:'{{url("get-my-ads")}}',
            method:'POST',
            data:{id:id,'_token':"{{csrf_token()}}"},
            success:function(data){
            	console.log(data);
                $('#sort-ads-html').html(data);
                
            }
        });
	});
});
</script>
<script>
    document.getElementById('view-published-ads').addEventListener('click', function(event) {
        event.preventDefault();
        var selectElement = document.getElementById('ads-sort');
        selectElement.value = 'active';
        selectElement.dispatchEvent(new Event('change'));
        $('#adsModal').modal('hide'); // Close the modal
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });

    document.getElementById('view-pending-ads').addEventListener('click', function(event) {
        event.preventDefault();
        var selectElement = document.getElementById('ads-sort');
        selectElement.value = 'pending';
        selectElement.dispatchEvent(new Event('change'));
        $('#adsModal').modal('hide'); // Close the modal
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });
</script>
<script>
$(document).ready(function(){
	$(".more_ads").on("click", function(){
	   let user_id = $(this).attr('user-id');
       $.ajax({
            url:'{{url("check-user-subscription")}}',
            method:'GET',
            data:{user_id:user_id,'_token':"{{csrf_token()}}"},
            success:function(response){
                if(response.success == '500')
                {
                    $("#exampleModal").html(response.html);
                    $("#exampleModal").modal('show');
                }else{
                    $(".otherMsg").html(response.html);
                    $("#exampleModalError").modal('show');
                }
               
            }
        });
	});
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
         var pswpElement = document.querySelectorAll('.pswp')[0];
        document.querySelectorAll('.view-image').forEach(function (thumbnail) {
            thumbnail.addEventListener('click', function () {
                var adId = this.getAttribute('data-ad-id');
                var fallbackImage = this.getAttribute('src');
                
                fetch('{{ url("get-ad-images") }}/' + adId)
                    .then(response => response.json())
                    .then(data => {
                        var items = data.length ? data.map(image => ({
                            src: image.url,
                            // w: image.width,
                            // h: image.height
                            w: this.naturalWidth,
                            h: this.naturalHeight
                        })) : [{
                            src: fallbackImage,
                            w: this.naturalWidth,
                            h: this.naturalHeight
                        }];

                        var options = {
                            index: 0 // Start at the first image
                        };

                        var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                        gallery.init();
                    });
            });
        });
    });
</script>
@stop