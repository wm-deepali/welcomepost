@extends('admin.layout.layout')
<style>
    .dfg {
    display: flex;
    flex-wrap: wrap;
    margin: 20px;
    font-family: Arial, sans-serif;
}

.w-40, .w-60 {
    padding: 10px;
    box-sizing: border-box;
}

.w-40 {
    width: 40%;
    border-right: 1px solid #ddd;
    background-color: #f7f7f7;
    float: left;
}

.w-60 {
    width: 60%;
    background-color: #fff;
    float: right;
}

@media (max-width: 768px) {
    .w-40, .w-60 {
        width: 100%;
        float: none;
        border-right: none;
        background-color: #fff; /* Adjust background color for full width */
    }
}

.chat-top {
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.inbox {
    font-size: 20px;
    font-weight: bold;
}

.filter .tab-content .tab-pane {
    max-height: 600px;
    overflow-y: auto;
}

.chat-filter-scroll {
    cursor: pointer;
    padding: 10px;
    border-bottom: 1px solid #ddd;
    display: flex;
    align-items: center;
}

.chat-user-details {
    display: flex;
    width: 100%;
}

.chat-user-profile {
    width: 50px;
    height: 50px;
    background-color: #ccc;
    border-radius: 50%;
    margin-right: 10px;
}

#proPic{
    width: 50px;
    height: 50px;
    background-color: #ccc;
    border-radius: 50%;
    margin-right: 10px;
}

.chat-user-detail-presonal {
    width: calc(100% - 60px);
}

.user-name-chat {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
}

.msg-count .msg-div {
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 5px 10px;
    font-size: 14px;
    margin-left: 10px;
}

.user-order-name {
    margin-top: 5px;
    color: #555;
}

.right-side-chat {
    display: none;
    padding: 5px;
}

.right-side-chat.tab-active {
    display: block;
}

.chat-section {
    background-color: #f9f9f9;
    padding: 10px;
    border-top: 1px solid #ddd;
}

.container-chats .chat-text {
    background-color: #e1f3fb;
    padding: 5px;
    border-radius: 10px;
    margin-bottom: 10px;
    width: fit-content;
}

.container-chats{
    flex-direction: column;
    max-height: 800px;
    overflow-y: scroll;
}

.container-chats .chat-text.right {
    background-color: #c0e3ff;
    margin-left: auto;
    margin-right: 0;
}

.text-input-container {
    margin-top: 20px;
    display: flex;
    align-items: center;
}

.input-group {
    width: 100%;
    position: relative;
}

.image-select {
    position: absolute;
    right: 10px;
    top: 10px;
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
}

.ques-input {
    width: calc(100% - 70px);
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-right: 10px;
    resize: none;
}

/* Top Chat Bar Container */
.top-chat-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background-color: #f0f0f0; /* Background color */
    border-bottom: 1px solid #ccc; /* Example border for separation */
}

/* Profile Image Styling */
.chat-user-profile img {
    width: 40px; /* Adjust size as needed */
    height: 40px; /* Adjust size as needed */
    border-radius: 50%; /* Round profile image */
}

/* User Details Styling */
.user-details {
    margin-left: 10px;
}

.user-name-chat {
    font-weight: bold;
    margin-bottom: 5px;
}

/* Call Icon Styling */
.call-user {
    margin-left: 0; /* Pushes .call-user to the far right */
    cursor: pointer; /* Pointer cursor on hover */
}

/* Dropdown Arrow Styling */
.dropdown-toggle::after {
    display: inline-block;
    margin-left: 5px;
    vertical-align: middle;
    content: "";
    border-top: 4px solid;
    border-right: 4px solid transparent;
    border-left: 4px solid transparent;
}

/* Dropdown Menu Styling */
.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000; /* Ensure dropdown appears above other content */
    background-color: #fff; /* Dropdown background color */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Example box shadow */
    border: 1px solid #ccc; /* Example border */
}

.dropdown-item {
    font-size: 14px; /* Adjust font size as needed */
    padding: 8px 12px; /* Adjust padding as needed */
    color: #333; /* Text color */
    text-decoration: none; /* Remove default underline */
    display: block; /* Ensure dropdown items are block-level */
}

.dropdown-item:hover,
.dropdown-item:focus {
    background-color: #f0f0f0; /* Hover background color */
    color: #000; /* Hover text color */
    text-decoration: none; /* Remove default underline on hover */
}

.chat-delete-button {
    margin-left: auto;
}

.delete-btn {
    background-color: #e4606d;
    color: white;
    border: none;
    float:right;
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
    font-size: 12px;
}

.delete-btn:hover {
    background-color: #d9534f;
}

</style>
@section('content')
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Chat Support</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Chat Support</li>
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
          <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dfg"  style="display:block;">
                        <div class="d-inline" style="display: flex;">
                            <div class="w-40">
                                <div class="chat-top">
                                    <div class="inbox">Inbox</div>
                                    <div class="filter">
                                        <div class="tab-content list">
                                            <div class="tab-pane chat-f active" id="all" role="tabpanel">
                                                @foreach($chatroom as $key => $orderDetails)
                                                    <div class="chat-filter-scroll tab-a" message-data-id="{{ $orderDetails->user_id }}" data-id="{{ $orderDetails->id }}">
                                                        <div class="chat-user-details">
                                                             <?php
                                                                if(session('id') == $orderDetails->user_id) {
                                                                    $result = DB::table('customers')->where('id', 1)->get();
                                                                } else {
                                                                    $result = DB::table('customers')->where('id', $orderDetails->user_id)->get();
                                                                }
                                        
                                                                $resultcount = DB::table('chat_messages')->where('consumer_id', 1)->where('user_id', $orderDetails->user_id)->where('is_read', '0')->count();
                                                                
                                                                ?>
                                                            @if(!empty($result) && isset($result[0]))
                                                            <div class="chat-user-profile"><img id="proPic" src="{{$result[0]->image}}" alt="{{$result[0]->name ?? ''}}"></div>
                                                            @endif
                                                            <div class="chat-user-detail-presonal">
                                                                <div class="user-name-chat">
                                                                    @if(!empty($result) && isset($result[0]))
                                                                        {{$result[0]->name}}
                                                                    @endif
                                                                    <span class="float-right time">{{ $orderDetails->created_at }}</span>
                                                                </div>
                                                                @if($resultcount != '0')
                                                                    <div class="msg-count">
                                                                        <span class="msg-div">{{ $resultcount }}</span>
                                                                    </div>
                                                                @endif
                                                                <div class="user-order-name">{{ $orderDetails->message }}</div>
                                                                <div class="chat-delete-button">
                                                                    <button class="delete-btn" data-room-id="{{ $orderDetails->id }}" data-user-id="{{$orderDetails->user_id}}">Clear</button>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-60">
                                @foreach($chatroom as $key => $orderDetails)
                                @php
                					$max_id;
                					$id                 = $orderDetails->id;
                					$consumer_id        = $orderDetails->user_id;
                					$session_user_id    = $orderDetails->sender_id;
                					$sender_id          = $session_user_id;
                					
                				@endphp
                				<div class="right-side-chat tab @if($max_id == $id) tab-active @endif" id="{{$orderDetails->id}}" data-id="tab<?php echo $orderDetails->id; ?>">
                                    <div class="top-chat-bar">
                                        <?php
                							   if(session('id') == $orderDetails->user_id)
                							    {
                							        $result         = DB::table('customers')->where('id',1)->get();
                							    }else{
                							        $result         = DB::table('customers')->where('id',$orderDetails->user_id)->get();
                							    }
                							    
                							?>
                						@if(!empty($result) && isset($result[0]))
                                        <div class="chat-user-profile"><img id="proPic" src="{{$result[0]->image}}" alt="{{$result[0]->name ?? ''}}"></div>
                                        @endif
                                        <div class="user-name-chat">
                							{{$result[0]->name ?? ''}}
                						</div>
                						
                						<div class="right-ico">
                						    <a href="tel:{{$result[0]->mobile ?? ''}}">
                							<div class="call-user" style="margin-right:300px;">
                								<svg width="24px" height="24px" viewBox="0 0 1024 1024" data-aut-id="icon" class="" fill-rule="evenodd">
                									<path class="rui-w4DG7" d="M746.086 916.070c-67.994 0-258.253-16.589-427.213-185.549-205.619-205.414-185.344-442.573-184.32-452.608l1.229-12.083 100.352-125.747h178.176l103.629 103.629v170.803l-74.547 74.752c11.878 18.432 29.901 43.622 51.814 65.536 22.118 22.118 47.514 40.346 65.946 52.019l74.547-72.090 192.512 0.205 79.872 101.99v178.586l-124.314 98.714-12.083 1.229c-1.638-0.205-10.65 0.614-25.6 0.614zM215.45 296.96c-1.229 45.875 5.734 219.955 161.382 375.603 155.443 155.443 329.318 162.611 375.808 161.382l73.523-58.368v-110.797l-37.888-48.538h-119.603l-94.618 91.341-26.214-13.722c-2.458-1.229-62.054-32.973-110.797-81.715s-80.282-108.339-81.715-110.797l-14.131-26.624 94.413-94.618v-103.014l-55.706-55.706h-104.858l-59.597 75.571zM776.602 333.619l72.704-72.704v-106.291l-56.934-56.934h-116.531l-55.501 55.501v90.726h81.92v-56.73l7.578-7.578h48.742l9.011 9.011v38.502l-72.704 72.704v80.691h81.92v-46.899zM776.602 421.683h-81.92v73.318h81.92v-73.318z"></path>
                								</svg>
                							</div>
                							</a>
                							<!--<li class="nav-item dropdown">-->
                       <!--                         <a class="nav-link dropdown-toggle" href="#"-->
                       <!--                             id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"-->
                       <!--                             aria-expanded="false">-->
                                                    
                       <!--                         </a>-->
                       <!--                         <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="width:auto !important;">-->
                       <!--                             <li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;">-->
                       <!--                                 <a href="#" class="dropdown-item clear_chat" sender_id="{{ $orderDetails->user_id}}" style="background:none;border:none;color:#000">Clear Chat</a>-->
                							<!--		</li>-->
                							<!--	</ul>-->
                       <!--                     </li>-->
                						</div>
                                    </div>
                                    <div class="inner-chat-quick">
                                        <ul class="list-style-none">
                                            <li>
                							@php
                							    $result = DB::table('chat_room')->where('isAdmin',$isAdmin)->where('id',$orderDetails->id)->get();
                							    echo $result[0]->message ?? '';
                							@endphp
                							</li>
                						</ul>
                                    </div>
                                    <!--<div class="text-center timming-date"><b>Today</b></div> -->
                                    <div class="chat-section">
                                        <div class="container-chats">
                                             <?php
                							    $messageChat = DB::select(DB::raw("select * from chat_messages where user_id = '$session_user_id' AND consumer_id = '$consumer_id' OR user_id = '$consumer_id' AND  consumer_id = '$session_user_id' "));
                							    if(isset($result) && count($result) > 0){
                    							    if($result[0]->clear_chat == '0')
                    							    {
                    								    foreach ($messageChat as $row) 
                    								    { 
                    								        $subs_date = explode(" ",$row->created_at);  
                                            				$dats_subs = date_create($subs_date[0]); 
                                            				$time_subs = date_create($subs_date[1]);
                                            				
                    								        $user_id = $session_user_id;
                    								        $reciever_id = $row->reciever_id;
                    								        $sender_id = $row->sender_id;
                    							            if($user_id == $reciever_id)
                    							            { 
                							 ?>
                								 <div class="chat-text" style="margin-top:20px;">
                								     <span class="message"><?php echo $row->topic;?></span>
                									<p style="margin-top:10px;">{{ date_format($dats_subs,"d-m-Y") }} | {{ date_format($time_subs,"H:i A") }}</p>
                                                    <?php }else{ ?> 
                								<div class="chat-text right" style="margin-top:20px;">
                								    <span class="message"><?php echo $row->topic;?></span>
                									<p style="margin-left: 444px; margin-top:10px;">{{ date_format($dats_subs,"d-m-Y") }} | {{ date_format($time_subs,"H:i A") }}</p>
                                                <?php } ?> 
                									
                                               </div>
                									
                								<?php } }else { ?>
                								    <span class="message" style="color:grey;"><i>Chat deleted</i></span>
                							<?php	}} ?>
                							    <br>
                							<?php if(isset($result) && count($result) > 0 && $result[0]->block == '1'){ ?> 
                							    <span class="message" style="color:grey;"><i>User block</i></span>
                							<?php } ?>	
                                            </div>
                                        </div>
                                        <div class="text-input-container">
                                            <div class="input-group">
                                                <!--<span class="image-select" >-->
                                                <!--    <svg  width="24px" height="24px" viewBox="0 0 1024 1024" data-aut-id="attachIcon" class="" fill-rule="evenodd"><path class="rui-w4DG7" d="M537.073 92.46l-444.613 459.216v317.889l53.922 61.979h328.968l456.194-471.197v-83.726l-200.432-207.078h-81.108l-393.966 406.905v180.847h202.545l257.273-265.731-58.957-60.921-232.905 240.557h-84.635v-59.109l351.22-362.7 163.678 169.066-413.399 426.992h-265.077v-258.13l420.197-433.991z"></path></svg>-->
                                                <!--</span>-->
                                                <button class="btn btn-success image-select" class="sendMsg" id="postbtn<?php echo $orderDetails->id; ?>" style="right:0 !important; height: 58px !important;">Send</button>
                                                <?php if(isset($result) && count($result) > 0 && $result[0]->block == '1'){ ?>
                                                <textarea class="ques-input" id="messagepost<?php echo $orderDetails->id; ?>" placeholder="Type a message" disabled style="background-color: #e8e8e8;"></textarea>
                								<?php }else{?>
                								<textarea class="ques-input" id="messagepost<?php echo $orderDetails->id; ?>" placeholder="Type a message"></textarea>
                								<?php } ?>
                								<input type="hidden" id="consumer_id<?php echo $orderDetails->id; ?>" class="form-control" value="<?php echo $consumer_id;?>">
                								<input type="hidden" id="reciever_id<?php echo $orderDetails->id; ?>" class="form-control" value="<?php echo $consumer_id;?>">
                								<input type="hidden" id="user_id<?php echo $orderDetails->id; ?>" class="form-control" value="<?php echo $session_user_id;?>">
                								<input type="hidden" id="is_admin<?php echo $orderDetails->id; ?>" class="form-control" value="<?php echo $isAdmin; ?>">
                						
                							</div>
                                        </div>
                                    </div>
                                    
                					<script>
                			$(document).ready(function(){
                			    function reloadPage() {
                                    $('.right-side-chat.tab-active').each(function() {
                                        var tabId = $(this).attr('id');
                                        var consumer_id = $('#consumer_id' + tabId).val();
                                        var reciever_id = $('#reciever_id' + tabId).val();
                                        var user_id = $('#user_id' + tabId).val();
                                        var is_admin = $('#is_admin' + tabId).val();
                                        
                                        $.ajax({
                                            url: '{{url("get-chat")}}',
                                            method: 'POST',
                                            data: {
                                                consumer_id: consumer_id,
                                                reciever_id: reciever_id,
                                                user_id: user_id,
                                                isAdminChat: is_admin,
                                                admin:1,
                                                '_token': "{{csrf_token()}}"
                                            },
                                            success: function(data) {
                                                $('#'+tabId+' .container-chats').html(data);
                                            }
                                        });
                                    });
                                }
                                
                                setInterval(reloadPage, 8000);
                    			$("#postbtn<?php echo $orderDetails->id; ?>").click(function(){
                        			var messagepost = $('#messagepost<?php echo $orderDetails->id; ?>').val();
                        			var consumer_id = $('#consumer_id<?php echo $orderDetails->id; ?>').val();
                        			var reciever_id = $('#reciever_id<?php echo $orderDetails->id; ?>').val();
                        			var user_id = $('#user_id<?php echo $orderDetails->id; ?>').val();
                        			var is_admin = $('#is_admin<?php echo $orderDetails->id; ?>').val();
                        			if(messagepost == ""){
                        				alert('Plz type Some Message');
                        			}
                        			
                    			
                    			
                        			$.ajax({
                        			url:'{{url("post-chat-message")}}',
                        			method:'POST',
                        			data:{messagepost:messagepost,consumer_id:consumer_id,reciever_id:reciever_id,user_id:1,isAdminChat:1,'_token':"{{csrf_token()}}",admin:1},
                        			success:function(data){
                        				//location.reload(true);
                        			//alert(data);
                        			$('.tab-active .container-chats').html(data);
                        			$('#messagepost<?php echo $orderDetails->id; ?>').val('');
                        			
                        			//$('.chat-text right').html(data);
                        			}
                        			});
                
                		    	});
                		    	
                
                			});
                			</script>
                					
                					@endforeach
                					
                                </div>
                			
                			</div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
      </div>
    </section>
  </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
 $('.delete-btn').on('click', function () {
    var button = $(this);
    var roomId = button.data('room-id');
    var userId = button.data('user-id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, clear it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("admin.clear") }}',
                type: 'POST',
                data: {
                    roomId: roomId,
                    userId: userId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            'Cleared!',
                            'The chat has been cleared.',
                            'success'
                        );
                        location.reload();
                    } else {
                        Swal.fire(
                            'Failed!',
                            'Failed to clear the chat. Please try again.',
                            'error'
                        );
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'An error occurred. Please try again.',
                        'error'
                    );
                    console.error('Error:', error);
                }
            });
        }
    });
});
$(".chat-filter-scroll").click(function(){
	let chat_id = $(this).attr('message-data-id');
	$.ajax({
		url:'{{url("chat-read")}}',
		method:'POST',
		data:{chat_id:chat_id,'_token':"{{csrf_token()}}"},
		success:function(data){
            console.log(data);
		}
	});
});

$(".clear_chat").click(function(){
    let sender_id = $(this).attr('sender_id');
  //  alert(sender_id);
    $.ajax({
		url:'{{url("clear-chat")}}',
		method:'POST',
		data:{sender_id:sender_id,'_token':"{{csrf_token()}}"},
		success:function(data){
            console.log(data);
            if (data.success) 
            {
                Swal.fire(
                    "Chat Cleared"
                );
                setTimeout(function() {
                    location.reload();
                }, 40);
            }
		}
	});
    
})

    $(document).ready(function() {
        $('.chat-user-details').on('click', function() {
            var tabId = $(this).closest('.chat-filter-scroll').attr('data-id');
            console.log(tabId)
            $('.right-side-chat').removeClass('tab-active'); // Remove tab-active from all chat windows
            var selectedTab = document.getElementById(tabId);
            console.log(selectedTab);
            if (selectedTab) {
                selectedTab.classList.add('tab-active');
            }
        });
    });
</script>
@endsection