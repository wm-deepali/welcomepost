<style type="text/css">
.news-top-menu {
        margin-top: var(--topspac1);
        position: static !important;
}

@media(max-width:768px) {
    .news-top-menu {
        margin-top: 0px;
        position: static !important;
    }
}

.dropdown-toggle::after {
    margin-top: 8px;
}

</style>

<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>

<section class="news-hom-big news-details">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="nav-side-menu">
                    <a href="{{ route('user-dash')}}" class="@if($page == 'Dashboard') active @endif">
                        <div class="brand">Dashboard</div>
                    </a>
                    <i class="material-icons toggle-btn" data-toggle="collapse" data-target="#menu-content">menu</i>
                    <!--<i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>-->
                    <div class="menu-list">
                        <ul id="menu-content" class="menu-content collapse out">
                            <!--<li><a href="{{ route('user-dash')}}" class="@if($page == 'Dashboard')active @endif">Dashboard</a></li>-->
                            <li  data-toggle="collapse" data-target="#products" class="collapsed">
                                <a href="#"> Profile & Account <span class="arrow"></span></a>
                            </li>
                            <ul class="sub-menu collapse" id="products">
                                <li><a href="{{ route('user-dashboard')}}" class="@if($page == 'Home') active @endif"> Profile & Account</a></li>
                                <li><a href="{{ route('user-profile')}}" class="@if($page == 'Profile') active @endif"> Update Profile & Account</a></li>
                                <li><a href="{{ route('change-password')}}" class="@if($page == 'Change Password') active @endif">Change Password</a></li>
                                <!--<li><a href="{{ route('privacy-setting')}}" class="@if($page == 'Privacy Setting') active @endif">Privacy Setting</a></li>-->
                                <!--<li><a href="{{ url('notification')}}" class="@if($page == 'Notification') active @endif">Notification</a></li>-->
                                <li><a href="{{ route('close-account')}}" class="@if($page == 'Close Account') active @endif">Close Account</a></li>
                                <li><a href="{{ route('logout-alldevice')}}" class="@if($page == 'Logout All Device') active @endif">Logout from All Device</a></li>
                            </ul>
                             <li>
                                <a href="{{route('my-ads')}}" class="@if($page == 'My Ads') active @endif"> My Ads</a>
                            </li>
                            <li>
                                <a href="{{route('notifications')}}" class="@if($page == 'Notifications') active @endif"> Notification</a>
                            </li>
                            <li>
                                <a href="{{route('my-ads-enquiry')}}" class="@if($page == 'Sent Enquiry') active @endif"> Sent Enquiry</a>
                            </li>
                            <li>
                                <a href="{{route('owner-enquiry')}}" class="@if($page == 'Inbox Enquiry') active @endif"> Inbox Enquiry</a>
                            </li>
                            <li>
                                <a href="{{ route('my-subscription')}}" class="@if($page == 'My Subscription') active @endif"> My Subscriptions</a>
                            </li>
                            <li>
                                <a href="{{ route('purchase-subscription')}}" class="@if($page == 'Purchase Subscription') active @endif"> Buy New Subscription</a>
                            </li>
                        <li  data-toggle="collapse" data-target="#orderinvoice" class="collapsed">
                                <a href="#"> Orders & Invoices <span class="arrow"></span></a>
                            </li>
                            <ul class="sub-menu collapse" id="orderinvoice">
                                <li><a href="{{ url('my-orders')}}" class="@if($page == 'My Orders') active @endif"> My Orders</a></li>
                                
                           </ul>


                            <li  data-toggle="collapse" data-target="#payouts" class="collapsed">
                                <a href="#"> Seed & Payouts <span class="arrow"></span></a>
                            </li>
                            <ul class="sub-menu collapse" id="payouts">
                                <li><a href="{{ route('my-referrals')}}" class="@if($page == 'My Referrals') active @endif"> My Referral Seeds</a></li>
                                <li><a href="{{ route('my-autojoining')}}" class="@if($page == 'Auto Joining') active @endif"> Auto Seeds</a></li>
                                <li><a href="{{ route('user-wallets')}}" class="@if($page == 'Wallet') active @endif">User Wallet</a></li>
                                <li><a href="{{ route('my-earning')}}" class="@if($page == 'Profile') active @endif"> My Earning</a></li>
                                <li><a href="{{ route('user-payouts')}}" class="@if($page == 'Payouts') active @endif">Payouts</a></li>
                                <li><a href="{{ route('my-team')}}" class="@if($page == 'Close Account') active @endif">My Total Seeds</a></li>
                           </ul>



                            <li>
                                <a href="{{ route('help')}}" class="@if($page == 'Help and Support') active @endif">Help & Support </a>
                            </li>
                            <li>
                                <a href="{{ route('user-logout')}}"> Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>