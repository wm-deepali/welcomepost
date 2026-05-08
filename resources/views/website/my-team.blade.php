@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<div class="col-sm-12 col-md-9">
 	<div class="profile-cont" style="overflow:auto;">
 		<h3>My Total Seeds</h3>
 		 <table class="table table-responsive">
          <thead>
              
            <tr>
              <th scope="col">Sr. No</th>
              <th scope="col">Subscription Id</th>
              <th scope="col">Type</th>
              <th scope="col">Name</th>
              <th scope="col">Seed Id</th>
              <th scope="col">Joining Date</th>
              <th scope="col">Expiry Date</th>
              <th scope="col">Total Benefits</th>
              <th scope="col">Seed Type</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
              @foreach($datas as $key=>$data)
            <tr>
              <th scope="row">{{++$key}}</th>
              <td>#{{$data->subscriptionhistory->subscription_number ?? ""}}</td>
              <td>{{$data->subscriptionhistory->type ?? 'Normal'}}</td>
              <td>{{$data->customerchild->name ?? "--"}}</td>
              <td>#{{$data->customerchild->member_id ?? ""}}</td>
              <td>{{$data->joining_date}}</td>
              <td>{{$data->reserve_expiry_at}}</td>
              @php $totalearnings = \App\Models\CustomerCommission::where('user_id',$data->child_id)->where('parent_id',$data->user_id)->sum('total_earned');  @endphp
              <td> ₹ {{$totalearnings ?? 0}}</td>
              <td>{{ (isset($data->customerchild->referralto) && $data->customerchild->referralto == null) ? 'Auto Join':'Referred'}}</td>
              <td>{{$data->status}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
    </div>
</div>
</section>
@endsection