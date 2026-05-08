
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
                    <b class="label-control label" style="color: blue;">Date & Time:-</b><p>{{ $data->created_at}}</p>
                </div>
                <div class="col-sm-3">
                    <b class="label-control label" style="color: blue;">Image:-</b><p><img src="{{ $data->image}}" width="60px"></p>
                </div>
                <div class="col-sm-3">
                    <b class="label-control label" style="color: blue;">Ad Title:-</b><p>{{ $data->ad_title}}</p>
                </div>
                <div class="col-sm-3">
                    <b class="label-control label" style="color: blue;">Published Date:-</b><p>{{ $data->published_date}}</p>
                </div>
                <div class="col-sm-3">
                    <b class="label-control label" style="color: blue;">Expiry Date:-</b><p>{{ $data->ad_expiry}}</p>
                </div>
                <div class="col-sm-3">
                    <b class="label-control label" style="color: blue;">Subscription ID:-</b><p>{{ $data->subscriptionhistory->subscription_number ?? ""}}</p>
                </div>
                <div class="col-sm-3">
                    <b class="label-control label" style="color: blue;">Category:-</b><p>{{ $data->category->name ?? "-"}}</p>
                </div>
                 <div class="col-sm-3">
                    <b class="label-control label" style="color: blue;">Sub Category:-</b><p>{{ $data->subcategory->name ?? "-"}}</p>
                </div>
                <div class="col-sm-3">
                    <b class="label-control label" style="color: blue;">Ad Type:-</b><p>{{ $data->ad_type ?? "-"}}</p>
                </div>
                <div class="col-sm-3">
                    <b class="label-control label" style="color: blue;">Description:-</b><p>{!! $data->description ?? "-" !!}</p>
                </div>
                <div class="col-sm-3">
                    <b class="label-control label" style="color: blue;">Price:-</b><p>{!! $data->price ?? "-" !!}</p> 
                </div>
            </div>
        </div>  
    </div>
</div>
