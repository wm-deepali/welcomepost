<div>
    <div class="form-group row">
        <div class="col-md-3">
            <b class="label-control">Subscription Name:</b>
            <p>{{ $data->subscriptions->package ?? "" }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Subscription Id:</b>
            <p>{{ $data->subscription_number }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Purchase Date:</b>
            <p>{{ $data->created_at->format('d-m-Y') }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Expiry Date:</b>
            <p>{{ $data->subscription_expiry }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Categories Included:</b>
            <p>{{ $categories->implode(",") }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Number of Ads:</b>
            <p>{{ $data->used_ads + $data->remaining_ads ?? "0" }}</p>
        </div>
    </div>

    <div class="form-group row d-flex justify-content-center">
        <h3>Payment Detail</h3>
    </div>
    <div class="form-group row">
        <div class="col-md-3">
            <b class="label-control">Billed Amount:</b>
            <p>₹ {{ $data->order_amount_with_gst ?? "" }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">GST:</b>
            <p>₹ {{ $data->gst_amount }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Payment Status:</b>
            <p>{{ $data->payment_status }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Payment Method:</b>
            <p>{{ $data->payment_method }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Transaction Id:</b>
            <p>{{ $data->transaction_id }}</p>
        </div>
    </div>
    <div class="form-group row d-flex justify-content-center">
        <h3>Ad Information</h3>
    </div>
    <div class="form-group row">
        <div class="col-md-3">
            <b class="label-control">Total Ads:</b>
            <p>{{ $data->used_ads + $data->remaining_ads ?? "0" }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Used Ads:</b>
            <p>{{ $data->used_ads }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Pending Ads:</b>
            <p>{{ $data->remaining_ads }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Active Ads:</b>
            <p>{{ $data->used_ads }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Total Views (Including All Ads):</b>
            <p>{{ $data->adsummary->sum('total_view') ?? 0 }}</p>
        </div>
        <div class="col-md-3">
            <b class="label-control">Total Clicks (Including All Ads):</b>
            <p>{{ $data->adsummary->sum('total_click') ?? 0 }}</p>
        </div>
    </div>
</div>
