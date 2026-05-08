@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
	 		<div class="col-sm-12 col-md-9">
	 			<div class="profile-cont table-responsive">
	 				<h3>{{$page}}</h3>
	 				<table class="table">
                        <thead>
                            <tr>
                                <!--<th scope="col">#</th>-->
                                <th scope="col">Ad Title</th>
                                <th scope="col">Name</th>
                                <th scope="col">Mobile</th>
                                {{-- <th scope="col">Message</th> --}}
                                <th scope="col">Registered Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="sort-ads-html">
                            @if(isset($my_ads))
                                @foreach($my_ads as $index => $orderDetail)
                                    @php
                                        $my_enquiry = DB::table('ads_enquiries')->where('user_id', '!=', NULL)->where('post_id', $orderDetail->id)->orderby('id', 'desc')->get();
                                    @endphp
                                    
                                    @foreach($my_enquiry as $ads)
                                    <tr>
                                        <!--<th scope="row">{{$index + 1}}</th>-->
                                        <td>{{ ucfirst($orderDetail->ad_title) }}</td>
                                        <td>{{ $ads->name }}</td>
                                        @if($ads->status == "pending")
                                        <td id="mobile-cell-{{$ads->id}}" onclick="showToast()">{{ substr($ads->mobile, 0, 3) . '****' . substr($ads->mobile, 7) }}</td>
                                        @else
                                        <td>{{ $ads->mobile ?? "--" }}</td>
                                        @endif
                                        {{-- <td>{{ $ads->message }}</td> --}}
                                        <td>{{ $ads->created_at }}</td>
                                        @if($ads->status == "pending")
                                        <td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#replyModal{{$ads->id}}">
                                                Reply
                                            </button>
                                        </td>
                                        <div class="modal fade" id="replyModal{{$ads->id}}" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel{{$ads->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form id="replyForm{{$ads->id}}" action="{{ route('submit.enquiry.reply') }}" method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="replyModalLabel{{$ads->id}}">Reply</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <textarea name="reply" class="form-control" rows="4"></textarea>
                                                            <input type="hidden" value="{{$ads->id}}" name="ad_id">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Send</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <script>
                                            $(document).ready(function() {
                                                $('#replyForm{{$ads->id}}').submit(function(e) {
                                                    e.preventDefault(); // Prevent the default form submission
                                        
                                                    // Submit the form using AJAX
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: $(this).attr('action'),
                                                        data: $(this).serialize(),
                                                        success: function(response) {
                                                            if(response.success){
                                                                $('#replyModal{{$ads->id}}').modal('hide');
                                                                window.location.reload();
                                                            }
                                                        },
                                                        error: function(xhr, status, error) {
                                                            // Handle errors if any
                                                            console.error(xhr.responseText);
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                        @else
                                        <td><button type="button" class="btn btn-danger" disabled>Sent</button></td>
                                        @endif
                                        <td>
                                            @if($ads->isBlocked==0)
                                            <button type="button" class="btn btn-primary block_chat" data-enquiry-id="{{$ads->id}}" data-sender-id="{{$ads->user_id}}">
                                            Block User</button>
                                            @else
                                            <button type="button" class="btn btn-primary unblock_chat" data-enquiry-id="{{$ads->id}}" data-sender-id="{{$ads->user_id}}">
                                                UnBlock</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            @endif
                        </tbody>
                    </table>
	            </div>
	        </div>
    	</div>
	</div>
</section>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showToast() {
        toastr.options = {
            "positionClass": "toast-bottom-center",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "progressBar": true,
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "closeButton": true,
            "backgroundColor": "3d3f94",
             "color": "#fff",
        };

        toastr.info('You can\'t view contact details until you reply.');
    }
    $(".unblock_chat").click(function() {
        let sender_id = $(this).data('sender-id');
        let enquiry_id = $(this).data('enquiry-id');
        $.ajax({
            url: '{{ url('block-chat') }}',
            method: 'POST',
            data: {
                sender_id: sender_id,
                enquiry_id: enquiry_id,
                '_token': "{{ csrf_token() }}"
            },
            success: function(data) {
                console.log(data);
                if (data.success) {
                    Swal.fire(data.message);
                    setTimeout(function() {
                        location.reload();
                    }, 40);
                }
            }
        });
    });
    $(".block_chat").click(function() {
    let sender_id = $(this).data('sender-id');
    let enquiry_id = $(this).data('enquiry-id');

    // Show the Swal popup
    Swal.fire({
        title: 'Block User',
        html: `
            <div>
                <label for="reason">Select a reason for blocking:</label>
                <select id="block_reason" class="swal2-input">
                    <option value="spam">Spam</option>
                    <option value="abusive language">Abusive Language</option>
                    <option value="inappropriate content">Inappropriate Content</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div id="custom_reason_container" style="display: none;">
                <label for="custom_reason">Please specify:</label>
                <textarea id="custom_reason" class="swal2-textarea"></textarea>
            </div>
            <div>
                <p><p style="color:red;">Note: </p>Company will verify your block user action,<br> if you are wrong your account can be <br>suspended by the company.</p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Block',
        preConfirm: () => {
            const blockReason = Swal.getPopup().querySelector('#block_reason').value;
            const customReason = Swal.getPopup().querySelector('#custom_reason').value;

            if (blockReason === 'other' && !customReason) {
                Swal.showValidationMessage('Please provide a reason for blocking');
                return false;
            }

            return {
                blockReason: blockReason === 'other' ? customReason : blockReason
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const blockReason = result.value.blockReason;

            // Send the AJAX request with the block reason
            $.ajax({
                url: '{{ url('block-chat') }}',
                method: 'POST',
                data: {
                    sender_id: sender_id,
                    enquiry_id: enquiry_id,
                    block_reason: blockReason,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(data) {
                    console.log(data);
                    if (data.success) {
                        Swal.fire(data.message);
                        setTimeout(function() {
                            location.reload();
                        }, 40);
                    }
                }
            });
        }
    });

        // Show/hide the custom reason textarea based on the dropdown selection
        $(document).on('change', '#block_reason', function() {
            if ($(this).val() === 'other') {
                $('#custom_reason_container').show();
            } else {
                $('#custom_reason_container').hide();
            }
        });
    });
</script>
@stop