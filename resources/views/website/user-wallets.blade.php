@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<style>
   .copy-text {
       position: relative;
       padding: 5px;
       background: #fff;
       border: 1px solid #ddd;
       border-radius: 10px;
       display: flex;
       justify-content: space-between;
    }
   .copy-text input.text {
       color: #555;
       border: none;
       outline: none;
    }
   .copy-text button {
        padding: 8px 12px;
        background: #5784f5;
        color: #fff;
        font-size: 14px;
        border: none;
        outline: none;
        border-radius: 10px;
        cursor: pointer;
    }
   .copy-text button:active {
        background: #809ce2;
    }
   .copy-text button:before {
       content: "Copied";
       position: absolute;
       top: -45px;
       right: 0px;
       background: #5c81dc;
       padding: 8px 10px;
       border-radius: 20px;
       font-size: 15px;
       display: none;
    }
   .copy-text button:after {
       content: "";
       position: absolute;
       top: -20px;
       right: 25px;
       width: 10px;
       height: 10px;
       background: #5c81dc;
       transform: rotate(45deg);
       display: none;
    }
   .copy-text.active button:before,
   .copy-text.active button:after {
        display: block;
    }
   .Referral_id.select-add-type {
        width: 308px;
        max-width: 100%;
    }
   .add-heading {
        font-weight: bold;
    }
   .payment-method {
        color:#000;
    }
   .payment-method.active {
        background:#007bff;
        color:#fff;
    }
   .wallet-info {
        width: 150px;
    }
    .profile-cont {
        height: 100%;
    }
    .table-responsive.table {
        display: block;
        width: 100%;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }
    .payment-info {
        display: flex;
        flex-direction: row;
    }
    
    @media (max-width:400px) {
        .payment-info {
            display: flex;
            flex-direction: column;
        }
    }
    
</style>
<!--<section>-->
    <div class="col-sm-7 col-md-9">
        <div class="row mb-4">
            <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                <div class="card card-body text-white bg-success">
                    <h3 class="mt-3 text-center"><strong>₹{{$lifeTimeEarning ?? 0.00}}</strong></h3>
                    <p class="text-center"> Lifetime Earnings</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                <div class="card card-body text-white bg-danger">
                    <h3 class="mt-3 text-center"><strong>₹{{$currentMonthPending ?? 0.00}}</strong></h3>
                    <p class="text-center">Current Month</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                <div class="">
                    <div class="card card-body text-white bg-info">
                        <h3 class="mt-3 text-center"><strong>₹{{$pendingReleaseEarning ?? "0.00"}}</strong></h3>
                        <p class="text-center">Pending Release</p>
                    </div>
                    <div class=""></div>
                    <div class=""></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                <div class="">
                    <div class="card card-body text-white bg-warning">
                        <h3 class="mt-3 text-center"><strong>₹{{$releasedEarning ?? "0.00"}}</strong></h3>
                        <p class="text-center">Released Payouts</p>
                    </div>
                    <div class=""></div>
                    <div class=""></div>
                </div>
            </div>
        </div> 
        <div class="wallet">
            <div class="row mb-3">
                <div class="col-lg-6 col-md-12 col-sm-12 mb-3">
                    <div class="profile-cont mt-10">
                        <h3 class="mt-3">Payment Information</h3>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <div>
                                    <ul class="">
                                        <li>
                                            <div class="payment-info">
                                                <div class="wallet-info mr-4"><strong>Unpaid Balance&nbsp;:</strong></div>
                                                <div><p>₹{{$pendingReleaseEarning ?? "0.00"}}</p></div> 
                                            </div>
                                        </li>
                                        <li>
                                            <div class="payment-info">
                                                <div class="wallet-info mr-4"><strong>Current Month&nbsp;:</strong></div>
                                                <div><p>₹{{$currentMonthPending ?? "0.00"}}</p></div> 
                                            </div>
                                        </li>
                                        <li>
                                            <div class="payment-info">
                                                <div class="wallet-info mr-4"><strong>Wallet Balance&nbsp;:</strong></div>
                                                <div><p>₹{{$customers->wallet_amount??'0.00'}}</p></div> 
                                            </div>
                                        </li>
                                        <li>
                                            <div class="payment-info">
                                                <div class="wallet-info mr-4"><strong>Welcome Bonus&nbsp;:</strong></div>
                                                <div><p>₹{{$customers->wallet_bonus}}</p></div> 
                                            </div>
                                        </li>
                                        <li>
                                            <div class="payment-info">
                                                <div class="wallet-info mr-4"><strong>Minimum&nbsp;&nbsp;Payout:</strong></div>
                                                <div><p>₹500.00 for Bank Transfer(INDIA), and ₹700.00 for Custom.</p></div> 
                                            </div>
                                        </li>
                                        <li>
                                            <div class="payment-info">
                                                <div class="wallet-info mr-4"><strong>Note&nbsp;:</strong></div>
                                                <div><p>Welcome bonus is non refundable amount, it is 
                                                        only can be use to buy any subscription.</p></div> 
                                            </div>
                                        </li>
                                    </ul>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 mb-3">
                    <div class="profile-cont mt-10">
                        <div class="d-flex justify-content-between">
                            <div class="">
                                <h3 class="mt-3">Payment Details</h3>
                            </div>
                            <div class="mt-3 mx-4">
                                <a href="{{route('user-profile')}}" class="border rounded px-3 p-2">Edit</a>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="">
                                    <li>
                                        <div class="payment-info">
                                            <div  class="wallet-info mr-4"><strong>Payment&nbsp;&nbsp;Method:</strong></div>
                                            <p>Bank Transfer</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="payment-info">
                                            <div  class="wallet-info mr-4"><strong>&nbsp;Account&nbsp;Name:</strong></div>
                                            <p>{{$customers->account_name}}</p> 
                                        </div>
                                    </li>
                                    <li>
                                        <div class="payment-info">
                                            <div  class="wallet-info mr-4"><strong>&nbsp;Account&nbsp;Number:</strong></div>
                                            <p>{{$customers->account_number}}</p> 
                                        </div>
                                    </li>
                                    <li>
                                        <div class="payment-info">
                                            <div  class="wallet-info mr-4"><strong>&nbsp;Bank&nbsp;Name:</strong></div>
                                            <p>{{$customers->bank_name}}</p> 
                                        </div>
                                    </li>
                                    <li>
                                        <div class="payment-info">
                                            <div  class="wallet-info mr-4"><strong>&nbsp;Account&nbsp;Ifsc:</strong></div>
                                            <p>{{$customers->account_ifsc}}</p> 
                                        </div>
                                    </li>
                                    <li>
                                        <div class="payment-info">
                                            <div  class="wallet-info mr-4"><strong>&nbsp;Branch&nbsp;Name:</strong></div>
                                            <p>{{$customers->branch_name}}</p> 
                                        </div>
                                    </li>
                                </ul>
                                <p>You can update Payment Details in <a href="{{route('user-profile')}}">Setting</a> page.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                    <div class="profile-cont mt-10">
                        <h3 class="mt-3">Payment History</h3>
                        <hr>
                        <table class="table-responsive table table-borderless">
                            <thead>
                                <tr>
                                  <th scope="col">ID</th>
                                  <th scope="col" colspan="2">DATE</th>
                                  <th scope="col">STATUS</th>
                                  <th scope="col">AMOUNT</th>
                                  <th colspan="2" scope="col">TRANSACTION</th>
                                  <th scope="col">DESCRIPTION</th>
                                </tr>
                              </thead>
                              <tbody>
                                @php
                                    $count = 1;
                                @endphp
                                @foreach ($transaction_history as $trans)
                                <tr>
                                <th scope="row">{{$count++}}</th>
                                <td colspan="2">{{$trans->datetime}}</td>
                                <td>{{ $trans->status%2 == 0 ? 'Debited' :  'Credited'}}</td>
                                <td>₹{{number_format($trans->amount, 2)}}</td>
                                <td colspan="2">
                                    @if($trans->status == 1 || $trans->status == 2)
                                        <span class="badge badge-primary">Wallet</span>
                                    @elseif($trans->status == 3||$trans->status == 4)
                                        <span class="badge badge-success">Bonus</span>
                                    @endif
                                </td>
                                <td colspan="2">{{$trans->description ?? "--"}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
</section>
<script>
    let copyText = document.querySelector(".copy-text");
    copyText.querySelector("button").addEventListener("click", function () {
    let input = copyText.querySelector("input.text");
   	input.select();
   	document.execCommand("copy");
   	copyText.classList.add("active");
   	window.getSelection().removeAllRanges();
        setTimeout(function () {
            copyText.classList.remove("active");
        }, 2500);
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
@endsection