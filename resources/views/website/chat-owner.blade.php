@extends('website.layout.layout')
@section('title', $page)
@section('content')
    <?php error_reporting(0); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
    .chat-filter-scroll.tab-a {
        cursor: pointer !important;
    }
    
    .chat-d {
      display: none;
    }
    .chat-d.active {
      display: block;
    }
    .right-ico.m {
    	position: absolute;
    	right: 0;
    	width: auto;
    	margin-right: 30px;
    }
    .right-ico.m .call-user {
    	width: 20px;
    }
    .message {
    	border-radius: 20px;
    }
    .chat-page-main-section{
        display:flex;
    
        
    }
    .user-icon{
        width:50px;
        height:50px;
        border-radius:50%;
        overflow:hidden;
        background-color:#fff;
    }
    .chat-user-profile1 {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #fff;
        border: 1px solid #80808026;
        display: flex;
        border-radius: 50%;
        justify-content: center;
        align-items: center;
    }
    
    .chat-user-profile1 img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        object-position: center;
    }

    
    
    .chat-online {
        color: #34ce57
    }
    
    .chat-offline {
        color: #e4606d
    }
    
    .chat-messages {
        display: flex;
        flex-direction: column;
        max-height: 800px;
        overflow-y: scroll;
    }
    
    .chat-message-left,
    .chat-message-right {
        display: flex;
        flex-shrink: 0
    }
    
    .chat-message-left {
        margin-right: auto
    }
    
    .chat-message-right {
        flex-direction: row-reverse;
        margin-left: auto
    }
    .py-3 {
        padding-top: 1rem!important;
        padding-bottom: 1rem!important;
    }
    .px-4 {
        padding-right: 1.5rem!important;
        padding-left: 1.5rem!important;
    }
    .flex-grow-0 {
        flex-grow: 0!important;
    }
    .border-top {
        border-top: 1px solid #dee2e6!important;
    }
    .user-list-chat{
            padding-bottom: 8px;
        border-bottom: 1px solid #80808040;
    }
    </style>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <!-- START -->
    <section class="news-hom-big news-details">
        <div class="container">
            <div class="user-chatting-page-desktop-view">
            <div class="card mb-5">
	            <div class="row g-0">
	                {{--@if($isAdmin!=1)
                    <div class="col-12 col-lg-5 col-xl-3 border-right">
                        <div class="px-4 d-block">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control my-3" id="searchInput" placeholder="Search...">
                                </div>
                            </div>
                        </div>
                        <div class="tab-content list ml-1">
                               
                                <div class="tab-pane chat-f active" id="all" role="tabpanel">
                                        @foreach($chatroom as $key => $orderDetails)
                                        <div class="chat-filter-scroll tab-a" message-data-id= "<?php echo $orderDetails->user_id;?>" data-id="tab<?php echo $orderDetails->id; ?>">
                                            <div class="chat-user-details">
                                                <?php
    											    if(session('id') == $orderDetails->user_id)
    											    {
    											        $result         = DB::table('customers')->where('id',$orderDetails->sender_id)->get();
    											    }else{
    											        $result         = DB::table('customers')->where('id',$orderDetails->user_id)->get();
    											    }
    											    
    											    $resultcount    = DB::table('chat_messages')->where('consumer_id',session('id'))->where('user_id',$orderDetails->user_id)->where('is_read','0')->count();
    											    
    											?>
                                                <div class="chat-user-profile1">
                                                    <img src="{{$result[0]->image}}" class="rounded-circle mr-1" alt="{{$result[0]->name}}" >
                                                </div>
                                                <div class="chat-user-detail-presonal" style='margin-top:5px; border-bottom:1px solid #80808063'>
                                                    <div class="user-name-chat">
        											    @php
                                                            $result = DB::table('customers')
                                                                ->where('id', $orderDetails->sender_id)
                                                                ->get();
                                                                if($result[0]->name==""){
                                                                    echo 'Chat Support';
                                                                }
                                                            echo $result[0]->name;
                                                        @endphp
        											</div>
        											@if($resultcount != '0')
        											<div class="msg-count">
        											    <span class="msg-div">{{ $resultcount }}</span>
        											</div>
        											@endif
        											<span style="font-size:9px">{{$orderDetails->created_at}}</span>
                                                    <div class="user-order-name">{{$orderDetails->message}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                </div>
                            </div>
                        <hr class="d-block d-lg-none mt-1 mb-0">
                    </div>
                    @endif--}}
	            	<div class="col-12 col-lg-12 col-xl-12">
	            	    @foreach($chatroom as $key => $orderDetails)
                        @php
        					$max_id;
        					$id                 = $orderDetails->id;
        					if($user_id!=$orderDetails->user_id){
        					    $consumer_id        = $orderDetails->user_id;
        					}else{
        					    $consumer_id        = $orderDetails->sender_id;
        					}
        					$session_user_id    = $user_id;
        					$sender_id          = $session_user_id;
        					
        				@endphp
                        <div class="right-side-chat tab @if($max_id == $id) tab-active @endif " data-id="tab<?php echo $orderDetails->id; ?>">
                            <div class="py-2 px-4 border-bottom d-block">
                                <?php
    							   if(session('id') == $orderDetails->user_id)
    							    {
    							        $result         = DB::table('customers')->where('id',$orderDetails->sender_id)->get();
    							    }else{
    							        $result         = DB::table('customers')->where('id',$orderDetails->user_id)->get();
    							    }
    							?>
                                <div class="d-flex align-items-center py-1">
                                    <div class="position-relative">
                                        <img src="https://welcomepost.in/assets/website/images/logo.png" class="rounded-circle mr-1" alt="{{$result[0]->name}}" width="40" height="40">
                                    </div>
                                    <div class="flex-grow-1 pl-3">
                                            <strong>{{$result[0]->name}}</strong>
                                            <div class="text-muted small"><em>@php
                                                    							   $result = DB::table('chat_room')->where('isAdmin',$isAdmin)->where('id',$orderDetails->id)->get();
                                                    							   echo $result[0]->message;
                                                    						@endphp</em>
                                            </div>
                                        </div>
                                    <div>
                                        <a href="tel:{{$result[0]->mobile}}" class="btn btn-primary btn-lg mr-1 px-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone feather-lg">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                            </svg>
                                        </a>
                                        <li class="nav-item dropdown btn btn-light border btn-sm px-3" >
                                            <a class="nav-link" href="#"
                                                id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal feather-lg">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="19" cy="12" r="1"></circle>
                                                    <circle cx="5" cy="12" r="1"></circle>
                                                </svg>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="width:auto !important;">
                                                <li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;">
                                                    <a href="#" class="dropdown-item clear_chat" data-sender-id="{{ $orderDetails->sender_id}}" style="background:none;border:none;color:#000">Clear Chat</a>
            									</li>
            									{{--<?php 
            									    $result = DB::table('chat_room')->where('isAdmin',0)->where('id',$orderDetails->id)->get();
            									    if($result[0]->block == '1'){
            									?>
            									<li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;">
            									    <a href="#" class="dropdown-item block_chat" data-sender-id="{{ $orderDetails->sender_id}}" style="background:none;border:none;color:#000">Un-Block</a>
            									</li>
            									<?php } else{ ?>
            										<li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;">
            									    <a href="#" class="dropdown-item block_chat" data-sender-id="{{ $orderDetails->sender_id}}" style="background:none;border:none;color:#000">Block</a>
            									</li>
            									<?php } ?>--}}
                                                
            								</ul>
                                        </li>
                                    </div>
                                </div>
                                <div class="inner-chat-quick">
                                    <ul class="list-style-none">
                                        <li>
                                            @php
                                                $result = DB::table('chat_room')
                                                    ->where('id', $orderDetails->id)->where('isAdmin',$isAdmin)
                                                    ->get();
                                                    if($isAdmin!=1){
                                                        echo $result[0]->message;
                                                    }else{
                                                        echo 'Please be patient while our executive gets online';
                                                    }
                                                
                                            @endphp
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="position-relative">
                                <div class="chat-messages p-4">
                                    <?php
                                    $chat_messages = DB::select(DB::raw("select * from chat_messages where user_id = '$session_user_id' AND consumer_id = '$consumer_id' OR user_id = '$consumer_id' AND  consumer_id = '$session_user_id' "));
                                    
                                    if(count($chat_messages) > 0) {
                                        foreach($chat_messages as $row):
                                            $resultPro = DB::table('customers')->where('id', $row->user_id)->get();
                                            ?>
                                            <div class="<?php echo ($row->user_id == $session_user_id) ? 'chat-message-right' : 'chat-message-left'; ?> pb-4">
                                                <div class="text-center">
                                                    <img src="{{$resultPro[0]->image}}" class="rounded-circle mr-1" alt="{{$resultPro[0]->name}}" width="40" height="40">
                                                    <div class="text-muted small text-nowrap mt-2">{{$resultPro[0]->name}}</div>
                                                </div>
                                                <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                                                    <div class="font-weight-bold mb-1"><?php echo $row->topic; ?></div>
                                                    <p style="margin-top:10px;"><?php echo date_format(date_create($row->created_at),"d-m-Y"); ?> | <?php echo date_format(date_create($row->created_at),"H:i A"); ?></p>
                                                </div>
                                            </div>
                                            <?php
                                        endforeach;
                                    } else {
                                        // Display a message when there are no chat messages
                                        if($orderDetails->block != '1'){
                                        ?>
                                        
                                        <div class="chat-message-center">
                                            <strong>Say hi!!</strong>
                                        </div>
                                        <?php
                                        }
                                    }
                                
                                    // Check if the chat is blocked
                                    if ($orderDetails->user_id==$session_user_id&&$orderDetails->block == '1') {
                                        ?>
                                        <div class="chat-message-center">
                                            <strong>This chat is blocked</strong>
                                        </div>
                                        <?php
                                    }else if($orderDetails->sender_id==$session_user_id&&$orderDetails->block == '1'){
                                        ?>
                                        <div class="chat-message-center">
                                            <strong>You are blocked</strong>
                                        </div>
                                        <?php
                                    }
                                
                                    // Check if the chat is cleared
                                    if ($orderDetails->clear == '1') {
                                        ?>
                                        <div class="chat-message-center">
                                            <strong>Chat is cleared</strong>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                           
                            <div class="flex-grow-0 py-3 px-4 border-top">
                                <div class="input-group">
                                    <?php if($result[0]->block == '1'){ ?>
                                    <input type="text" class="form-control" id="messagepost<?php echo $orderDetails->id; ?>" placeholder="Type your message" disabled style="background-color: #e8e8e8;">
                                     <button class="btn btn-primary" id="postbtn<?php echo $orderDetails->id; ?>" disabled>Send</button>
                                    <?php }else{?>
                                    <input type="text" class="form-control" id="messagepost<?php echo $orderDetails->id; ?>" placeholder="Type your message">
                                      <button class="btn btn-primary" id="postbtn<?php echo $orderDetails->id; ?>" >Send</button>
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
                    			var consumer_id = $('#consumer_id<?php echo $orderDetails->id; ?>').val();
                    			var reciever_id = $('#reciever_id<?php echo $orderDetails->id; ?>').val();
                    			var user_id = $('#user_id<?php echo $orderDetails->id; ?>').val();
                    			var is_admin = $('#is_admin<?php echo $orderDetails->id; ?>').val();
                                $.ajax({
                            		url:'{{url("get-chat")}}',
                            		method:'POST',
                            		data:{consumer_id:consumer_id,reciever_id:reciever_id,user_id:user_id,isAdminChat:is_admin,'_token':"{{csrf_token()}}"},
                            		success:function(data){
                                		$('.chat-messages').html(data);
                            		}
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
                        			data:{messagepost:messagepost,consumer_id:consumer_id,reciever_id:reciever_id,user_id:user_id,isAdminChat:is_admin,'_token':"{{csrf_token()}}"},
                        			success:function(data){
                        				//location.reload(true);
                        			//alert(data);
                        			$('.chat-messages').html(data);
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
    </section>
    <!--END-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(".chat-filter-scroll").click(function() {
            let chat_id = $(this).attr('message-data-id');
            $.ajax({
                url: '{{ url('chat-read') }}',
                method: 'POST',
                data: {
                    chat_id: chat_id,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
        $(".clear_chat").click(function() {
            let sender_id = $(this).data('sender-id');
            $.ajax({
                url: '{{ url('clear-chat') }}',
                method: 'POST',
                data: {
                    sender_id: sender_id,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(data) {
                    console.log(data);
                    if (data.success) {
                        Swal.fire(
                            "Chat Cleared"
                        );
                        setTimeout(function() {
                            location.reload();
                        }, 40);
                    }
                }
            });

        });
        
        $(document).ready(function(){
            $('#searchInput').on('input', function() {
                var searchText = $(this).val().toLowerCase(); // Get the value of the search input and convert it to lowercase
                $('.chat-filter-scroll').each(function() {
                    var customerName = $(this).find('.user-name-chat').text().toLowerCase(); // Get the customer name from each customer tab and convert it to lowercase
                    if (customerName.includes(searchText)) {
                        $(this).show(); // If the customer name contains the search text, show the customer tab
                    } else {
                        $(this).hide(); // Otherwise, hide the customer tab
                    }
                });
            });
        });
    </script>
@endsection
