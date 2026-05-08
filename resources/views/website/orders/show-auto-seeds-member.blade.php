
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View</h4>
            </div>
            <div class="modal-body">
                <h3>Auto Seeds Member</h3>
                <table class="table">
  <thead>
      
    <tr>
      <th scope="col">Sr. No</th>
      <th scope="col">Subscription Id</th>
      <th scope="col">Member Id</th>
      <th scope="col">Joining Date</th>
      <th scope="col">Expiry Date</th>
      <th scope="col">Status</th>
      <th scope="col">Total Earning</th>
    </tr>
  </thead>
  <tbody>
      @foreach($datas as $key=>$data)
    <tr>
      <th scope="row">{{++$key}}</th>
      <td>#{{$data->subscriptionhistory->subscription_number ?? ""}}</td>
      <td>#{{$data->customerchild->member_id ?? ""}}</td>
      <td>{{$data->joining_date}}</td>
      <td>{{$data->reserve_expiry_at}}</td>
      <td>{{$data->status}}</td>
      @php $totalearnings = \App\Models\SubscriptionHistory::where('user_id',$data->child_id)->where('comission_paid_parent_id',$data->user_id)->sum('comission_paid_amount');  @endphp
      <td> ₹ {{$totalearnings ?? 0}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
              

                
              </div>  
            <div class="modal-footer">
                
            </div>
</div>
</div>
