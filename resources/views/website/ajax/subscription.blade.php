<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Buy More Ads</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form name="login_form" method="get" action="{{ url('get-ads-buy')}}">
            <div class="modal-body">
                 @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-group">No. of Ads</label>
                        <input type="number" class="form-control" id="ads" name="ads"/>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-group">Price</label>
                        <input type="text" name="price" id="price" class="form-control" readonly/>
                    </div>
                </div>
                <input type="hidden" value="{{$category_id}}" name="category_id"/>
                <input type="hidden" value="{{$id}}" name="subscription_id"/>
                
                
                <p class="price-div">
                    <span class="per_cost">Per Ads Cost : Rs.<span class="ad_cost">{{ $costing }}</span></span>
                    <input type="hidden" value="{{ $costing}}" class="ads_costing_value">
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    
$("#ads").on("keyup", function(){
    let ads             = $(this).val();
    let costing_price = $(".ads_costing_value").val();
    let total_price     = ads * costing_price;
    $("#price").val(total_price);
});
</script>