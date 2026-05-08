@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<style>
    .card-three {
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        min-height: 150px; /* Ensures uniform height */
    }

    .card-h {
        font-size: 18px;
        font-weight: bold;
    }

    .card-h-n {
        font-size: 24px;
        margin-top: 10px;
    }

    /* Adjustments for mobile view */
    @media (max-width: 576px) {
        .card-three {
            padding: 15px;
            min-height: 120px; /* Adjusted height for mobile */
        }
        
        .card-h {
            font-size: 14px; /* Smaller font for headings */
        }

        .card-h-n {
            font-size: 18px; /* Smaller font for numbers */
        }
    }
</style>
<div class="col-sm-12 col-md-9">
	<div class="templ-rhs-form">
		<h3>Dashboard</h3>
		<div class="sub-exp s">
		    @if(isset($expiry_history))
		        @php
		            $result 		= DB::table('subscriptions')->where('id',$expiry_history->subscription_id)->first();
            	    $status_dates 	= date("d-m-Y");  
            	    $no    			= explode(" ",$result->package_validity);
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
            @endif 
		</div>
		<div class="row">
            <div class="col-6 col-md-4">
                <div class="card-three text-center" style="background-color: #D800FF;">
                    <div class="card-h">Total Active Seed</div>
                    <div class="card-h-n">{{ $total_active_seeds ?? '0' }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card-three text-center" style="background-color: #FF0000;">
                    <div class="card-h">Active Subscription</div>
                    <div class="card-h-n">{{ $total_active_subscription ?? '0' }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card-three text-center" style="background-color: #FFDC00;">
                    <div class="card-h">Active Ad</div>
                    <div class="card-h-n">{{ $count_active_ads }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card-three text-center" style="background-color: #00E4FF;">
                    <div class="card-h">Pending Ads</div>
                    <div class="card-h-n">{{ $count_panding_ads }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card-three text-center" style="background-color: #FF00A6;">
                    <div class="card-h">Total Earning</div>
                    <div class="card-h-n">₹{{ $releasedEarning ?? '0' }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card-three text-center" style="background-color: #0800B2;">
                    <div class="card-h">Total Inquiries</div>
                    <div class="card-h-n">{{ $count_ads_enquiry }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</section>
@endsection