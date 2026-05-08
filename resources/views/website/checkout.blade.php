@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
 <style>
    /* Custom Tooltip Style */
    .ui-tooltip-content {
      background-color: #3d3f94;
      color: #fff;
      border: 1px solid #007bff;
    }
    .cart-summary-pack{
        border:1px solid;
        padding:10px;
    }
    .cart-summary-pack .cart-sum ul li {
    display: block;
    padding: 10px 0;
    border-bottom: 1px solid #3333;
}
.cart-summary-pack .cart-sum ul li .li-cart {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.cart-summary-pack .cart-sum ul li .li-cart .cart-info h2 {
    font-size: 18px;
    margin: 0;
    font-family: "Lato";
}
.cart-summary-pack .cart-sum ul li .li-cart .cart-info p {
    margin: 0;
    font-size: 11px;
    color: #595959;
}
.cart-summary-pack .cart-sum ul li .li-cart .cart-price {
    font-family: "Lato";
    white-space: nowrap;
}
.cart-summary-pack .cart-sum ul li .li-cart .cart-price i {
    font-size: 90%;
    padding-right: 3px;
}
.cart-summary-pack .cart-sum ul li .total-price span {
    display: block;
    text-align: right;
    font-size: 18px;
    font-weight: 500;
    font-family: "Lato";
}
  </style>
<div class="col-sm-12 col-md-9">
    <div class="profile-cont">
        @if (session('success'))
        <h5 style="color:green;">{{ Session::get('success') }}</h5>
        @php Session::forget('success')@endphp
        @endif
        <h3>Payment</h3>
        <div class="row">
           
            
            <div class="col-sm-12 col-md-4 text-center">
                <div class="subscription-cont">
                    <h3 class="text-center">{{$subscription->package}} 
                    @php
                        $category_list = explode(',', $subscription->category_id);
                        $result = App\Models\Categories::whereIn('id',$category_list)->pluck('name');
                        $categoryall = $result->implode(',');
                    @endphp
                    <i data-toggle="tooltip" data-placement="top" data-html="true" title="{!! $categoryall !!}" style="font-size:24px" class="fa">&#xf05a;</i> </h3>
                    @if($subscription->package_validity == '1' || $subscription->package_validity == '0')
                        @php
                        
                            $total_day = 'Day';
                        @endphp
                    @else
                        @php
                            $total_day = 'Days';
                        @endphp
                    @endif
                
                    <div class="price">{{ $subscription->package_validity}} {{ $total_day }} Validity Plan</div>
                    <div class="price">{{ $subscription->no_of_ads}} Ads</div>
                    @if(isset($orderDetails->discount)&&$orderDetails->discount!=0)
                    <div class="price s">INR {{ $subscription->mrp }}</div>
                    @endif
                    <div class="price">INR {{ $subscription->offered_price }}</div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8 text-center">
				<div class="cart-summary-pack">
					<h4 class="h4-title">Price Summary</h4>
					<div class="cart-sum">
						<ul>
								<li>
									<div class="li-cart">
										<div class="cart-info">
										
											<h2>MRP</h2>
										</div>
										<div class="cart-price">
											<span><i class="fa">&#xf156;</i> {{ $subscription->mrp }}</span>
										</div>
									</div>
									
								</li>
								<li>
								    <div class="li-cart">
										<div class="cart-info">
										
											<h2>Offered price</h2>
										</div>
										<div class="cart-price">
											<span><i class="fa">&#xf156;</i> {{ $subscription->offered_price }}</span>
										</div>
									</div>
								</li>

							<li>
								<div class="li-cart">
									<div class="cart-info">
										<h2>Discount</h2>
										<p class="text-success" style="display: none" id="coupon-message"></p>
									</div>
									<div class="cart-price text-success">
										<span id="coupon_discount_amount" coupon_discount_amount="0"><i class="fa">&#xf156;</i>{{ $subscription->mrp - $subscription->offered_price }}</span>
									</div>
								</div>
							</li>
							<div id="remaining_balance_wel" style="display: none">
							    <li>
    							    <div class="li-cart">
    									<div class="cart-info">
    										<h2>Welcome Bonus Discount</h2>
    									</div>
    									<div class="cart-price text-success">
    									    @if($welcome_bonus>=$subscription->offered_price)
    										    <span><i class="fa">&#xf156;</i>{{$subscription->offered_price}}</span>
    										@else
    									        <span><i class="fa">&#xf156;</i>{{$welcome_bonus}}</span>
    										@endif
    									</div>
    								</div>
    							</li>
                            </div>
                            <li>
								<div class="li-cart">
									<div class="cart-info">
										<h2>SubTotal</h2>
										<p class="text-success" style="display: none" id="coupon-message"></p>
									</div>
									<div class="cart-price">
										<span id="subtotal" ><i class="fa">&#xf156;</i>{{ $subscription->offered_price }}</span>
										@if($welcome_bonus>=$subscription->offered_price)
										    <span id="subtotalWel" style="display: none" ><i class="fa">&#xf156;</i>0</span>
									    @else
									        <span id="subtotalWel" style="display: none" ><i class="fa">&#xf156;</i>{{$subscription->offered_price-$welcome_bonus}}</span>
									    @endif
									</div>
								</div>
							</li>
							<li>
								<div class="li-cart">
									<div class="cart-info">
										<h2>GST</h2>
										<p>{{$gst_percent}}% {{$gst_type}}</p>
									</div>
									<div class="cart-price">
										<span id="gst_amount" gst_amount="{{$total_gst}}"><i class="fa">&#xf156;</i>{{$total_gst}}</span>
										@if($welcome_bonus>=$subscription->offered_price)
										    <span id="gst_amount_wel" style="display:none;" gst_amount="0"><i class="fa">&#xf156;</i>0</span>
									    @else
									        <span id="gst_amount_wel" style="display:none;" gst_amount="{{$AftWelDisGst??''}}"><i class="fa">&#xf156;</i>{{$AftWelDisGst??''}}</span>
									    @endif
									</div>
								</div>
							</li>
							<li>
								<div class="li-cart">
									<div class="cart-info">
										<h2>Total</h2>
									</div>
									<div class="cart-price">
										<span id="total" total="{{$total}}"><i class="fa">&#xf156;</i>{{$total}}</span>
										@if($welcome_bonus>=$subscription->offered_price)
										    <span id="totalWel" style="display:none;" total="0"><i class="fa">&#xf156;</i>0</span>
										@else
									       <span id="totalWel" style="display:none;" total="{{$totalWel ?? ''}}"><i class="fa">&#xf156;</i>{{$totalWel ?? ''}}</span>
									    @endif
									</div>
								</div>
							</li>
                           <li style="display:{{$welcome_bonus != 0 ? 'block':'none'}}">
                                <div class="li-cart">
                                    <div class="cart-info">
                                            <h2>Pay with Welcome Bonus</h2>
                                            <p class="text-success" style="display: none" id="welcome-remaining">Remaining Welcome Balance: <i class="fa">&#xf156;</i>{{ max(0, $welcome_bonus - $subscription->offered_price) }}</p>
                                    </div>
                                    <div class="cart-price">
                                            <input type="checkbox" id="payWithWelcomeCheckbox" onchange="updatePaymentMethod()">
                                            <label for="payWithWelcomeCheckbox"><i class="fa">&#xf156;</i>{{ ($welcome_bonus) ?? 0.0 }}</label>
                                    </div>
                                </div>
                            </li>
                            <li style="display:{{$wallet != 0 ? 'block':'none'}}">
                                <div class="li-cart">
                                    <div class="cart-info">
                                            <h2>Pay with Wallet</h2>
                                            <p>{{$admin_wallet_limit}}% of your MRP</p>
                                    </div>
                                    <div class="cart-price">
                                            <input type="checkbox" id="payWithWalletCheckbox" onchange="updatePaymentMethod()">
                                            <label for="payWithWalletCheckbox"><i class="fa">&#xf156;</i>{{ $usable_wallet_amount > $wallet ? $wallet : $usable_wallet_amount }}</label>
                                    </div>
                                </div>
                            </li>
                            <div id="remaining_balance_wallet" style="display: none">
                                <li>
    								<div class="li-cart">
    									<div class="cart-info">
    										<h2>Remaining wallet balance</h2>
    									</div>
    									<div class="cart-price">
    										<span><i class="fa">&#xf156;</i>{{max(0,$wallet - $usable_wallet_amount)}}</span>
    									</div>
    								</div>
    							</li>
    							<li>
    							    <div class="li-cart">
    									<div class="cart-info">
    										<h2>Final payable amount</h2>
    									</div>
    									<div class="cart-price">
    										<span><i class="fa">&#xf156;</i>{{ (float)$total - ($usable_wallet_amount > $wallet ? (float)$wallet : (float)$usable_wallet_amount) ?? 0.0 }}</span>
    									</div>
    								</div>
    							</li>
                            </div>
							<li>
								<button id="paymentButton" class="btn btn-primary mt-10 p-3" data-name="{{$customer->name}}" data-email="{{$customer->email}}" data-phone="{{$customer->mobile??"3344453223"}}"  data-subscription-name="{{$subscription->package}}" data-wallet-limit="{{$admin_wallet_limit}}" data-welcome-bonus="{{$welcome_bonus}}" data-usable-wallet="{{$usable_wallet_amount}}" data-wallet-balance="{{ $wallet }}" data-subscription-id="{{$subscription->id}}" data-total="{{$total}}" data-remaining="{{$remainingWalletBalance}}" data-total-gst="{{$total_gst}}">Pay using Cashfree</button>
                                <div id="wallet_but_view" style="display: none">
                                    <button class="pay_with_wallet btn btn-primary mt-10 p-3" id="payWallet" data-name="{{$customer->name}}" data-email="{{$customer->email}}" data-phone="{{$customer->mobile??"3344453223"}}"  data-subscription-name="{{$subscription->package}}" data-wallet-limit="{{$admin_wallet_limit}}" data-welcome-bonus="{{$welcome_bonus}}" data-usable-wallet="{{$usable_wallet_amount}}" data-wallet-balance="{{ $wallet }}" data-total-wel="{{$totalWel??0}}" data-total-wel-wout-gst="{{$totalWelWOutGst??0}}" data-subscription-id="{{$subscription->id}}" data-total="{{$total}}" data-remaining="{{$remainingWalletBalance}}" data-total-gst="{{$total_gst}}">Subscribe</button>
                                </div>
							</li>
						</ul>
					</div>
					<div class="place-order-listing">
						<p>By clicking above payment button, you agree to the <a href="https://welcomepost.in/pages/13/pages-terms-of-use">Terms &amp; Conditions</a>
						</p>
					</div>
				</div>
			</div>
           
        </div>
    </div>
</div>
</div>
</div>
</section>
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.10.4/sweetalert2.min.css" >
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.10.4/sweetalert2.min.js"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    function updatePaymentMethod() {
            var payWithWalletCheckbox = document.getElementById("payWithWalletCheckbox");
            var payWithWelcomeCheckbox = document.getElementById("payWithWelcomeCheckbox");
            var remainingBalLiWal = document.getElementById("remaining_balance_wallet");
            var remainingBalLiWel = document.getElementById("remaining_balance_wel");
            var razor_but_view = document.getElementById("paymentButton");
            var wallet_but_view = document.getElementById("wallet_but_view");
            var remainingWelcome = document.getElementById('welcome-remaining');
            var isPayWithWallet = payWithWalletCheckbox.checked;
            var isPaywithWelcome = payWithWelcomeCheckbox.checked;
            var subTotalWel = document.getElementById('subtotalWel');
            var subTotal = document.getElementById('subtotal');
            var gstWel = document.getElementById('gst_amount_wel');
            var gst = document.getElementById('gst_amount');
            var totalSp = document.getElementById('total');
            var totalWel = document.getElementById('totalWel');

            // Perform actions based on the state of the checkbox
            if (isPayWithWallet) {
                // User wants to pay with wallet
                totalSp.style.display = 'block';
                totalWel.style.display = 'none';
                gstWel.style.display = 'none';
                gst.style.display = 'block';
                remainingWelcome.style.display = 'none';
                subTotal.style.display = 'block';
                subTotalWel.style.display = 'none';
                payWithWelcomeCheckbox.checked = false;
                remainingBalLiWal.style.display = 'block';
                remainingBalLiWel.style.display = 'none';
                razor_but_view.style.display = 'none';
                wallet_but_view.style.display = 'block';
                console.log("User wants to pay with wallet");
                // You can add additional logic here
            }else if(isPaywithWelcome){
                totalSp.style.display = 'none';
                totalWel.style.display = 'block';
                gstWel.style.display = 'block';
                gst.style.display = 'none';
                subTotal.style.display = 'none';
                remainingWelcome.style.display = 'block';
                subTotalWel.style.display = 'block';
                payWithWalletCheckbox.checked = false;
                // User does not want to pay with wallet
                remainingBalLiWel.style.display = 'block';
                remainingBalLiWal.style.display = 'none';
                razor_but_view.style.display = 'none';
                wallet_but_view.style.display = 'block';
                // You can add additional logic here
            }else{
                // User does not want to pay with wallet
                totalSp.style.display = 'block';
                totalWel.style.display = 'none';
                gstWel.style.display = 'none';
                gst.style.display = 'block';
                subTotal.style.display = 'block';
                subTotalWel.style.display = 'none';
                 remainingWelcome.style.display = 'none';
                payWithWalletCheckbox.checked = false;
                payWithWelcomeCheckbox.checked = false;
                remainingBalLiWel.style.display = 'none';
                remainingBalLiWal.style.display = 'none';
                razor_but_view.style.display = 'block';
                wallet_but_view.style.display = 'none';
                console.log("User does not want to pay with wallet");
                // You can add additional logic here
            }
        }
</script>

<script>
        $("#payWallet").on("click", function() {
            var subscriptionId = $(this).data('subscription-id');
            var total = parseFloat($(this).data('total'));
            var subscriptionOfferedPrice = '{{$subscription->offered_price}}';
            var remainingWalletBalance = parseFloat($(this).data('remaining'));
            var walletBalance = parseFloat($(this).data('wallet-balance'));
            var usableWallet = parseFloat($(this).data('usable-wallet'));
            var welcomeBonusWallet = parseFloat($(this).data('welcome-bonus'));
            var walletLimit = parseFloat($(this).data('wallet-limit'));
            var remainingBalance = walletBalance - usableWallet;
            var description = 'Subscription purchasing ' + $(this).data('subscription-name');
            var phone = $(this).data('phone');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var isPayWithWallet = payWithWalletCheckbox.checked;
            var totalGst = parseFloat($(this).data('total-gst'));

            // Check if wallet balance is sufficient
            if(welcomeBonusWallet>=total&&!isPayWithWallet){
                walletPayBegin(welcomeBonusWallet-subscriptionOfferedPrice,subscriptionId,total,2);
            } else if(welcomeBonusWallet!=0&&welcomeBonusWallet<subscriptionOfferedPrice&&!isPayWithWallet){
                var totalWel = parseFloat($(this).data('total-wel'));
                var totalWelWoutGst = parseFloat($(this).data('total-wel-wout-gst'));
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'success',
                    text: 'Your welcome bonus is not enough. Do you want to proceed with cashfree for remaining ₹'+(totalWel)+' amount?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Subscribe'
                }).then((result) => {
                    if (result.isConfirmed) {
                        payCashfree(description,phone,name,email,subscriptionId,totalWelWoutGst,welcomeBonusWallet,0,totalWel,1);
                    }
                });
            }
            else if(usableWallet>walletBalance){
                 Swal.fire({
                    title: 'Are you sure?',
                    icon: 'success',
                    text: 'Your wallet balance is not enough. Do you want to proceed with cashfree for remaining ₹'+(total - walletBalance)+' amount?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Subscribe'
                }).then((result) => {
                    if (result.isConfirmed) {
                        payCashfree(description,phone,name,email,subscriptionId,total-totalGst,walletBalance,remainingWalletBalance,total-walletBalance,0);
                    }
                });
            }else {
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'success',
                    text: 'You can only use '+walletLimit+'% of wallet balance. Do you want to proceed with cashfree for remaining ₹'+(total - usableWallet)+' amount?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Subscribe'
                }).then((result) => {
                    if (result.isConfirmed) {
                        payCashfree(description,phone,name,email,subscriptionId,total-totalGst,walletBalance,remainingWalletBalance,total-usableWallet,0);
                    }
                });
            }
        });
        function walletPayBegin(remainingBalance,subscriptionId,total,type){
            Swal.fire({
                title: 'Are you sure?',
                icon: 'success',
                text: 'After this payment your remaining welcome bonus balance is ₹' + (remainingBalance) + '. Do you want to proceed with welcome bonus payment?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Subscribe'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform wallet payment
                    $.ajax({
                        url: '{{ url("free-subscription") }}',
                        method: 'POST',
                        data: {
                            id: subscriptionId,
                            total_subscription: total,
                            wallet_remaining: remainingBalance,
                            type: type,
                            '_token': "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.success) {
                                Swal.fire({
                                    title: "Package Purchased Successfully",
                                    icon: "success",
                                    timer: 2000,
                                    timerProgressBar: true,
                                    onClose: () => {
                                        window.location.href = "{{ route('purchase-subscription') }}";
                                    }
                                });
                            }
                        }
                    });
                }
            });
        }
        $('#paymentButton').click(function() {
            var subscriptionId = $(this).data('subscription-id');
            var total = parseFloat($(this).data('total'));
            var remainingWalletBalance = parseFloat($(this).data('remaining'));
            var walletBalance = parseFloat($(this).data('wallet-balance'));
            var usableWallet = parseFloat($(this).data('usable-wallet'));
            var welcomeBonusWallet = parseFloat($(this).data('welcome-bonus'));
            var walletLimit = parseFloat($(this).data('wallet-limit'));
            var totalGst = parseFloat($(this).data('total-gst'));
            var remainingBalance = walletBalance - usableWallet;
            var description = 'Subscription purchasing ' + $(this).data('subscription-name');
            var phone = $(this).data('phone');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var valWoutGst = total-totalGst;
            console.log(valWoutGst)
            payCashfree(description,phone,name,email,subscriptionId,valWoutGst,walletBalance,remainingWalletBalance,total,0);
        
        });
        
        function payCashfree(description,phone,name,email,subscriptionId,total,walletBalance,remainingWalletBalance,usableWallet,isWelcome){
            // Send AJAX request to the backend
            console.log(usableWallet)
            $.ajax({
                url: '{{ route("order.process") }}',
                method: 'POST', // Change method to POST
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json' // Set content type to JSON
                },
                data: JSON.stringify({
                    price: usableWallet,
                    total_wout_gst:total,
                    description: description,
                    phone: phone,
                    name: name,
                    subscription_id: subscriptionId,
                    email: email,
                    remaining_wallet: remainingWalletBalance,
                    iswelcome:isWelcome
                }),
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    if(response.paymentLink !='')
                    {
                       
                       const cashfree = Cashfree({
                            mode: "production",
                        });
                        cashfree.checkout({
                          paymentSessionId: response.paymentLink
                        });
                        
                    }
                    if(response.error !='')
                    {
                        //alert(response.error);
                    }
                    //window.location.href = response.paymentLink;
                },
                error: function(xhr, status, error) {
                    alert(response.error);
                    // Handle errors if any
                    console.error(xhr.responseText);
                }
            });
        }
</script>


{{-- <script>
    $(".pay_now").on("click",function(){
        Swal.fire({
            title: 'Are you sure?',
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Subscribe Free'
            }).then((result) => {
                if (result.isConfirmed) {
                    var id = $('#wallet_form').attr('id');
                    var total_subscription = $('#wallet_form').attr('total_subscription');
                    var remaining = $('#wallet_form').attr('wallet_remaining');
            
                $.ajax({
        		url:'{{url("free-subscription")}}',
        		method:'POST',
        		data:{id:id,'_token':"{{csrf_token()}}",'total_subscription':total_subscription,'wallet_remaining':wallet_remaining},
        		success:function(data){
                    console.log(data);
                    if (data.success) 
                    {
                        Swal.fire(
                            "Package Purchased Successfully."
                        );
                        setTimeout(function() {
                            location.reload();
                        }, 40);
                    }
        		}
        	});
                }
            })
        
    });

    $(".pay_with_wallet").on("click",function(){
        
        var subscriptionId = $(this).data('subscription-id');
        var total = $(this).data('total');
        var remainingWalletBalance = $(this).data('remaining');
        var walletBalance = $(this).data('wallet-balance');

        Swal.fire({
            title: 'Are you sure?',
            icon: 'success',
            text: 'After this payment your remaining wallet balance is ₹' + remainingWalletBalance + '. Do you want to proceed with wallet payment?',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Subscribe'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{url("free-subscription")}}',
                    method: 'POST',
                    data: {
                        id: subscriptionId,
                        total_subscription: total,
                        wallet_remaining: remainingWalletBalance,
                        '_token': "{{csrf_token()}}"
                    },
                    success: function(data) {
                        console.log(data);
                        if(data.success) {
                            Swal.fire({
                            title: "Package Purchased Successfully",
                            icon: "success",
                            timer: 2000, // Time in milliseconds (2 seconds in this example)
                            timerProgressBar: true,
                            onClose: () => {
                                window.location.href = "{{ route('purchase-subscription') }}"; // Redirect to the purchase-subscription route
                            }
                        });
                        }
                    }
                });
            }
        });
        
    });
</script> --}}
@stop