@php
    $user_id = auth()->id();
    $permission = \App\Models\SubAdminPermission::where('user_id',$user_id)->first();
    $pendingAdsCount = \App\Models\Adposting::where('status', 0)->count();
    $chatCount = DB::table('chat_messages')->where('consumer_id',$user_id)->where('isAdminChat',1)->where('is_read','0')->count();
    $ticketCount =\App\Models\RaiseTicket::where('isResolved',0)->count();
    $suspendedCount = \App\Models\BlockUser::where('count','>=','5')->count();
    $currentDate = Carbon\Carbon::now()->toDateString();
    $customerCount = \App\Models\Customer::whereDate('created_at', $currentDate)->count();
    $deletedCustomerCount = \App\Models\Customer::where('delete_status','1')->count();
    $admin = App\Models\Adminsettings::first();
@endphp
<div class="wrapper">
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
   <!-- Left navbar links -->
   <ul class="navbar-nav">
      <li class="nav-item">
         <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
   </ul>
   <ul class="navbar-nav ml-auto">
        <!-- Notification Dropdown -->
        @if($user_id==1)
        <li class="nav-item">
            <form action="{{ route('toggleMaintenance') }}" method="POST">
                @csrf
                <input type="hidden" name="maintenance_mode" value="{{ $admin->is_site_maintainance }}">
                <button type="submit" class="btn btn-link">
                    {{ $admin->is_site_maintainance == 0 ? 'Turn On Maintenance' :  'Turn Off Maintenance'}}
                </button>
            </form>
        </li>
        @endif
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-bell" aria-hidden="true"></i>
                <span class="count-notify" style="font-size:12px;color: white;background-color: red;border-radius: 30px;padding: 3px;">{{ $pendingAdsCount+$chatCount+$ticketCount+$suspendedCount+$customerCount+$deletedCustomerCount }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">Notifications</span>
                <!-- New Pending Ads -->
                @if($permission->ads_edit==1)
                <a href="{{url('ads')}}" class="dropdown-item">
                    <i class="fas fa-exclamation-circle mr-2"></i> New Pending Ads
                    <span class="float-right text-muted text-sm">{{$pendingAdsCount ?? 0}}</span>
                </a>
                @endif
                <!-- Admin Unread Chat Count -->
                @if($permission->chat_edit==1)
                <a href="{{url('admin-chat')}}" class="dropdown-item">
                    <i class="fas fa-comments mr-2"></i> Admin Unread Chat
                    <span class="float-right text-muted text-sm">{{$chatCount}}</span>
                </a>
                @endif
                <!-- Ticket Unsolved Count -->
                @if($permission->help_edit==1)
                <a href="{{url('admin-raise-ticket')}}" class="dropdown-item">
                    <i class="fas fa-ticket-alt mr-2"></i> Ticket Unsolved
                    <span class="float-right text-muted text-sm">{{$ticketCount}}</span>
                </a>
                @endif
                <!-- Suspended User Count -->
                @if($permission->users_edit==1)
                <a href="{{url('user#blocked')}}" class="dropdown-item">
                    <i class="fas fa-user-slash mr-2"></i> Suspended Users
                    <span class="float-right text-muted text-sm">{{$suspendedCount}}</span>
                </a>
                
                <!-- New Registration Count -->
                <a href="{{url('user')}}" class="dropdown-item">
                    <i class="fas fa-user-plus mr-2"></i> New Registrations
                    <span class="float-right text-muted text-sm">{{$customerCount}}</span>
                </a>
                
                <!-- Deleted User Count -->
                <a href="{{url('user#delete')}}" class="dropdown-item">
                    <i class="fas fa-user-times mr-2"></i> Deleted Users
                    <span class="float-right text-muted text-sm">{{$deletedCustomerCount}}</span>
                </a>
                @endif
            </div>
        </li>
         <li class="nav-item dropdown">
             <a class="nav-link" href="{{url('admin-profile')}}">
             <i class="fa fa-user" aria-hidden="true"></i>
             </a>
          </li>
    </ul>
</nav>
<!-- /.navbar -->
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
   <!-- Brand Logo -->
   <a href="{{url('admin-dashboard')}}" class="brand-link">
   <img src="{{url('assets/adminlte/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
      style="opacity: .8">
   <span class="brand-text font-weight-light">Welcome Post</span>
   </a>
   <!-- Sidebar -->
   <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
         <div class="info">
            <a href="#" class="d-block">Welcome Admin</a>
         </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
               <a href="{{url('admin-dashboard')}}" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
               </a>
            </li>
            <!--<li class="nav-item">
               <a href="{{url('professional')}}" class="nav-link">
                   <i class="nav-icon fas fa-tachometer-alt"></i>
                   <p>Professional</p>
               </a>
               </li>-->
            @if($permission->master_edit==1)
            <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
                     Master Setting
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{url('categories')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Categories </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('sub-categories')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Sub Categories </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('countries')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Countries </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('states')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  States </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('city')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  City </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('zip')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Zip </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('location')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Location </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('brand')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Brands </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('vehicletypes')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Vehicle Type </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('fueltype')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Fuel Type </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('transmission')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Transmission </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('residencetype')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Residence </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('furnishingtype')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Furnishing </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('construction')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Construction </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('facing')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Facing </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('job')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Job </p>
                     </a>
                  </li>
               </ul>
            </li>
            @endif
            @if($permission->users_edit==1)
            <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
                     Users
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{url('user')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Manage Users </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{route('waiting-subscribers')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Reserve for Seeds </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{route('waiting-user-seed')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Users waiting list for seeds </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('view-all-referrals')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Referral Members</p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('view-all-auto-joining')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Auto Joining Members</p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('subscription-history')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Subscription Records</p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('deleted-user')}}" class="nav-link">
                        <i class="nav-icon fa fa-user"></i>
                        <p>Deleted User</p>
                     </a>
                  </li>
               </ul>
            </li>
            @endif
            @if($permission->chat_edit==1)
            <li class="nav-item">
               <a href="{{url('admin-chat')}}" class="nav-link">
                  <i class="nav-icon fas fa-comments"></i>
                  <p>Chat Support</p>
               </a>
            </li>
            @endif
            @if($permission->invoice_order_edit==1)
            <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
                     Invoice & Orders
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{url('subscription-order')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Manage Orders </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('transaction-history')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Manage Transactions</p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="#" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Refunds & Cancellations</p>
                     </a>
                  </li>
               </ul>
            </li>
            @endif
            @if($permission->subscription_edit==1)
            <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
                     Subscriptions
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{url('subscription')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Manage Subscriptions </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('freetrail')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Free Trials</p>
                     </a>
                  </li>
               </ul>
            </li>
            @endif
            @if($permission->ads_edit==1)
            <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>Ads & Inquiries
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{url('ads')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Ads</p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('ads-enquiry')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Advt Inquiries</p>
                     </a>
                  </li>
               </ul>
            </li>
            @endif
            @if($permission->mis_report_edit==1)
            <li class="nav-item has-treeview">
                 <!--<a href="{{url('mis-report')}}" class="nav-link">-->
                <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>MIS Reports
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{url('mis-report')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Subscription Report </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('referral-link-report')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Referral Link Report </p>
                     </a>
                  </li>
                 
                 
                 <li class="nav-item">
                     <a href="{{url('user-commissions')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Customer Commission </p>
                     </a>
                  </li>
                 
               </ul>
            </li>
            @endif
            @if($permission->content_edit==1)
            <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
                     Content Management
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{route('infocard.index')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> InfoCards </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{route('banner.index')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Banners </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('about')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> About Us </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('contact-details')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Contact Details  </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('blog')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Blogs </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('faqcategory')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> FAQ Category  </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('faq')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> FAQ </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{route('footer.setting')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Footer Setting </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('pages')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Pages </p>
                     </a>
                  </li>
                  <!--<li class="nav-item">
                     <a href="{{url('formtype')}}" class="nav-link ">
                     <i class="far fa-circle nav-icon"></i>
                      <p> Form Types </p>
                     </a>
                     </li> -->
               </ul>
            </li>
            @endif
            @if($permission->setting_edit==1)
            <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
                     Notifications
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{url('/send-notification')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Send notifications </p>
                        
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('/custom-notification-history')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Custom notifications </p>
                        
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{route('manage-default-notifications')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Notifications Events </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('default-notification-history')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Notifications History</p>
                     </a>
                  </li>
                  
                  
               </ul>
            </li>
            <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
                     Setting
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{url('admin-setting')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Admin setting </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{route('sub-admin')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Sub Admin and Roles</p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('manage-commission-setting')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Manage Commission Setting</p>
                     </a>
                  </li>
                  
                  {{--<li class="nav-item">
                     <a href="{{url('payment-setting')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Payment Gateway</p>
                     </a>
                  </li>--}}
               </ul>
            </li>
            @endif
            @if($permission->help_edit==1)
            <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
                     Help & Support
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <!--<li class="nav-item">-->
                  <!--   <a href="{{url('call-back-enquiry')}}" class="nav-link ">-->
                  <!--      <i class="far fa-circle nav-icon"></i>-->
                  <!--      <p>  Call Back </p>-->
                  <!--   </a>-->
                  <!--</li>-->
                  <!--<li class="nav-item">-->
                  <!--   <a href="{{url('call-back-enquiry')}}" class="nav-link ">-->
                  <!--      <i class="far fa-circle nav-icon"></i>-->
                  <!--      <p>  Subscribe </p>-->
                  <!--   </a>-->
                  <!--</li>-->
                  <li class="nav-item">
                     <a href="{{route('subject')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Subject </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('admin-raise-ticket')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Raise Ticket </p>
                     </a>
                  </li>
                  <!--<li class="nav-item">-->
                  <!--   <a href="{{url('enquiry')}}" class="nav-link">-->
                  <!--      <i class="nav-icon fa fa-tasks"></i>-->
                  <!--      <p>Customer Inquries </p>-->
                  <!--   </a>-->
                  <!--</li>-->
               </ul>
            </li>
            @endif
            @if($permission->wallet_payouts_edit==1)
			<li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
				  Wallet & Payouts
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{url('user-wallet')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p> User Wallets </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('earnings')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Earnings </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('payouts')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Payouts </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('pool-wallet-history')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Pool Wallet History </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('pool-wallet-summery')}}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>  Pool Wallet Summery </p>
                     </a>
                  </li>
               </ul>
            </li>
            @endif
            <!-- <li class="nav-item">
               <a href="{{url('ads')}}" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>Manage Ad Posting</p>
               </a>
               </li>
               <li class="nav-item">
               <a href="{{url('ads-enquiry')}}" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>Ads Enquiry</p>
               </a>
               </li> -->
            <!-- <li class="nav-item">
               <a href="{{url('payment-setting')}}" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>Payment Gateway Setting</p>
               </a>
               </li> -->
            <!-- <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                 <i class="nav-icon fa fa-tasks"></i>
                 <p>
                    Manage Ad Posting
                    <i class="right fas fa-angle-left"></i>
                 </p>
               
               </a>
               <ul class="nav nav-treeview">
               
               <li class="nav-item">
               <a href="{{url('ads')}}" class="nav-link ">
               <i class="far fa-circle nav-icon"></i>
               <p>  Ads </p>
               </a>
               </li>
               
               <li class="nav-item">
               <a href="{{url('job-ads')}}" class="nav-link ">
               <i class="far fa-circle nav-icon"></i>
               <p>  New Posting </p>
               </a>
               </li>
               
               <li class="nav-item">
               <a href="{{url('published-job-ads')}}" class="nav-link ">
               <i class="far fa-circle nav-icon"></i>
               <p>  Published Posting </p>
               </a>
               </li>
               
               <li class="nav-item">
               <a href="{{url('rejected-job-ads')}}" class="nav-link ">
               <i class="far fa-circle nav-icon"></i>
               <p>  Rejected Posting </p>
               </a>
               </li>
               
               
               </ul>
               </li>-->
            <!-- <li class="nav-item has-treeview">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
                     Manage Subscription
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{url('subscription')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Subscription Package </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('per-ads-costing')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Per Ads Costing </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('freetrail')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Free Trail </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('subscription-order')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Subscription Orders </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="{{url('subscription-history')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> Subscription History </p>
                     </a>
                  </li>
               </ul>
               </li> -->
            <!-- <li class="nav-item">
               <a href="{{url('transaction-history')}}" class="nav-link">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>Manage Transaction History</p>
               </a>
            </li> -->
            <li class="nav-item">
               <a href="{{url('logout')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Log Out</p>
               </a>
            </li>
         </ul>
      </nav>
      <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
</aside>